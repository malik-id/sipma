<x-layouts.admin title="Verifikasi Pendaftaran Bakal Calon">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Verifikasi Bakal Calon</h1>
            <p class="text-xs sm:text-sm text-zinc-500 mt-1">Periksa kelengkapan berkas, validasi dokumen, dan tetapkan status verifikasi.</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl border border-zinc-200 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('admin.registrations.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-zinc-600 uppercase tracking-wider mb-1">Periode Pemilihan</label>
                <select name="election_id" class="w-full text-xs font-bold border-zinc-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-zinc-50 py-2 px-3" onchange="this.form.submit()">
                    <option value="">Semua Pemilihan</option>
                    @foreach ($elections as $el)
                        <option value="{{ $el->id }}" {{ request('election_id') == $el->id ? 'selected' : '' }}>
                            {{ $el->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-zinc-600 uppercase tracking-wider mb-1">Status Pendaftaran</label>
                <select name="status" class="w-full text-xs font-bold border-zinc-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-zinc-50 py-2 px-3" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    @foreach (\App\Enums\RegistrationStatus::cases() as $st)
                        <option value="{{ $st->value }}" {{ request('status') === $st->value ? 'selected' : '' }}>
                            {{ $st->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-zinc-600 uppercase tracking-wider mb-1">Cari Pasangan / NIM / No. Reg</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..."
                    class="w-full text-xs border-zinc-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-zinc-50 py-2 px-3" />
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-zinc-950 hover:bg-zinc-800 text-amber-400 font-bold text-xs rounded-lg transition shadow-sm">
                    Filter
                </button>
                @if (request()->hasAny(['election_id', 'status', 'search']))
                    <a href="{{ route('admin.registrations.index') }}" class="px-3 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-bold text-xs rounded-lg transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Pendaftaran --}}
    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-zinc-600">
                <thead class="bg-zinc-50 text-xs uppercase font-bold text-zinc-500 border-b border-zinc-200">
                    <tr>
                        <th class="px-6 py-4">No. Pendaftaran</th>
                        <th class="px-6 py-4">Pasangan Bakal Calon</th>
                        <th class="px-6 py-4">Pemilihan</th>
                        <th class="px-6 py-4">Diajukan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse ($registrations as $reg)
                        <tr class="hover:bg-zinc-50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-zinc-900 text-xs">
                                {{ $reg->registration_number ?? 'DRAFT' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-zinc-900">
                                    {{ $reg->chairman->name }} &amp; {{ $reg->viceChairman?->name ?? '—' }}
                                </div>
                                <div class="text-xs text-zinc-400 mt-0.5">
                                    {{ $reg->chairman->nim }} / {{ $reg->viceChairman?->nim ?? '—' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-zinc-600 font-medium">
                                {{ $reg->election->name }}
                            </td>
                            <td class="px-6 py-4 text-xs text-zinc-500">
                                {{ $reg->submitted_at ? $reg->submitted_at->translatedFormat('d M Y, H:i') : '—' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badge = match($reg->status->value) {
                                        'draft' => 'bg-zinc-100 text-zinc-700',
                                        'submitted', 'resubmitted' => 'bg-amber-50 text-amber-900 border border-amber-200',
                                        'under_review' => 'bg-amber-100 text-amber-900 border border-amber-300',
                                        'revision_required' => 'bg-orange-50 text-orange-800 border border-orange-200',
                                        'verified' => 'bg-emerald-50 text-emerald-800 border border-emerald-200',
                                        'established' => 'bg-zinc-900 text-amber-400',
                                        'rejected' => 'bg-rose-50 text-rose-800 border border-rose-200',
                                        default => 'bg-zinc-100 text-zinc-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badge }}">
                                    {{ $reg->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.registrations.show', $reg) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-zinc-950 text-amber-400 hover:bg-zinc-800 rounded-lg text-xs font-bold transition shadow-2xs">
                                        Periksa Berkas →
                                    </a>

                                    <form method="POST" action="{{ route('admin.registrations.destroy', $reg) }}"
                                        data-confirm="Hapus pendaftaran bakal calon '{{ $reg->registration_number }}' ({{ $reg->chairman->name }})? Seluruh berkas dan riwayat terkait akan dihapus.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-zinc-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Hapus Pendaftaran">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-zinc-400">
                                Tidak ada pendaftaran yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($registrations->hasPages())
            <div class="px-6 py-4 border-t border-zinc-100">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>

</x-layouts.admin>
