<?php

namespace App\Actions;

use App\Enums\RegistrationStatus;
use App\Models\CandidateRegistration;
use App\Models\CandidateRegistrationHistory;
use App\Models\Election;
use App\Models\RegistrationMember;
use App\Models\Student;
use App\Models\User;
use App\Support\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class SaveCandidateRegistrationAction
{
    public function handle(User $actor, Election $election, array $data, ?CandidateRegistration $registration = null): CandidateRegistration
    {
        abort_unless($actor->active && $actor->role === 'student' && $actor->student_id, 403);
        if ($registration) {
            Gate::forUser($actor)->authorize('update', $registration);
        }

        return DB::transaction(function () use ($actor, $election, $data, $registration) {
            $election = Election::whereKey($election->id)->lockForUpdate()->firstOrFail();
            Rule::ensure($election->registrationOpen(), 'Periode pendaftaran tidak sedang dibuka.');
            if ($registration) {
                $registration = CandidateRegistration::whereKey($registration->id)->lockForUpdate()->firstOrFail();
                Rule::ensure($registration->status === RegistrationStatus::Draft, 'Pendaftaran sudah dikunci.');
            }
            $chairman = Student::whereKey($actor->student_id)->lockForUpdate()->firstOrFail();
            app(CheckCandidateEligibilityAction::class)->handle($chairman, $election, $registration?->id);
            $vice = null;
            if (! empty($data['vice_lookup'])) {
                $lookup = mb_strtolower(trim($data['vice_lookup']));
                $vice = Student::where(fn ($q) => $q->where('nim', $lookup)->orWhere('email', $lookup))->lockForUpdate()->first();
                Rule::ensure($vice !== null, 'Wakil tidak ditemukan. Gunakan NIM atau email lengkap.', 'vice_lookup');
                Rule::ensure($vice->id !== $chairman->id, 'Ketua dan Wakil harus berbeda.', 'vice_lookup');
                app(CheckCandidateEligibilityAction::class)->handle($vice, $election, $registration?->id);
            }
            $new = ! $registration;
            $registration ??= new CandidateRegistration;
            $registration->fill(['election_id' => $election->id, 'chairman_student_id' => $chairman->id, 'vice_chairman_student_id' => $vice?->id, 'chairman_phone' => $data['chairman_phone'] ?? null, 'vice_chairman_phone' => $data['vice_chairman_phone'] ?? null, 'vision' => $data['vision'] ?? null, 'mission' => array_values(array_filter(array_map('trim', preg_split('/\R/', $data['mission_text'] ?? ''))))]);
            if ($new) {
                $registration->status = RegistrationStatus::Draft;
            }
            $registration->save();
            $registration->members()->delete();
            RegistrationMember::create(['election_id' => $election->id, 'candidate_registration_id' => $registration->id, 'student_id' => $chairman->id, 'position' => 'chairman']);
            if ($vice) {
                RegistrationMember::create(['election_id' => $election->id, 'candidate_registration_id' => $registration->id, 'student_id' => $vice->id, 'position' => 'vice']);
            }
            $registration->programs()->delete();
            foreach (array_values(array_filter(preg_split('/\R/', $data['programs_text'] ?? ''), fn ($line) => trim($line) !== '')) as $i => $line) {
                [$title,$description] = array_pad(explode('|', $line, 2), 2, null);
                $registration->programs()->create(['title' => mb_substr(trim($title), 0, 255), 'description' => $description ? trim($description) : null, 'sort_order' => $i]);
            }
            foreach ($election->requirements()->where('active', true)->whereIn('type', ['text', 'checkbox'])->get() as $requirement) {
                $value = $data['answers'][$requirement->id] ?? '';
                $registration->answers()->updateOrCreate(['requirement_id' => $requirement->id], ['value' => (string) $value]);
            }
            if ($new) {
                CandidateRegistrationHistory::create(['candidate_registration_id' => $registration->id, 'status_to' => 'draft', 'changed_by' => $actor->id, 'created_at' => now()]);
            }
            app(RecordAudit::class)->handle($actor, $new ? 'registration.created' : 'registration.draft_updated', $registration);

            return $registration;
        }, 3);
    }
}
