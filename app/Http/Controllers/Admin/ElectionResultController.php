<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CalculateElectionResultAction;
use App\Actions\RecordAudit;
use App\Enums\ElectionStatus;
use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ElectionResultController extends Controller
{
    public function index(Request $request, CalculateElectionResultAction $calculator): View
    {
        Gate::authorize('publish-results');

        $elections = Election::latest('voting_end')->get();
        $selectedElectionId = $request->input('election_id');

        $activeElection = $selectedElectionId
            ? $elections->firstWhere('id', $selectedElectionId)
            : ($elections->firstWhere('status.value', 'published')
                ?? $elections->firstWhere('status.value', 'closed')
                ?? $elections->first());

        $results = null;

        if ($activeElection) {
            $results = $calculator->handle($activeElection, ignorePublicCheck: true);
        }

        return view('admin.results.index', compact('elections', 'activeElection', 'results'));
    }

    public function publish(Election $election): RedirectResponse
    {
        Gate::authorize('publish-results');

        if (now()->lt($election->voting_end)) {
            return back()->withErrors(['error' => 'Hasil tidak dapat dipublikasikan sebelum waktu pemungutan suara berakhir.']);
        }

        $before = ['status' => $election->status->value, 'result_publish_at' => $election->result_publish_at];

        $election->update([
            'status' => ElectionStatus::Published,
            'result_publish_at' => $election->result_publish_at && $election->result_publish_at->lte(now()) ? $election->result_publish_at : now(),
        ]);

        app(RecordAudit::class)->handle(auth()->user(), 'results.published', $election, $before, [
            'status' => ElectionStatus::Published->value,
            'result_publish_at' => $election->result_publish_at,
        ]);

        return back()->with('success', "Hasil pemilihan '{$election->name}' resmi dipublikasikan.");
    }
}
