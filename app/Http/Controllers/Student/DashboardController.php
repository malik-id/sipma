<?php

namespace App\Http\Controllers\Student;

use App\Enums\ElectionStatus;
use App\Http\Controllers\Controller;
use App\Models\CandidateRegistration;
use App\Models\Election;
use App\Models\Voter;
use App\Models\VotingParticipation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard with active election widgets and countdowns.
     */
    public function index(): View
    {
        $user = auth()->user();
        $student = $user->student;

        // Fetch the most relevant active or upcoming election
        $activeElection = Election::query()
            ->whereNotIn('status', [ElectionStatus::Archived])
            ->latest('voting_start')
            ->first();

        $phaseInfo = $activeElection ? $activeElection->getCurrentPhaseInfo() : null;

        // Check student's voter status for the active election
        $voterRecord = null;
        $hasVoted = false;
        $candidateRegistration = null;

        if ($activeElection && $student) {
            $voterRecord = Voter::where('election_id', $activeElection->id)
                ->where('student_id', $student->id)
                ->first();

            if ($voterRecord) {
                $hasVoted = VotingParticipation::where('election_id', $activeElection->id)
                    ->where('voter_id', $voterRecord->id)
                    ->exists();
            }

            // Check if student is part of any candidate registration
            $candidateRegistration = CandidateRegistration::where('election_id', $activeElection->id)
                ->where(function ($q) use ($student) {
                    $q->where('chairman_student_id', $student->id)
                        ->orWhere('vice_chairman_student_id', $student->id);
                })
                ->first();
        }

        // All non-archived elections list
        $allElections = Election::query()
            ->whereNotIn('status', [ElectionStatus::Archived])
            ->latest()
            ->get();

        return view('student.dashboard', compact(
            'user',
            'student',
            'activeElection',
            'phaseInfo',
            'voterRecord',
            'hasVoted',
            'candidateRegistration',
            'allElections'
        ));
    }
}
