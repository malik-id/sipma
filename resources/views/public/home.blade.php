<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Sistem Informasi Pemilihan Mahasiswa HIMAKOM — Pemilihan Ketua & Wakil Ketua Himpunan Mahasiswa yang transparan, aman, dan demokratis." />
    <title>SIPMA HIMAKOM — Sistem Informasi Pemilihan Mahasiswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full font-sans antialiased text-slate-800 bg-slate-50">

    {{-- Navbar --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center font-bold text-white text-sm shadow-sm">H</div>
                <div>
                    <span class="font-bold text-slate-900 text-sm tracking-tight">SIPMA HIMAKOM</span>
                    <p class="text-[10px] text-slate-400 leading-none mt-0.5">Sistem Pemilihan Mahasiswa</p>
                </div>
            </div>
            <nav class="flex items-center gap-2">
                <a href="{{ route('check-voter') }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition">Cek DPT</a>
                <a href="{{ route('public.candidates.index') }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition">Kandidat</a>
                <a href="{{ route('public.results.index') }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition">Hasil</a>
                @auth
                    <a href="{{ auth()->user()->canAccessAdminPanel() ? route('admin.dashboard') : route('dashboard') }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-blue-700 transition">
                        Masuk Panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-blue-700 transition">
                        Masuk / Login
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 text-white py-20 px-4">
        <div class="max-w-5xl mx-auto text-center space-y-6">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold uppercase tracking-wider backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Sistem E-Voting Resmi HIMAKOM
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight tracking-tight">
                Pemilihan Ketua &amp; Wakil Ketua<br class="hidden sm:block">
                <span class="text-blue-200">HIMAKOM Fakultas Ilmu Komputer</span>
            </h1>
            <p class="text-lg text-blue-100 max-w-2xl mx-auto leading-relaxed">
                Sistem pemungutan suara digital yang transparan, anonim, dan aman. Satu pemilih, satu suara — pilih dengan keyakinan.
            </p>

            @if ($activeElection)
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 max-w-xl mx-auto mt-8">
                    <p class="text-xs text-blue-200 uppercase tracking-wider font-semibold mb-1">Periode Pemilihan Aktif</p>
                    <p class="text-xl font-bold text-white">{{ $activeElection->name }}</p>
                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs text-blue-100">
                        <div class="bg-white/10 rounded-xl p-3">
                            <p class="text-blue-200">Mulai Voting</p>
                            <p class="font-semibold text-white mt-0.5">{{ $activeElection->voting_start->format('d M Y H:i') }}</p>
                        </div>
                        <div class="bg-white/10 rounded-xl p-3">
                            <p class="text-blue-200">Berakhir Voting</p>
                            <p class="font-semibold text-white mt-0.5">{{ $activeElection->voting_end->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-blue-700 font-bold rounded-xl text-sm shadow-md hover:bg-blue-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Masuk &amp; Berikan Suara
                        </a>
                        <a href="{{ route('check-voter') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl text-sm border border-white/30 transition">
                            Cek Status Pemilih
                        </a>
                    </div>
                </div>
            @else
                <div class="mt-8">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-white text-blue-700 font-bold rounded-xl text-sm shadow-md hover:bg-blue-50 transition">
                        Masuk ke Sistem
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Stats Bar --}}
    <section class="bg-white border-b border-slate-200 py-6">
        <div class="max-w-5xl mx-auto px-4 grid grid-cols-3 gap-6 text-center">
            <div>
                <p class="text-2xl font-extrabold text-blue-700">{{ number_format($totalStudents) }}</p>
                <p class="text-xs text-slate-500 mt-1">Mahasiswa Terdaftar</p>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-blue-700">{{ number_format($totalVoters) }}</p>
                <p class="text-xs text-slate-500 mt-1">Pemilih Memenuhi Syarat</p>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-blue-700">{{ number_format($totalElections) }}</p>
                <p class="text-xs text-slate-500 mt-1">Periode Pemilihan</p>
            </div>
        </div>
    </section>

    {{-- Featured Candidates --}}
    @if ($featuredCandidates->isNotEmpty())
        <section class="py-16 px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-bold text-slate-900">Pasangan Calon Resmi</h2>
                    <p class="text-slate-500 mt-2 text-sm">Kandidat resmi yang telah memenuhi seluruh persyaratan dan ditetapkan oleh panitia.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($featuredCandidates as $candidate)
                        <a href="{{ route('public.candidates.show', $candidate) }}"
                           class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-blue-300 transition-all group">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-700 font-extrabold text-xl">
                                    {{ $candidate->candidate_number }}
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400">Pasangan Nomor Urut</p>
                                    <p class="font-bold text-slate-800">{{ $candidate->candidate_number }}</p>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <p class="font-semibold text-slate-900 group-hover:text-blue-700 transition">{{ $candidate->chairman->name }}</p>
                                <p class="text-sm text-slate-500">&amp; {{ $candidate->viceChairman->name }}</p>
                                <p class="text-xs text-slate-400 mt-2">{{ $candidate->chairman->study_program }}</p>
                            </div>
                            <div class="mt-4 text-xs text-blue-600 font-semibold group-hover:underline">Lihat Profil Lengkap →</div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-8">
                    <a href="{{ route('public.candidates.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-slate-300 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Lihat Semua Kandidat
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- How It Works --}}
    <section class="bg-white py-16 px-4 border-t border-slate-200">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold text-slate-900">Cara Menggunakan Hak Pilih</h2>
                <p class="text-slate-500 mt-2 text-sm">Proses pemungutan suara yang mudah, aman, dan dapat dipertanggungjawabkan.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div class="text-center space-y-3">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl mx-auto flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900">1. Cek Status DPT</h3>
                    <p class="text-sm text-slate-500">Pastikan nama Anda terdaftar dalam Daftar Pemilih Tetap (DPT) dan berstatus <em>eligible</em>.</p>
                </div>
                <div class="text-center space-y-3">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl mx-auto flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900">2. Login Dengan Google</h3>
                    <p class="text-sm text-slate-500">Login menggunakan akun Google kampus yang terdaftar di database Fasilkom.</p>
                </div>
                <div class="text-center space-y-3">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl mx-auto flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900">3. Pilih &amp; Konfirmasi</h3>
                    <p class="text-sm text-slate-500">Pilih pasangan calon, konfirmasi pilihan Anda, dan suara Anda terekam secara anonim dan aman.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-400 py-10 px-4 text-center text-xs">
        <div class="max-w-5xl mx-auto space-y-2">
            <p class="text-white font-semibold">SIPMA HIMAKOM — Sistem Informasi Pemilihan Mahasiswa</p>
            <p>Fakultas Ilmu Komputer &bull; Pemilihan yang Transparan, Aman, &amp; Demokratis</p>
            <div class="flex items-center justify-center gap-4 pt-4">
                <a href="{{ route('check-voter') }}" class="hover:text-white transition">Cek Pemilih</a>
                <a href="{{ route('public.candidates.index') }}" class="hover:text-white transition">Kandidat</a>
                <a href="{{ route('public.results.index') }}" class="hover:text-white transition">Hasil Pemilihan</a>
                <a href="{{ route('admin.login') }}" class="hover:text-white transition">Panel Panitia</a>
            </div>
        </div>
    </footer>

</body>
</html>
