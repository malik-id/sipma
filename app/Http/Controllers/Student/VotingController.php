<?php

namespace App\Http\Controllers\Student;

use App\Actions\CastVoteAction;
use App\Enums\ElectionStatus;
use App\Enums\VoterStatus;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Voter;
use App\Models\VotingParticipation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VotingController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $student = $user->student;

        // Find currently active voting election, or the most recent upcoming/active one
        $election = Election::where('status', ElectionStatus::Voting)
            ->where('voting_start', '<=', now())
            ->where('voting_end', '>=', now())
            ->first();

        if (! $election) {
            $upcomingElection = Election::whereIn('status', [ElectionStatus::Voting, ElectionStatus::CandidateFinalization, ElectionStatus::Verification, ElectionStatus::Registration])
                ->latest('voting_start')
                ->first();

            return view('student.voting.not-open', [
                'election' => $upcomingElection,
            ]);
        }

        return $this->show($election);
    }

    public function show(Election $election): View
    {
        $user = auth()->user();
        $student = $user->student;

        // 1. Check Voter Record
        $voter = Voter::where('election_id', $election->id)
            ->where('student_id', $student->id)
            ->first();

        $hasVoted = false;
        $participation = null;

        if ($voter) {
            $participation = VotingParticipation::where('election_id', $election->id)
                ->where('voter_id', $voter->id)
                ->first();
            $hasVoted = $participation !== null;
        }

        // 2. If already voted, show already voted view
        if ($hasVoted) {
            return view('student.voting.already-voted', compact('election', 'voter', 'participation'));
        }

        // 3. If voter not eligible
        $isEligible = $voter && $voter->voter_status === VoterStatus::Eligible;

        if (! $isEligible) {
            return view('student.voting.ineligible', compact('election', 'voter'));
        }

        // 4. If voting time is outside window
        if (! $election->votingOpen()) {
            return view('student.voting.not-open', compact('election'));
        }

        // 5. Eligible & Voting is open: Load official candidates
        $candidates = Candidate::where('election_id', $election->id)
            ->where('status', 'active')
            ->whereNotNull('candidate_number')
            ->with(['chairman', 'viceChairman', 'registration.programs'])
            ->orderBy('candidate_number', 'asc')
            ->get();

        return view('student.voting.ballot', compact('election', 'voter', 'candidates'));
    }

    public function vote(Request $request, Election $election, CastVoteAction $action): RedirectResponse
    {
        $request->validate([
            'candidate_id' => ['required', 'exists:candidates,id'],
        ], [
            'candidate_id.required' => 'Pilih salah satu pasangan calon untuk memberikan suara.',
            'candidate_id.exists' => 'Pasangan calon yang dipilih tidak valid.',
        ]);

        try {
            $action->handle(auth()->user(), $election, (int) $request->candidate_id);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return redirect()->route('student.voting.completed', $election)
            ->with('success', 'Suara Anda telah berhasil direkam secara aman dan anonim!');
    }

    public function completed(Election $election): View
    {
        $user = auth()->user();
        $student = $user->student;

        $voter = Voter::where('election_id', $election->id)
            ->where('student_id', $student->id)
            ->first();

        $participation = null;
        if ($voter) {
            $participation = VotingParticipation::where('election_id', $election->id)
                ->where('voter_id', $voter->id)
                ->first();
        }

        return view('student.voting.completed', compact('election', 'participation'));
    }
}
