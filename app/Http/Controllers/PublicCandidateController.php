<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicCandidateController extends Controller
{
    public function index(Request $request): View
    {
        $elections = Election::whereNotIn('status', ['draft', 'archived'])
            ->latest('voting_start')
            ->get();

        $selectedElectionId = $request->input('election_id');
        $activeElection = $selectedElectionId
            ? $elections->firstWhere('id', $selectedElectionId)
            : ($elections->firstWhere('status.value', '!=', 'draft') ?? $elections->first());

        $candidates = collect();

        if ($activeElection) {
            $candidates = Candidate::where('election_id', $activeElection->id)
                ->where('status', 'active')
                ->whereNotNull('candidate_number')
                ->with(['chairman', 'viceChairman', 'registration.programs'])
                ->orderBy('candidate_number', 'asc')
                ->get();
        }

        return view('public.candidates.index', compact('elections', 'activeElection', 'candidates'));
    }
}
