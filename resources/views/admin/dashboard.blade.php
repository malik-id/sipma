<x-layouts.admin title="Dashboard" header="Ringkasan Sistem">

    {{-- Welcome Card (Clean Minimalist with Gold Accent) --}}
    <div class="bg-white rounded-xl border border-zinc-200 border-l-4 border-l-amber-500 p-6 sm:p-7 shadow-2xs mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 uppercase tracking-wider">
                        Panel Kontrol Panitia
                    </span>
                    <span class="text-xs text-zinc-400">&bull;</span>
                    <span class="text-xs text-zinc-500 font-medium">Universitas Mega Buana Palopo</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="mt-1 text-xs sm:text-sm text-zinc-500 max-w-2xl leading-relaxed">
                    Pusat manajemen terpadu pemilihan umum mahasiswa. Kelola master data, DPT, pendaftaran calon, dan pantau rekapitulasi suara.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('admin.elections.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-zinc-950 bg-amber-500 hover:bg-amber-400 rounded-lg shadow-xs transition">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Periode Pemilihan
                </a>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        {{-- Total Mahasiswa --}}
        <div class="bg-white rounded-xl p-5 border border-zinc-200 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Total Mahasiswa</div>
                <div class="text-2xl sm:text-3xl font-black text-zinc-900 mt-1">{{ number_format($totalStudents) }}</div>
                <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 mt-2 inline-flex items-center gap-1">
                    <span>Kelola Master Data</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-zinc-100 text-zinc-800 flex items-center justify-center shrink-0 border border-zinc-200">
                <span class="material-symbols-outlined text-[24px]">group</span>
            </div>
        </div>

        {{-- Pemilih DPT --}}
        <div class="bg-white rounded-xl p-5 border border-zinc-200 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Pemilih Terdaftar (DPT)</div>
                <div class="text-2xl sm:text-3xl font-black text-zinc-900 mt-1">{{ number_format($totalVoters) }}</div>
                <a href="{{ route('admin.voters.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 mt-2 inline-flex items-center gap-1">
                    <span>Lihat Daftar Pemilih</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center shrink-0 border border-amber-200">
                <span class="material-symbols-outlined text-[24px]">how_to_vote</span>
            </div>
        </div>

        {{-- Periode Pemilihan --}}
        <div class="bg-white rounded-xl p-5 border border-zinc-200 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Periode Pemilihan</div>
                <div class="text-2xl sm:text-3xl font-black text-zinc-900 mt-1">{{ number_format($totalElections) }}</div>
                <a href="{{ route('admin.elections.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 mt-2 inline-flex items-center gap-1">
                    <span>Atur Periode</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-zinc-100 text-zinc-800 flex items-center justify-center shrink-0 border border-zinc-200">
                <span class="material-symbols-outlined text-[24px]">event_available</span>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl p-6 border border-zinc-200 shadow-2xs mb-6">
        <h3 class="text-xs font-bold text-zinc-500 uppercase tracking-widest mb-4">Aksi Cepat Menu Utama</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('admin.students.create') }}" class="p-4 rounded-xl border border-zinc-200 hover:border-amber-400 hover:bg-amber-50/20 transition group flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-zinc-100 text-zinc-700 flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-zinc-950 transition">
                    <span class="material-symbols-outlined text-[20px]">person_add</span>
                </div>
                <div>
                    <div class="text-xs font-bold text-zinc-900 group-hover:text-amber-600 transition">Tambah / Import Mahasiswa</div>
                    <p class="text-[11px] text-zinc-500 mt-0.5">Input berkas Excel atau tambah manual</p>
                </div>
            </a>

            <a href="{{ route('admin.voters.index') }}" class="p-4 rounded-xl border border-zinc-200 hover:border-amber-400 hover:bg-amber-50/20 transition group flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-zinc-100 text-zinc-700 flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-zinc-950 transition">
                    <span class="material-symbols-outlined text-[20px]">sync</span>
                </div>
                <div>
                    <div class="text-xs font-bold text-zinc-900 group-hover:text-amber-600 transition">Generate &amp; Import DPT</div>
                    <p class="text-[11px] text-zinc-500 mt-0.5">Sinkronkan data pemilih pemilihan</p>
                </div>
            </a>

            <a href="{{ route('admin.voting-monitor') }}" class="p-4 rounded-xl border border-zinc-200 hover:border-amber-400 hover:bg-amber-50/20 transition group flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-zinc-100 text-zinc-700 flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-zinc-950 transition">
                    <span class="material-symbols-outlined text-[20px]">monitoring</span>
                </div>
                <div>
                    <div class="text-xs font-bold text-zinc-900 group-hover:text-amber-600 transition">Monitor Partisipasi Suara</div>
                    <p class="text-[11px] text-zinc-500 mt-0.5">Pantau suara masuk secara real-time</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Daftar Periode Pemilihan Terkini --}}
    @if ($activeElections->isNotEmpty())
        <div class="bg-white rounded-xl border border-zinc-200 shadow-2xs p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold text-zinc-500 uppercase tracking-widest">Periode Pemilihan Aktif</h3>
                <a href="{{ route('admin.elections.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">Lihat Semua &rarr;</a>
            </div>

            <div class="divide-y divide-zinc-100">
                @foreach ($activeElections as $el)
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <span class="text-sm font-bold text-zinc-900 block">{{ $el->name }}</span>
                            <span class="text-xs text-zinc-500">
                                Voting: {{ $el->voting_start->translatedFormat('d M Y H:i') }} &mdash; {{ $el->voting_end->translatedFormat('d M Y H:i') }} WITA
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                {{ $el->status->value === 'voting' ? 'bg-amber-100 text-amber-900 border border-amber-300' : '' }}
                                {{ $el->status->value === 'published' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ in_array($el->status->value, ['draft', 'registration']) ? 'bg-zinc-100 text-zinc-700' : '' }}
                                {{ $el->status->value === 'closed' ? 'bg-zinc-200 text-zinc-800' : '' }}
                            ">
                                {{ strtoupper($el->status->value) }}
                            </span>
                            <a href="{{ route('admin.elections.show', $el) }}" class="px-3 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 text-xs font-bold rounded-lg transition">
                                Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</x-layouts.admin>
