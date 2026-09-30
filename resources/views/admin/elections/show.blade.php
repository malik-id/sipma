<x-layouts.admin title="{{ $election->name }}" header="Detail & Kontrol Pemilihan">

    {{-- Top Back & Navigation --}}
    <div class="flex items-center justify-between gap-4 mb-6">
        <a href="{{ route('admin.elections.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Periode
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.elections.requirements.index', $election) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-sm transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Pengaturan Syarat Berkas ({{ $election->requirements_count }})
            </a>

            @if (now()->lt($election->voting_start) && $election->participations_count === 0)
                <a href="{{ route('admin.elections.edit', $election) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Jadwal & Detail
                </a>
            @endif

            @if ($election->participations_count === 0)
                <form method="POST" action="{{ route('admin.elections.destroy', $election) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode pemilihan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg border border-transparent hover:border-rose-200 transition" title="Hapus Periode">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Main Banner & Countdown Card --}}
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 text-white rounded-2xl p-6 sm:p-8 shadow-sm mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/10 backdrop-blur border border-white/20 text-white">
                        Status Saat Ini: {{ $election->status->label() }}
                    </span>
                    <span class="text-xs text-blue-200 font-mono">ID: #{{ $election->id }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">{{ $election->name }}</h1>
                @if ($election->description)
                    <p class="text-sm text-blue-100 leading-relaxed">{{ $election->description }}</p>
                @endif
            </div>

            {{-- Live Server Countdown / Phase Info --}}
            @if ($phaseInfo && $phaseInfo['target_time'])
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-5 border border-white/15 shrink-0 sm:min-w-[280px]">
                    <div class="text-xs uppercase font-semibold tracking-wider text-blue-200">{{ $phaseInfo['target_label'] }}</div>
                    <div class="text-xl font-bold text-white mt-1">
                        {{ $phaseInfo['target_time']->translatedFormat('d F Y, H:i') }} WIB
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs font-mono bg-black/20 px-3 py-1.5 rounded-lg text-emerald-300">
                        <svg class="w-4 h-4 animate-spin shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>{{ now()->diffForHumans($phaseInfo['target_time'], ['syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW, 'parts' => 3]) }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Status Transition Management Panel --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-900">Kontrol Alur & Status Pemilihan (Server-Side)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Ubah status tahapan pemilihan sesuai jadwal resmi. Validasi jam dilakukan di server.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $election->status->badgeClasses() }}">
                {{ $election->status->label() }}
            </span>
        </div>

        <form method="POST" action="{{ route('admin.elections.update-status', $election) }}">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
                @php
                    $allStatuses = \App\Enums\ElectionStatus::cases();
                @endphp

                @foreach ($allStatuses as $statusOption)
                    <label class="relative flex flex-col p-3.5 rounded-xl border cursor-pointer transition {{ $election->status === $statusOption ? 'border-blue-600 bg-blue-50/50 ring-2 ring-blue-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                        <div class="flex items-center justify-between mb-2">
                            <input
                                type="radio"
                                name="status"
                                value="{{ $statusOption->value }}"
                                {{ $election->status === $statusOption ? 'checked' : '' }}
                                class="text-blue-600 focus:ring-blue-500 w-4 h-4"
                            />
                            @if ($election->status === $statusOption)
                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            @endif
                        </div>
                        <span class="text-xs font-bold text-slate-900">{{ $statusOption->label() }}</span>
                        <span class="text-[11px] text-slate-400 font-mono mt-1">{{ $statusOption->value }}</span>
                    </label>
                @endforeach
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <div class="text-xs text-slate-500 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>Status <strong>Voting</strong> & <strong>Published</strong> diverifikasi ketat terhadap jam server.</span>
                </div>

                <button type="submit" class="px-5 py-2 bg-slate-900 text-white font-semibold text-xs rounded-lg hover:bg-slate-800 shadow-sm transition">
                    Perbarui Status Pemilihan
                </button>
            </div>
        </form>
    </div>

    {{-- Detailed Linimasa & Timeline Stepper --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <h3 class="text-base font-bold text-slate-900 mb-4">Linimasa Lengkap Tahapan Pemilihan</h3>

        <div class="relative border-l-2 border-slate-200 ml-4 pl-6 space-y-8 my-4">
            {{-- 1. Pendaftaran Bakal Calon --}}
            <div class="relative">
                <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->between($election->registration_start, $election->registration_end) ? 'bg-amber-500 ring-4 ring-amber-100 animate-pulse' : (now()->gt($election->registration_end) ? 'bg-emerald-500' : 'bg-slate-300') }}"></span>
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h4 class="text-sm font-bold text-slate-900">1. Pendaftaran Bakal Calon Pasangan</h4>
                    <span class="text-xs font-mono font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded">
                        {{ $election->registration_start->translatedFormat('d M Y H:i') }} - {{ $election->registration_end->translatedFormat('d M Y H:i') }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Calon ketua dan wakil ketua mendaftar serta mengunggah kelengkapan dokumen persyaratan.</p>
            </div>

            {{-- 2. Verifikasi Administrasi --}}
            <div class="relative">
                <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->between($election->verification_start, $election->verification_end) ? 'bg-indigo-500 ring-4 ring-indigo-100 animate-pulse' : (now()->gt($election->verification_end) ? 'bg-emerald-500' : 'bg-slate-300') }}"></span>
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h4 class="text-sm font-bold text-slate-900">2. Verifikasi Administrasi & Berkas</h4>
                    <span class="text-xs font-mono font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded">
                        {{ $election->verification_start->translatedFormat('d M Y H:i') }} - {{ $election->verification_end->translatedFormat('d M Y H:i') }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Panitia melakukan audit keabsahan berkas (KHS, SK Aktif, Visi-Misi, Sertifikat).</p>
            </div>

            {{-- 3. Penetapan Calon --}}
            @if ($election->candidate_finalization_at)
                <div class="relative">
                    <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->gte($election->candidate_finalization_at) ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <h4 class="text-sm font-bold text-slate-900">3. Penetapan Calon Resmi & Pengundian Nomor Urut</h4>
                        <span class="text-xs font-mono font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded">
                            {{ $election->candidate_finalization_at->translatedFormat('d M Y H:i') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Pengumuman daftar pasangan calon tetap dan nomor urut.</p>
                </div>
            @endif

            {{-- 4. Masa Kampanye --}}
            @if ($election->campaign_start && $election->campaign_end)
                <div class="relative">
                    <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->between($election->campaign_start, $election->campaign_end) ? 'bg-blue-500 ring-4 ring-blue-100 animate-pulse' : (now()->gt($election->campaign_end) ? 'bg-emerald-500' : 'bg-slate-300') }}"></span>
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <h4 class="text-sm font-bold text-slate-900">4. Masa Kampanye Pasangan Calon</h4>
                        <span class="text-xs font-mono font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded">
                            {{ $election->campaign_start->translatedFormat('d M Y H:i') }} - {{ $election->campaign_end->translatedFormat('d M Y H:i') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Penyampaian visi misi, debat kandidat, dan sosialisasi program kerja.</p>
                </div>
            @endif

            {{-- 5. Pemungutan Suara (Voting) --}}
            <div class="relative">
                <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->between($election->voting_start, $election->voting_end) ? 'bg-emerald-500 ring-4 ring-emerald-100 animate-pulse' : (now()->gt($election->voting_end) ? 'bg-emerald-500' : 'bg-slate-300') }}"></span>
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h4 class="text-sm font-bold text-slate-900">5. Pemungutan Suara (E-Voting)</h4>
                    <span class="text-xs font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded">
                        {{ $election->voting_start->translatedFormat('d M Y H:i') }} - {{ $election->voting_end->translatedFormat('d M Y H:i') }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Pemilih yang terdaftar di DPT memberikan suara secara rahasia dan aman di sistem.</p>
            </div>

            {{-- 6. Publikasi Hasil --}}
            @if ($election->result_publish_at)
                <div class="relative">
                    <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->gte($election->result_publish_at) ? 'bg-blue-500' : 'bg-slate-300') }}"></span>
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <h4 class="text-sm font-bold text-slate-900">6. Publikasi Hasil & Rekapitulasi Suara</h4>
                        <span class="text-xs font-mono font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded">
                            {{ $election->result_publish_at->translatedFormat('d M Y H:i') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Pengumuman pemenang dan perolehan suara terbuka untuk seluruh mahasiswa.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Syarat Berkas Overview --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Syarat Berkas Calon Terdaftar</h3>
                <p class="text-xs text-slate-500 mt-0.5">Berkas yang harus diunggah pasangan bakal calon pada saat pendaftaran.</p>
            </div>
            <a href="{{ route('admin.elections.requirements.index', $election) }}"
               class="px-3 py-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                Kelola Syarat Lengkap →
            </a>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($election->requirements as $req)
                <div class="py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold font-mono">
                            {{ $loop->iteration }}
                        </span>
                        <div>
                            <div class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                                {{ $req->name }}
                                @if ($req->required)
                                    <span class="text-[10px] uppercase font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">Wajib</span>
                                @else
                                    <span class="text-[10px] uppercase font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">Opsional</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $req->description ?: 'Tanpa deskripsi' }}</p>
                        </div>
                    </div>

                    <div class="text-xs text-slate-500 text-right">
                        <div>Tipe: <strong class="text-slate-700 uppercase">{{ $req->type }}</strong></div>
                        @if ($req->type !== 'text')
                            <div class="text-[11px] text-slate-400">
                                Ekstensi: {{ $req->allowed_extensions ? implode(', ', $req->allowed_extensions) : 'Bebas' }} (Maks {{ number_format($req->max_file_size / 1024, 1) }} MB)
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-6 text-center text-slate-400 text-xs">
                    Belum ada syarat berkas yang dikonfigurasi.
                </div>
            @endforelse
        </div>
    </div>

</x-layouts.admin>
