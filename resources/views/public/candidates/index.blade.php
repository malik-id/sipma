<!DOCTYPE html>
<html lang="id" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Pasangan Calon — SIPMA Universitas Mega Buana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col font-sans antialiased text-zinc-800 bg-zinc-50">

    {{-- Top Navbar --}}
    <header class="bg-white border-b border-zinc-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
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
                <a href="{{ route('check-voter') }}" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-lg">how_to_vote</span>
                    <span>Cek DPT</span>
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

            {{-- Mobile Right Action + Hamburger Toggle --}}
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
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                <span class="material-symbols-outlined text-xl text-amber-500">home</span>
                <span>Beranda Utama</span>
            </a>
            <a href="{{ route('check-voter') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                <span class="material-symbols-outlined text-xl text-amber-500">how_to_vote</span>
                <span>Cek Hak Pilih (DPT)</span>
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

    {{-- Main Area --}}
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 tracking-tight">Kandidat Pasangan Calon</h1>
                <p class="text-xs sm:text-sm text-zinc-500 mt-1">Kenali visi, misi, dan program kerja para calon sebelum memberikan hak suara.</p>
            </div>

            @if ($elections->count() > 1)
                <div>
                    <form method="GET" action="{{ route('public.candidates.index') }}">
                        <select name="election_id" class="text-xs border-zinc-300 rounded-xl focus:ring-amber-500 focus:border-amber-500 bg-white font-medium shadow-sm py-2 px-3" onchange="this.form.submit()">
                            @foreach ($elections as $el)
                                <option value="{{ $el->id }}" {{ $activeElection && $activeElection->id === $el->id ? 'selected' : '' }}>
                                    {{ $el->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endif
        </div>

        @if ($candidates->isEmpty())
            <div class="bg-white rounded-2xl border border-zinc-200 p-12 text-center shadow-sm">
                <div class="w-14 h-14 rounded-full bg-zinc-100 text-zinc-400 flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-3xl">groups</span>
                </div>
                <h3 class="font-bold text-zinc-800 text-base">Belum Ada Kandidat Ditetapkan</h3>
                <p class="text-xs sm:text-sm text-zinc-500 mt-1 max-w-md mx-auto">
                    Kandidat resmi untuk pemilihan ini belum ditetapkan oleh panitia. Silakan cek kembali pada tahapan penetapan calon.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($candidates as $cand)
                    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden flex flex-col justify-between hover:border-amber-400 hover:shadow-md transition">
                        <div>
                            {{-- Header Card dengan Nomor Urut --}}
                            <div class="bg-zinc-950 px-6 py-4 flex items-center justify-between text-white border-b border-zinc-800">
                                <span class="text-[11px] font-bold tracking-widest uppercase text-amber-400">Nomor Urut Paslon</span>
                                <span class="w-8 h-8 rounded-full bg-amber-400 text-zinc-950 font-extrabold text-sm flex items-center justify-center">
                                    {{ $cand->candidate_number }}
                                </span>
                            </div>

                            {{-- Foto Pasangan --}}
                            <div class="p-6">
                                <div class="w-full h-52 rounded-xl bg-zinc-100 border border-zinc-200 overflow-hidden mb-5 flex items-center justify-center">
                                    @if ($cand->photo_path)
                                        <img src="{{ asset('storage/' . $cand->photo_path) }}" alt="Pasangan No {{ $cand->candidate_number }}" class="w-full h-full object-cover" />
                                    @else
                                        <div class="text-zinc-400 text-xs text-center p-4">
                                            Foto paslon belum diunggah
                                        </div>
                                    @endif
                                </div>

                                {{-- Identitas Ketua & Wakil --}}
                                <div class="space-y-3 pb-4 border-b border-zinc-100">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 block">Calon Ketua</span>
                                        <div class="font-bold text-zinc-900 text-base leading-snug">{{ $cand->chairman->name }}</div>
                                        <div class="text-xs text-zinc-500">{{ $cand->chairman->study_program }} &bull; Semester {{ $cand->chairman->semester }}</div>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 block">Calon Wakil Ketua</span>
                                        <div class="font-bold text-zinc-900 text-base leading-snug">{{ $cand->viceChairman?->name ?? '—' }}</div>
                                        <div class="text-xs text-zinc-500">{{ $cand->viceChairman?->study_program ?? '—' }} &bull; Semester {{ $cand->viceChairman?->semester ?? '—' }}</div>
                                    </div>
                                </div>

                                {{-- Visi --}}
                                <div class="pt-4">
                                    <span class="text-[11px] font-bold text-zinc-700 uppercase tracking-wider block mb-1">Visi</span>
                                    <p class="text-xs text-zinc-600 leading-relaxed italic bg-zinc-50 p-3 rounded-lg border border-zinc-100">
                                        "{{ $cand->vision ?: 'Visi belum dicantumkan.' }}"
                                    </p>
                                </div>

                                {{-- Misi --}}
                                @if ($cand->mission && count($cand->mission) > 0)
                                    <div class="pt-4">
                                        <span class="text-[11px] font-bold text-zinc-700 uppercase tracking-wider block mb-1">Misi</span>
                                        <ul class="space-y-1 text-xs text-zinc-600 list-disc list-inside">
                                            @foreach ($cand->mission as $m)
                                                <li>{{ $m }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Footer Action --}}
                        <div class="p-4 bg-zinc-50 border-t border-zinc-100">
                            <a href="{{ route('student.voting.index') }}" class="w-full py-2.5 px-4 bg-zinc-950 hover:bg-zinc-800 text-amber-400 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5">
                                <span>Gunakan Hak Pilih</span> →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <footer class="border-t border-zinc-200 bg-white py-6 text-center text-xs text-zinc-400">
        SIPMA &copy; {{ date('Y') }} Universitas Mega Buana Palopo. Seluruh hak cipta dilindungi.
    </footer>

</body>
</html>
