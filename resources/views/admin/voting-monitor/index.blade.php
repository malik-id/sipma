<x-layouts.admin title="Monitoring Voting Real-Time">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Monitoring Partisipasi Pemilih</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau tingkat partisipasi pemilih secara langsung. Kerahasiaan pilihan tetap terjamin 100%.</p>
        </div>

        {{-- Election Selector --}}
        <div>
            <form method="GET" action="{{ route('admin.voting-monitor') }}">
                <select name="election_id" class="text-sm border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm" onchange="this.form.submit()">
                    @foreach ($elections as $el)
                        <option value="{{ $el->id }}" {{ $activeElection && $activeElection->id === $el->id ? 'selected' : '' }}>
                            {{ $el->name }} ({{ ucfirst($el->status->value) }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @if ($activeElection)

        {{-- Metrics Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total DPT</span>
                <div class="text-2xl font-black text-slate-800">{{ number_format($stats['total_voters']) }}</div>
                <div class="text-xs text-slate-400 mt-1">Mahasiswa terdaftar</div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 block mb-1">Sudah Memilih</span>
                <div class="text-2xl font-black text-emerald-600">{{ number_format($stats['participated']) }}</div>
                <div class="text-xs text-slate-400 mt-1">Suara telah masuk</div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-600 block mb-1">Belum Memilih</span>
                <div class="text-2xl font-black text-amber-600">{{ number_format($stats['not_participated']) }}</div>
                <div class="text-xs text-slate-400 mt-1">Hak pilih belum dipakai</div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block mb-1">Tingkat Partisipasi</span>
                <div class="text-2xl font-black text-blue-600">{{ $stats['turnout_percentage'] }}%</div>
                <div class="w-full bg-slate-100 rounded-full h-2 mt-2 overflow-hidden">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $stats['turnout_percentage']) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white rounded-xl border border-slate-200 p-4 mb-6 shadow-xs">
            <form method="GET" action="{{ route('admin.voting-monitor') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <input type="hidden" name="election_id" value="{{ $activeElection->id }}" />

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status Keikutsertaan</label>
                    <select name="voted_status" class="w-full text-xs border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="voted" {{ request('voted_status') === 'voted' ? 'selected' : '' }}>Sudah Memilih</option>
                        <option value="not_voted" {{ request('voted_status') === 'not_voted' ? 'selected' : '' }}>Belum Memilih</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Program Studi</label>
                    <select name="study_program" class="w-full text-xs border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50" onchange="this.form.submit()">
                        <option value="">Semua Program Studi</option>
                        <option value="Informatika" {{ request('study_program') === 'Informatika' ? 'selected' : '' }}>Informatika</option>
                        <option value="Sistem Informasi" {{ request('study_program') === 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cari Pemilih</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM..."
                        class="w-full text-xs border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50" />
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs rounded-lg transition shadow-xs">
                        Filter
                    </button>
                    @if (request()->hasAny(['voted_status', 'study_program', 'search']))
                        <a href="{{ route('admin.voting-monitor', ['election_id' => $activeElection->id]) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium text-xs rounded-lg transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Pemilih (Anonymity Guaranteed) --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Daftar Kehadiran Pemilih</h3>
                <span class="text-xs text-slate-400">🔒 Pilihan surat suara dirahasiakan sepenuhnya oleh sistem.</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3">NIM</th>
                            <th class="px-6 py-3">Nama Pemilih</th>
                            <th class="px-6 py-3">Program Studi</th>
                            <th class="px-6 py-3">Status Hak Pilih</th>
                            <th class="px-6 py-3">Status Partisipasi</th>
                            <th class="px-6 py-3">Waktu Memilih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($voterRecords as $vrec)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-3 font-mono font-medium text-slate-800 text-xs">
                                    {{ $vrec->student->nim }}
                                </td>
                                <td class="px-6 py-3 font-semibold text-slate-800">
                                    {{ $vrec->student->name }}
                                </td>
                                <td class="px-6 py-3 text-xs text-slate-500">
                                    {{ $vrec->student->study_program }}
                                </td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $vrec->voter_status->value === 'eligible' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $vrec->voter_status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    @if ($vrec->participation)
                                        <span class="inline-flex items-center gap-1 text-emerald-700 font-bold text-xs">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            Sudah Memilih
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-slate-400 text-xs">
                                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                            Belum Memilih
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-xs text-slate-400 font-mono">
                                    {{ $vrec->participation ? $vrec->participation->voted_at->translatedFormat('H:i:s, d M') : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data pemilih yang sesuai kriteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($voterRecords->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $voterRecords->links() }}
                </div>
            @endif
        </div>

    @else
        <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center text-slate-400">
            Pilih periode pemilihan di atas untuk memantau pemungutan suara.
        </div>
    @endif

</x-layouts.admin>
