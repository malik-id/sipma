<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CandidateController as AdminCandidateController;
use App\Http\Controllers\Admin\CandidateRegistrationController as AdminCandidateRegistrationController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\ElectionRequirementController;
use App\Http\Controllers\Admin\ElectionResultController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\VotingMonitorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicCandidateController;
use App\Http\Controllers\PublicResultController;
use App\Http\Controllers\PublicVoterCheckController;
use App\Http\Controllers\Student\CandidateRegistrationController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\VotingController;
use App\Models\Election;
use App\Models\Student;
use App\Models\Voter;
use Illuminate\Support\Facades\Route;

// ─── Public ───────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cek-pemilih', [PublicVoterCheckController::class, 'index'])->name('check-voter');
Route::get('/kandidat', [PublicCandidateController::class, 'index'])->name('public.candidates.index');
Route::get('/kandidat/{candidate}', [PublicCandidateController::class, 'show'])->name('public.candidates.show');
Route::get('/hasil', [PublicResultController::class, 'index'])->name('public.results.index');

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/login/google', [AuthController::class, 'google'])->name('login.google');
    Route::get('/auth/google/callback', [AuthController::class, 'callback'])->name('auth.google.callback');
    Route::get('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'admin'])->name('admin.login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Mahasiswa ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [ProfileController::class, 'index'])->name('student.profile');

    // Voting Portal
    Route::prefix('voting')->name('student.voting.')->group(function () {
        Route::get('/', [VotingController::class, 'index'])->name('index');
        Route::get('/{election}', [VotingController::class, 'show'])->name('show');
        Route::post('/{election}', [VotingController::class, 'vote'])->middleware('throttle:vote')->name('vote');
        Route::get('/{election}/selesai', [VotingController::class, 'completed'])->name('completed');
    });

    // Pendaftaran Bakal Calon
    Route::prefix('pendaftaran-bakal-calon')->name('registration.')->group(function () {
        Route::get('/', [CandidateRegistrationController::class, 'index'])->name('index');
        Route::get('/buat', [CandidateRegistrationController::class, 'create'])->name('create');
        Route::post('/', [CandidateRegistrationController::class, 'store'])->name('store');
        Route::get('/{registration}', [CandidateRegistrationController::class, 'show'])->name('show');
        Route::get('/{registration}/edit', [CandidateRegistrationController::class, 'edit'])->name('edit');
        Route::put('/{registration}', [CandidateRegistrationController::class, 'update'])->name('update');
        Route::post('/{registration}/submit', [CandidateRegistrationController::class, 'submit'])->name('submit');
        Route::post('/{registration}/resubmit', [CandidateRegistrationController::class, 'resubmit'])->name('resubmit');
        Route::post('/{registration}/dokumen/{requirement}', [CandidateRegistrationController::class, 'uploadDocument'])->name('upload-document');
        Route::post('/{registration}/foto', [CandidateRegistrationController::class, 'uploadPhoto'])->name('upload-photo');
    });
});

// ─── Admin & Super Admin & Dosen Pendamping ───────────────────────────────────
Route::middleware(['auth', 'active', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard (accessible by admin, super_admin, dosen_pendamping)
    Route::get('/dashboard', function () {
        abort_unless(auth()->user()->canAccessAdminPanel(), 403);
        $totalStudents = Student::count();
        $totalVoters = Voter::where('voter_status', 'eligible')->count();
        $totalElections = Election::count();
        $activeElections = Election::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalStudents', 'totalVoters', 'totalElections', 'activeElections'));
    })->name('dashboard');

    // ── Admin & Super Admin only ──────────────────────────────────────────────

    // Mahasiswa
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::resource('students', StudentController::class)->except(['show']);

    // Pemilih / DPT
    Route::get('voters', [VoterController::class, 'index'])->name('voters.index');
    Route::post('voters/generate', [VoterController::class, 'generate'])->name('voters.generate');
    Route::post('voters/import', [VoterController::class, 'import'])->name('voters.import');
    Route::patch('voters/{voter}/status', [VoterController::class, 'updateStatus'])->name('voters.update-status');
    Route::delete('voters/{voter}', [VoterController::class, 'destroy'])->name('voters.destroy');

    // Periode Pemilihan
    Route::patch('elections/{election}/status', [ElectionController::class, 'updateStatus'])->name('elections.update-status');
    Route::resource('elections', ElectionController::class);

    // Syarat Berkas Calon
    Route::patch('elections/{election}/requirements/{requirement}/toggle', [ElectionRequirementController::class, 'toggle'])->name('elections.requirements.toggle');
    Route::resource('elections.requirements', ElectionRequirementController::class)->except(['show']);

    // Verifikasi Bakal Calon
    Route::get('pendaftaran-bakal-calon', [AdminCandidateRegistrationController::class, 'index'])->name('registrations.index');
    Route::get('pendaftaran-bakal-calon/{registration}', [AdminCandidateRegistrationController::class, 'show'])->name('registrations.show');
    Route::post('pendaftaran-bakal-calon/{registration}/start-review', [AdminCandidateRegistrationController::class, 'startReview'])->name('registrations.start-review');
    Route::post('pendaftaran-bakal-calon/dokumen/{document}/review', [AdminCandidateRegistrationController::class, 'reviewDocument'])->name('registrations.review-document');
    Route::post('pendaftaran-bakal-calon/{registration}/verify', [AdminCandidateRegistrationController::class, 'verify'])->name('registrations.verify');
    Route::post('pendaftaran-bakal-calon/{registration}/revision', [AdminCandidateRegistrationController::class, 'requestRevision'])->name('registrations.request-revision');
    Route::post('pendaftaran-bakal-calon/{registration}/reject', [AdminCandidateRegistrationController::class, 'reject'])->name('registrations.reject');
    Route::get('pendaftaran-bakal-calon/dokumen/{document}/download', [AdminCandidateRegistrationController::class, 'downloadDocument'])->name('registrations.download-document');

    // Calon Resmi
    Route::get('kandidat', [AdminCandidateController::class, 'index'])->name('candidates.index');
    Route::post('kandidat/{registration}/tetapkan', [AdminCandidateController::class, 'establish'])->name('candidates.establish');
    Route::post('kandidat/{candidate}/nomor-urut', [AdminCandidateController::class, 'assignNumber'])->name('candidates.assign-number');
    Route::post('kandidat/{candidate}/status', [AdminCandidateController::class, 'toggleStatus'])->name('candidates.toggle-status');

    // Monitoring Voting Real-Time
    Route::get('voting-monitor', [VotingMonitorController::class, 'index'])->name('voting-monitor');

    // Perhitungan & Publikasi Hasil + Export
    Route::get('hasil', [ElectionResultController::class, 'index'])->name('results.index');
    Route::post('hasil/{election}/publish', [ElectionResultController::class, 'publish'])->name('results.publish');
    Route::get('hasil/{election}/export-excel', [ElectionResultController::class, 'exportExcel'])->name('results.export-excel');
    Route::get('hasil/{election}/export-csv', [ElectionResultController::class, 'exportCsv'])->name('results.export-csv');

    // Audit Log (admin + super_admin + dosen_pendamping)
    Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-logs.index');

    // Pengaturan Sistem (super_admin only — gated inside controller)
    Route::get('settings', [SystemSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SystemSettingController::class, 'update'])->name('settings.update');
});
