<?php

namespace App\Actions;

use App\Models\CandidateRegistration;
use App\Support\Rule;

final class ValidateRegistrationCompleteness
{
    public function handle(CandidateRegistration $registration, bool $verifiedDocuments = false): void
    {
        $registration->load(['election.requirements', 'chairman', 'viceChairman', 'currentDocuments', 'answers', 'programs']);
        $election = $registration->election;
        app(CheckCandidateEligibilityAction::class)->handle($registration->chairman, $election, $registration->id);
        Rule::ensure($registration->viceChairman !== null, 'Data Wakil wajib diisi.');
        app(CheckCandidateEligibilityAction::class)->handle($registration->viceChairman, $election, $registration->id);
        Rule::ensure($registration->chairman_student_id !== $registration->vice_chairman_student_id, 'Ketua dan Wakil harus berbeda.');
        Rule::ensure(filled($registration->chairman_phone) && filled($registration->vice_chairman_phone), 'Nomor telepon Ketua dan Wakil wajib diisi.');
        Rule::ensure(filled($registration->vision) && count($registration->mission ?? []) > 0 && $registration->programs->isNotEmpty(), 'Visi, misi, dan minimal satu program kerja wajib diisi.');
        Rule::ensure(filled($registration->photo_path), 'Foto pasangan wajib diunggah.');
        foreach ($election->requirements->where('active', true) as $requirement) {
            if ($requirement->type === 'document') {
                $document = $registration->currentDocuments->firstWhere('requirement_id', $requirement->id);
                Rule::ensure(! $requirement->required || $document, 'Dokumen '.$requirement->name.' wajib diunggah.');
                if ($document) {
                    Rule::ensure($verifiedDocuments ? $document->verification_status === 'valid' : ! in_array($document->verification_status, ['invalid', 'revision_required']), 'Dokumen '.$requirement->name.' belum memenuhi persyaratan.');
                }
            } elseif (in_array($requirement->type, ['text', 'checkbox'])) {
                $answer = $registration->answers->firstWhere('requirement_id', $requirement->id)?->value;
                Rule::ensure(! $requirement->required || ($requirement->type === 'checkbox' ? $answer === '1' : filled($answer)), 'Persyaratan '.$requirement->name.' wajib diisi.');
            }
            // system_validation is the fixed active-student + semester + distinct-pair rule above.
        }
    }
}
