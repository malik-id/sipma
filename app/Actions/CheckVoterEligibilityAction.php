<?php
namespace App\Actions;
use App\Models\{Election, User, Voter};
use App\Enums\VoterStatus;
use App\Support\Rule;
final class CheckVoterEligibilityAction
{
    public function handle(User $user, Election $election, bool $forVoting = true): Voter
    {
        $student = $user->student()->first();
        Rule::ensure($user->active && $user->role === 'student' && $student && $student->isActive(), 'Akun mahasiswa tidak memenuhi syarat.');
        $voter = Voter::where('election_id',$election->id)->where('student_id',$student->id)->first();
        Rule::ensure($voter && $voter->voter_status === VoterStatus::Eligible, 'Anda belum terdaftar sebagai pemilih yang eligible.');
        if ($forVoting) {
            Rule::ensure($election->votingOpen(), 'Pemungutan suara belum dibuka atau sudah selesai.');
            Rule::ensure(! $voter->participation()->exists(), 'Anda sudah menggunakan hak pilih.');
        }
        return $voter;
    }
}
