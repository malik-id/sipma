<!DOCTYPE html>
<html lang="id" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hasil Resmi Pemilihan — SIPMA Universitas Mega Buana</title>
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
                <a href="{{ route('public.candidates.index') }}" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-lg">badge</span>
                    <span>Kandidat</span>
                </a>
                <a href="{{ route('check-voter') }}" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-lg">how_to_vote</span>
                    <span>Cek DPT</span>
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
            <a href="{{ route('public.candidates.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                <span class="material-symbols-outlined text-xl text-amber-500">badge</span>
                <span>Daftar Pasangan Calon</span>
            </a>
            <a href="{{ route('check-voter') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                <span class="material-symbols-outlined text-xl text-amber-500">how_to_vote</span>
                <span>Cek Hak Pilih (DPT)</span>
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
                <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 tracking-tight">Hasil Resmi Perolehan Suara</h1>
                <p class="text-xs sm:text-sm text-zinc-500 mt-1">Perolehan suara resmi dari kotak suara digital SIPMA Universitas Mega Buana Palopo.</p>
            </div>

            @if ($publishedElections->count() > 1)
                <div>
                    <form method="GET" action="{{ route('public.results.index') }}">
                        <select name="election_id" class="text-xs border-zinc-300 rounded-xl focus:ring-amber-500 focus:border-amber-500 bg-white font-medium shadow-sm py-2 px-3" onchange="this.form.submit()">
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
            <div class="bg-white rounded-2xl border border-zinc-200 p-12 text-center shadow-sm">
                <div class="w-14 h-14 rounded-full bg-zinc-100 text-zinc-400 flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-3xl">schedule</span>
                </div>
                <h3 class="font-bold text-zinc-800 text-base">Hasil Belum Dipublikasikan</h3>
                <p class="text-xs sm:text-sm text-zinc-500 mt-1 max-w-md mx-auto">
                    Hasil pemilihan akan dipublikasikan secara terbuka setelah waktu pemungutan suara ditutup dan verifikasi rekapitulasi selesai.
                </p>
            </div>
        @else
            @php
                $sortedCandidates = $results['candidates']->sortByDesc('ballots_count');
                $winner = $sortedCandidates->first();
                $isTie = $sortedCandidates->count() > 1 && $sortedCandidates->values()->get(0)->ballots_count > 0 && $sortedCandidates->values()->get(0)->ballots_count === $sortedCandidates->values()->get(1)->ballots_count;
                $hasVotes = $results['total'] > 0;
            @endphp

            {{-- 1. Card Pasangan Calon Pemenang / Unggul --}}
            @if ($winner && $hasVotes)
                <div class="mb-8 rounded-3xl border-2 {{ $isTie ? 'border-amber-400 bg-amber-50/50' : 'border-amber-400 bg-white' }} shadow-md overflow-hidden relative">
                    <div class="bg-zinc-950 text-amber-400 px-6 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px] text-amber-400">
                                {{ $isTie ? 'balance' : 'emoji_events' }}
                            </span>
                            <span class="text-xs sm:text-sm font-extrabold uppercase tracking-wider">
                                {{ $isTie ? 'Hasil Seri (Perolehan Suara Sama)' : 'Pasangan Calon Pemenang / Perolehan Suara Terbanyak' }}
                            </span>
                        </div>
                        <span class="px-3 py-0.5 rounded-full bg-amber-400 text-zinc-950 text-xs font-black">
                            {{ $results['turnout'] }}% Partisipasi
                        </span>
                    </div>

                    <div class="p-6 sm:p-8 flex flex-col md:flex-row items-center gap-6 sm:gap-8 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-white">
                        {{-- Foto Pemenang --}}
                        <div class="relative shrink-0">
                            <div class="w-32 h-44 sm:w-40 sm:h-52 rounded-2xl bg-zinc-100 border-2 border-amber-300 overflow-hidden shadow-sm flex items-center justify-center">
                                @if ($winner->photo_path)
                                    <img src="{{ asset('storage/' . $winner->photo_path) }}" alt="Foto Pasangan Unggul" class="w-full h-full object-cover" />
                                @else
                                    <div class="text-zinc-400 text-center p-3 text-xs">
                                        <span class="material-symbols-outlined text-4xl text-amber-500/70 mb-1">military_tech</span>
                                        <p class="font-bold text-zinc-600">Paslon {{ $winner->candidate_number }}</p>
                                    </div>
                                @endif
                            </div>
                            <span class="absolute -top-3 -left-3 w-10 h-10 rounded-2xl bg-zinc-950 text-amber-400 border-2 border-amber-400 font-black text-base flex items-center justify-center shadow-md">
                                {{ $winner->candidate_number }}
                            </span>
                        </div>

                        {{-- Identitas & Suara Pemenang --}}
                        <div class="flex-1 text-center md:text-left space-y-3">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                <span class="material-symbols-outlined text-[16px]">stars</span>
                                <span>Nomor Urut {{ $winner->candidate_number }}</span>
                            </div>

                            <h2 class="text-xl sm:text-3xl font-extrabold text-zinc-900 tracking-tight">
                                {{ $winner->chairman->name }} &amp; {{ $winner->viceChairman?->name ?? '—' }}
                            </h2>

                            <p class="text-xs sm:text-sm text-zinc-600">
                                <span class="font-semibold text-zinc-800">{{ $winner->chairman->study_program }}</span>
                                @if ($winner->viceChairman)
                                    &bull; <span class="font-semibold text-zinc-800">{{ $winner->viceChairman->study_program }}</span>
                                @endif
                            </p>

                            @if ($winner->vision)
                                <p class="text-xs text-zinc-500 line-clamp-2 max-w-xl italic bg-white/80 p-2.5 rounded-xl border border-amber-200/50">
                                    "{{ $winner->vision }}"
                                </p>
                            @endif
                        </div>

                        {{-- Perolehan Angka --}}
                        <div class="shrink-0 bg-zinc-950 text-white p-6 rounded-2xl text-center min-w-[200px] border border-zinc-800 shadow-sm">
                            <span class="text-[10px] uppercase font-bold text-amber-400 tracking-widest block mb-1">Perolehan Suara</span>
                            <div class="text-3xl sm:text-4xl font-black text-amber-400">
                                {{ number_format($winner->ballots_count) }}
                            </div>
                            <div class="text-xs text-zinc-400 mt-0.5">Surat Suara Masuk</div>
                            <div class="mt-3 pt-3 border-t border-zinc-800">
                                <div class="text-lg font-black text-white">
                                    {{ $results['total'] > 0 ? round(($winner->ballots_count / $results['total']) * 100, 2) : 0 }}%
                                </div>
                                <div class="text-[10px] text-zinc-400">Persentase Total Suara</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 2. Metrics Summary --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-2xl border border-zinc-200 p-6 shadow-sm">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 block mb-1">Total Suara Sah</span>
                    <div class="text-3xl font-extrabold text-zinc-900">{{ number_format($results['total']) }}</div>
                    <div class="text-xs text-zinc-400 mt-1">Surat suara anonim tercatat</div>
                </div>

                <div class="bg-white rounded-2xl border border-zinc-200 p-6 shadow-sm">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 block mb-1">Daftar Pemilih Tetap</span>
                    <div class="text-3xl font-extrabold text-zinc-900">{{ number_format($results['eligible']) }}</div>
                    <div class="text-xs text-zinc-400 mt-1">Mahasiswa berhak memilih</div>
                </div>

                <div class="bg-white rounded-2xl border border-zinc-200 p-6 shadow-sm">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700 block mb-1">Tingkat Partisipasi</span>
                    <div class="text-3xl font-extrabold text-amber-600">{{ $results['turnout'] }}%</div>
                    <div class="text-xs text-zinc-400 mt-1">{{ number_format($results['participated']) }} pemilih memberikan suara</div>
                </div>
            </div>

            {{-- 3. Grid: Rincian Garis Lurus (Kiri) & Grafik Visual (Kanan) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">

                {{-- Kolom Kiri: Rincian Garis Lurus (Progress Bar) --}}
                <div class="lg:col-span-7 bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6 pb-3 border-b border-zinc-100">
                        <h2 class="text-base sm:text-lg font-bold text-zinc-900">Rincian Perolehan Suara Pasangan Calon</h2>
                        <span class="text-xs text-zinc-400 font-mono">{{ $results['candidates']->count() }} Pasangan</span>
                    </div>

                    <div class="space-y-6">
                        @foreach ($results['candidates'] as $cand)
                            @php
                                $percentage = $results['total'] > 0 ? round(($cand->ballots_count / $results['total']) * 100, 2) : 0;
                                $isTop = $winner && $winner->id === $cand->id && $hasVotes;
                            @endphp
                            <div class="p-5 rounded-2xl border {{ $isTop ? 'border-amber-400 bg-amber-50/30' : 'border-zinc-200 bg-zinc-50/50' }} transition">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3">
                                    <div class="flex items-center gap-3.5">
                                        <span class="w-11 h-11 rounded-2xl {{ $isTop ? 'bg-amber-500 text-zinc-950 font-black' : 'bg-zinc-950 text-amber-400 font-bold' }} text-base flex items-center justify-center shrink-0 shadow-2xs">
                                            {{ $cand->candidate_number }}
                                        </span>
                                        <div>
                                            <div class="font-bold text-zinc-900 text-sm sm:text-base flex items-center gap-2">
                                                <span>{{ $cand->chairman->name }} &amp; {{ $cand->viceChairman?->name ?? '—' }}</span>
                                                @if ($isTop && ! $isTie)
                                                    <span class="material-symbols-outlined text-amber-500 text-[18px]">verified</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-zinc-500 mt-0.5">
                                                {{ $cand->chairman->study_program }} &bull; {{ $cand->viceChairman?->study_program ?? '—' }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-left sm:text-right shrink-0">
                                        <div class="text-xl sm:text-2xl font-black text-zinc-900">
                                            {{ number_format($cand->ballots_count) }} <span class="text-xs font-normal text-zinc-500">suara</span>
                                        </div>
                                        <div class="text-xs font-bold text-amber-600">{{ $percentage }}%</div>
                                    </div>
                                </div>

                                {{-- Progress Bar (Gold / Amber) --}}
                                <div class="w-full bg-zinc-200 rounded-full h-3.5 overflow-hidden">
                                    <div class="bg-amber-500 h-3.5 rounded-full transition-all duration-700" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kolom Kanan: Grafik Distribusi Suara (Chart.js) --}}
                <div class="lg:col-span-5 bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 sm:p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-zinc-100">
                            <h2 class="text-base sm:text-lg font-bold text-zinc-900 flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-500 text-[20px]">pie_chart</span>
                                Grafik Perolehan Suara
                            </h2>
                            <span class="text-xs font-bold text-zinc-500 bg-zinc-100 px-2.5 py-1 rounded-lg">Real-Time</span>
                        </div>

                        <div class="relative w-full max-w-[280px] sm:max-w-[320px] mx-auto my-6 aspect-square flex items-center justify-center">
                            <canvas id="resultsChart"></canvas>
                        </div>
                    </div>

                    {{-- Legend Summary --}}
                    <div class="pt-4 border-t border-zinc-100 space-y-2">
                        @php
                            $colors = ['#f59e0b', '#18181b', '#71717a', '#d97706', '#fbbf24'];
                        @endphp
                        @foreach ($results['candidates'] as $idx => $cand)
                            @php
                                $color = $colors[$idx % count($colors)];
                                $percentage = $results['total'] > 0 ? round(($cand->ballots_count / $results['total']) * 100, 2) : 0;
                            @endphp
                            <div class="flex items-center justify-between text-xs py-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-md shrink-0" style="background-color: {{ $color }}"></span>
                                    <span class="font-bold text-zinc-800">No. {{ $cand->candidate_number }} — {{ $cand->chairman->name }}</span>
                                </div>
                                <span class="font-mono font-bold text-zinc-600">{{ $percentage }}% ({{ number_format($cand->ballots_count) }})</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @endif
    </main>

    {{-- Chart.js Script --}}
    @if ($activeElection && $results && $results['candidates']->isNotEmpty())
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('resultsChart');
                if (!ctx) return;

                const candidateLabels = @json($results['candidates']->map(fn($c) => 'No. ' . $c->candidate_number . ' - ' . $c->chairman->name));
                const candidateVotes = @json($results['candidates']->pluck('ballots_count'));
                const colors = ['#f59e0b', '#18181b', '#71717a', '#d97706', '#fbbf24'];

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: candidateLabels,
                        datasets: [{
                            data: candidateVotes,
                            backgroundColor: colors.slice(0, candidateLabels.length),
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const total = candidateVotes.reduce((a, b) => a + b, 0);
                                        const val = context.parsed || 0;
                                        const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                        return ` ${val.toLocaleString('id-ID')} suara (${pct}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '62%'
                    }
                });
            });
        </script>
    @endif

    <footer class="border-t border-zinc-200 bg-white py-6 text-center text-xs text-zinc-400">
        SIPMA &copy; {{ date('Y') }} Universitas Mega Buana Palopo. Seluruh hak cipta dilindungi.
    </footer>

</body>
</html>
