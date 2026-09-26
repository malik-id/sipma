<?php
namespace App\Actions;
use App\Models\{Ballot, Candidate, Election, Student, User, VotingParticipation};
use App\Support\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
final class CastVoteAction
{
    public function handle(User $user, Election $election, int $candidateId): void
    {
        DB::transaction(function () use ($user,$election,$candidateId) {
            // Same lock ordering as administrative writes: election -> student -> voter.
            $election = Election::whereKey($election->id)->lockForUpdate()->firstOrFail();
            $user = User::whereKey($user->id)->firstOrFail();
            Student::whereKey($user->student_id)->lockForUpdate()->first();
            $voter = app(CheckVoterEligibilityAction::class)->handle($user,$election);
            $voter = $voter->newQuery()->whereKey($voter->id)->lockForUpdate()->firstOrFail();
            Rule::ensure(! $voter->participation()->exists(), 'Anda sudah menggunakan hak pilih.');
            $candidate = Candidate::where('election_id',$election->id)->whereKey($candidateId)->where('status','active')->whereNotNull('candidate_number')->first();
            Rule::ensure($candidate !== null, 'Kandidat tidak tersedia pada pemilihan ini.', 'candidate_id');
            VotingParticipation::create(['election_id'=>$election->id,'voter_id'=>$voter->id,'voted_at'=>now(),'created_at'=>now()]);
            $uuid = (string) Str::uuid();
            Ballot::create(['id'=>$uuid,'ballot_uuid'=>$uuid,'election_id'=>$election->id,'candidate_id'=>$candidate->id,'integrity_hash'=>hash_hmac('sha256', $uuid.'|'.$election->id.'|'.$candidate->id, config('app.key')),'submitted_at'=>$election->voting_end,'created_at'=>$election->voting_end]);
        }, 3);
    }
}
