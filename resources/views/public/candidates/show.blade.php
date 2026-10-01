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

    <main class="flex-1 py-10 px-4">
        <div class="max-w-4xl mx-auto space-y-8">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-xs text-zinc-400">
                <a href="{{ route('public.candidates.index') }}" class="hover:text-amber-500 transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">groups</span>
                    <span>Kandidat</span>
                </a>
                <span>/</span>
                <span class="text-zinc-800 font-bold">Paslon Nomor {{ $candidate->candidate_number }}</span>
            </div>

            {{-- Hero Card --}}
            <div class="bg-white rounded-2xl shadow-xs border border-zinc-200 overflow-hidden">
                <div class="bg-zinc-950 px-8 py-10 text-white flex flex-col sm:flex-row items-start sm:items-center gap-6 border-b border-zinc-800">
                    @if ($candidate->photo_path)
                        <img src="{{ Storage::url($candidate->photo_path) }}" alt="Foto Paslon" class="w-24 h-24 rounded-2xl object-cover border-2 border-amber-400 shadow-lg flex-shrink-0">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-zinc-900 border-2 border-amber-400 flex items-center justify-center text-amber-400 font-extrabold text-3xl shadow-lg flex-shrink-0">
                            {{ $candidate->candidate_number }}
                        </div>
                    @endif
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-full text-xs font-bold mb-3">
                            <span class="material-symbols-outlined text-sm">how_to_vote</span>
                            Pasangan Nomor Urut {{ $candidate->candidate_number }}
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold leading-tight text-white">
                            {{ $candidate->chairman->name }}
                        </h1>
                        <p class="text-zinc-400 mt-1 font-medium">
                            &amp; {{ $candidate->viceChairman?->name ?? '—' }}
                        </p>
                        <p class="text-zinc-500 text-xs mt-2">
                            {{ $candidate->chairman->study_program }} &bull; {{ $candidate->election->name }}
                        </p>
                    </div>
                </div>

                {{-- Visi --}}
                <div class="px-8 py-6 border-b border-zinc-100">
                    <h2 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">lightbulb</span>
                        Visi
                    </h2>
                    <p class="text-zinc-800 leading-relaxed text-sm italic bg-zinc-50 p-4 rounded-xl border border-zinc-100">{{ $candidate->registration?->vision ?? $candidate->vision ?? 'Visi belum dicantumkan.' }}</p>
                </div>

                {{-- Misi --}}
                @php
                    $missions = $candidate->registration?->mission ?? $candidate->mission ?? [];
                @endphp
                @if (!empty($missions))
                    <div class="px-8 py-6 border-b border-zinc-100">
                        <h2 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-base">checklist</span>
                            Misi
                        </h2>
                        <ol class="space-y-2">
                            @foreach ($missions as $idx => $misi)
                                <li class="flex gap-3 text-sm text-zinc-700">
                                    <span class="w-6 h-6 rounded-full bg-amber-500/10 text-amber-600 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">{{ $idx + 1 }}</span>
                                    <span>{{ $misi }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                {{-- Program Kerja --}}
                @if ($candidate->registration?->programs->isNotEmpty())
                    <div class="px-8 py-6">
                        <h2 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-4 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-base">work</span>
                            Program Kerja Unggulan
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($candidate->registration->programs->sortBy('sort_order') as $program)
                                <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-4">
                                    <h3 class="font-bold text-zinc-900 text-sm">{{ $program->title }}</h3>
                                    @if ($program->description)
                                        <p class="text-xs text-zinc-500 mt-1 leading-relaxed">{{ $program->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Back & Voting CTA --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 justify-between">
                <a href="{{ route('public.candidates.index') }}" class="inline-flex items-center gap-2 text-sm text-zinc-600 hover:text-zinc-900 transition font-bold">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    <span>Kembali ke Daftar Kandidat</span>
                </a>
                @auth
                    @if (auth()->user()->role === 'student')
                        <a href="{{ route('student.voting.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-sm font-bold rounded-xl shadow-xs transition">
                            <span class="material-symbols-outlined text-base">how_to_vote</span>
                            <span>Gunakan Hak Pilih</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-sm font-bold rounded-xl shadow-xs transition">
                        <span class="material-symbols-outlined text-base">login</span>
                        <span>Login untuk Memilih</span>
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
