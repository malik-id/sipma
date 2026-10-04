<x-layouts.admin title="Perhitungan & Publikasi Hasil Pemilihan">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Hasil Pemilihan</h1>
            <p class="text-xs sm:text-sm text-zinc-500 mt-1">Perhitungan suara dari surat suara anonim (ballots) dan manajemen publikasi hasil.</p>
        </div>

        {{-- Election Selector & Publish Button --}}
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.results.index') }}">
                <select name="election_id" class="text-xs border-zinc-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-bold py-2 px-3" onchange="this.form.submit()">
                    @foreach ($elections as $el)
                        <option value="{{ $el->id }}" {{ $activeElection && $activeElection->id === $el->id ? 'selected' : '' }}>
                            {{ $el->name }} ({{ ucfirst($el->status->value) }})
                        </option>
                    @endforeach
                </select>
            </form>

            @if ($activeElection && $activeElection->status->value !== 'published')
                <form method="POST" action="{{ route('admin.results.publish', $activeElection) }}"
                    onsubmit="return confirm('Apakah Anda yakin ingin mempublikasikan hasil pemilihan ini ke publik sekarang?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-zinc-950 font-bold text-xs rounded-lg shadow-sm transition">
                        Publikasikan Hasil →
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if ($activeElection && $results)

        {{-- Status Banner --}}
        @if ($activeElection->status->value === 'published')
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-xs sm:text-sm font-bold text-emerald-900">Hasil Pemilihan Sudah Dipublikasikan ke Publik</span>
                </div>
                <a href="{{ route('public.results.index', ['election_id' => $activeElection->id]) }}" target="_blank"
                   class="text-xs font-bold text-emerald-800 hover:underline">
                    Lihat Halaman Publik →
                </a>
            </div>
        @else
            <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 p-4 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <span class="text-xs sm:text-sm font-bold text-amber-900">Hasil Pemilihan Belum Dipublikasikan (Hanya Panitia)</span>
                </div>
            </div>
        @endif

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-zinc-200 border-l-4 border-l-zinc-900 p-5 shadow-xs">
                <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 block mb-1">Total Suara Masuk</span>
                <div class="text-2xl font-black text-zinc-900">{{ number_format($results['total']) }}</div>
                <div class="text-xs text-zinc-400 mt-1">Surat suara sah</div>
            </div>

            <div class="bg-white rounded-xl border border-zinc-200 border-l-4 border-l-zinc-900 p-5 shadow-xs">
                <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 block mb-1">Pemilih Terdaftar</span>
                <div class="text-2xl font-black text-zinc-900">{{ number_format($results['eligible']) }}</div>
                <div class="text-xs text-zinc-400 mt-1">DPT eligible</div>
            </div>

            <div class="bg-white rounded-xl border border-zinc-200 border-l-4 border-l-amber-500 p-5 shadow-xs">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800 block mb-1">Tingkat Partisipasi</span>
                <div class="text-2xl font-black text-amber-600">{{ $results['turnout'] }}%</div>
                <div class="text-xs text-zinc-400 mt-1">{{ number_format($results['participated']) }} pemilih berpartisipasi</div>
            </div>

            <div class="bg-white rounded-xl border border-zinc-200 border-l-4 border-l-emerald-500 p-5 shadow-xs">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-800 block mb-1">Integritas Suara</span>
                <div class="text-xs font-bold text-emerald-800 bg-emerald-50 px-2 py-1 rounded-md inline-block mt-1">
                    ✓ HMAC SHA-256 Valid
                </div>
                <div class="text-[10px] text-zinc-400 mt-2">Dihitung dari tabel <code>ballots</code></div>
            </div>
        </div>

        {{-- Hasil Perolehan Suara Calon --}}
        @php
            $sortedCandidates = $results['candidates']->sortByDesc('ballots_count');
            $winner = $sortedCandidates->first();
            $isTie = $sortedCandidates->count() > 1 && $sortedCandidates->values()->get(0)->ballots_count > 0 && $sortedCandidates->values()->get(0)->ballots_count === $sortedCandidates->values()->get(1)->ballots_count;
            $hasVotes = $results['total'] > 0;
        @endphp

        {{-- 1. Card Pasangan Calon Pemenang / Unggul --}}
        @if ($winner && $hasVotes)
            <div class="mb-6 rounded-2xl border-2 {{ $isTie ? 'border-amber-400 bg-amber-50/50' : 'border-amber-400 bg-white' }} shadow-md overflow-hidden relative">
                <div class="bg-zinc-950 text-amber-400 px-6 py-2.5 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">
                            {{ $isTie ? 'balance' : 'emoji_events' }}
                        </span>
                        <span class="text-xs font-extrabold uppercase tracking-wider">
                            {{ $isTie ? 'Hasil Seri (Perolehan Suara Sama)' : 'Pasangan Calon Pemenang / Perolehan Suara Tertinggi' }}
                        </span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-400 text-zinc-950 text-[11px] font-black">
                        {{ $results['turnout'] }}% Partisipasi
                    </span>
                </div>

                <div class="p-6 flex flex-col md:flex-row items-center gap-6 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-white">
                    {{-- Foto Pemenang --}}
                    <div class="relative shrink-0">
                        <div class="w-28 h-36 sm:w-32 sm:h-40 rounded-xl bg-zinc-100 border-2 border-amber-300 overflow-hidden shadow-xs flex items-center justify-center">
                            @if ($winner->photo_path)
                                <img src="{{ asset('storage/' . $winner->photo_path) }}" alt="Foto Pasangan Unggul" class="w-full h-full object-cover" />
                            @else
                                <div class="text-zinc-400 text-center p-2 text-xs">
                                    <span class="material-symbols-outlined text-3xl text-amber-500/70 mb-1">military_tech</span>
                                    <p class="font-bold text-zinc-600">Paslon {{ $winner->candidate_number }}</p>
                                </div>
                            @endif
                        </div>
                        <span class="absolute -top-2.5 -left-2.5 w-8 h-8 rounded-xl bg-zinc-950 text-amber-400 border-2 border-amber-400 font-black text-xs flex items-center justify-center shadow-xs">
                            {{ $winner->candidate_number }}
                        </span>
                    </div>

                    {{-- Identitas Pemenang --}}
                    <div class="flex-1 text-center md:text-left space-y-2">
                        <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                            <span class="material-symbols-outlined text-[14px]">stars</span>
                            <span>Nomor Urut {{ $winner->candidate_number }}</span>
                        </div>

                        <h2 class="text-lg sm:text-2xl font-extrabold text-zinc-900 tracking-tight">
                            {{ $winner->chairman->name }} &amp; {{ $winner->viceChairman?->name ?? '—' }}
                        </h2>

                        <p class="text-xs text-zinc-600">
                            <span class="font-semibold text-zinc-800">{{ $winner->chairman->study_program }}</span>
                            @if ($winner->viceChairman)
                                &bull; <span class="font-semibold text-zinc-800">{{ $winner->viceChairman->study_program }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- Perolehan Angka --}}
                    <div class="shrink-0 bg-zinc-950 text-white p-5 rounded-xl text-center min-w-[170px] border border-zinc-800 shadow-xs">
                        <span class="text-[10px] uppercase font-bold text-amber-400 tracking-widest block mb-0.5">Perolehan Suara</span>
                        <div class="text-2xl sm:text-3xl font-black text-amber-400">
                            {{ number_format($winner->ballots_count) }}
                        </div>
                        <div class="text-[11px] text-zinc-400 mt-0.5">Surat Suara Masuk</div>
                        <div class="mt-2 pt-2 border-t border-zinc-800">
                            <div class="text-base font-black text-white">
                                {{ $results['total'] > 0 ? round(($winner->ballots_count / $results['total']) * 100, 2) : 0 }}%
                            </div>
                            <div class="text-[9px] text-zinc-400">Persentase Total</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 2. Grid: Progress Bar & Grafik Distribusi --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">

            {{-- Kolom Kiri: Rincian Garis Lurus (Progress Bar) --}}
            <div class="lg:col-span-7 bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-zinc-100">
                    <h2 class="text-base font-bold text-zinc-900">Perolehan Suara Pasangan Calon</h2>
                    <span class="text-xs text-zinc-400 font-mono">{{ $results['candidates']->count() }} Pasangan</span>
                </div>

                <div class="space-y-4">
                    @foreach ($results['candidates'] as $cand)
                        @php
                            $percentage = $results['total'] > 0 ? round(($cand->ballots_count / $results['total']) * 100, 2) : 0;
                            $isTop = $winner && $winner->id === $cand->id && $hasVotes;
                        @endphp
                        <div class="p-4 rounded-xl border {{ $isTop ? 'border-amber-400 bg-amber-50/20' : 'border-zinc-200 bg-zinc-50/50' }}">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2.5">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-xl {{ $isTop ? 'bg-amber-500 text-zinc-950 font-black' : 'bg-zinc-950 text-amber-400 font-bold' }} text-sm flex items-center justify-center shrink-0">
                                        {{ $cand->candidate_number }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-zinc-900 text-sm flex items-center gap-1.5">
                                            <span>{{ $cand->chairman->name }} &amp; {{ $cand->viceChairman?->name ?? '—' }}</span>
                                            @if ($isTop && ! $isTie)
                                                <span class="material-symbols-outlined text-amber-500 text-[16px]">verified</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-zinc-500">
                                            {{ $cand->chairman->study_program }} &bull; {{ $cand->viceChairman?->study_program ?? '—' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <div class="text-xl font-black text-zinc-900">{{ number_format($cand->ballots_count) }} <span class="text-xs font-medium text-zinc-500">suara</span></div>
                                    <div class="text-xs font-bold text-amber-600">{{ $percentage }}%</div>
                                </div>
                            </div>

                            {{-- Progress Bar (Gold / Amber) --}}
                            <div class="w-full bg-zinc-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-amber-500 h-3 rounded-full transition-all duration-700" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Kolom Kanan: Grafik Distribusi Suara (Chart.js) --}}
            <div class="lg:col-span-5 bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-zinc-100">
                        <h2 class="text-base font-bold text-zinc-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-500 text-[18px]">pie_chart</span>
                            Grafik Perolehan Suara
                        </h2>
                        <span class="text-[11px] font-bold text-zinc-500 bg-zinc-100 px-2 py-0.5 rounded">Chart</span>
                    </div>

                    <div class="relative w-full max-w-[240px] sm:max-w-[260px] mx-auto my-4 aspect-square flex items-center justify-center">
                        <canvas id="adminResultsChart"></canvas>
                    </div>
                </div>

                {{-- Legend & Export --}}
                <div class="pt-3 border-t border-zinc-100 space-y-3">
                    @php
                        $colors = ['#f59e0b', '#18181b', '#71717a', '#d97706', '#fbbf24'];
                    @endphp
                    <div class="space-y-1.5">
                        @foreach ($results['candidates'] as $idx => $cand)
                            @php
                                $color = $colors[$idx % count($colors)];
                                $percentage = $results['total'] > 0 ? round(($cand->ballots_count / $results['total']) * 100, 2) : 0;
                            @endphp
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-sm shrink-0" style="background-color: {{ $color }}"></span>
                                    <span class="font-bold text-zinc-800">No. {{ $cand->candidate_number }} — {{ $cand->chairman->name }}</span>
                                </div>
                                <span class="font-mono font-bold text-zinc-600">{{ $percentage }}% ({{ number_format($cand->ballots_count) }})</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-2 border-t border-zinc-100 flex items-center gap-2">
                        <a href="{{ route('admin.results.export-excel', $activeElection) }}"
                           class="flex-1 py-1.5 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition text-center flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">table_view</span>
                            Export Excel
                        </a>
                        <a href="{{ route('admin.results.export-csv', $activeElection) }}"
                           class="flex-1 py-1.5 px-3 bg-zinc-950 hover:bg-zinc-800 text-amber-400 font-bold text-xs rounded-lg transition text-center flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                            Export CSV
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- Chart.js Script --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('adminResultsChart');
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
                        cutout: '60%'
                    }
                });
            });
        </script>

    @else
        <div class="bg-white rounded-2xl border border-zinc-200 p-8 text-center text-zinc-400">
            Pilih periode pemilihan di atas untuk menghitung hasil suara.
        </div>
    @endif

</x-layouts.admin>
