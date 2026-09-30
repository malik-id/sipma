<?php

use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\ElectionRequirementController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicVoterCheckController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Models\Election;
use App\Models\Student;
use App\Models\Voter;
use Illuminate\Support\Facades\Route;

// ─── Public ───────────────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'))->name('home');
Route::get('/cek-pemilih', [PublicVoterCheckController::class, 'index'])->name('check-voter');

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
use App\Http\Controllers\Student\CandidateRegistrationController;

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

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
    });
});

// ─── Admin & Super Admin ──────────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalStudents = Student::count();
        $totalVoters = Voter::where('voter_status', 'eligible')->count();
        $totalElections = Election::count();
        $activeElections = Election::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalStudents', 'totalVoters', 'totalElections', 'activeElections'));
    })->name('dashboard');

    // Mahasiswa
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::resource('students', StudentController::class)->except(['show']);

    // Pemilih / DPT
    Route::get('voters', [VoterController::class, 'index'])->name('voters.index');
    Route::post('voters/generate', [VoterController::class, 'generate'])->name('voters.generate');
    Route::patch('voters/{voter}/status', [VoterController::class, 'updateStatus'])->name('voters.update-status');
    Route::delete('voters/{voter}', [VoterController::class, 'destroy'])->name('voters.destroy');

    // Periode Pemilihan
    Route::patch('elections/{election}/status', [ElectionController::class, 'updateStatus'])->name('elections.update-status');
    Route::resource('elections', ElectionController::class);

    // Syarat Berkas Calon
    Route::patch('elections/{election}/requirements/{requirement}/toggle', [ElectionRequirementController::class, 'toggle'])->name('elections.requirements.toggle');
    Route::resource('elections.requirements', ElectionRequirementController::class)->except(['show']);
});
