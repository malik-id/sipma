<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ReviewCandidateRegistrationAction;
use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\CandidateRegistration;
use App\Models\CandidateRegistrationDocument;
use App\Models\Election;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CandidateRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('review-registrations');

        $query = CandidateRegistration::with(['election', 'chairman', 'viceChairman'])
            ->latest();

        if ($request->filled('election_id')) {
            $query->where('election_id', $request->election_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                    ->orWhereHas('chairman', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%");
                    })
                    ->orWhereHas('viceChairman', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%");
                    });
            });
        }

        $registrations = $query->paginate(15)->withQueryString();
        $elections = Election::latest('voting_start')->get();

        return view('admin.registrations.index', compact('registrations', 'elections'));
    }

    public function show(CandidateRegistration $registration): View
    {
        Gate::authorize('review-registrations');

        $registration->load([
            'election.requirements',
            'chairman',
            'viceChairman',
            'currentDocuments.requirement',
            'programs',
            'histories.user',
            'answers.requirement',
        ]);

        return view('admin.registrations.show', compact('registration'));
    }

    public function startReview(CandidateRegistration $registration, ReviewCandidateRegistrationAction $action): RedirectResponse
    {
        Gate::authorize('review-registrations');

        try {
            $action->handle(auth()->user(), $registration, RegistrationStatus::UnderReview);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return back()->with('success', 'Status pendaftaran diubah menjadi "Dalam Pemeriksaan".');
    }

    public function reviewDocument(Request $request, CandidateRegistrationDocument $document, ReviewCandidateRegistrationAction $action): RedirectResponse
    {
        Gate::authorize('review-registrations');

        $request->validate([
            'status' => ['required', 'in:valid,invalid,revision_required'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        if (in_array($request->status, ['invalid', 'revision_required']) && blank($request->note)) {
            return back()->withErrors(['note' => 'Catatan wajib diisi jika dokumen ditandai tidak valid atau perlu perbaikan.']);
        }

        try {
            $action->document(auth()->user(), $document, $request->status, $request->note);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return back()->with('success', 'Status verifikasi dokumen diperbarui.');
    }

    public function verify(CandidateRegistration $registration, ReviewCandidateRegistrationAction $action): RedirectResponse
    {
        Gate::authorize('review-registrations');

        try {
            $action->handle(auth()->user(), $registration, RegistrationStatus::Verified);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return back()->with('success', 'Pendaftaran berhasil diverifikasi dan siap ditetapkan sebagai calon resmi.');
    }

    public function requestRevision(Request $request, CandidateRegistration $registration, ReviewCandidateRegistrationAction $action): RedirectResponse
    {
        Gate::authorize('review-registrations');

        $request->validate([
            'notes' => ['required', 'string', 'max:1000'],
            'deadline' => ['nullable', 'date', 'after:now'],
        ]);

        try {
            $action->handle(auth()->user(), $registration, RegistrationStatus::RevisionRequired, $request->notes, $request->deadline);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return back()->with('success', 'Permintaan perbaikan berhasil dikirimkan ke pendaftar.');
    }

    public function reject(Request $request, CandidateRegistration $registration, ReviewCandidateRegistrationAction $action): RedirectResponse
    {
        Gate::authorize('review-registrations');

        $request->validate([
            'notes' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $action->handle(auth()->user(), $registration, RegistrationStatus::Rejected, $request->notes);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return back()->with('success', 'Pendaftaran bakal calon telah ditolak.');
    }

    public function downloadDocument(CandidateRegistrationDocument $document): StreamedResponse
    {
        Gate::authorize('review-registrations');

        abort_unless(Storage::disk('local')->exists($document->file_path), 404, 'File dokumen tidak ditemukan.');

        return Storage::disk('local')->download($document->file_path, $document->original_filename);
    }
}
