<?php

namespace App\Http\Controllers\Admin;

use App\Actions\EstablishCandidateAction;
use App\Actions\RecordAudit;
use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidateRegistration;
use App\Models\Election;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
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

    public function create(Request $request): View
    {
        Gate::authorize('manage-candidates');

        $elections = Election::latest('voting_start')->get();
        $selectedElectionId = $request->input('election_id');
        $activeElection = $selectedElectionId
            ? $elections->firstWhere('id', $selectedElectionId)
            : ($elections->firstWhere('status.value', '!=', 'archived') ?? $elections->first());

        $students = Student::orderBy('name')->get();

        return view('admin.candidates.create', compact('elections', 'activeElection', 'students'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-candidates');

        $validated = $request->validate([
            'election_id' => ['required', 'exists:elections,id'],
            'chairman_student_id' => ['required', 'exists:students,id'],
            'vice_chairman_student_id' => ['nullable', 'exists:students,id', 'different:chairman_student_id'],
            'candidate_number' => [
                'nullable',
                'integer',
                'min:1',
                Rule::unique('candidates')->where(fn ($q) => $q->where('election_id', $request->election_id)),
            ],
            'vision' => ['required', 'string', 'max:2000'],
            'mission' => ['required', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
            'chairman_phone' => ['nullable', 'string', 'max:30'],
            'vice_chairman_phone' => ['nullable', 'string', 'max:30'],
        ], [
            'chairman_student_id.required' => 'Calon Ketua wajib dipilih.',
            'vice_chairman_student_id.different' => 'Calon Wakil Ketua tidak boleh sama dengan Calon Ketua.',
            'candidate_number.unique' => 'Nomor urut ini sudah digunakan pada pemilihan ini.',
            'vision.required' => 'Visi pasangan calon wajib diisi.',
            'mission.required' => 'Misi pasangan calon wajib diisi.',
            'photo.image' => 'Berkas foto harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 3MB.',
        ]);

        $election = Election::findOrFail($validated['election_id']);

        // Check if candidates already registered
        $existingChairman = Candidate::where('election_id', $election->id)
            ->where(function ($q) use ($validated) {
                $q->where('chairman_student_id', $validated['chairman_student_id'])
                    ->orWhere('vice_chairman_student_id', $validated['chairman_student_id']);
            })->exists();

        if ($existingChairman) {
            return back()->withInput()->withErrors(['chairman_student_id' => 'Mahasiswa calon ketua sudah terdaftar sebagai calon pada pemilihan ini.']);
        }

        if (! empty($validated['vice_chairman_student_id'])) {
            $existingVice = Candidate::where('election_id', $election->id)
                ->where(function ($q) use ($validated) {
                    $q->where('chairman_student_id', $validated['vice_chairman_student_id'])
                        ->orWhere('vice_chairman_student_id', $validated['vice_chairman_student_id']);
                })->exists();

            if ($existingVice) {
                return back()->withInput()->withErrors(['vice_chairman_student_id' => 'Mahasiswa calon wakil sudah terdaftar sebagai calon pada pemilihan ini.']);
            }
        }

        // Upload photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('candidates/photos', 'public');
        }

        // Parse mission lines
        $missions = array_values(array_filter(
            array_map('trim', explode("\n", $validated['mission'])),
            fn ($line) => ! empty($line)
        ));

        DB::transaction(function () use ($validated, $election, $photoPath, $missions) {
            // Generate reg number
            $regNumber = 'REG-'.$election->id.'-'.strtoupper(bin2hex(random_bytes(3)));

            // Create CandidateRegistration
            $registration = CandidateRegistration::create([
                'election_id' => $election->id,
                'registration_number' => $regNumber,
                'chairman_student_id' => $validated['chairman_student_id'],
                'vice_chairman_student_id' => $validated['vice_chairman_student_id'] ?? null,
                'chairman_phone' => $validated['chairman_phone'] ?? null,
                'vice_chairman_phone' => $validated['vice_chairman_phone'] ?? null,
                'vision' => $validated['vision'],
                'mission' => $missions,
                'photo_path' => $photoPath,
                'status' => RegistrationStatus::Established,
                'submitted_at' => now(),
                'verified_at' => now(),
                'established_at' => now(),
                'verified_by' => auth()->id(),
                'reviewed_by' => auth()->id(),
            ]);

            // Create official Candidate
            $candidate = Candidate::create([
                'election_id' => $election->id,
                'candidate_registration_id' => $registration->id,
                'candidate_number' => $validated['candidate_number'] ?? null,
                'chairman_student_id' => $validated['chairman_student_id'],
                'vice_chairman_student_id' => $validated['vice_chairman_student_id'] ?? null,
                'vision' => $validated['vision'],
                'mission' => $missions,
                'photo_path' => $photoPath,
                'status' => 'active',
                'established_at' => now(),
            ]);

            app(RecordAudit::class)->handle(auth()->user(), 'candidate.created_by_admin', $candidate, null, $candidate->toArray());
        });

        return redirect()->route('admin.candidates.index', ['election_id' => $election->id])
            ->with('success', 'Pasangan calon berhasil didaftarkan langsung oleh Admin.');
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

    public function destroy(Candidate $candidate): RedirectResponse
    {
        Gate::authorize('manage-candidates');

        $oldValues = $candidate->toArray();
        $electionId = $candidate->election_id;
        $name = $candidate->chairman->name;

        DB::transaction(function () use ($candidate) {
            // Delete ballots
            DB::table('ballots')->where('candidate_id', $candidate->id)->delete();

            // If photo exists
            if ($candidate->photo_path && Storage::disk('public')->exists($candidate->photo_path)) {
                Storage::disk('public')->delete($candidate->photo_path);
            }

            // If linked to registration, delete registration or reset status
            if ($registration = $candidate->registration) {
                DB::table('candidate_registration_documents')->where('candidate_registration_id', $registration->id)->delete();
                DB::table('candidate_registration_histories')->where('candidate_registration_id', $registration->id)->delete();
                DB::table('candidate_programs')->where('candidate_registration_id', $registration->id)->delete();
                DB::table('requirement_answers')->where('candidate_registration_id', $registration->id)->delete();
                DB::table('registration_members')->where('candidate_registration_id', $registration->id)->delete();
                $registration->delete();
            }

            $candidate->delete();
        });

        app(RecordAudit::class)->handle(auth()->user(), 'candidate.deleted', null, $oldValues, null);

        return redirect()->route('admin.candidates.index', ['election_id' => $electionId])
            ->with('success', "Calon '{$name}' beserta data surat suara terkait berhasil dihapus.");
    }
}
