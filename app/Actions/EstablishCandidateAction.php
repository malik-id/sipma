<?php
namespace App\Actions;
use App\Enums\{ElectionStatus, RegistrationStatus};
use App\Models\{Candidate, CandidateRegistration, Election, User};
use App\Support\Rule;
use Illuminate\Support\Facades\{DB, Gate};
final class EstablishCandidateAction
{
    public function handle(User $actor, CandidateRegistration $registration): Candidate
    {
        Gate::forUser($actor)->authorize('manage-candidates');
        return DB::transaction(function () use ($actor,$registration) {
            $election = Election::whereKey($registration->election_id)->lockForUpdate()->firstOrFail();
            $election->assertMutable();
            $registration = CandidateRegistration::whereKey($registration->id)->lockForUpdate()->firstOrFail();
            Rule::ensure($election->status === ElectionStatus::CandidateFinalization && now()->gte($election->verification_end), 'Penetapan dilakukan setelah verifikasi pada tahap penetapan calon.');
            Rule::ensure($registration->status === RegistrationStatus::Verified, 'Hanya pendaftaran terverifikasi yang dapat ditetapkan.');
            app(ValidateRegistrationCompleteness::class)->handle($registration,true);
            $candidate = Candidate::create(['election_id'=>$election->id,'candidate_registration_id'=>$registration->id,'chairman_student_id'=>$registration->chairman_student_id,'vice_chairman_student_id'=>$registration->vice_chairman_student_id,'vision'=>$registration->vision,'mission'=>$registration->mission,'photo_path'=>$registration->photo_path,'status'=>'active','established_at'=>now()]);
            $registration->established_at = now();
            app(TransitionRegistration::class)->handle($registration,RegistrationStatus::Established,$actor);
            return $candidate;
        },3);
    }
}
