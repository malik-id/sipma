<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Sistem Informasi Pemilihan Mahasiswa Universitas Mega Buana Palopo — Pemilihan transparan, langsung, dan aman." />
    <title>SIPMA — Universitas Mega Buana Palopo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full font-sans antialiased text-zinc-800 bg-zinc-50">

    {{-- Header --}}
    <header class="bg-white border-b border-zinc-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo UMB Palopo" class="w-10 h-10 object-contain" />
                <div>
                    <span class="font-bold text-zinc-900 text-sm tracking-tight block">SIPMA UMB</span>
                    <p class="text-[11px] text-zinc-500 leading-none">Universitas Mega Buana Palopo</p>
                </div>
            </a>

            {{-- Desktop Navigation (sm and up) --}}
            <nav class="hidden sm:flex items-center gap-2">
                <a href="{{ route('check-voter') }}" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-lg">how_to_vote</span>
                    <span>Cek DPT</span>
                </a>
                <a href="{{ route('public.candidates.index') }}" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-lg">badge</span>
                    <span>Kandidat</span>
                </a>
                <a href="{{ route('public.results.index') }}" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-lg">leaderboard</span>
                    <span>Hasil</span>
                </a>
                @auth
                    <a href="{{ auth()->user()->canAccessAdminPanel() ? route('admin.dashboard') : route('dashboard') }}" class="px-3.5 py-1.5 bg-zinc-900 text-amber-400 hover:bg-zinc-800 text-xs font-bold rounded-lg shadow-xs transition flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-lg">dashboard</span>
                        <span>Panel</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-lg shadow-xs transition flex items-center gap-1">
                        <span class="material-symbols-outlined text-lg">login</span>
                        <span>Masuk</span>
                    </a>
                @endauth
            </nav>

            {{-- Mobile Right Action + Hamburger Toggle (< sm) --}}
            <div class="flex items-center gap-2 sm:hidden">
                @auth
                    <a href="{{ auth()->user()->canAccessAdminPanel() ? route('admin.dashboard') : route('dashboard') }}" class="px-2.5 py-1.5 bg-zinc-900 text-amber-400 text-xs font-bold rounded-lg shadow-xs flex items-center gap-1">
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
            <a href="{{ route('check-voter') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                <span class="material-symbols-outlined text-xl text-amber-500">how_to_vote</span>
                <span>Cek Hak Pilih (DPT)</span>
            </a>
            <a href="{{ route('public.candidates.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                <span class="material-symbols-outlined text-xl text-amber-500">badge</span>
                <span>Daftar Pasangan Calon</span>
            </a>
            <a href="{{ route('public.results.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                <span class="material-symbols-outlined text-xl text-amber-500">leaderboard</span>
                <span>Hasil Perolehan Suara</span>
            </a>
            <div class="pt-2 mt-1 border-t border-zinc-100">
                <a href="{{ route('admin.login') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-zinc-500 hover:text-zinc-900 transition">
                    <span class="material-symbols-outlined text-lg text-zinc-400">admin_panel_settings</span>
                    <span>Portal Khusus Panitia / Admin</span>
                </a>
            </div>
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

    {{-- Hero Section (Minimalist Executive Dark + Gold) --}}
    <section class="bg-zinc-950 text-white py-16 sm:py-24 px-4 border-b border-zinc-900 relative">
        <div class="max-w-5xl mx-auto text-center space-y-6">
            <div class="flex items-center justify-center gap-2.5 text-amber-400 text-xs font-semibold tracking-widest uppercase">
                <span class="w-6 h-0.5 bg-amber-400 rounded-full"></span>
                <span>Sistem E-Voting Resmi Mahasiswa</span>
                <span class="w-6 h-0.5 bg-amber-400 rounded-full"></span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-extrabold leading-tight tracking-tight text-white">
                Sistem Informasi Pemilihan Mahasiswa<br class="hidden sm:block">
                <span class="text-amber-400">Universitas Mega Buana Palopo</span>
            </h1>
            
            <p class="text-sm sm:text-base text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                Platform pemungutan suara digital terintegrasi yang jujur, adil, transparan, dan anonim. Satu mahasiswa, satu suara untuk masa depan organisasi yang lebih baik.
            </p>

            @if ($activeElection)
                <div class="bg-zinc-900/90 border border-zinc-800 rounded-2xl p-6 max-w-xl mx-auto mt-8 text-left sm:text-center shadow-lg">
                    <span class="text-[10px] text-amber-400 uppercase tracking-widest font-bold block mb-1">Periode Pemilihan Aktif</span>
                    <h2 class="text-lg sm:text-xl font-bold text-white">{{ $activeElection->name }}</h2>
                    
                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-zinc-950 p-3 rounded-xl border border-zinc-800">
                            <p class="text-zinc-400">Mulai Voting</p>
                            <p class="font-bold text-white mt-0.5">{{ $activeElection->voting_start->format('d M Y H:i') }} WITA</p>
                        </div>
                        <div class="bg-zinc-950 p-3 rounded-xl border border-zinc-800">
                            <p class="text-zinc-400">Berakhir Voting</p>
                            <p class="font-bold text-white mt-0.5">{{ $activeElection->voting_end->format('d M Y H:i') }} WITA</p>
                        </div>
                    </div>
                    
                    <div class="mt-5 flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-zinc-950 font-bold rounded-xl text-xs sm:text-sm shadow-sm transition">
                            <span class="material-symbols-outlined text-base">how_to_vote</span>
                            Masuk &amp; Berikan Suara
                        </a>
                        <a href="{{ route('check-voter') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-200 font-semibold rounded-xl text-xs sm:text-sm border border-zinc-700 transition">
                            <span class="material-symbols-outlined text-base">search</span>
                            Cek Status DPT
                        </a>
                    </div>
                </div>
            @else
                <div class="mt-8">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-amber-500 hover:bg-amber-400 text-zinc-950 font-bold rounded-xl text-sm shadow-sm transition">
                        Masuk ke Portal Mahasiswa
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Stats Bar --}}
    <section class="bg-white border-b border-zinc-200 py-8">
        <div class="max-w-5xl mx-auto px-4 grid grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-2xl sm:text-3xl font-extrabold text-zinc-900">{{ number_format($totalStudents) }}</p>
                <p class="text-xs text-zinc-500 mt-1">Mahasiswa Terdaftar</p>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-extrabold text-amber-600">{{ number_format($totalVoters) }}</p>
                <p class="text-xs text-zinc-500 mt-1">Pemilih Memenuhi Syarat</p>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-extrabold text-zinc-900">{{ number_format($totalElections) }}</p>
                <p class="text-xs text-zinc-500 mt-1">Periode Pemilihan</p>
            </div>
        </div>
    </section>

    {{-- Featured Candidates --}}
    @if ($featuredCandidates->isNotEmpty())
        <section class="py-16 px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-bold text-zinc-900">Pasangan Calon Resmi</h2>
                    <p class="text-zinc-500 mt-1 text-xs sm:text-sm">Kandidat yang telah memenuhi verifikasi berkas dan ditetapkan panitia.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($featuredCandidates as $candidate)
                        <a href="{{ route('public.candidates.show', $candidate) }}"
                           class="bg-white rounded-2xl border border-zinc-200 p-6 hover:border-amber-400 hover:shadow-sm transition-all group">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-xl bg-zinc-900 text-amber-400 flex items-center justify-center font-extrabold text-xl">
                                    {{ $candidate->candidate_number }}
                                </div>
                                <div>
                                    <p class="text-[11px] text-zinc-400">Nomor Urut</p>
                                    <p class="font-bold text-zinc-900">Kandidat {{ $candidate->candidate_number }}</p>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <p class="font-bold text-zinc-900 group-hover:text-amber-600 transition text-sm">{{ $candidate->chairman->name }}</p>
                                <p class="text-xs text-zinc-500">&amp; {{ $candidate->viceChairman?->name ?? '—' }}</p>
                                <p class="text-[11px] text-zinc-400 mt-2">{{ $candidate->chairman->study_program }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-zinc-100 text-xs text-amber-600 font-semibold group-hover:translate-x-0.5 transition flex items-center gap-1">
                                Lihat Visi &amp; Misi <span>→</span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-8">
                    <a href="{{ route('public.candidates.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-zinc-300 rounded-xl text-xs sm:text-sm font-semibold text-zinc-700 hover:bg-zinc-100 transition">
                        Lihat Semua Kandidat
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- How It Works (Minimalist 3 Steps) --}}
    <section class="bg-white py-16 px-4 border-t border-zinc-200">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold text-zinc-900">Cara Menggunakan Hak Pilih</h2>
                <p class="text-zinc-500 mt-1 text-xs sm:text-sm">Langkah mudah memberikan suara secara digital dan rahasia.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="p-6 rounded-2xl bg-zinc-50 border border-zinc-200 text-center space-y-3">
                    <div class="w-12 h-12 bg-white border border-zinc-200 rounded-xl mx-auto flex items-center justify-center text-zinc-900 font-bold">
                        1
                    </div>
                    <h3 class="font-bold text-zinc-900 text-sm">Cek Status DPT</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed">Pastikan identitas NIM Anda telah terdaftar dan berstatus berhak memilih (eligible).</p>
                </div>
                <div class="p-6 rounded-2xl bg-zinc-50 border border-zinc-200 text-center space-y-3">
                    <div class="w-12 h-12 bg-white border border-zinc-200 rounded-xl mx-auto flex items-center justify-center text-amber-600 font-bold">
                        2
                    </div>
                    <h3 class="font-bold text-zinc-900 text-sm">Login Akun Mahasiswa</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed">Masuk ke portal SIPMA menggunakan akun Google resmi kampus yang terdaftar.</p>
                </div>
                <div class="p-6 rounded-2xl bg-zinc-50 border border-zinc-200 text-center space-y-3">
                    <div class="w-12 h-12 bg-white border border-zinc-200 rounded-xl mx-auto flex items-center justify-center text-zinc-900 font-bold">
                        3
                    </div>
                    <h3 class="font-bold text-zinc-900 text-sm">Pilih di Bilik Suara</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed">Tentukan pilihan calon Anda di bilik suara digital secara rahasia dan aman.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-zinc-950 text-zinc-400 py-10 px-4 text-center text-xs border-t border-zinc-900">
        <div class="max-w-5xl mx-auto space-y-3">
            <div class="flex items-center justify-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-6 h-6 object-contain" />
                <p class="text-white font-bold">SIPMA — Universitas Mega Buana Palopo</p>
            </div>
            <p class="text-zinc-500">Sistem Informasi Pemilihan Mahasiswa yang Transparan, Akuntabel, dan Demokratis.</p>
            <div class="flex items-center justify-center gap-4 pt-2 text-zinc-400">
                <a href="{{ route('check-voter') }}" class="hover:text-amber-400 transition">Cek DPT</a>
                <a href="{{ route('public.candidates.index') }}" class="hover:text-amber-400 transition">Kandidat</a>
                <a href="{{ route('public.results.index') }}" class="hover:text-amber-400 transition">Hasil Pemilihan</a>
                <a href="{{ route('admin.login') }}" class="hover:text-amber-400 transition">Portal Panitia</a>
            </div>
        </div>
    </footer>

</body>
</html>
