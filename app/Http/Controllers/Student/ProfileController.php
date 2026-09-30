<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CandidateRegistration;
use App\Models\Election;
use App\Models\Voter;
use App\Models\VotingParticipation;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $student = $user->student;

        // All elections with the student's voter and participation status
        $elections = Election::whereNotIn('status', ['draft', 'archived'])
            ->latest('voting_start')
            ->get();

        $voterRecords = $student
            ? Voter::where('student_id', $student->id)
                ->with('election')
                ->get()
                ->keyBy('election_id')
            : collect();

        $participations = $student
            ? VotingParticipation::whereIn('election_id', $elections->pluck('id'))
                ->whereIn('voter_id', $voterRecords->pluck('id'))
                ->get()
                ->keyBy('election_id')
            : collect();

        $registrations = $student
            ? CandidateRegistration::where(function ($q) use ($student) {
                $q->where('chairman_student_id', $student->id)
                    ->orWhere('vice_chairman_student_id', $student->id);
            })
                ->with('election')
                ->latest()
                ->get()
            : collect();

        return view('student.profile', compact(
            'user',
            'student',
            'elections',
            'voterRecords',
            'participations',
            'registrations',
        ));
    }
}
