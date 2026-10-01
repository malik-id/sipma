<!DOCTYPE html>
<html lang="id" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'Dashboard' }} — SIPMA Universitas Mega Buana</title>

    {{-- Google Font: Quicksand --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    {{-- Google Material Symbols Outlined --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-zinc-900 bg-zinc-50">

    <div class="min-h-full flex flex-col md:flex-row">

        {{-- Desktop Left Sidebar (Laptop / Tablet Screen) --}}
        <aside class="hidden md:flex w-64 bg-zinc-950 text-zinc-300 flex-col shrink-0 border-r border-zinc-850 min-h-screen sticky top-0 h-screen">
            
            {{-- Brand Header --}}
            <div class="h-16 flex items-center px-5 border-b border-zinc-850 gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo UMB Palopo" class="w-9 h-9 object-contain rounded-full bg-white p-0.5 shadow-xs shrink-0" />
                <div class="truncate">
                    <span class="font-bold text-white text-sm tracking-tight block">SIPMA UMB</span>
                    <span class="text-[11px] text-amber-400/90 font-medium tracking-wide truncate block">Portal Mahasiswa</span>
                </div>
            </div>

            {{-- Sidebar Navigation Menu --}}
            <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
                <div class="pb-1.5 px-3 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Menu Utama</div>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                    <span class="material-symbols-outlined text-[20px]">home</span>
                    Dashboard
                </a>

                <a href="{{ route('student.voting.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold rounded-xl transition {{ request()->routeIs('student.voting.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                    <span class="material-symbols-outlined text-[20px]">how_to_vote</span>
                    Bilik Suara
                </a>

                <a href="{{ route('registration.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold rounded-xl transition {{ request()->routeIs('registration.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                    <span class="material-symbols-outlined text-[20px]">assignment_add</span>
                    Pendaftaran Calon
                </a>

                <div class="pt-4 pb-1.5 px-3 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Informasi Publik</div>

                <a href="{{ route('public.candidates.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold rounded-xl transition {{ request()->routeIs('public.candidates.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                    Daftar Kandidat
                </a>

                <a href="{{ route('public.results.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold rounded-xl transition {{ request()->routeIs('public.results.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                    <span class="material-symbols-outlined text-[20px]">leaderboard</span>
                    Hasil Pemilihan
                </a>

                <a href="{{ route('check-voter') }}"
                   class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold rounded-xl transition {{ request()->routeIs('check-voter') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                    <span class="material-symbols-outlined text-[20px]">search_check</span>
                    Cek Status DPT
                </a>

                <div class="pt-4 pb-1.5 px-3 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Akun</div>

                <a href="{{ route('student.profile') }}"
                   class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold rounded-xl transition {{ request()->routeIs('student.profile') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                    <span class="material-symbols-outlined text-[20px]">person</span>
                    Profil Mahasiswa
                </a>
            </nav>

            {{-- Sidebar Footer / User Info --}}
            <div class="p-3 border-t border-zinc-850 flex items-center justify-between bg-zinc-950/80">
                <a href="{{ route('student.profile') }}" class="truncate pr-2 group">
                    <div class="text-xs font-bold text-white truncate group-hover:text-amber-400 transition">{{ auth()->user()->student?->name ?? auth()->user()->name }}</div>
                    <div class="text-[11px] text-amber-400 font-mono tracking-tight">{{ auth()->user()->student?->nim ?? 'Mahasiswa' }}</div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-400 hover:bg-zinc-900 transition flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile Top Header (Small Screens Only) --}}
        <header class="md:hidden bg-white border-b border-zinc-200 sticky top-0 z-30 px-4 h-16 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="Logo UMB Palopo" class="w-8 h-8 object-contain rounded-full bg-white shadow-2xs" />
                <div>
                    <span class="font-bold text-zinc-900 text-xs tracking-tight block">SIPMA UMB</span>
                    <p class="text-[10px] text-zinc-500 leading-none">Portal Mahasiswa</p>
                </div>
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('student.profile') }}" class="p-1.5 rounded-lg text-zinc-600 hover:bg-zinc-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">account_circle</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-zinc-100 transition flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- Main Container --}}
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-8 py-6 pb-24 md:pb-8">
                @if (session('success'))
                    <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3 text-emerald-900 text-xs sm:text-sm font-medium">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px] shrink-0">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 p-4 flex items-start gap-3 text-rose-900 text-xs sm:text-sm">
                        <span class="material-symbols-outlined text-rose-500 text-[20px] shrink-0 mt-0.5">error</span>
                        <div class="space-y-1">
                            @foreach ($errors->all() as $err)
                                <p>{{ $err }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>

            {{-- Desktop Footer --}}
            <footer class="hidden md:block border-t border-zinc-200 bg-white py-4 px-8 text-center text-xs text-zinc-500">
                SIPMA &copy; {{ date('Y') }} Universitas Mega Buana Palopo. Seluruh hak cipta dilindungi.
            </footer>
        </div>

        {{-- Mobile Android Bottom Navigation Bar --}}
        <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-zinc-200 px-3 py-1.5 flex items-center justify-around shadow-lg">
            {{-- Beranda --}}
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-2 text-[10px] font-bold transition {{ request()->routeIs('dashboard') ? 'text-amber-600' : 'text-zinc-400 hover:text-zinc-700' }}">
                <span class="material-symbols-outlined text-[22px]">home</span>
                <span>Beranda</span>
            </a>

            {{-- Voting --}}
            <a href="{{ route('student.voting.index') }}" class="flex flex-col items-center py-1 px-2 text-[10px] font-bold transition {{ request()->routeIs('student.voting.*') ? 'text-amber-600' : 'text-zinc-400 hover:text-zinc-700' }}">
                <span class="material-symbols-outlined text-[22px]">how_to_vote</span>
                <span>Voting</span>
            </a>

            {{-- Pendaftaran --}}
            <a href="{{ route('registration.index') }}" class="flex flex-col items-center py-1 px-2 text-[10px] font-bold transition {{ request()->routeIs('registration.*') ? 'text-amber-600' : 'text-zinc-400 hover:text-zinc-700' }}">
                <span class="material-symbols-outlined text-[22px]">assignment_add</span>
                <span>Daftar</span>
            </a>

            {{-- Kandidat --}}
            <a href="{{ route('public.candidates.index') }}" class="flex flex-col items-center py-1 px-2 text-[10px] font-bold transition {{ request()->routeIs('public.candidates.*') ? 'text-amber-600' : 'text-zinc-400 hover:text-zinc-700' }}">
                <span class="material-symbols-outlined text-[22px]">group</span>
                <span>Kandidat</span>
            </a>

            {{-- Profil --}}
            <a href="{{ route('student.profile') }}" class="flex flex-col items-center py-1 px-2 text-[10px] font-bold transition {{ request()->routeIs('student.profile') ? 'text-amber-600' : 'text-zinc-400 hover:text-zinc-700' }}">
                <span class="material-symbols-outlined text-[22px]">person</span>
                <span>Profil</span>
            </a>
        </nav>
    </div>
</body>
</html>
