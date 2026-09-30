<x-layouts.admin title="Verifikasi Pendaftaran Bakal Calon">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Verifikasi Bakal Calon</h1>
            <p class="text-sm text-slate-500 mt-1">Periksa kelengkapan berkas, validasi dokumen, dan tetapkan status verifikasi.</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl border border-slate-200 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('admin.registrations.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Periode Pemilihan</label>
                <select name="election_id" class="w-full text-sm border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50" onchange="this.form.submit()">
                    <option value="">Semua Pemilihan</option>
                    @foreach ($elections as $el)
                        <option value="{{ $el->id }}" {{ request('election_id') == $el->id ? 'selected' : '' }}>
                            {{ $el->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status Pendaftaran</label>
                <select name="status" class="w-full text-sm border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    @foreach (\App\Enums\RegistrationStatus::cases() as $st)
                        <option value="{{ $st->value }}" {{ request('status') === $st->value ? 'selected' : '' }}>
                            {{ $st->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cari Pasangan / NIM / No. Reg</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..."
                    class="w-full text-sm border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50" />
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition shadow-sm">
                    Filter
                </button>
                @if (request()->hasAny(['election_id', 'status', 'search']))
                    <a href="{{ route('admin.registrations.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium text-sm rounded-lg transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Pendaftaran --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No. Pendaftaran</th>
                        <th class="px-6 py-4">Pasangan Bakal Calon</th>
                        <th class="px-6 py-4">Pemilihan</th>
                        <th class="px-6 py-4">Diajukan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($registrations as $reg)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-mono font-medium text-slate-800 text-xs">
                                {{ $reg->registration_number ?? 'DRAFT' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">
                                    {{ $reg->chairman->name }} &amp; {{ $reg->viceChairman?->name ?? '—' }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $reg->chairman->nim }} / {{ $reg->viceChairman?->nim ?? '—' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ $reg->election->name }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $reg->submitted_at ? $reg->submitted_at->translatedFormat('d M Y, H:i') : '—' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badge = match($reg->status->value) {
                                        'draft' => 'bg-slate-100 text-slate-700',
                                        'submitted', 'resubmitted' => 'bg-blue-100 text-blue-800',
                                        'under_review' => 'bg-amber-100 text-amber-800',
                                        'revision_required' => 'bg-orange-100 text-orange-800',
                                        'verified' => 'bg-emerald-100 text-emerald-800',
                                        'established' => 'bg-indigo-100 text-indigo-800',
                                        'rejected' => 'bg-rose-100 text-rose-800',
                                        default => 'bg-slate-100 text-slate-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                                    {{ $reg->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.registrations.show', $reg) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-semibold transition">
                                    Periksa Berkas →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada pendaftaran yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($registrations->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>

</x-layouts.admin>
