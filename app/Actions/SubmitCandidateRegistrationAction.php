<?php

namespace App\Actions;

use App\Enums\RegistrationStatus;
use App\Models\CandidateRegistration;
use App\Models\Election;
use App\Models\User;
use App\Support\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class SubmitCandidateRegistrationAction
{
    public function handle(User $actor, CandidateRegistration $registration, bool $resubmit = false): void
    {
        Gate::forUser($actor)->authorize('update', $registration);
        DB::transaction(function () use ($actor, $registration, $resubmit) {
            $election = Election::whereKey($registration->election_id)->lockForUpdate()->firstOrFail();
            $registration = CandidateRegistration::whereKey($registration->id)->lockForUpdate()->firstOrFail();
            if ($resubmit) {
                Rule::ensure($registration->status === RegistrationStatus::RevisionRequired && $election->verificationOpen(), 'Pengiriman ulang belum tersedia atau periode verifikasi berakhir.');
                Rule::ensure(! $registration->revision_deadline || now()->lt($registration->revision_deadline), 'Batas waktu perbaikan telah berakhir.');
            } else {
                Rule::ensure($registration->status === RegistrationStatus::Draft && $election->registrationOpen(), 'Pendaftaran tidak dapat diajukan pada saat ini.');
            }
            app(ValidateRegistrationCompleteness::class)->handle($registration);
            if ($resubmit) {
                $registration->resubmitted_at = now();
            } else {
                $registration->registration_number = 'BC-'.$election->registration_start->format('Y').'-'.$election->id.'-'.str_pad((string) $registration->id, 4, '0', STR_PAD_LEFT);
                $registration->submitted_at = now();
            }
            app(TransitionRegistration::class)->handle($registration, $resubmit ? RegistrationStatus::Resubmitted : RegistrationStatus::Submitted, $actor);
        }, 3);
    }
}
