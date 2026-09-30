<?php

namespace App\Http\Controllers\Admin;

use App\Actions\EstablishCandidateAction;
use App\Actions\RecordAudit;
use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidateRegistration;
use App\Models\Election;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('manage-candidates');

        $selectedElectionId = $request->input('election_id');
        $elections = Election::latest('voting_start')->get();

        $activeElection = $selectedElectionId
            ? $elections->firstWhere('id', $selectedElectionId)
            : ($elections->firstWhere('status.value', '!=', 'archived') ?? $elections->first());

        $candidates = collect();
        $verifiedRegistrations = collect();

        if ($activeElection) {
            $candidates = Candidate::where('election_id', $activeElection->id)
                ->with(['chairman', 'viceChairman', 'registration'])
                ->orderByRaw('candidate_number IS NULL, candidate_number ASC')
                ->get();

            $verifiedRegistrations = CandidateRegistration::where('election_id', $activeElection->id)
                ->where('status', RegistrationStatus::Verified)
                ->with(['chairman', 'viceChairman'])
                ->get();
        }

        return view('admin.candidates.index', compact(
            'elections',
            'activeElection',
            'candidates',
            'verifiedRegistrations'
        ));
    }

    public function establish(CandidateRegistration $registration, EstablishCandidateAction $action): RedirectResponse
    {
        Gate::authorize('manage-candidates');

        try {
            $candidate = $action->handle(auth()->user(), $registration);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return back()->with('success', "Pasangan {$candidate->chairman->name} & {$candidate->viceChairman->name} berhasil ditetapkan sebagai calon resmi.");
    }

    public function assignNumber(Request $request, Candidate $candidate): RedirectResponse
    {
        Gate::authorize('manage-candidates');

        $request->validate([
            'candidate_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('candidates')->where(fn ($q) => $q->where('election_id', $candidate->election_id))->ignore($candidate->id),
            ],
        ], [
            'candidate_number.unique' => 'Nomor urut ini sudah digunakan oleh pasangan calon lain pada pemilihan ini.',
            'candidate_number.required' => 'Nomor urut wajib diisi.',
            'candidate_number.min' => 'Nomor urut minimal 1.',
        ]);

        $before = $candidate->only('candidate_number');
        $candidate->update(['candidate_number' => $request->candidate_number]);

        app(RecordAudit::class)->handle(auth()->user(), 'candidate.number_assigned', $candidate, $before, ['candidate_number' => $request->candidate_number]);

        return back()->with('success', "Nomor urut {$request->candidate_number} berhasil ditetapkan untuk pasangan {$candidate->chairman->name}.");
    }

    public function toggleStatus(Candidate $candidate): RedirectResponse
    {
        Gate::authorize('manage-candidates');

        $newStatus = $candidate->status === 'active' ? 'disqualified' : 'active';
        $before = $candidate->only('status');
        $candidate->update(['status' => $newStatus]);

        app(RecordAudit::class)->handle(auth()->user(), 'candidate.status_changed', $candidate, $before, ['status' => $newStatus]);

        $label = $newStatus === 'active' ? 'diaktifkan kembali' : 'didiskualifikasi';

        return back()->with('success', "Status kandidat berhasil {$label}.");
    }
}
