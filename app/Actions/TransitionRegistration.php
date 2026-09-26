<?php
namespace App\Actions;
use App\Enums\RegistrationStatus;
use App\Models\{CandidateRegistration, CandidateRegistrationHistory, User};
use App\Notifications\RegistrationUpdated;
use App\Support\Rule;
final class TransitionRegistration
{
    // Internal helper. Call only inside an authorized transaction with a locked registration.
    public function handle(CandidateRegistration $registration, RegistrationStatus $next, User $actor, ?string $notes = null): void
    {
        $previous = $registration->status;
        Rule::ensure($previous->permits($next), 'Perubahan status pendaftaran tidak diizinkan.');
        $registration->status = $next;
        $registration->save();
        CandidateRegistrationHistory::create(['candidate_registration_id'=>$registration->id,'status_from'=>$previous->value,'status_to'=>$next->value,'notes'=>$notes,'changed_by'=>$actor->id,'created_at'=>now()]);
        app(RecordAudit::class)->handle($actor,'registration.'.$next->value,$registration,['status'=>$previous->value],['status'=>$next->value]);
        User::whereIn('student_id',[$registration->chairman_student_id,$registration->vice_chairman_student_id])->get()->each(fn ($user) => $user->notify(new RegistrationUpdated($registration)));
    }
}
