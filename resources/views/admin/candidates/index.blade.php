<x-layouts.admin title="Penetapan & Manajemen Calon Resmi">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Calon Resmi Pemilihan</h1>
            <p class="text-xs sm:text-sm text-zinc-500 mt-1">Tetapkan pasangan calon yang telah terverifikasi dan tentukan nomor urut.</p>
        </div>

        {{-- Actions & Election Picker --}}
        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('admin.candidates.index') }}">
                <select name="election_id" class="text-xs border-zinc-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-white shadow-sm font-bold py-2 px-3" onchange="this.form.submit()">
                    @foreach ($elections as $el)
                        <option value="{{ $el->id }}" {{ $activeElection && $activeElection->id === $el->id ? 'selected' : '' }}>
                            {{ $el->name }} ({{ ucfirst($el->status->value) }})
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('admin.candidates.create', ['election_id' => $activeElection?->id]) }}"
                class="px-3.5 py-2 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-lg shadow-sm transition inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Tambah Calon Langsung</span>
            </a>
        </div>
    </div>

    @if ($activeElection)

        {{-- 1. Bakal Calon Terverifikasi Siap Ditetapkan --}}
        @if ($verifiedRegistrations->isNotEmpty())
            <div class="mb-8 bg-amber-50 border border-amber-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-zinc-950 text-amber-400 flex items-center justify-center font-bold text-sm">
                        <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-amber-950">Bakal Calon Terverifikasi Siap Ditetapkan ({{ $verifiedRegistrations->count() }})</h2>
                        <p class="text-xs text-amber-800">Pasangan di bawah ini telah lolos verifikasi berkas dan siap ditetapkan menjadi calon resmi.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($verifiedRegistrations as $vr)
                        <div class="bg-white p-4 rounded-xl border border-amber-200 flex items-center justify-between gap-4 shadow-xs">
                            <div>
                                <div class="font-bold text-zinc-900 text-sm">{{ $vr->chairman->name }} &amp; {{ $vr->viceChairman?->name ?? '—' }}</div>
                                <div class="text-xs text-zinc-400 font-mono mt-0.5">No. Reg: {{ $vr->registration_number }}</div>
                            </div>
                            <form method="POST" action="{{ route('admin.candidates.establish', $vr) }}"
                                onsubmit="return confirm('Tetapkan pasangan ini sebagai calon resmi pemilihan?')">
                                @csrf
                                <button type="submit" class="px-3.5 py-2 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-lg shadow-sm transition">
                                    Tetapkan Calon Resmi →
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 2. Daftar Calon Resmi yang Sudah Ditetapkan --}}
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
            <h2 class="text-base font-bold text-zinc-900 mb-4">Daftar Pasangan Calon Resmi</h2>

            @if ($candidates->isEmpty())
                <div class="text-center py-12 text-zinc-400">
                    <span class="material-symbols-outlined text-[36px] text-zinc-300 mb-2">badge</span>
                    <p class="font-bold text-zinc-700 text-sm">Belum ada calon resmi yang ditetapkan pada pemilihan ini.</p>
                    <p class="text-xs text-zinc-400 mt-1">Verifikasi berkas pendaftaran bakal calon terlebih dahulu untuk menetapkan calon resmi.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($candidates as $cand)
                        <div class="rounded-xl border border-zinc-200 bg-white overflow-hidden shadow-xs hover:border-amber-400 transition flex flex-col justify-between">
                            <div>
                                {{-- Card Header: Nomor Urut Badge --}}
                                <div class="p-4 bg-zinc-950 text-white flex items-center justify-between border-b border-zinc-800">
                                    <div class="flex items-center gap-2">
                                        @if ($cand->candidate_number)
                                            <span class="w-8 h-8 rounded-full bg-amber-400 text-zinc-950 font-extrabold flex items-center justify-center text-sm">
                                                {{ $cand->candidate_number }}
                                            </span>
                                            <span class="text-xs font-bold text-amber-400">Nomor Urut {{ $cand->candidate_number }}</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full bg-zinc-800 text-amber-400 text-[10px] font-bold">
                                                Belum Ada No. Urut
                                            </span>
                                        @endif
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $cand->status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30' }}">
                                        {{ $cand->status === 'active' ? 'Aktif' : 'Didiskualifikasi' }}
                                    </span>
                                </div>

                                {{-- Foto & Profil --}}
                                <div class="p-5">
                                    <div class="w-full h-44 rounded-xl bg-zinc-100 border border-zinc-200 overflow-hidden mb-4 flex items-center justify-center">
                                        @if ($cand->photo_path)
                                            <img src="{{ asset('storage/' . $cand->photo_path) }}" alt="Foto Pasangan" class="w-full h-full object-cover" />
                                        @else
                                            <div class="text-zinc-400 text-xs text-center p-4">
                                                Tidak ada foto resmi
                                            </div>
                                        @endif
                                    </div>

                                    <div class="space-y-2">
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider">Ketua</span>
                                            <div class="font-bold text-zinc-900 text-sm">{{ $cand->chairman->name }}</div>
                                            <div class="text-xs text-zinc-500 font-mono">{{ $cand->chairman->nim }} &bull; {{ $cand->chairman->study_program }}</div>
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider">Wakil Ketua</span>
                                            <div class="font-bold text-zinc-900 text-sm">{{ $cand->viceChairman?->name ?? '—' }}</div>
                                            <div class="text-xs text-zinc-500 font-mono">{{ $cand->viceChairman?->nim ?? '—' }} &bull; {{ $cand->viceChairman?->study_program ?? '—' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Footer: Assign Number & Status Toggle --}}
                            <div class="p-4 bg-zinc-50 border-t border-zinc-100 space-y-3">
                                <form method="POST" action="{{ route('admin.candidates.assign-number', $cand) }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="candidate_number" value="{{ $cand->candidate_number }}" min="1" placeholder="No. Urut"
                                        class="w-20 text-xs border-zinc-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-white font-bold" required />
                                    <button type="submit" class="px-3 py-1.5 bg-zinc-950 hover:bg-zinc-800 text-amber-400 text-xs font-bold rounded-lg transition shadow-xs">
                                        Simpan No
                                    </button>
                                </form>

                                <div class="flex items-center justify-between pt-2 border-t border-zinc-200 text-xs">
                                    <div class="flex items-center gap-3">
                                        <form method="POST" action="{{ route('admin.candidates.toggle-status', $cand) }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold {{ $cand->status === 'active' ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-700 hover:text-emerald-900' }}">
                                                {{ $cand->status === 'active' ? 'Diskualifikasi' : 'Aktifkan Kembali' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.candidates.destroy', $cand) }}"
                                            data-confirm="Hapus pasangan calon '{{ $cand->chairman->name }}' beserta data suara terkait?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 transition">
                                                Hapus Calon
                                            </button>
                                        </form>
                                    </div>
                                    <span class="text-[10px] text-zinc-400">Ditetapkan: {{ $cand->established_at ? $cand->established_at->translatedFormat('d M Y') : '—' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    @else
        <div class="bg-white rounded-2xl border border-zinc-200 p-8 text-center text-zinc-400">
            Pilih periode pemilihan di atas untuk mengelola calon resmi.
        </div>
    @endif

</x-layouts.admin>
