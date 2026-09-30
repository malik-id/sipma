<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Student;
use App\Models\Voter;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Find the most relevant active/upcoming election for the hero
        $activeElection = Election::whereNotIn('status', ['draft', 'archived'])
            ->latest('voting_start')
            ->first();

        // Quick stats for credibility section
        $totalStudents = Student::where('student_status', 'active')->count();
        $totalElections = Election::whereNotIn('status', ['draft'])->count();
        $totalVoters = Voter::where('voter_status', 'eligible')->count();

        // Published candidates for the active election (preview)
        $featuredCandidates = collect();
        if ($activeElection && in_array($activeElection->status->value ?? $activeElection->status, ['upcoming', 'voting', 'closed', 'published'])) {
            $featuredCandidates = Candidate::where('election_id', $activeElection->id)
                ->where('status', 'active')
                ->whereNotNull('candidate_number')
                ->with(['chairman', 'viceChairman'])
                ->orderBy('candidate_number')
                ->get();
        }

        return view('public.home', compact(
            'activeElection',
            'totalStudents',
            'totalElections',
            'totalVoters',
            'featuredCandidates',
        ));
    }
}
