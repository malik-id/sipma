<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Profil Pasangan Calon {{ $candidate->candidate_number }}: {{ $candidate->chairman->name }} & {{ $candidate->viceChairman->name }}. Lihat visi, misi, dan program kerja." />
    <title>Paslon {{ $candidate->candidate_number }} — {{ $candidate->chairman->name }} &amp; {{ $candidate->viceChairman->name }} | SIPMA HIMAKOM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col font-sans antialiased text-slate-800">

    {{-- Navbar --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center font-bold text-white text-sm">H</div>
                <span class="font-bold text-slate-900 text-sm">SIPMA HIMAKOM</span>
            </div>
            <nav class="flex items-center gap-2">
                <a href="{{ route('home') }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition">Beranda</a>
                <a href="{{ route('public.candidates.index') }}" class="px-3 py-1.5 text-xs font-semibold text-blue-600 rounded-lg bg-blue-50 transition">Kandidat</a>
                @auth
                    <a href="{{ auth()->user()->canAccessAdminPanel() ? route('admin.dashboard') : route('dashboard') }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition">
                        Panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition">Login</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-1 py-10 px-4">
        <div class="max-w-4xl mx-auto space-y-8">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <a href="{{ route('public.candidates.index') }}" class="hover:text-blue-600 transition">Kandidat</a>
                <span>/</span>
                <span class="text-slate-700">Paslon {{ $candidate->candidate_number }}</span>
            </div>

            {{-- Hero Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 px-8 py-10 text-white flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    @if ($candidate->photo_path)
                        <img src="{{ Storage::url($candidate->photo_path) }}" alt="Foto Paslon" class="w-24 h-24 rounded-2xl object-cover border-4 border-white/40 shadow-lg flex-shrink-0">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-white/20 border-4 border-white/40 flex items-center justify-center text-white font-extrabold text-3xl shadow-lg flex-shrink-0">
                            {{ $candidate->candidate_number }}
                        </div>
                    @endif
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 rounded-full text-xs font-semibold mb-3">
                            Pasangan Nomor Urut {{ $candidate->candidate_number }}
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold leading-tight">
                            {{ $candidate->chairman->name }}
                        </h1>
                        <p class="text-blue-200 mt-1">
                            &amp; {{ $candidate->viceChairman->name }}
                        </p>
                        <p class="text-blue-100 text-sm mt-2">
                            {{ $candidate->chairman->study_program }} &bull; {{ $candidate->election->name }}
                        </p>
                    </div>
                </div>

                {{-- Visi --}}
                <div class="px-8 py-6 border-b border-slate-200">
                    <h2 class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-3">Visi</h2>
                    <p class="text-slate-800 leading-relaxed text-sm">{{ $candidate->registration->vision ?? '-' }}</p>
                </div>

                {{-- Misi --}}
                @if ($candidate->registration?->mission)
                    <div class="px-8 py-6 border-b border-slate-200">
                        <h2 class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-3">Misi</h2>
                        <ol class="space-y-2">
                            @foreach ($candidate->registration->mission as $idx => $misi)
                                <li class="flex gap-3 text-sm text-slate-700">
                                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">{{ $idx + 1 }}</span>
                                    <span>{{ $misi }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                {{-- Program Kerja --}}
                @if ($candidate->registration?->programs->isNotEmpty())
                    <div class="px-8 py-6">
                        <h2 class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-4">Program Kerja</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($candidate->registration->programs->sortBy('sort_order') as $program)
                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                                    <h3 class="font-semibold text-slate-900 text-sm">{{ $program->title }}</h3>
                                    @if ($program->description)
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $program->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Back & Voting CTA --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 justify-between">
                <a href="{{ route('public.candidates.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-blue-700 transition font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar Kandidat
                </a>
                @auth
                    @if (auth()->user()->role === 'student')
                        <a href="{{ route('student.voting.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition">
                            Gunakan Hak Pilih →
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition">
                        Login untuk Memilih →
                    </a>
                @endauth
            </div>
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-8 px-4 text-center text-xs mt-auto">
        <p class="text-white font-semibold">SIPMA HIMAKOM — Sistem Informasi Pemilihan Mahasiswa</p>
        <p class="mt-1">Transparansi &bull; Integritas &bull; Demokrasi</p>
    </footer>

</body>
</html>
