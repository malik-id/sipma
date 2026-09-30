<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hasil Resmi Pemilihan — SIPMA HIMAKOM</title>
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
                    <p class="text-[10px] text-slate-400 leading-none mt-0.5">Hasil Resmi Pemilihan</p>
                </div>
            </div>

            <nav class="flex items-center gap-2">
                <a href="{{ route('public.candidates.index') }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition">
                    Daftar Kandidat
                </a>
                <a href="{{ route('check-voter') }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition">
                    Cek DPT
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

    {{-- Main Area --}}
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Hasil Resmi Pemilihan</h1>
                <p class="text-sm text-slate-500 mt-1">Perolehan suara resmi dari kotak suara digital SIPMA HIMAKOM.</p>
            </div>

            @if ($publishedElections->count() > 1)
                <div>
                    <form method="GET" action="{{ route('public.results.index') }}">
                        <select name="election_id" class="text-xs border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-white shadow-xs font-medium" onchange="this.form.submit()">
                            @foreach ($publishedElections as $el)
                                <option value="{{ $el->id }}" {{ $activeElection && $activeElection->id === $el->id ? 'selected' : '' }}>
                                    {{ $el->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endif
        </div>

        @if (! $activeElection || ! $results)
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">Hasil Belum Dipublikasikan</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Hasil pemilihan akan dipublikasikan secara resmi setelah seluruh proses pemungutan suara ditutup dan waktu pengumuman tiba.
                </p>
            </div>
        @else
            {{-- Metrics Summary --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Suara Sah</span>
                    <div class="text-3xl font-black text-slate-900">{{ number_format($results['total']) }}</div>
                    <div class="text-xs text-slate-400 mt-1">Surat suara anonim tercatat</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Daftar Pemilih Tetap</span>
                    <div class="text-3xl font-black text-slate-900">{{ number_format($results['eligible']) }}</div>
                    <div class="text-xs text-slate-400 mt-1">Mahasiswa berhak memilih</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block mb-1">Tingkat Partisipasi</span>
                    <div class="text-3xl font-black text-blue-600">{{ $results['turnout'] }}%</div>
                    <div class="text-xs text-slate-400 mt-1">{{ number_format($results['participated']) }} pemilih memberikan suara</div>
                </div>
            </div>

            {{-- Perolehan Suara Kandidat --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-800 mb-6">Perolehan Suara Pasangan Calon</h2>

                <div class="space-y-6">
                    @foreach ($results['candidates'] as $cand)
                        @php
                            $percentage = $results['total'] > 0 ? round(($cand->ballots_count / $results['total']) * 100, 2) : 0;
                        @endphp
                        <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                                <div class="flex items-center gap-4">
                                    <span class="w-12 h-12 rounded-2xl bg-blue-600 text-white font-black text-lg flex items-center justify-center shrink-0 shadow-xs">
                                        {{ $cand->candidate_number }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-slate-900 text-lg">
                                            {{ $cand->chairman->name }} &amp; {{ $cand->viceChairman?->name ?? '—' }}
                                        </div>
                                        <div class="text-xs text-slate-500 mt-0.5">
                                            {{ $cand->chairman->study_program }} &bull; {{ $cand->viceChairman?->study_program ?? '—' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="text-left sm:text-right">
                                    <div class="text-3xl font-black text-slate-900">
                                        {{ number_format($cand->ballots_count) }} <span class="text-xs font-medium text-slate-500">suara</span>
                                    </div>
                                    <div class="text-sm font-bold text-blue-600">{{ $percentage }}%</div>
                                </div>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="w-full bg-slate-200 rounded-full h-4 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-4 rounded-full transition-all duration-700" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    <footer class="border-t border-slate-200 bg-white py-6 text-center text-xs text-slate-400">
        SIPMA &copy; {{ date('Y') }} HIMAKOM — Sistem Informasi Pemilihan Mahasiswa
    </footer>

</body>
</html>
