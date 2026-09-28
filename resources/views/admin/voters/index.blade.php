<x-layouts.admin title="Daftar Pemilih Tetap (DPT)" header="Manajemen Pemilih (Voter)">

    {{-- Stats Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Terdaftar DPT</div>
                <div class="text-2xl font-bold text-slate-900">{{ number_format($stats['total']) }}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Berhak Memilih (Eligible)</div>
                <div class="text-2xl font-bold text-emerald-600">{{ number_format($stats['eligible']) }}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tidak Berhak (Not Eligible)</div>
                <div class="text-2xl font-bold text-rose-600">{{ number_format($stats['not_eligible']) }}</div>
            </div>
        </div>
    </div>

    {{-- Filter & Actions --}}
    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('admin.voters.index') }}" class="flex flex-wrap items-center gap-3">
            {{-- Pilih Periode Pemilihan --}}
            <select
                name="election_id"
                onchange="this.form.submit()"
                class="px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm font-medium">
                @forelse ($elections as $el)
                    <option value="{{ $el->id }}" {{ $selectedElectionId == $el->id ? 'selected' : '' }}>
                        {{ $el->name }} ({{ ucfirst($el->status->value ?? $el->status) }})
                    </option>
                @empty
                    <option value="">Belum ada pemilihan</option>
                @endforelse
            </select>

            <div class="relative">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari pemilih..."
                    class="pl-8 pr-3 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
                />
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <button type="submit" class="px-3 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700">
                Filter
            </button>
        </form>

        {{-- Daftarkan Mahasiswa ke DPT Otomatis --}}
        @if ($selectedElectionId)
            <form method="POST" action="{{ route('admin.voters.generate') }}" onsubmit="return confirm('Daftarkan seluruh mahasiswa aktif ke DPT pemilihan ini?')">
                @csrf
                <input type="hidden" name="election_id" value="{{ $selectedElectionId }}" />
                <input type="hidden" name="student_status" value="active" />
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Daftarkan Semua Mahasiswa Aktif
                </button>
            </form>
        @endif
    </div>

    {{-- Tabel DPT --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">NIM</th>
                        <th class="px-6 py-3.5">Nama Pemilih</th>
                        <th class="px-6 py-3.5">Program Studi</th>
                        <th class="px-6 py-3.5 text-center">Status Pemilih</th>
                        <th class="px-6 py-3.5">Verifikasi</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($voters as $voter)
                        <tr class="hover:bg-slate-50/75 transition">
                            <td class="px-6 py-4 font-mono font-medium text-slate-900">{{ $voter->student->nim }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $voter->student->name }}</div>
                                <div class="text-xs text-slate-400">{{ $voter->student->email }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $voter->student->study_program }}</td>
                            <td class="px-6 py-4 text-center">
                                @if ($voter->voter_status->value === 'eligible')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Eligible (Berhak)
                                    </span>
                                @elseif ($voter->voter_status->value === 'suspended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                        Ditangguhkan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        Tidak Berhak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                <div>{{ $voter->verified_at ? $voter->verified_at->translatedFormat('d M Y H:i') : '-' }}</div>
                                <div class="text-slate-400 truncate max-w-xs">{{ $voter->notes ?: 'Tanpa catatan' }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    {{-- Quick Toggle Status --}}
                                    <form method="POST" action="{{ route('admin.voters.update-status', $voter) }}">
                                        @csrf
                                        @method('PATCH')
                                        @if ($voter->voter_status->value === 'eligible')
                                            <input type="hidden" name="voter_status" value="not_eligible" />
                                            <input type="hidden" name="notes" value="Dinonaktifkan oleh admin" />
                                            <button type="submit" class="px-2.5 py-1 text-xs text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition" title="Jadikan Tidak Berhak">
                                                Nonaktifkan
                                            </button>
                                        @else
                                            <input type="hidden" name="voter_status" value="eligible" />
                                            <input type="hidden" name="notes" value="Diverifikasi aktif oleh admin" />
                                            <button type="submit" class="px-2.5 py-1 text-xs text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition" title="Jadikan Berhak">
                                                Aktifkan
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Hapus dari DPT --}}
                                    <form method="POST" action="{{ route('admin.voters.destroy', $voter) }}" onsubmit="return confirm('Hapus pemilih ini dari DPT?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data pemilih pada pemilihan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($voters->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $voters->links() }}
            </div>
        @endif
    </div>

</x-layouts.admin>
