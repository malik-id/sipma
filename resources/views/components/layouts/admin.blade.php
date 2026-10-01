<!DOCTYPE html>
<html lang="id" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'Admin' }} — SIPMA Universitas Mega Buana Palopo</title>

    {{-- Google Font: Quicksand --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    {{-- Google Material Symbols Outlined --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-zinc-900 bg-zinc-50">

    {{-- Mobile Sidebar Drawer Backdrop --}}
    <div id="mobile-sidebar-backdrop"
         onclick="toggleMobileSidebar()"
         class="fixed inset-0 bg-zinc-950/60 backdrop-blur-xs z-40 hidden md:hidden transition-opacity"></div>

    <div class="min-h-full flex flex-col md:flex-row">

        {{-- Sidebar (Desktop & Mobile Drawer) --}}
        <aside id="admin-sidebar"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-zinc-950 text-zinc-300 flex flex-col shrink-0 -translate-x-full md:translate-x-0 md:sticky md:top-0 md:h-screen md:overflow-y-auto transition-transform duration-200 ease-in-out border-r border-zinc-850 shadow-sm md:shadow-none">

            {{-- Sidebar Brand --}}
            <div class="h-16 flex items-center justify-between px-5 border-b border-zinc-850">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Logo UMB Palopo"
                         class="w-9 h-9 object-contain rounded-full bg-white p-0.5 shadow-xs shrink-0" />
                    <div class="truncate">
                        <span class="text-sm font-bold tracking-tight text-white block leading-tight group-hover:text-amber-400 transition">SIPMA ADMIN</span>
                        <span class="text-[11px] text-amber-400/90 font-medium tracking-wide truncate block">UMB Palopo</span>
                    </div>
                </a>

                {{-- Close Button for Mobile Drawer --}}
                <button type="button"
                        onclick="toggleMobileSidebar()"
                        class="md:hidden p-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            {{-- Navigation Menu with Google Material Icons --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 text-xs font-bold rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    Dashboard
                </a>

                @if (auth()->user()->isAdmin())
                    <div class="pt-4 pb-1.5 px-3 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Master Data</div>

                    <a href="{{ route('admin.students.index') }}"
                       class="flex items-center gap-3 px-3 py-2 text-xs font-bold rounded-lg transition {{ request()->routeIs('admin.students.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                        <span class="material-symbols-outlined text-[18px]">group</span>
                        Data Mahasiswa
                    </a>

                    <a href="{{ route('admin.voters.index') }}"
                       class="flex items-center gap-3 px-3 py-2 text-xs font-bold rounded-lg transition {{ request()->routeIs('admin.voters.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                        <span class="material-symbols-outlined text-[18px]">how_to_vote</span>
                        Daftar Pemilih (DPT)
                    </a>

                    <div class="pt-4 pb-1.5 px-3 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Pemilihan</div>

                    <a href="{{ route('admin.elections.index') }}"
                       class="flex items-center gap-3 px-3 py-2 text-xs font-bold rounded-lg transition {{ request()->routeIs('admin.elections.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                        <span class="material-symbols-outlined text-[18px]">event_available</span>
                        Periode Pemilihan
                    </a>

                    <a href="{{ route('admin.registrations.index') }}"
                       class="flex items-center gap-3 px-3 py-2 text-xs font-bold rounded-lg transition {{ request()->routeIs('admin.registrations.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                        <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                        Verifikasi Bakal Calon
                    </a>

                    <a href="{{ route('admin.candidates.index') }}"
                       class="flex items-center gap-3 px-3 py-2 text-xs font-bold rounded-lg transition {{ request()->routeIs('admin.candidates.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                        <span class="material-symbols-outlined text-[18px]">badge</span>
                        Calon Resmi
                    </a>
                @endif

                <div class="pt-4 pb-1.5 px-3 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Pemungutan &amp; Hasil</div>

                <a href="{{ route('admin.voting-monitor') }}"
                   class="flex items-center gap-3 px-3 py-2 text-xs font-bold rounded-lg transition {{ request()->routeIs('admin.voting-monitor') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                    <span class="material-symbols-outlined text-[18px]">monitoring</span>
                    Monitoring Partisipasi
                </a>

                <a href="{{ route('admin.results.index') }}"
                   class="flex items-center gap-3 px-3 py-2 text-xs font-bold rounded-lg transition {{ request()->routeIs('admin.results.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                    <span class="material-symbols-outlined text-[18px]">leaderboard</span>
                    Hasil Pemilihan
                </a>

                <div class="pt-4 pb-1.5 px-3 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Pengawasan</div>

                <a href="{{ route('admin.audit-logs.index') }}"
                   class="flex items-center gap-3 px-3 py-2 text-xs font-bold rounded-lg transition {{ request()->routeIs('admin.audit-logs.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                    <span class="material-symbols-outlined text-[18px]">verified_user</span>
                    Audit Log
                </a>

                @if (auth()->user()->role === 'super_admin')
                    <a href="{{ route('admin.settings.index') }}"
                       class="flex items-center gap-3 px-3 py-2 text-xs font-bold rounded-lg transition {{ request()->routeIs('admin.settings.*') ? 'bg-zinc-900 text-amber-400 border-l-2 border-amber-400 shadow-xs' : 'text-zinc-400 hover:bg-zinc-900/70 hover:text-zinc-100' }}">
                        <span class="material-symbols-outlined text-[18px]">settings</span>
                        Pengaturan Sistem
                    </a>
                @endif
            </nav>

            {{-- Sidebar Footer / User Info --}}
            <div class="p-3 border-t border-zinc-850 flex items-center justify-between bg-zinc-950/80">
                <div class="truncate pr-2">
                    <div class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-amber-400 font-mono tracking-tight capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-400 hover:bg-zinc-900 transition flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Top Header --}}
            <header class="h-16 bg-white border-b border-zinc-200 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    {{-- Mobile Hamburger Button --}}
                    <button type="button"
                            onclick="toggleMobileSidebar()"
                            class="md:hidden p-2 rounded-lg text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition flex items-center justify-center"
                            aria-label="Buka Menu">
                        <span class="material-symbols-outlined text-[22px]">menu</span>
                    </button>

                    <h2 class="text-sm sm:text-base font-bold text-zinc-900 truncate">
                        {{ $header ?? $title ?? 'Panel Admin' }}
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-200">
                        {{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}
                    </span>

                    <a href="{{ route('check-voter') }}" target="_blank"
                       class="text-xs font-bold text-zinc-700 hover:text-zinc-950 flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-zinc-200 hover:bg-zinc-50 transition">
                        <span>Portal Publik</span>
                        <span class="material-symbols-outlined text-[16px] text-zinc-400">open_in_new</span>
                    </a>
                </div>
            </header>

            {{-- Main Body --}}
            <main class="flex-1 p-4 sm:p-8 overflow-y-auto">
                @if (session('success'))
                    <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3 text-emerald-900 text-xs sm:text-sm font-medium">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 p-4 flex items-start gap-3 text-rose-900 text-xs sm:text-sm">
                        <span class="material-symbols-outlined text-rose-500 text-[20px] mt-0.5">error</span>
                        <div class="space-y-1">
                            @foreach ($errors->all() as $err)
                                <p>{{ $err }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Script for Mobile Sidebar Toggle --}}
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
