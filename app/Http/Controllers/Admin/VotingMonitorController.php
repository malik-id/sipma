<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Voter;
use App\Models\VotingParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class VotingMonitorController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('view-monitor');

        $elections = Election::latest('voting_start')->get();
        $selectedElectionId = $request->input('election_id');

        $activeElection = $selectedElectionId
            ? $elections->firstWhere('id', $selectedElectionId)
            : ($elections->firstWhere('status.value', 'voting') ?? $elections->first());

        $stats = [
            'total_voters' => 0,
            'eligible_voters' => 0,
            'participated' => 0,
            'not_participated' => 0,
            'turnout_percentage' => 0,
        ];

        $voterRecords = collect();

        if ($activeElection) {
            $totalVoters = Voter::where('election_id', $activeElection->id)->count();
            $eligibleVoters = Voter::where('election_id', $activeElection->id)
                ->where('voter_status', 'eligible')
                ->count();
            $participated = VotingParticipation::where('election_id', $activeElection->id)->count();
            $notParticipated = max(0, $eligibleVoters - $participated);
            $turnoutPercentage = $eligibleVoters > 0 ? round(($participated / $eligibleVoters) * 100, 2) : 0;

            $stats = [
                'total_voters' => $totalVoters,
                'eligible_voters' => $eligibleVoters,
                'participated' => $participated,
                'not_participated' => $notParticipated,
                'turnout_percentage' => $turnoutPercentage,
            ];

            $query = Voter::where('election_id', $activeElection->id)
                ->with(['student', 'participation']);

            if ($request->filled('voted_status')) {
                if ($request->voted_status === 'voted') {
                    $query->has('participation');
                } elseif ($request->voted_status === 'not_voted') {
                    $query->doesntHave('participation');
                }
            }

            if ($request->filled('study_program')) {
                $query->whereHas('student', fn ($q) => $q->where('study_program', $request->study_program));
            }

            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                });
            }

            $voterRecords = $query->paginate(20)->withQueryString();
        }

        return view('admin.voting-monitor.index', compact('elections', 'activeElection', 'stats', 'voterRecords'));
    }
}
