<?php

namespace App\Http\Controllers\Admin;

use App\Actions\RecordAudit;
use App\Actions\ReviewCandidateRegistrationAction;
use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\CandidateRegistration;
use App\Models\CandidateRegistrationDocument;
use App\Models\Election;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
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

    public function destroy(Request $request, CandidateRegistration $registration): RedirectResponse
    {
        Gate::authorize('review-registrations');

        $regNumber = $registration->registration_number ?? 'DRAFT';
        $oldValues = $registration->toArray();

        DB::transaction(function () use ($registration) {
            // If linked to candidate, delete ballots and candidate
            if ($candidate = $registration->candidate) {
                DB::table('ballots')->where('candidate_id', $candidate->id)->delete();
                $candidate->delete();
            }

            // Delete documents from storage
            foreach ($registration->documents as $doc) {
                if (Storage::disk('local')->exists($doc->file_path)) {
                    Storage::disk('local')->delete($doc->file_path);
                }
            }

            if ($registration->photo_path && Storage::disk('public')->exists($registration->photo_path)) {
                Storage::disk('public')->delete($registration->photo_path);
            }

            DB::table('candidate_registration_documents')->where('candidate_registration_id', $registration->id)->delete();
            DB::table('candidate_registration_histories')->where('candidate_registration_id', $registration->id)->delete();
            DB::table('candidate_programs')->where('candidate_registration_id', $registration->id)->delete();
            DB::table('requirement_answers')->where('candidate_registration_id', $registration->id)->delete();
            DB::table('registration_members')->where('candidate_registration_id', $registration->id)->delete();

            $registration->delete();
        });

        app(RecordAudit::class)->handle(auth()->user(), 'registration.deleted', null, $oldValues, null);

        return redirect()->route('admin.registrations.index')
            ->with('success', "Pendaftaran bakal calon '{$regNumber}' berhasil dihapus.");
    }

    public function viewDocument(CandidateRegistrationDocument $document): Response
    {
        Gate::authorize('review-registrations');

        if (Storage::disk('local')->exists($document->file_path)) {
            return Storage::disk('local')->response($document->file_path, $document->original_filename, [
                'Content-Disposition' => 'inline; filename="'.$document->original_filename.'"',
            ]);
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->response($document->file_path, $document->original_filename, [
                'Content-Disposition' => 'inline; filename="'.$document->original_filename.'"',
            ]);
        }

        abort(404, 'File dokumen tidak ditemukan di penyimpanan server.');
    }

    public function downloadDocument(CandidateRegistrationDocument $document): StreamedResponse
    {
        Gate::authorize('review-registrations');

        if (Storage::disk('local')->exists($document->file_path)) {
            return Storage::disk('local')->download($document->file_path, $document->original_filename);
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->download($document->file_path, $document->original_filename);
        }

        abort(404, 'File dokumen tidak ditemukan di penyimpanan server.');
    }
}
