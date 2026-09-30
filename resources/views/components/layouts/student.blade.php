<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'Dashboard' }} — SIPMA HIMAKOM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800">
    <div class="min-h-full flex flex-col">
        {{-- Navbar --}}
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center font-bold text-white text-sm shadow-sm">H</div>
                    <div>
                        <span class="font-bold text-slate-900 text-sm tracking-tight">SIPMA HIMAKOM</span>
                        <p class="text-xs text-slate-400 leading-none mt-0.5">Portal Mahasiswa</p>
                    </div>
                </div>

                <nav class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('student.voting.index') }}"
                       class="px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('student.voting.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Bilik Suara
                    </a>
                    <a href="{{ route('public.candidates.index') }}"
                       class="px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('public.candidates.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Kandidat
                    </a>
                    <a href="{{ route('registration.index') }}"
                       class="px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('registration.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Pendaftaran Calon
                    </a>
                    <a href="{{ route('public.results.index') }}"
                       class="px-3 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('public.results.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Hasil
                    </a>
                    <a href="{{ route('check-voter') }}"
                       class="px-3 py-2 text-sm font-medium rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                        Cek DPT
                    </a>
                </nav>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-semibold text-slate-800">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-400">{{ auth()->user()->student?->nim }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 rounded-lg hover:bg-rose-50 transition" title="Keluar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Main --}}
        <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 py-8">
            @if (session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3 text-emerald-800 text-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 p-4 flex items-start gap-3 text-rose-800 text-sm">
                    <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="space-y-1">
                        @foreach ($errors->all() as $err)
                            <p>{{ $err }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>

        <footer class="border-t border-slate-200 bg-white py-4 text-center text-xs text-slate-400">
            SIPMA &copy; {{ date('Y') }} HIMAKOM — Fakultas Ilmu Komputer
        </footer>
    </div>
</body>
</html>
