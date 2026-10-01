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
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 mb-6">
            <h2 class="text-base font-bold text-zinc-900 mb-6">Perolehan Suara Pasangan Calon</h2>

            <div class="space-y-6">
                @foreach ($results['candidates'] as $cand)
                    @php
                        $percentage = $results['total'] > 0 ? round(($cand->ballots_count / $results['total']) * 100, 2) : 0;
                    @endphp
                    <div class="p-5 rounded-xl border border-zinc-200 bg-zinc-50/50">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3">
                            <div class="flex items-center gap-4">
                                <span class="w-10 h-10 rounded-full bg-zinc-950 text-amber-400 font-extrabold text-base flex items-center justify-center shrink-0">
                                    {{ $cand->candidate_number }}
                                </span>
                                <div>
                                    <div class="font-bold text-zinc-900 text-base">
                                        {{ $cand->chairman->name }} &amp; {{ $cand->viceChairman?->name ?? '—' }}
                                    </div>
                                    <div class="text-xs text-zinc-500 mt-0.5">
                                        {{ $cand->chairman->study_program }} &bull; {{ $cand->viceChairman?->study_program ?? '—' }}
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-2xl font-black text-zinc-900">{{ number_format($cand->ballots_count) }} <span class="text-xs font-medium text-zinc-500">suara</span></div>
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

    @else
        <div class="bg-white rounded-2xl border border-zinc-200 p-8 text-center text-zinc-400">
            Pilih periode pemilihan di atas untuk menghitung hasil suara.
        </div>
    @endif

</x-layouts.admin>
