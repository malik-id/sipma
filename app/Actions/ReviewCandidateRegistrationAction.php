<?php

namespace App\Actions;

use App\Enums\RegistrationStatus;
use App\Models\CandidateRegistration;
use App\Models\CandidateRegistrationDocument;
use App\Models\Election;
use App\Models\User;
use App\Support\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class ReviewCandidateRegistrationAction
{
    public function handle(User $actor, CandidateRegistration $registration, RegistrationStatus $next, ?string $notes = null, ?string $deadline = null): void
    {
        Gate::forUser($actor)->authorize('review-registrations');
        DB::transaction(function () use ($actor, $registration, $next, $notes, $deadline) {
            $election = Election::whereKey($registration->election_id)->lockForUpdate()->firstOrFail();
            $registration = CandidateRegistration::whereKey($registration->id)->lockForUpdate()->firstOrFail();
            Rule::ensure($election->verificationOpen(), 'Periode verifikasi tidak sedang dibuka.');
            Rule::ensure(in_array($next, [RegistrationStatus::UnderReview, RegistrationStatus::RevisionRequired, RegistrationStatus::Verified, RegistrationStatus::Rejected]), 'Transisi tidak diizinkan.');
            if (in_array($next, [RegistrationStatus::RevisionRequired, RegistrationStatus::Rejected])) {
                Rule::ensure(filled($notes), 'Catatan atau alasan wajib diisi.', 'notes');
            }
            if ($next === RegistrationStatus::RevisionRequired) {
                Rule::ensure($registration->currentDocuments()->whereIn('verification_status', ['invalid', 'revision_required'])->exists(), 'Tandai minimal satu dokumen yang perlu diperbaiki.');
                $registration->revision_notes = $notes;
                $registration->revision_deadline = $deadline ? Carbon::parse($deadline) : $election->verification_end;
                Rule::ensure($registration->revision_deadline->gt(now()) && $registration->revision_deadline->lte($election->verification_end), 'Batas revisi harus sebelum akhir verifikasi dan setelah sekarang.');
            }
            if ($next === RegistrationStatus::Rejected) {
                $registration->rejection_reason = $notes;
            }
            if ($next === RegistrationStatus::Verified) {
                app(ValidateRegistrationCompleteness::class)->handle($registration, true);
                $registration->verified_at = now();
                $registration->verified_by = $actor->id;
            }
            $registration->reviewed_by = $actor->id;
            app(TransitionRegistration::class)->handle($registration, $next, $actor, $notes);
        }, 3);
    }

    public function document(User $actor, CandidateRegistrationDocument $document, string $status, ?string $note): void
    {
        Gate::forUser($actor)->authorize('review-registrations');
        DB::transaction(function () use ($actor, $document, $status, $note) {
            $election = Election::whereKey($document->registration->election_id)->lockForUpdate()->firstOrFail();
            $registration = CandidateRegistration::whereKey($document->candidate_registration_id)->lockForUpdate()->firstOrFail();
            $document->refresh();
            Rule::ensure($election->verificationOpen() && $registration->status === RegistrationStatus::UnderReview && ! $document->superseded_at, 'Dokumen tidak dapat diperiksa pada status ini.');
            Rule::ensure(in_array($status, ['valid', 'invalid', 'revision_required']), 'Status dokumen tidak valid.');
            Rule::ensure($status === 'valid' || filled($note), 'Catatan perbaikan wajib diisi.');
            $before = $document->only('verification_status');
            $document->forceFill(['verification_status' => $status, 'verification_note' => $note, 'verified_by' => $actor->id, 'verified_at' => now()])->save();
            app(RecordAudit::class)->handle($actor, 'document.reviewed', $document, $before, ['verification_status' => $status]);
        }, 3);
    }
}
