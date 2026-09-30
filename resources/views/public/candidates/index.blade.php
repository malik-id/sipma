<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Kandidat Calon — SIPMA HIMAKOM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col font-sans antialiased text-slate-800">

    {{-- Top Navbar --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center font-bold text-white text-sm shadow-sm">H</div>
                <div>
                    <span class="font-bold text-slate-900 text-sm tracking-tight">SIPMA HIMAKOM</span>
                    <p class="text-[10px] text-slate-400 leading-none mt-0.5">Daftar Pasangan Calon Resmi</p>
                </div>
            </div>

            <nav class="flex items-center gap-2">
                <a href="{{ route('check-voter') }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition">
                    Cek DPT
                </a>
                <a href="{{ route('public.results.index') }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition">
                    Hasil Pemilihan
                </a>
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-blue-700 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-blue-700 transition">
                        Masuk
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Hero Section --}}
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Kandidat Ketua &amp; Wakil Ketua</h1>
                <p class="text-sm text-slate-500 mt-1">Kenali visi, misi, dan program kerja pasangan calon sebelum memberikan hak suara Anda.</p>
            </div>

            @if ($elections->count() > 1)
                <div>
                    <form method="GET" action="{{ route('public.candidates.index') }}">
                        <select name="election_id" class="text-xs border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-white shadow-xs font-medium" onchange="this.form.submit()">
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
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">Belum Ada Kandidat Ditetapkan</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Kandidat resmi untuk periode ini belum ditetapkan oleh panitia pemilihan. Silakan kembali lagi pada tahap penetapan calon.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($candidates as $cand)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            {{-- Header Card dengan Nomor Urut --}}
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex items-center justify-between text-white">
                                <span class="text-xs font-semibold tracking-wider uppercase text-blue-100">Nomor Urut</span>
                                <span class="w-8 h-8 rounded-full bg-white text-blue-700 font-black text-sm flex items-center justify-center shadow-xs">
                                    {{ $cand->candidate_number }}
                                </span>
                            </div>

                            {{-- Foto Pasangan --}}
                            <div class="p-6">
                                <div class="w-full h-56 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden mb-5 flex items-center justify-center">
                                    @if ($cand->photo_path)
                                        <img src="{{ asset('storage/' . $cand->photo_path) }}" alt="Pasangan No {{ $cand->candidate_number }}" class="w-full h-full object-cover" />
                                    @else
                                        <div class="text-slate-400 text-xs text-center p-4">
                                            Foto belum tersedia
                                        </div>
                                    @endif
                                </div>

                                {{-- Identitas Ketua & Wakil --}}
                                <div class="space-y-3 pb-4 border-b border-slate-100">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 block">Calon Ketua</span>
                                        <div class="font-bold text-slate-900 text-base leading-snug">{{ $cand->chairman->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $cand->chairman->study_program }} &bull; Semester {{ $cand->chairman->semester }}</div>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 block">Calon Wakil Ketua</span>
                                        <div class="font-bold text-slate-900 text-base leading-snug">{{ $cand->viceChairman?->name ?? '—' }}</div>
                                        <div class="text-xs text-slate-500">{{ $cand->viceChairman?->study_program ?? '—' }} &bull; Semester {{ $cand->viceChairman?->semester ?? '—' }}</div>
                                    </div>
                                </div>

                                {{-- Visi --}}
                                <div class="pt-4">
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-1">Visi</span>
                                    <p class="text-xs text-slate-600 leading-relaxed italic bg-slate-50 p-3 rounded-lg border border-slate-100">
                                        "{{ $cand->vision ?: 'Visi belum dicantumkan.' }}"
                                    </p>
                                </div>

                                {{-- Misi --}}
                                @if ($cand->mission && count($cand->mission) > 0)
                                    <div class="pt-4">
                                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-1">Misi</span>
                                        <ul class="space-y-1.5 text-xs text-slate-600 list-disc list-inside">
                                            @foreach ($cand->mission as $m)
                                                <li>{{ $m }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Program Kerja Unggulan --}}
                                @if ($cand->registration && $cand->registration->programs->isNotEmpty())
                                    <div class="pt-4">
                                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">Program Kerja Unggulan</span>
                                        <div class="space-y-2">
                                            @foreach ($cand->registration->programs->take(3) as $prog)
                                                <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100 text-xs">
                                                    <span class="font-bold text-slate-800">{{ $prog->title }}</span>
                                                    @if ($prog->description)
                                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">{{ $prog->description }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Footer Action --}}
                        <div class="p-4 bg-slate-50 border-t border-slate-100">
                            <a href="{{ route('student.voting.index') }}" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                                <span>Gunakan Hak Pilih</span> →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <footer class="border-t border-slate-200 bg-white py-6 text-center text-xs text-slate-400">
        SIPMA &copy; {{ date('Y') }} HIMAKOM — Sistem Informasi Pemilihan Mahasiswa
    </footer>

</body>
</html>
