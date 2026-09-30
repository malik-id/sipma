<?php

namespace App\Http\Controllers\Student;

use App\Actions\SaveCandidateRegistrationAction;
use App\Actions\SubmitCandidateRegistrationAction;
use App\Enums\ElectionStatus;
use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\CandidateRegistration;
use App\Models\CandidateRegistrationDocument;
use App\Models\CandidateRequirement;
use App\Models\Election;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CandidateRegistrationController extends Controller
{
    public function index(): View
    {
        $student = auth()->user()->student;

        $registrations = CandidateRegistration::where(function ($q) use ($student) {
            $q->where('chairman_student_id', $student->id)
                ->orWhere('vice_chairman_student_id', $student->id);
        })
            ->with(['election', 'chairman', 'viceChairman'])
            ->latest()
            ->get();

        $openElection = Election::where('status', ElectionStatus::Registration)
            ->where('registration_start', '<=', now())
            ->where('registration_end', '>=', now())
            ->first();

        return view('student.registration.index', compact('registrations', 'openElection'));
    }

    public function create(): View
    {
        $election = Election::where('status', ElectionStatus::Registration)
            ->where('registration_start', '<=', now())
            ->where('registration_end', '>=', now())
            ->firstOrFail();

        return view('student.registration.create', compact('election'));
    }

    public function store(Request $request, SaveCandidateRegistrationAction $action): RedirectResponse
    {
        $request->validate([
            'election_id' => ['required', 'exists:elections,id'],
            'vice_lookup' => ['nullable', 'string', 'max:255'],
            'chairman_phone' => ['required', 'string', 'max:30'],
            'vice_chairman_phone' => ['nullable', 'string', 'max:30'],
            'vision' => ['nullable', 'string', 'max:2000'],
            'mission_text' => ['nullable', 'string', 'max:5000'],
            'programs_text' => ['nullable', 'string', 'max:5000'],
        ]);

        $election = Election::findOrFail($request->election_id);

        try {
            $registration = $action->handle(auth()->user(), $election, $request->all());
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('registration.show', $registration)
            ->with('success', 'Draft pendaftaran berhasil disimpan. Lengkapi dokumen persyaratan sebelum mengajukan.');
    }

    public function show(CandidateRegistration $registration): View
    {
        Gate::authorize('view', $registration);

        $registration->load([
            'election.requirements',
            'chairman',
            'viceChairman',
            'currentDocuments.requirement',
            'programs',
            'histories',
            'answers',
        ]);

        $missingDocs = $registration->election->requirements
            ->where('active', true)
            ->where('type', 'file')
            ->filter(fn ($req) => ! $registration->currentDocuments->firstWhere('requirement_id', $req->id));

        return view('student.registration.show', compact('registration', 'missingDocs'));
    }

    public function edit(CandidateRegistration $registration): View
    {
        Gate::authorize('update', $registration);

        abort_if(
            ! in_array($registration->status, [RegistrationStatus::Draft, RegistrationStatus::RevisionRequired]),
            403,
            'Pendaftaran ini tidak dapat diedit.'
        );

        $registration->load(['election.requirements', 'chairman', 'viceChairman', 'programs', 'answers']);

        return view('student.registration.edit', compact('registration'));
    }

    public function update(Request $request, CandidateRegistration $registration, SaveCandidateRegistrationAction $action): RedirectResponse
    {
        Gate::authorize('update', $registration);

        $request->validate([
            'vice_lookup' => ['nullable', 'string', 'max:255'],
            'chairman_phone' => ['required', 'string', 'max:30'],
            'vice_chairman_phone' => ['nullable', 'string', 'max:30'],
            'vision' => ['nullable', 'string', 'max:2000'],
            'mission_text' => ['nullable', 'string', 'max:5000'],
            'programs_text' => ['nullable', 'string', 'max:5000'],
        ]);

        $election = Election::findOrFail($registration->election_id);

        try {
            $action->handle(auth()->user(), $election, $request->all(), $registration);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('registration.show', $registration)
            ->with('success', 'Draft pendaftaran berhasil diperbarui.');
    }

    public function submit(Request $request, CandidateRegistration $registration, SubmitCandidateRegistrationAction $action): RedirectResponse
    {
        try {
            $action->handle(auth()->user(), $registration);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return redirect()->route('registration.show', $registration)
            ->with('success', 'Pendaftaran Anda berhasil diajukan! Panitia akan segera melakukan verifikasi.');
    }

    public function resubmit(Request $request, CandidateRegistration $registration, SubmitCandidateRegistrationAction $action): RedirectResponse
    {
        try {
            $action->handle(auth()->user(), $registration, resubmit: true);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return redirect()->route('registration.show', $registration)
            ->with('success', 'Pendaftaran berhasil dikirim ulang untuk ditinjau kembali oleh panitia.');
    }

    public function uploadDocument(Request $request, CandidateRegistration $registration, CandidateRequirement $requirement): RedirectResponse
    {
        Gate::authorize('update', $registration);

        abort_if(
            $registration->election_id !== $requirement->election_id,
            403,
            'Syarat tidak sesuai dengan pemilihan ini.'
        );

        $allowedExtensions = $requirement->allowed_extensions ?? ['pdf', 'jpg', 'jpeg', 'png'];
        $maxSize = $requirement->max_file_size ?? 5120;

        $request->validate([
            'document' => [
                'required',
                'file',
                'max:'.$maxSize,
                'mimes:'.implode(',', $allowedExtensions),
            ],
        ], [
            'document.mimes' => 'Format file tidak diizinkan. Gunakan: '.implode(', ', $allowedExtensions),
            'document.max' => 'Ukuran file maksimal '.number_format($maxSize / 1024, 1).' MB.',
        ]);

        $file = $request->file('document');
        $path = $file->store("registrations/{$registration->id}", 'local');

        // Supersede previous version if exists
        $existing = CandidateRegistrationDocument::where('candidate_registration_id', $registration->id)
            ->where('requirement_id', $requirement->id)
            ->whereNull('superseded_at')
            ->first();

        $version = $existing ? ($existing->version + 1) : 1;

        if ($existing) {
            $existing->update(['superseded_at' => now()]);
        }

        CandidateRegistrationDocument::create([
            'candidate_registration_id' => $registration->id,
            'requirement_id' => $requirement->id,
            'document_type' => $requirement->type,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'version' => $version,
        ]);

        return back()->with('success', "Dokumen '{$requirement->name}' berhasil diunggah.");
    }

    public function uploadPhoto(Request $request, CandidateRegistration $registration): RedirectResponse
    {
        Gate::authorize('update', $registration);

        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($registration->photo_path && Storage::disk('public')->exists($registration->photo_path)) {
            Storage::disk('public')->delete($registration->photo_path);
        }

        $path = $request->file('photo')->store('candidates', 'public');
        $registration->update(['photo_path' => $path]);

        return back()->with('success', 'Foto pasangan calon berhasil diperbarui.');
    }
}
