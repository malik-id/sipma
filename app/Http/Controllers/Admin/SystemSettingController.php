<?php

namespace App\Http\Controllers\Admin;

use App\Actions\RecordAudit;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SystemSettingController extends Controller
{
    public function index(): View
    {
        Gate::authorize('super-admin');

        $settings = SystemSetting::pluck('value', 'key')->all();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request, RecordAudit $audit): RedirectResponse
    {
        Gate::authorize('super-admin');

        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:100'],
            'organization_name' => ['required', 'string', 'max:100'],
            'faculty_name' => ['required', 'string', 'max:150'],
            'contact_email' => ['nullable', 'email', 'max:100'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'announcement_banner' => ['nullable', 'string', 'max:1000'],
            'allowed_email_domain' => ['nullable', 'string', 'max:100'],
        ]);

        $old = SystemSetting::pluck('value', 'key')->all();

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value);
        }

        $audit->handle($request->user(), 'system.settings_updated', null, $old, $validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Konfigurasi aplikasi berhasil diperbarui.');
    }

    public function reset(Request $request, RecordAudit $audit): RedirectResponse
    {
        Gate::authorize('super-admin');

        $request->validate([
            'confirm_reset' => ['required', 'in:RESET-DATA,reset'],
        ], [
            'confirm_reset.in' => 'Konfirmasi teks reset tidak sesuai. Ketik RESET-DATA untuk melanjutkan.',
            'confirm_reset.required' => 'Konfirmasi teks wajib diisi.',
        ]);

        DB::transaction(function () {
            // 1. Delete voting & ballots
            DB::table('ballots')->delete();
            DB::table('voting_participations')->delete();

            // 2. Delete candidates
            DB::table('candidates')->delete();

            // 3. Delete candidate registrations & related child data
            DB::table('candidate_registration_documents')->delete();
            DB::table('candidate_registration_histories')->delete();
            DB::table('candidate_programs')->delete();
            DB::table('requirement_answers')->delete();
            DB::table('registration_members')->delete();
            DB::table('candidate_registrations')->delete();

            // 4. Delete candidate requirements & voters & elections
            DB::table('candidate_requirements')->delete();
            DB::table('voters')->delete();
            DB::table('import_batches')->delete();
            DB::table('notifications')->delete();
            DB::table('elections')->delete();

            // 5. Unlink student_id from users (admin/dosen)
            User::query()->update(['student_id' => null]);

            // 6. Delete student users (preserve admin, super_admin, dosen_pendamping)
            User::whereNotIn('role', ['super_admin', 'admin', 'dosen_pendamping', 'dosen'])->delete();

            // 7. Delete all students
            Student::query()->delete();

            // Clean storage photos
            try {
                Storage::disk('public')->deleteDirectory('candidates/photos');
                Storage::disk('public')->deleteDirectory('registrations');
                Storage::disk('local')->deleteDirectory('registrations');
            } catch (\Throwable $e) {
                // Ignore storage directory delete failures
            }
        });

        $audit->handle($request->user(), 'system.database_reset', null, null, [
            'performed_by' => $request->user()->name,
            'timestamp' => now()->toDateTimeString(),
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Database sistem berhasil di-reset. Seluruh data pemilihan, hasil suara, pendaftaran, dan mahasiswa telah dibersihkan. Akun Admin dan Dosen tetap aman.');
    }
}
