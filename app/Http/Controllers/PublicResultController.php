<?php

namespace App\Http\Controllers;

use App\Actions\CalculateElectionResultAction;
use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicResultController extends Controller
{
    public function index(Request $request, CalculateElectionResultAction $calculator): View
    {
        $publishedElections = Election::where('status', 'published')
            ->where('voting_end', '<=', now())
            ->where('result_publish_at', '<=', now())
            ->latest('voting_end')
            ->get();

        $selectedElectionId = $request->input('election_id');
        $activeElection = $selectedElectionId
            ? $publishedElections->firstWhere('id', $selectedElectionId)
            : $publishedElections->first();

        $results = null;

        if ($activeElection) {
            $results = $calculator->handle($activeElection);
        }

        return view('public.results.index', compact('publishedElections', 'activeElection', 'results'));
    }
}
