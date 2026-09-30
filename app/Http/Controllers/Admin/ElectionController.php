<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ElectionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreElectionRequest;
use App\Http\Requests\Admin\UpdateElectionRequest;
use App\Models\AuditLog;
use App\Models\Election;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ElectionController extends Controller
{
    /**
     * Display a listing of elections.
     */
    public function index(Request $request): View
    {
        $query = Election::query()
            ->withCount(['voters', 'requirements', 'registrations', 'candidates', 'participations'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $elections = $query->paginate(10)->withQueryString();

        return view('admin.elections.index', compact('elections'));
    }

    /**
     * Show the form for creating a new election.
     */
    public function create(): View
    {
        return view('admin.elections.create');
    }

    /**
     * Store a newly created election in storage.
     */
    public function store(StoreElectionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();
        $validated['status'] = ElectionStatus::Draft;

        $election = Election::create($validated);

        // Seed standard default requirements for convenience
        $this->seedDefaultRequirements($election);

        AuditLog::create([
            'user_id' => auth()->id(),
            'actor_type' => 'admin',
            'action' => 'election.create',
            'entity_type' => Election::class,
            'entity_id' => (string) $election->id,
            'new_values' => $election->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.elections.show', $election)
            ->with('success', "Periode pemilihan '{$election->name}' berhasil dibuat dengan syarat berkas default.");
    }

    /**
     * Display the specified election details, timeline, and summary.
     */
    public function show(Election $election): View
    {
        $election->loadCount(['voters', 'requirements', 'registrations', 'candidates', 'participations', 'ballots']);
        $election->load(['requirements']);

        $phaseInfo = $election->getCurrentPhaseInfo();

        return view('admin.elections.show', compact('election', 'phaseInfo'));
    }

    /**
     * Show the form for editing the specified election.
     */
    public function edit(Election $election): View
    {
        $election->assertMutable();

        return view('admin.elections.edit', compact('election'));
    }

    /**
     * Update the specified election in storage.
     */
    public function update(UpdateElectionRequest $request, Election $election): RedirectResponse
    {
        $election->assertMutable();

        $oldValues = $election->toArray();
        $election->update($request->validated());

        AuditLog::create([
            'user_id' => auth()->id(),
            'actor_type' => 'admin',
            'action' => 'election.update',
            'entity_type' => Election::class,
            'entity_id' => (string) $election->id,
            'old_values' => $oldValues,
            'new_values' => $election->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.elections.show', $election)
            ->with('success', "Periode pemilihan '{$election->name}' berhasil diperbarui.");
    }

    /**
     * Update the election status with server-side validation.
     */
    public function updateStatus(Request $request, Election $election): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(ElectionStatus::class)],
        ]);

        $newStatus = ElectionStatus::from($validated['status']);
        $now = now();

        // Server-side validation: prevent cheating or time manipulation
        if ($newStatus === ElectionStatus::Voting) {
            if ($now->lt($election->voting_start)) {
                return back()->withErrors([
                    'status' => 'Pemungutan suara belum dapat dibuka karena jadwal resmi dimulai pada '.$election->voting_start->translatedFormat('d F Y H:i').' WIB.',
                ]);
            }
            if ($now->gt($election->voting_end)) {
                return back()->withErrors([
                    'status' => 'Waktu pemungutan suara telah berakhir pada '.$election->voting_end->translatedFormat('d F Y H:i').' WIB.',
                ]);
            }
        }

        if ($newStatus === ElectionStatus::Published) {
            if ($election->result_publish_at && $now->lt($election->result_publish_at)) {
                return back()->withErrors([
                    'status' => 'Hasil pemilihan baru dapat dipublikasikan setelah jadwal rilis pada '.$election->result_publish_at->translatedFormat('d F Y H:i').' WIB.',
                ]);
            }
            if ($now->lt($election->voting_end)) {
                return back()->withErrors([
                    'status' => 'Hasil tidak dapat dipublikasikan sebelum waktu voting resmi berakhir.',
                ]);
            }
        }

        $oldStatus = $election->status->value;
        $election->update(['status' => $newStatus]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'actor_type' => 'admin',
            'action' => 'election.status_change',
            'entity_type' => Election::class,
            'entity_id' => (string) $election->id,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $newStatus->value],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return back()->with('success', "Status pemilihan berhasil diubah menjadi '{$newStatus->label()}'.");
    }

    /**
     * Remove the specified election from storage.
     */
    public function destroy(Election $election): RedirectResponse
    {
        if ($election->participations()->exists() || $election->ballots()->exists()) {
            return back()->withErrors(['election' => 'Pemilihan tidak dapat dihapus karena sudah memiliki data partisipasi suara.']);
        }

        $electionName = $election->name;

        // Cascade delete related requirements and voters
        $election->requirements()->delete();
        $election->voters()->delete();
        $election->delete();

        return redirect()
            ->route('admin.elections.index')
            ->with('success', "Periode pemilihan '{$electionName}' berhasil dihapus.");
    }

    /**
     * Seed initial common requirements for a newly created election.
     */
    private function seedDefaultRequirements(Election $election): void
    {
        $defaults = [
            [
                'name' => 'Transkrip Nilai / KHS Kumulatif',
                'description' => 'Dokumen transkrip nilai resmi yang menunjukkan IPK minimal sesuai ketentuan panitia (Format PDF).',
                'type' => 'file',
                'required' => true,
                'allowed_extensions' => ['pdf'],
                'max_file_size' => 5120, // 5MB
                'sort_order' => 1,
                'active' => true,
            ],
            [
                'name' => 'Surat Keterangan Aktif Kuliah',
                'description' => 'Surat keterangan mahasiswa aktif dari Dekanat / BAAK (Format PDF).',
                'type' => 'file',
                'required' => true,
                'allowed_extensions' => ['pdf'],
                'max_file_size' => 3072, // 3MB
                'sort_order' => 2,
                'active' => true,
            ],
            [
                'name' => 'Pas Foto Formal Pasangan Calon',
                'description' => 'Foto formal berlatar belakang merah/biru, berpakaian rapi dengan jas almamater (Format JPG/PNG).',
                'type' => 'image',
                'required' => true,
                'allowed_extensions' => ['jpg', 'jpeg', 'png'],
                'max_file_size' => 3072, // 3MB
                'sort_order' => 3,
                'active' => true,
            ],
            [
                'name' => 'Sertifikat Pengkaderan / Organisasi',
                'description' => 'Bukti kelulusan pengkaderan tingkat himpunan/fakultas atau SK kepengurusan organisasi (Format PDF).',
                'type' => 'file',
                'required' => true,
                'allowed_extensions' => ['pdf'],
                'max_file_size' => 5120, // 5MB
                'sort_order' => 4,
                'active' => true,
            ],
            [
                'name' => 'Dokumen Visi, Misi, dan Program Kerja Unggulan',
                'description' => 'Paparan visi, misi, analisis SWOT, dan program kerja unggulan selama 1 periode kepengurusan (Format PDF).',
                'type' => 'file',
                'required' => true,
                'allowed_extensions' => ['pdf'],
                'max_file_size' => 10240, // 10MB
                'sort_order' => 5,
                'active' => true,
            ],
        ];

        foreach ($defaults as $req) {
            $election->requirements()->create($req);
        }
    }
}
