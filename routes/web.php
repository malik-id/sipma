<?php

use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicVoterCheckController;
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
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', fn () => view('student.dashboard'))->name('dashboard');
});

// ─── Admin & Super Admin ──────────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalStudents = Student::count();
        $totalVoters = Voter::where('voter_status', 'eligible')->count();
        $totalElections = Election::count();

        return view('admin.dashboard', compact('totalStudents', 'totalVoters', 'totalElections'));
    })->name('dashboard');

    // Mahasiswa
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::resource('students', StudentController::class)->except(['show']);

    // Pemilih / DPT
    Route::get('voters', [VoterController::class, 'index'])->name('voters.index');
    Route::post('voters/generate', [VoterController::class, 'generate'])->name('voters.generate');
    Route::patch('voters/{voter}/status', [VoterController::class, 'updateStatus'])->name('voters.update-status');
    Route::delete('voters/{voter}', [VoterController::class, 'destroy'])->name('voters.destroy');
});
