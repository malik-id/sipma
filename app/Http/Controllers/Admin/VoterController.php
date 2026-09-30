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
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        if (VotingParticipation::where('election_id', $voter->election_id)->where('voter_id', $voter->id)->exists()) {
            return back()->withErrors(['error' => 'Pemilih ini tidak dapat dihapus karena sudah memberikan suara dalam pemilihan.']);
        }

        $old = $voter->toArray();
        $name = $voter->student->name;
        $voter->delete();

        $audit->handle($request->user(), 'voter.delete', null, $old, null);

        return back()->with('success', "Data pemilih {$name} berhasil dihapus dari DPT.");
    }

    public function import(Request $request, RecordAudit $audit): RedirectResponse
    {
        $request->validate([
            'election_id' => ['required', 'exists:elections,id'],
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:10240'],
        ]);

        $election = Election::findOrFail($request->input('election_id'));
        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        $rows = [];
        if (in_array($extension, ['xlsx', 'xls'], true)) {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
        } else {
            $handle = fopen($file->getRealPath(), 'r');
            if (! $handle) {
                return back()->withErrors(['file' => 'Gagal membaca berkas CSV.']);
            }
            while (($data = fgetcsv($handle, 2000, ',')) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        if (empty($rows)) {
            return back()->withErrors(['file' => 'Berkas kosong atau tidak dapat dibaca.']);
        }

        $headerMap = [
            'nama' => 'name',
            'program studi' => 'study_program',
            'program_studi' => 'study_program',
            'prodi' => 'study_program',
            'jurusan' => 'study_program',
            'angkatan' => 'class_year',
            'tahun_masuk' => 'class_year',
            'status' => 'voter_status',
            'status_pemilih' => 'voter_status',
            'catatan' => 'notes',
            'keterangan' => 'notes',
        ];

        $rawHeader = array_map(fn ($h) => strtolower(trim((string) $h)), array_shift($rows));
        $header = array_map(fn ($h) => $headerMap[$h] ?? $h, $rawHeader);

        if (! in_array('nim', $header, true) && ! in_array('email', $header, true)) {
            return back()->withErrors(['file' => 'Header berkas harus memiliki kolom "nim" atau "email". Contoh: nim,name,email,voter_status,notes']);
        }

        $indices = array_flip($header);
        $imported = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $nim = isset($indices['nim']) ? trim((string) ($row[$indices['nim']] ?? '')) : '';
            $email = isset($indices['email']) ? mb_strtolower(trim((string) ($row[$indices['email']] ?? ''))) : '';
            $name = isset($indices['name']) ? trim((string) ($row[$indices['name']] ?? '')) : '';
            $studyProgram = isset($indices['study_program']) ? trim((string) ($row[$indices['study_program']] ?? 'Informatika')) : 'Informatika';
            $classYear = isset($indices['class_year']) ? (int) ($row[$indices['class_year']] ?? date('Y')) : (int) date('Y');
            $semester = isset($indices['semester']) ? (int) ($row[$indices['semester']] ?? 3) : 3;
            $status = isset($indices['voter_status']) ? strtolower(trim((string) ($row[$indices['voter_status']] ?? 'eligible'))) : 'eligible';
            $notes = isset($indices['notes']) ? trim((string) ($row[$indices['notes']] ?? '')) : 'Diimpor dari berkas DPT';

            if (! in_array($status, ['eligible', 'not_eligible', 'suspended'], true)) {
                $status = 'eligible';
            }

            // Find or create student
            $student = null;
            if ($nim) {
                $student = Student::where('nim', $nim)->first();
            }
            if (! $student && $email) {
                $student = Student::where('email', $email)->first();
            }

            if (! $student && $name && ($email || $nim)) {
                $student = Student::create([
                    'nim' => $nim ?: 'NIM-'.rand(10000, 99999),
                    'name' => $name,
                    'email' => $email ?: $nim.'@student.ac.id',
                    'study_program' => $studyProgram ?: 'Informatika',
                    'class_year' => $classYear ?: (int) date('Y'),
                    'semester' => $semester ?: 3,
                    'student_status' => StudentStatus::Active,
                ]);
            }

            if (! $student) {
                $skipped++;

                continue;
            }

            $voter = Voter::where('election_id', $election->id)->where('student_id', $student->id)->first();
            if ($voter) {
                $voter->update([
                    'voter_status' => $status,
                    'notes' => $notes,
                    'verified_at' => now(),
                    'verified_by' => $request->user()->id,
                ]);
                $updated++;
            } else {
                Voter::create([
                    'election_id' => $election->id,
                    'student_id' => $student->id,
                    'voter_status' => $status,
                    'verified_at' => now(),
                    'verified_by' => $request->user()->id,
                    'notes' => $notes,
                ]);
                $imported++;
            }
        }

        $audit->handle($request->user(), 'voter.import', $election, null, [
            'election_id' => $election->id,
            'imported' => $imported,
            'updated' => $updated,
            'skipped' => $skipped,
            'filename' => $file->getClientOriginalName(),
        ]);

        $msg = "Impor DPT selesai: {$imported} pemilih baru ditambahkan, {$updated} pemilih diperbarui.";
        if ($skipped > 0) {
            $msg .= " ({$skipped} baris dilewati karena mahasiswa tidak ditemukan)";
        }

        return redirect()->route('admin.voters.index', ['election_id' => $election->id])
            ->with('success', $msg);
    }
}
