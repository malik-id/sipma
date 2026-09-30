<x-layouts.admin title="Syarat Berkas — {{ $election->name }}" header="Pengaturan Syarat Berkas Bakal Calon">

    {{-- Breadcrumb & Actions --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.elections.show', $election) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 font-medium transition mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Detail Pemilihan
            </a>
            <h2 class="text-sm font-semibold text-slate-700">{{ $election->name }}</h2>
        </div>

        <a href="{{ route('admin.elections.requirements.create', $election) }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Syarat Berkas Baru
        </a>
    </div>

    {{-- List of Requirements --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 sm:p-6 bg-slate-50/50 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Daftar Dokumen & Persyaratan Pendaftaran</h3>
                <p class="text-xs text-slate-500 mt-0.5">Syarat yang wajib atau opsional diunggah oleh pasangan calon pada tahap pendaftaran.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                Total: {{ $requirements->count() }} Syarat
            </span>
        </div>

        <div class="divide-y divide-slate-200">
            @forelse ($requirements as $requirement)
                <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50 transition">
                    <div class="flex items-start gap-4">
                        <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 font-mono font-bold text-sm flex items-center justify-center shrink-0">
                            {{ $requirement->sort_order }}
                        </span>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h4 class="text-base font-semibold text-slate-900">{{ $requirement->name }}</h4>
                                @if ($requirement->required)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                                        Wajib
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-600 uppercase">
                                        Opsional
                                    </span>
                                @endif

                                @if ($requirement->active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-500">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>

                            @if ($requirement->description)
                                <p class="text-xs text-slate-500 max-w-2xl leading-relaxed">{{ $requirement->description }}</p>
                            @endif

                            <div class="flex items-center gap-4 text-xs text-slate-500 pt-1">
                                <span>Tipe: <strong class="text-slate-700 uppercase">{{ $requirement->type }}</strong></span>
                                @if ($requirement->type !== 'text')
                                    <span>Ekstensi: <strong class="text-slate-700 font-mono">{{ $requirement->allowed_extensions ? implode(', ', $requirement->allowed_extensions) : 'Semua' }}</strong></span>
                                    <span>Ukuran Maks: <strong class="text-slate-700">{{ $requirement->max_file_size ? number_format($requirement->max_file_size / 1024, 1) . ' MB' : 'Tidak dibatasi' }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                        <form method="POST" action="{{ route('admin.elections.requirements.toggle', [$election, $requirement]) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg border {{ $requirement->active ? 'text-amber-700 bg-amber-50 border-amber-200 hover:bg-amber-100' : 'text-emerald-700 bg-emerald-50 border-emerald-200 hover:bg-emerald-100' }} transition">
                                {{ $requirement->active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <a href="{{ route('admin.elections.requirements.edit', [$election, $requirement]) }}"
                           class="p-2 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg border border-slate-200 transition"
                           title="Edit Syarat">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('admin.elections.requirements.destroy', [$election, $requirement]) }}" onsubmit="return confirm('Hapus syarat berkas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg border border-slate-200 transition" title="Hapus Syarat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h4 class="text-sm font-semibold text-slate-700">Belum ada syarat berkas</h4>
                    <p class="text-xs text-slate-500 mt-1">Tambahkan syarat berkas wajib atau opsional untuk pendaftaran bakal calon.</p>
                </div>
            @endforelse
        </div>
    </div>

</x-layouts.admin>
