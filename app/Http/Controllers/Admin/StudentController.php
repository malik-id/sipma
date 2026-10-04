<?php

namespace App\Http\Controllers\Admin;

use App\Actions\RecordAudit;
use App\Enums\StudentStatus;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('manage-voters');

        $query = Student::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('study_program', 'like', "%{$search}%");
            });
        }

        if ($classYear = $request->input('class_year')) {
            $query->where('class_year', (int) $classYear);
        }

        if ($status = $request->input('status')) {
            $query->where('student_status', $status);
        }

        if ($prodi = $request->input('study_program')) {
            $query->where('study_program', $prodi);
        }

        if ($semester = $request->input('semester')) {
            $query->where('semester', (int) $semester);
        }

        $students = $query->latest('id')->paginate(15)->withQueryString();

        $dbYears = Student::select('class_year')->distinct()->whereNotNull('class_year')->pluck('class_year')->all();
        $defaultYears = [2026, 2025, 2024, 2023];
        $classYears = collect(array_unique(array_merge($defaultYears, $dbYears)))->sortDesc()->values();
        $studyPrograms = Student::select('study_program')->distinct()->whereNotNull('study_program')->orderBy('study_program')->pluck('study_program');
        $statuses = StudentStatus::cases();

        return view('admin.students.index', compact('students', 'classYears', 'studyPrograms', 'statuses'));
    }

    public function create(): View
    {
        return view('admin.students.create', [
            'statuses' => StudentStatus::cases(),
        ]);
    }

    public function store(Request $request, RecordAudit $audit): RedirectResponse
    {
        $validated = $request->validate([
            'nim' => ['required', 'string', 'max:30', 'unique:students,nim'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email'],
            'study_program' => ['required', 'string', 'max:100'],
            'class_year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'student_status' => ['required', Rule::enum(StudentStatus::class)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $student = Student::create($validated);

        $audit->handle($request->user(), 'student.create', $student, null, $student->toArray());

        return redirect()->route('admin.students.index')
            ->with('success', "Mahasiswa {$student->name} ({$student->nim}) berhasil ditambahkan.");
    }

    public function edit(Student $student): View
    {
        return view('admin.students.edit', [
            'student' => $student,
            'statuses' => StudentStatus::cases(),
        ]);
    }

    public function update(Request $request, Student $student, RecordAudit $audit): RedirectResponse
    {
        $validated = $request->validate([
            'nim' => ['required', 'string', 'max:30', Rule::unique('students', 'nim')->ignore($student->id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($student->id)],
            'study_program' => ['required', 'string', 'max:100'],
            'class_year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'student_status' => ['required', Rule::enum(StudentStatus::class)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $oldValues = $student->toArray();
        $student->update($validated);

        $audit->handle($request->user(), 'student.update', $student, $oldValues, $student->fresh()->toArray());

        return redirect()->route('admin.students.index')
            ->with('success', "Data mahasiswa {$student->name} berhasil diperbarui.");
    }

    public function destroy(Request $request, Student $student, RecordAudit $audit): RedirectResponse
    {
        if ($student->voters()->exists() || $student->registrations()->exists()) {
            return back()->withErrors(['error' => 'Mahasiswa tidak dapat dihapus karena sudah memiliki keterkaitan data pemilih atau pendaftaran pemilihan.']);
        }

        $old = $student->toArray();
        $name = $student->name;
        $student->delete();

        $audit->handle($request->user(), 'student.delete', null, $old, null);

        return redirect()->route('admin.students.index')
            ->with('success', "Mahasiswa {$name} berhasil dihapus.");
    }

    public function import(Request $request, RecordAudit $audit): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:10240'],
        ]);

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
            'no_hp' => 'phone',
            'nomor_hp' => 'phone',
            'telepon' => 'phone',
            'hp' => 'phone',
        ];

        $rawHeader = array_map(fn ($h) => strtolower(trim((string) $h)), array_shift($rows));
        $header = array_map(fn ($h) => $headerMap[$h] ?? $h, $rawHeader);
        $expected = ['nim', 'name', 'email', 'study_program', 'class_year', 'semester'];

        foreach ($expected as $exp) {
            if (! in_array($exp, $header, true)) {
                return back()->withErrors(['file' => "Kolom wajib '{$exp}' (atau padanan Indonesianya) tidak ditemukan pada header berkas. Contoh header: nim,name,email,study_program,class_year,semester,phone"]);
            }
        }

        $indices = array_flip($header);
        $imported = 0;
        $updated = 0;
        $errors = [];
        $rowNum = 1;

        foreach ($rows as $row) {
            $rowNum++;
            if (count($row) < count($expected)) {
                continue;
            }

            $nim = trim((string) ($row[$indices['nim']] ?? ''));
            $name = trim((string) ($row[$indices['name']] ?? ''));
            $email = mb_strtolower(trim((string) ($row[$indices['email']] ?? '')));
            $studyProgram = trim((string) ($row[$indices['study_program']] ?? ''));
            $classYear = (int) ($row[$indices['class_year']] ?? 0);
            $semester = (int) ($row[$indices['semester']] ?? 0);
            $phone = isset($indices['phone']) ? trim((string) $row[$indices['phone']]) : null;

            if (empty($nim) || empty($name) || empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Baris {$rowNum}: Data tidak lengkap atau format email tidak valid ({$email})";

                continue;
            }

            $existing = Student::where('nim', $nim)->orWhere('email', $email)->first();

            if ($existing) {
                $existing->update([
                    'nim' => $nim,
                    'name' => $name,
                    'email' => $email,
                    'study_program' => $studyProgram ?: $existing->study_program,
                    'class_year' => $classYear > 0 ? $classYear : $existing->class_year,
                    'semester' => ($semester >= 1 && $semester <= 14) ? $semester : $existing->semester,
                    'phone' => $phone ?: $existing->phone,
                ]);
                $updated++;
            } else {
                Student::create([
                    'nim' => $nim,
                    'name' => $name,
                    'email' => $email,
                    'study_program' => $studyProgram ?: 'Informatika',
                    'class_year' => $classYear > 0 ? $classYear : (int) date('Y'),
                    'semester' => ($semester >= 1 && $semester <= 14) ? $semester : 1,
                    'student_status' => StudentStatus::Active,
                    'phone' => $phone,
                ]);
                $imported++;
            }
        }

        $audit->handle($request->user(), 'student.import', null, null, [
            'imported' => $imported,
            'updated' => $updated,
            'filename' => $file->getClientOriginalName(),
        ]);

        $msg = "Impor selesai: {$imported} mahasiswa baru ditambahkan, {$updated} data diperbarui.";
        if (count($errors) > 0) {
            $msg .= ' Ada '.count($errors).' baris yang dilewati karena tidak valid.';
        }

        return redirect()->route('admin.students.index')->with('success', $msg);
    }
}
