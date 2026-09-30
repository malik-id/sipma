<x-layouts.admin title="Dashboard" header="Ringkasan Sistem">

    {{-- Welcome Card --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 sm:p-8 text-white shadow-sm mb-8">
        <div class="max-w-2xl">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white mb-3">
                Panel Kontrol Panitia Pemilihan
            </span>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="mt-2 text-sm text-blue-100 leading-relaxed">
                Kelola master data mahasiswa, tetapkan daftar pemilih tetap (DPT), pantau periode pemilihan, dan verifikasi berkas kandidat dalam satu sistem terintegrasi.
            </p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Mahasiswa FIK</div>
                <div class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalStudents) }}</div>
                <a href="{{ route('admin.students.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700 mt-2 inline-block">
                    Kelola Mahasiswa →
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pemilih Berhak (DPT)</div>
                <div class="text-3xl font-extrabold text-emerald-600 mt-1">{{ number_format($totalVoters) }}</div>
                <a href="{{ route('admin.voters.index') }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700 mt-2 inline-block">
                    Lihat Daftar Pemilih →
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Periode Pemilihan</div>
                <div class="text-3xl font-extrabold text-indigo-600 mt-1">{{ number_format($totalElections) }}</div>
                <a href="{{ route('admin.elections.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 mt-2 inline-block">
                    Kelola Periode →
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('admin.students.create') }}" class="p-4 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/30 transition group flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-semibold text-slate-800">Tambah Mahasiswa</div>
                    <p class="text-xs text-slate-500 mt-0.5">Input mahasiswa baru secara manual</p>
                </div>
            </a>

            <a href="{{ route('admin.students.index') }}" class="p-4 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/30 transition group flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-semibold text-slate-800">Import CSV Mahasiswa</div>
                    <p class="text-xs text-slate-500 mt-0.5">Unggah ribuan data mahasiswa sekaligus</p>
                </div>
            </a>

            <a href="{{ route('check-voter') }}" target="_blank" class="p-4 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/30 transition group flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-semibold text-slate-800">Cek Portal DPT Terbuka</div>
                    <p class="text-xs text-slate-500 mt-0.5">Uji halaman pencarian pemilih publik</p>
                </div>
            </a>
        </div>
    </div>

</x-layouts.admin>
