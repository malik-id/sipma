<!DOCTYPE html>
<html lang="id" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Profil Pasangan Calon {{ $candidate->candidate_number }}: {{ $candidate->chairman->name }} & {{ $candidate->viceChairman?->name }}. Lihat visi, misi, dan program kerja." />
    <title>Paslon {{ $candidate->candidate_number }} — {{ $candidate->chairman->name }} &amp; {{ $candidate->viceChairman?->name ?? '—' }} | SIPMA UMB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col font-sans antialiased text-zinc-800 bg-zinc-50">

    {{-- Navbar --}}
    <header class="bg-white border-b border-zinc-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo UMB Palopo" class="w-9 h-9 object-contain" />
                <div>
                    <span class="font-bold text-zinc-900 text-sm tracking-tight block">SIPMA UMB</span>
                    <p class="text-[10px] text-zinc-500 leading-none">Universitas Mega Buana Palopo</p>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden sm:flex items-center gap-2">
                <a href="{{ route('home') }}" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-lg">home</span>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('public.candidates.index') }}" class="px-3 py-1.5 text-xs font-bold text-amber-600 rounded-lg bg-amber-500/10 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-lg">badge</span>
                    <span>Kandidat</span>
                </a>
                <a href="{{ route('check-voter') }}" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-lg">how_to_vote</span>
                    <span>Cek DPT</span>
                </a>
                @auth
                    <a href="{{ auth()->user()->canAccessAdminPanel() ? route('admin.dashboard') : route('dashboard') }}" class="px-3.5 py-1.5 bg-zinc-950 text-amber-400 text-xs font-bold rounded-lg hover:bg-zinc-800 transition flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-lg">dashboard</span>
                        <span>Panel</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3.5 py-1.5 bg-amber-500 text-zinc-950 text-xs font-bold rounded-lg hover:bg-amber-400 transition flex items-center gap-1">
                        <span class="material-symbols-outlined text-lg">login</span>
                        <span>Masuk</span>
                    </a>
                @endauth
            </nav>

            {{-- Mobile Action + Hamburger Toggle --}}
            <div class="flex items-center gap-2 sm:hidden">
                @auth
                    <a href="{{ auth()->user()->canAccessAdminPanel() ? route('admin.dashboard') : route('dashboard') }}" class="px-2.5 py-1.5 bg-zinc-950 text-amber-400 text-xs font-bold rounded-lg shadow-xs flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">dashboard</span>
                        <span>Panel</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-2.5 py-1.5 bg-amber-500 text-zinc-950 text-xs font-bold rounded-lg shadow-xs flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">login</span>
                        <span>Masuk</span>
                    </a>
                @endauth

                <button type="button" onclick="togglePublicMobileMenu()" class="p-2 text-zinc-700 hover:text-zinc-950 hover:bg-zinc-100 rounded-lg transition" aria-label="Menu">
                    <span id="public-menu-icon" class="material-symbols-outlined text-2xl">menu</span>
                </button>
            </div>
        </div>

        {{-- Mobile Dropdown Menu Drawer --}}
        <div id="public-mobile-menu" class="hidden sm:hidden border-t border-zinc-200 bg-white/95 backdrop-blur-sm px-4 py-3 space-y-1 shadow-md">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                <span class="material-symbols-outlined text-xl text-amber-500">home</span>
                <span>Beranda Utama</span>
            </a>
            <a href="{{ route('public.candidates.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                <span class="material-symbols-outlined text-xl text-amber-500">badge</span>
                <span>Daftar Pasangan Calon</span>
            </a>
            <a href="{{ route('check-voter') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                <span class="material-symbols-outlined text-xl text-amber-500">how_to_vote</span>
                <span>Cek Hak Pilih (DPT)</span>
            </a>
            <a href="{{ route('public.results.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                <span class="material-symbols-outlined text-xl text-amber-500">leaderboard</span>
                <span>Hasil Perolehan Suara</span>
            </a>
        </div>
    </header>

    <script>
        function togglePublicMobileMenu() {
            const menu = document.getElementById('public-mobile-menu');
            const icon = document.getElementById('public-menu-icon');
            if (menu) {
                const isHidden = menu.classList.contains('hidden');
                menu.classList.toggle('hidden');
                if (icon) {
                    icon.textContent = isHidden ? 'close' : 'menu';
                }
            }
        }
    </script>

    <main class="flex-1 py-10 px-4 sm:px-6">
        <div class="max-w-5xl mx-auto space-y-8">

            {{-- Breadcrumb & Back --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-zinc-500">
                    <a href="{{ route('home') }}" class="hover:text-zinc-900 transition">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('public.candidates.index') }}" class="hover:text-zinc-900 transition">Kandidat</a>
                    <span>/</span>
                    <span class="text-zinc-900 font-bold">Paslon Nomor Urut {{ $candidate->candidate_number }}</span>
                </div>

                <a href="{{ route('public.candidates.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-zinc-600 hover:text-zinc-950 transition">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>

            {{-- Header Hero Banner --}}
            <div class="bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-950 text-white rounded-3xl p-6 sm:p-8 border border-zinc-800 shadow-xl relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 sm:gap-8">
                    
                    {{-- Paslon Photo --}}
                    <div class="shrink-0">
                        <div class="w-36 h-44 sm:w-44 sm:h-52 rounded-2xl bg-zinc-800 border-2 border-amber-400 overflow-hidden shadow-2xl flex items-center justify-center relative">
                            @if ($candidate->photo_path)
                                <img src="{{ asset('storage/' . $candidate->photo_path) }}" alt="Pasangan Calon No {{ $candidate->candidate_number }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-4 text-zinc-400">
                                    <span class="material-symbols-outlined text-4xl text-zinc-600 block mb-1">person</span>
                                    <span class="text-xs">Foto Resmi Paslon</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Header Meta --}}
                    <div class="flex-1 text-center md:text-left space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-500/20 text-amber-400 border border-amber-500/40 rounded-full text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>Pasangan Nomor Urut {{ $candidate->candidate_number }}</span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                            {{ $candidate->chairman->name }}
                            @if ($candidate->viceChairman)
                                <span class="text-amber-400 font-light block text-xl sm:text-2xl mt-1">&amp; {{ $candidate->viceChairman->name }}</span>
                            @endif
                        </h1>

                        <p class="text-xs sm:text-sm text-zinc-400 flex flex-wrap items-center justify-center md:justify-start gap-x-3 gap-y-1 pt-1">
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-base text-amber-500">how_to_vote</span>
                                <span>{{ $candidate->election->name }}</span>
                            </span>
                            <span>&bull;</span>
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-base text-emerald-400">verified</span>
                                <span>Terverifikasi KPU Mahasiswa</span>
                            </span>
                        </p>

                        <div class="pt-2 flex flex-wrap items-center justify-center md:justify-start gap-3">
                            <a href="{{ route('student.voting.index') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-xl shadow transition inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">how_to_vote</span>
                                <span>Gunakan Hak Suara Sekarang</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Pasangan Calon (Ketua & Wakil) --}}
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-500 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-600 text-lg">badge</span>
                    <span>Biodata Pasangan Calon</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Card Calon Ketua --}}
                    <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-6 hover:border-amber-400 transition">
                        <div class="flex items-center gap-3 pb-4 border-b border-zinc-100">
                            <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-2xl">account_circle</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-600 block">Calon Ketua</span>
                                <h3 class="text-base font-bold text-zinc-900 leading-snug">{{ $candidate->chairman->name }}</h3>
                            </div>
                        </div>

                        <dl class="mt-4 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between py-1 border-b border-zinc-50">
                                <dt class="text-zinc-400 font-medium">Nomor Induk Mahasiswa (NIM)</dt>
                                <dd class="font-mono font-bold text-zinc-800">{{ $candidate->chairman->nim }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-1 border-b border-zinc-50">
                                <dt class="text-zinc-400 font-medium">Program Studi</dt>
                                <dd class="font-semibold text-zinc-800 text-right">{{ $candidate->chairman->study_program }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-1 border-b border-zinc-50">
                                <dt class="text-zinc-400 font-medium">Angkatan / Semester</dt>
                                <dd class="font-semibold text-zinc-800">{{ $candidate->chairman->class_year }} / Semester {{ $candidate->chairman->semester }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-1 border-b border-zinc-50">
                                <dt class="text-zinc-400 font-medium">Status Mahasiswa</dt>
                                <dd class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold {{ $candidate->chairman->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-zinc-100 text-zinc-800' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>{{ ucfirst($candidate->chairman->student_status->value ?? 'Aktif') }}</span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Card Calon Wakil Ketua --}}
                    <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-6 hover:border-amber-400 transition">
                        <div class="flex items-center gap-3 pb-4 border-b border-zinc-100">
                            <div class="w-12 h-12 rounded-xl bg-zinc-100 text-zinc-700 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-2xl">account_circle</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-zinc-500 block">Calon Wakil Ketua</span>
                                <h3 class="text-base font-bold text-zinc-900 leading-snug">{{ $candidate->viceChairman?->name ?? '—' }}</h3>
                            </div>
                        </div>

                        @if ($candidate->viceChairman)
                            <dl class="mt-4 space-y-2.5 text-xs">
                                <div class="flex items-center justify-between py-1 border-b border-zinc-50">
                                    <dt class="text-zinc-400 font-medium">Nomor Induk Mahasiswa (NIM)</dt>
                                    <dd class="font-mono font-bold text-zinc-800">{{ $candidate->viceChairman->nim }}</dd>
                                </div>
                                <div class="flex items-center justify-between py-1 border-b border-zinc-50">
                                    <dt class="text-zinc-400 font-medium">Program Studi</dt>
                                    <dd class="font-semibold text-zinc-800 text-right">{{ $candidate->viceChairman->study_program }}</dd>
                                </div>
                                <div class="flex items-center justify-between py-1 border-b border-zinc-50">
                                    <dt class="text-zinc-400 font-medium">Angkatan / Semester</dt>
                                    <dd class="font-semibold text-zinc-800">{{ $candidate->viceChairman->class_year }} / Semester {{ $candidate->viceChairman->semester }}</dd>
                                </div>
                                <div class="flex items-center justify-between py-1 border-b border-zinc-50">
                                    <dt class="text-zinc-400 font-medium">Status Mahasiswa</dt>
                                    <dd class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold {{ $candidate->viceChairman->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-zinc-100 text-zinc-800' }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>{{ ucfirst($candidate->viceChairman->student_status->value ?? 'Aktif') }}</span>
                                    </dd>
                                </div>
                            </dl>
                        @else
                            <div class="mt-6 text-center text-xs text-zinc-400 py-6">
                                Pasangan ini maju sebagai calon tunggal.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Visi & Misi --}}
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-6 sm:p-8 space-y-8">
                
                {{-- Visi --}}
                <div>
                    <h2 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">lightbulb</span>
                        <span>Visi Paslon</span>
                    </h2>
                    <div class="relative bg-amber-50/50 border border-amber-200/70 rounded-2xl p-5 sm:p-6">
                        <span class="material-symbols-outlined text-4xl text-amber-300 absolute right-4 top-4 select-none opacity-50">format_quote</span>
                        <p class="text-zinc-900 text-sm sm:text-base italic leading-relaxed font-medium">
                            "{{ $candidate->registration?->vision ?? $candidate->vision ?? 'Visi belum dicantumkan.' }}"
                        </p>
                    </div>
                </div>

                {{-- Misi --}}
                @php
                    $missions = $candidate->registration?->mission ?? $candidate->mission ?? [];
                @endphp
                @if (!empty($missions))
                    <div class="pt-6 border-t border-zinc-100">
                        <h2 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">format_list_numbered</span>
                            <span>Misi Paslon</span>
                        </h2>
                        <div class="space-y-3">
                            @foreach ($missions as $idx => $misi)
                                <div class="flex items-start gap-3.5 p-3.5 bg-zinc-50 rounded-xl border border-zinc-100">
                                    <span class="w-6 h-6 rounded-full bg-amber-500 text-zinc-950 font-extrabold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                        {{ $idx + 1 }}
                                    </span>
                                    <p class="text-xs sm:text-sm text-zinc-800 leading-relaxed font-medium">
                                        {{ $misi }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Program Kerja Unggulan --}}
                @if ($candidate->registration?->programs && $candidate->registration->programs->isNotEmpty())
                    <div class="pt-6 border-t border-zinc-100">
                        <h2 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">star</span>
                            <span>Program Kerja Unggulan</span>
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($candidate->registration->programs->sortBy('sort_order') as $program)
                                <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-4 sm:p-5 hover:border-amber-300 transition">
                                    <div class="flex items-center gap-2.5 mb-2">
                                        <span class="w-7 h-7 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-base">task_alt</span>
                                        </span>
                                        <h3 class="font-bold text-zinc-900 text-sm">{{ $program->title }}</h3>
                                    </div>
                                    @if ($program->description)
                                        <p class="text-xs text-zinc-600 leading-relaxed pl-9.5">{{ $program->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer Navigation / Action --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                <a href="{{ route('public.candidates.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-zinc-600 hover:text-zinc-900 transition">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    <span>Kembali ke Daftar Kandidat</span>
                </a>

                @auth
                    <a href="{{ route('student.voting.index') }}" class="w-full sm:w-auto px-6 py-3 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-xl shadow transition inline-flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">how_to_vote</span>
                        <span>Lanjut ke Bilik Suara</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 bg-zinc-950 hover:bg-zinc-800 text-amber-400 text-xs font-bold rounded-xl shadow transition inline-flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">login</span>
                        <span>Masuk untuk Memberikan Suara</span>
                    </a>
                @endauth
            </div>
        </div>
    </main>

    <footer class="border-t border-zinc-200 bg-white py-6 text-center text-xs text-zinc-400 mt-auto">
        SIPMA &copy; {{ date('Y') }} Universitas Mega Buana Palopo. Seluruh hak cipta dilindungi.
    </footer>

</body>
</html>
