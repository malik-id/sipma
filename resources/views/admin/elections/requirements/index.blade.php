<x-layouts.admin title="Syarat Berkas — {{ $election->name }}" header="Pengaturan Syarat Berkas Bakal Calon">

    {{-- Breadcrumb & Actions --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.elections.show', $election) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-zinc-600 hover:text-zinc-950 transition mb-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Detail Pemilihan
            </a>
            <h2 class="text-sm font-bold text-zinc-800">{{ $election->name }}</h2>
        </div>

        <a href="{{ route('admin.elections.requirements.create', $election) }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-zinc-950 bg-amber-500 rounded-lg hover:bg-amber-400 shadow-sm transition">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Tambah Syarat Berkas Baru
        </a>
    </div>

    {{-- List of Requirements --}}
    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 overflow-hidden">
        <div class="p-4 sm:p-6 bg-zinc-50 border-b border-zinc-200 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-zinc-900">Daftar Dokumen &amp; Persyaratan Pendaftaran</h3>
                <p class="text-xs text-zinc-500 mt-0.5">Syarat yang wajib atau opsional diunggah oleh pasangan calon pada tahap pendaftaran.</p>
            </div>
            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-900 border border-amber-200">
                Total: {{ $requirements->count() }} Syarat
            </span>
        </div>

        <div class="divide-y divide-zinc-200">
            @forelse ($requirements as $requirement)
                <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-zinc-50/50 transition">
                    <div class="flex items-start gap-4">
                        <span class="w-8 h-8 rounded-lg bg-zinc-100 text-zinc-700 font-mono font-bold text-sm flex items-center justify-center shrink-0">
                            {{ $requirement->sort_order }}
                        </span>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h4 class="text-base font-bold text-zinc-900">{{ $requirement->name }}</h4>
                                @if ($requirement->required)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                                        Wajib
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-zinc-100 text-zinc-600 uppercase">
                                        Opsional
                                    </span>
                                @endif

                                @if ($requirement->active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-zinc-100 text-zinc-500">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>

                            @if ($requirement->description)
                                <p class="text-xs text-zinc-500 max-w-2xl leading-relaxed">{{ $requirement->description }}</p>
                            @endif

                            <div class="flex items-center gap-4 text-xs text-zinc-500 pt-1">
                                <span>Tipe: <strong class="text-zinc-800 uppercase">{{ $requirement->type }}</strong></span>
                                @if ($requirement->type !== 'text')
                                    <span>Ekstensi: <strong class="text-zinc-800 font-mono">{{ $requirement->allowed_extensions ? implode(', ', $requirement->allowed_extensions) : 'Semua' }}</strong></span>
                                    <span>Ukuran Maks: <strong class="text-zinc-800">{{ $requirement->max_file_size ? number_format($requirement->max_file_size / 1024, 1) . ' MB' : 'Tidak dibatasi' }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                        <form method="POST" action="{{ route('admin.elections.requirements.toggle', [$election, $requirement]) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-lg border {{ $requirement->active ? 'text-amber-800 bg-amber-50 border-amber-200 hover:bg-amber-100' : 'text-emerald-800 bg-emerald-50 border-emerald-200 hover:bg-emerald-100' }} transition">
                                {{ $requirement->active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <a href="{{ route('admin.elections.requirements.edit', [$election, $requirement]) }}"
                           class="p-2 text-zinc-600 hover:text-amber-600 hover:bg-zinc-100 rounded-lg border border-zinc-200 transition flex items-center justify-center"
                           title="Edit Syarat">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </a>

                        <form method="POST" action="{{ route('admin.elections.requirements.destroy', [$election, $requirement]) }}" onsubmit="return confirm('Hapus syarat berkas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-zinc-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg border border-zinc-200 transition flex items-center justify-center" title="Hapus Syarat">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-zinc-400">
                    <span class="material-symbols-outlined text-[36px] text-zinc-300 mb-2">checklist</span>
                    <h4 class="text-sm font-bold text-zinc-700">Belum ada syarat berkas</h4>
                    <p class="text-xs text-zinc-500 mt-1">Tambahkan syarat berkas wajib atau opsional untuk pendaftaran bakal calon.</p>
                </div>
            @endforelse
        </div>
    </div>

</x-layouts.admin>
