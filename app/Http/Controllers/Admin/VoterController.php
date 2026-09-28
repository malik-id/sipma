<?php

namespace App\Http\Controllers\Admin;

use App\Actions\RecordAudit;
use App\Enums\StudentStatus;
use App\Enums\VoterStatus;
use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Student;
use App\Models\Voter;
use App\Models\VotingParticipation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VoterController extends Controller
{
    public function index(Request $request): View
    {
        $elections = Election::latest('id')->get();
        $selectedElectionId = $request->input('election_id', $elections->first()?->id);

        $query = Voter::with(['student', 'election']);

        if ($selectedElectionId) {
            $query->where('election_id', $selectedElectionId);
        }

        if ($search = $request->input('search')) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('voter_status', $status);
        }

        $voters = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => $selectedElectionId ? Voter::where('election_id', $selectedElectionId)->count() : 0,
            'eligible' => $selectedElectionId ? Voter::where('election_id', $selectedElectionId)->where('voter_status', 'eligible')->count() : 0,
            'not_eligible' => $selectedElectionId ? Voter::where('election_id', $selectedElectionId)->where('voter_status', 'not_eligible')->count() : 0,
        ];

        return view('admin.voters.index', [
            'voters' => $voters,
            'elections' => $elections,
            'selectedElectionId' => $selectedElectionId,
            'stats' => $stats,
            'statuses' => VoterStatus::cases(),
        ]);
    }

    public function generate(Request $request, RecordAudit $audit): RedirectResponse
    {
        $request->validate([
            'election_id' => ['required', 'exists:elections,id'],
            'student_status' => ['required', Rule::enum(StudentStatus::class)],
        ]);

        $election = Election::findOrFail($request->input('election_id'));
        $targetStatus = $request->input('student_status');

        $students = Student::where('student_status', $targetStatus)->get();
        $added = 0;

        foreach ($students as $student) {
            $voter = Voter::firstOrCreate(
                [
                    'election_id' => $election->id,
                    'student_id' => $student->id,
                ],
                [
                    'voter_status' => VoterStatus::Eligible,
                    'verified_at' => now(),
                    'verified_by' => $request->user()->id,
                    'notes' => 'Didaftarkan otomatis oleh sistem (Status: '.$targetStatus.')',
                ]
            );

            if ($voter->wasRecentlyCreated) {
                $added++;
            }
        }

        $audit->handle($request->user(), 'voter.generate', $election, null, [
            'election_id' => $election->id,
            'added' => $added,
            'filter_status' => $targetStatus,
        ]);

        return redirect()->route('admin.voters.index', ['election_id' => $election->id])
            ->with('success', "Berhasil mendaftarkan {$added} mahasiswa ke DPT pemilihan {$election->name}.");
    }

    public function updateStatus(Request $request, Voter $voter, RecordAudit $audit): RedirectResponse
    {
        $validated = $request->validate([
            'voter_status' => ['required', Rule::enum(VoterStatus::class)],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $old = $voter->toArray();

        $voter->update([
            'voter_status' => $validated['voter_status'],
            'notes' => $validated['notes'],
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
        ]);

        $audit->handle($request->user(), 'voter.update_status', $voter, $old, $voter->fresh()->toArray());

        return back()->with('success', "Status pemilih {$voter->student->name} berhasil diubah.");
    }

    public function destroy(Request $request, Voter $voter, RecordAudit $audit): RedirectResponse
    {
        // Cek jika pemilih sudah voting
        if (VotingParticipation::where('election_id', $voter->election_id)->where('voter_id', $voter->id)->exists()) {
            return back()->withErrors(['error' => 'Pemilih ini tidak dapat dihapus karena sudah memberikan suara dalam pemilihan.']);
        }

        $old = $voter->toArray();
        $name = $voter->student->name;
        $voter->delete();

        $audit->handle($request->user(), 'voter.delete', null, $old, null);

        return back()->with('success', "Data pemilih {$name} berhasil dihapus dari DPT.");
    }
}
