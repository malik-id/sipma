<x-layouts.admin title="Periode Pemilihan" header="Kelola Periode Pemilihan">

    {{-- Toolbar & Actions --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('admin.elections.index') }}" class="flex items-center gap-2 flex-1 max-w-lg">
            <div class="relative flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau deskripsi pemilihan..."
                    class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                />
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <select name="status" onchange="this.form.submit()" class="text-sm bg-white border border-slate-300 rounded-lg px-3 py-2 text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Status</option>
                @foreach (\App\Enums\ElectionStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-3.5 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition">
                Filter
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.elections.index') }}" class="px-2 py-2 text-xs text-slate-500 hover:text-slate-800 font-medium">Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.elections.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Periode Pemilihan Baru
        </a>
    </div>

    {{-- Election Cards / Table --}}
    <div class="space-y-4">
        @forelse ($elections as $election)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:border-slate-300 transition">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $election->status->badgeClasses() }}">
                                    {{ $election->status->label() }}
                                </span>
                                <h3 class="text-lg font-bold text-slate-900">{{ $election->name }}</h3>
                            </div>
                            @if ($election->description)
                                <p class="text-sm text-slate-500 line-clamp-2 max-w-3xl">{{ $election->description }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('admin.elections.requirements.index', $election) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition"
                               title="Kelola Dokumen Syarat Calon">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Syarat Calon ({{ $election->requirements_count }})
                            </a>

                            <a href="{{ route('admin.elections.show', $election) }}"
                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                                Detail & Linimasa
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Timeline Progress Snapshot --}}
                    <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-100 text-xs">
                        <div class="bg-slate-50 rounded-lg p-2.5">
                            <div class="text-slate-400 font-medium">Pendaftaran</div>
                            <div class="text-slate-800 font-semibold mt-0.5">
                                {{ $election->registration_start->translatedFormat('d M Y') }} - {{ $election->registration_end->translatedFormat('d M Y') }}
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-2.5">
                            <div class="text-slate-400 font-medium">Verifikasi</div>
                            <div class="text-slate-800 font-semibold mt-0.5">
                                {{ $election->verification_start->translatedFormat('d M Y') }} - {{ $election->verification_end->translatedFormat('d M Y') }}
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-2.5">
                            <div class="text-slate-400 font-medium">Pemungutan Suara</div>
                            <div class="text-slate-800 font-semibold mt-0.5">
                                {{ $election->voting_start->translatedFormat('d M Y H:i') }} - {{ $election->voting_end->translatedFormat('d M Y H:i') }}
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-2.5">
                            <div class="text-slate-400 font-medium">Publikasi Hasil</div>
                            <div class="text-slate-800 font-semibold mt-0.5">
                                {{ $election->result_publish_at ? $election->result_publish_at->translatedFormat('d M Y H:i') : 'Menyesuaikan' }}
                            </div>
                        </div>
                    </div>

                    {{-- Stats counter footer --}}
                    <div class="mt-4 flex items-center gap-6 text-xs text-slate-500">
                        <span><strong class="text-slate-800">{{ $election->voters_count }}</strong> Pemilih Terdaftar</span>
                        <span><strong class="text-slate-800">{{ $election->candidates_count }}</strong> Paslon Ditetapkan</span>
                        <span><strong class="text-slate-800">{{ $election->registrations_count }}</strong> Pendaftar</span>
                        <span><strong class="text-slate-800">{{ $election->participations_count }}</strong> Suara Masuk</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl p-12 text-center border border-slate-200">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-900">Belum ada periode pemilihan</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    Buat periode pemilihan baru untuk mengaktifkan alur pendaftaran bakal calon, penetapan DPT, dan pemungutan suara online.
                </p>
                <a href="{{ route('admin.elections.create') }}"
                   class="inline-flex items-center gap-2 mt-4 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition">
                    + Buat Periode Pemilihan Sekarang
                </a>
            </div>
        @endforelse

        @if ($elections->hasPages())
            <div class="pt-4">
                {{ $elections->links() }}
            </div>
        @endif
    </div>

</x-layouts.admin>
