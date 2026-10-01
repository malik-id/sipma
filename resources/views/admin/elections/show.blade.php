<x-layouts.admin title="{{ $election->name }}" header="Detail & Kontrol Pemilihan">

    {{-- Top Back & Navigation --}}
    <div class="flex items-center justify-between gap-4 mb-6">
        <a href="{{ route('admin.elections.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-zinc-600 hover:text-zinc-950 transition">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali ke Daftar Periode
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.elections.requirements.index', $election) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-zinc-700 bg-white border border-zinc-300 rounded-lg hover:bg-zinc-50 shadow-sm transition">
                <span class="material-symbols-outlined text-[18px] text-zinc-500">checklist</span>
                Pengaturan Syarat Berkas ({{ $election->requirements_count }})
            </a>

            @if (now()->lt($election->voting_start) && $election->participations_count === 0)
                <a href="{{ route('admin.elections.edit', $election) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-zinc-950 bg-amber-500 rounded-lg hover:bg-amber-400 shadow-sm transition">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Edit Jadwal &amp; Detail
                </a>
            @endif

            @if ($election->participations_count === 0)
                <form method="POST" action="{{ route('admin.elections.destroy', $election) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode pemilihan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg border border-transparent hover:border-rose-200 transition flex items-center justify-center" title="Hapus Periode">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Main Banner & Countdown Card --}}
    <div class="bg-zinc-950 text-white rounded-2xl p-6 sm:p-8 shadow-sm mb-8 border border-zinc-850">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-zinc-900 border border-zinc-700 text-amber-400">
                        Status Saat Ini: {{ $election->status->label() }}
                    </span>
                    <span class="text-xs text-zinc-400 font-mono">ID: #{{ $election->id }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">{{ $election->name }}</h1>
                @if ($election->description)
                    <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed">{{ $election->description }}</p>
                @endif
            </div>

            {{-- Live Server Countdown / Phase Info --}}
            @if ($phaseInfo && $phaseInfo['target_time'])
                <div class="bg-zinc-900 rounded-xl p-5 border border-zinc-800 shrink-0 sm:min-w-[280px]">
                    <div class="text-[11px] uppercase font-bold tracking-wider text-amber-400">{{ $phaseInfo['target_label'] }}</div>
                    <div class="text-lg sm:text-xl font-bold text-white mt-1">
                        {{ $phaseInfo['target_time']->translatedFormat('d F Y, H:i') }} WITA
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs font-mono bg-black/40 px-3 py-1.5 rounded-lg text-amber-300">
                        <span class="material-symbols-outlined text-[16px] text-amber-400 animate-spin">schedule</span>
                        <span>{{ now()->diffForHumans($phaseInfo['target_time'], ['syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW, 'parts' => 3]) }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Status Transition Management Panel --}}
    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-6 mb-8">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-100 mb-6">
            <div>
                <h3 class="text-base font-bold text-zinc-900">Kontrol Alur &amp; Status Pemilihan (Server-Side)</h3>
                <p class="text-xs text-zinc-500 mt-0.5">Ubah status tahapan pemilihan sesuai jadwal resmi. Validasi jam dilakukan di server.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $election->status->badgeClasses() }}">
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
                    <label class="relative flex flex-col p-3.5 rounded-xl border cursor-pointer transition {{ $election->status === $statusOption ? 'border-amber-500 bg-amber-50/50 ring-2 ring-amber-500/20' : 'border-zinc-200 hover:border-zinc-300 hover:bg-zinc-50' }}">
                        <div class="flex items-center justify-between mb-2">
                            <input
                                type="radio"
                                name="status"
                                value="{{ $statusOption->value }}"
                                {{ $election->status === $statusOption ? 'checked' : '' }}
                                class="text-amber-600 focus:ring-amber-500 w-4 h-4"
                            />
                            @if ($election->status === $statusOption)
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            @endif
                        </div>
                        <span class="text-xs font-bold text-zinc-900">{{ $statusOption->label() }}</span>
                        <span class="text-[11px] text-zinc-400 font-mono mt-1">{{ $statusOption->value }}</span>
                    </label>
                @endforeach
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-zinc-100">
                <div class="text-xs text-zinc-500 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-amber-500 text-[18px]">info</span>
                    <span>Status <strong>Voting</strong> &amp; <strong>Published</strong> diverifikasi ketat terhadap jam server.</span>
                </div>

                <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-zinc-950 text-amber-400 font-bold text-xs rounded-lg hover:bg-zinc-800 shadow-sm transition">
                    Perbarui Status Pemilihan
                </button>
            </div>
        </form>
    </div>

    {{-- Detailed Linimasa & Timeline Stepper --}}
    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-6 mb-8">
        <h3 class="text-base font-bold text-zinc-900 mb-4">Linimasa Lengkap Tahapan Pemilihan</h3>

        <div class="relative border-l-2 border-zinc-200 ml-4 pl-6 space-y-8 my-4">
            {{-- 1. Pendaftaran Bakal Calon --}}
            <div class="relative">
                <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->between($election->registration_start, $election->registration_end) ? 'bg-amber-500 ring-4 ring-amber-100 animate-pulse' : (now()->gt($election->registration_end) ? 'bg-emerald-500' : 'bg-zinc-300') }}"></span>
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h4 class="text-sm font-bold text-zinc-900">1. Pendaftaran Bakal Calon Pasangan</h4>
                    <span class="text-xs font-mono font-bold text-zinc-700 bg-zinc-100 px-2.5 py-1 rounded">
                        {{ $election->registration_start->translatedFormat('d M Y H:i') }} - {{ $election->registration_end->translatedFormat('d M Y H:i') }}
                    </span>
                </div>
                <p class="text-xs text-zinc-500 mt-1">Calon ketua dan wakil ketua mendaftar serta mengunggah kelengkapan dokumen persyaratan.</p>
            </div>

            {{-- 2. Verifikasi Administrasi --}}
            <div class="relative">
                <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->between($election->verification_start, $election->verification_end) ? 'bg-amber-500 ring-4 ring-amber-100 animate-pulse' : (now()->gt($election->verification_end) ? 'bg-emerald-500' : 'bg-zinc-300') }}"></span>
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h4 class="text-sm font-bold text-zinc-900">2. Verifikasi Administrasi &amp; Berkas</h4>
                    <span class="text-xs font-mono font-bold text-zinc-700 bg-zinc-100 px-2.5 py-1 rounded">
                        {{ $election->verification_start->translatedFormat('d M Y H:i') }} - {{ $election->verification_end->translatedFormat('d M Y H:i') }}
                    </span>
                </div>
                <p class="text-xs text-zinc-500 mt-1">Panitia melakukan audit keabsahan berkas (KHS, SK Aktif, Visi-Misi, Sertifikat).</p>
            </div>

            {{-- 3. Penetapan Calon --}}
            @if ($election->candidate_finalization_at)
                <div class="relative">
                    <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->gte($election->candidate_finalization_at) ? 'bg-emerald-500' : 'bg-zinc-300' }}"></span>
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <h4 class="text-sm font-bold text-zinc-900">3. Penetapan Calon Resmi &amp; Pengundian Nomor Urut</h4>
                        <span class="text-xs font-mono font-bold text-zinc-700 bg-zinc-100 px-2.5 py-1 rounded">
                            {{ $election->candidate_finalization_at->translatedFormat('d M Y H:i') }}
                        </span>
                    </div>
                    <p class="text-xs text-zinc-500 mt-1">Pengumuman daftar pasangan calon tetap dan nomor urut.</p>
                </div>
            @endif

            {{-- 4. Masa Kampanye --}}
            @if ($election->campaign_start && $election->campaign_end)
                <div class="relative">
                    <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->between($election->campaign_start, $election->campaign_end) ? 'bg-amber-500 ring-4 ring-amber-100 animate-pulse' : (now()->gt($election->campaign_end) ? 'bg-emerald-500' : 'bg-zinc-300') }}"></span>
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <h4 class="text-sm font-bold text-zinc-900">4. Masa Kampanye Pasangan Calon</h4>
                        <span class="text-xs font-mono font-bold text-zinc-700 bg-zinc-100 px-2.5 py-1 rounded">
                            {{ $election->campaign_start->translatedFormat('d M Y H:i') }} - {{ $election->campaign_end->translatedFormat('d M Y H:i') }}
                        </span>
                    </div>
                    <p class="text-xs text-zinc-500 mt-1">Penyampaian visi misi, debat kandidat, dan sosialisasi program kerja.</p>
                </div>
            @endif

            {{-- 5. Pemungutan Suara (Voting) --}}
            <div class="relative">
                <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->between($election->voting_start, $election->voting_end) ? 'bg-emerald-500 ring-4 ring-emerald-100 animate-pulse' : (now()->gt($election->voting_end) ? 'bg-emerald-500' : 'bg-zinc-300') }}"></span>
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h4 class="text-sm font-bold text-zinc-900">5. Pemungutan Suara (E-Voting)</h4>
                    <span class="text-xs font-mono font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded">
                        {{ $election->voting_start->translatedFormat('d M Y H:i') }} - {{ $election->voting_end->translatedFormat('d M Y H:i') }}
                    </span>
                </div>
                <p class="text-xs text-zinc-500 mt-1">Pemilih yang terdaftar di DPT memberikan suara secara rahasia dan aman di sistem.</p>
            </div>

            {{-- 6. Publikasi Hasil --}}
            @if ($election->result_publish_at)
                <div class="relative">
                    <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ now()->gte($election->result_publish_at) ? 'bg-amber-500' : 'bg-zinc-300' }}"></span>
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <h4 class="text-sm font-bold text-zinc-900">6. Publikasi Hasil &amp; Rekapitulasi Suara</h4>
                        <span class="text-xs font-mono font-bold text-zinc-700 bg-zinc-100 px-2.5 py-1 rounded">
                            {{ $election->result_publish_at->translatedFormat('d M Y H:i') }}
                        </span>
                    </div>
                    <p class="text-xs text-zinc-500 mt-1">Pengumuman pemenang dan perolehan suara terbuka untuk seluruh mahasiswa.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Syarat Berkas Overview --}}
    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-6">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-100 mb-4">
            <div>
                <h3 class="text-base font-bold text-zinc-900">Syarat Berkas Calon Terdaftar</h3>
                <p class="text-xs text-zinc-500 mt-0.5">Berkas yang harus diunggah pasangan bakal calon pada saat pendaftaran.</p>
            </div>
            <a href="{{ route('admin.elections.requirements.index', $election) }}"
               class="px-3 py-1.5 text-xs font-bold text-amber-700 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 rounded-lg transition">
                Kelola Syarat Lengkap →
            </a>
        </div>

        <div class="divide-y divide-zinc-100">
            @forelse ($election->requirements as $req)
                <div class="py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-zinc-100 text-zinc-600 flex items-center justify-center text-xs font-bold font-mono">
                            {{ $loop->iteration }}
                        </span>
                        <div>
                            <div class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                                {{ $req->name }}
                                @if ($req->required)
                                    <span class="text-[10px] uppercase font-bold text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">Wajib</span>
                                @else
                                    <span class="text-[10px] uppercase font-bold text-zinc-500 bg-zinc-100 px-1.5 py-0.5 rounded">Opsional</span>
                                @endif
                            </div>
                            <p class="text-xs text-zinc-400 mt-0.5">{{ $req->description ?: 'Tanpa deskripsi' }}</p>
                        </div>
                    </div>

                    <div class="text-xs text-zinc-500 text-right">
                        <div>Tipe: <strong class="text-zinc-800 uppercase">{{ $req->type }}</strong></div>
                        @if ($req->type !== 'text')
                            <div class="text-[11px] text-zinc-400">
                                Ekstensi: {{ $req->allowed_extensions ? implode(', ', $req->allowed_extensions) : 'Bebas' }} (Maks {{ number_format($req->max_file_size / 1024, 1) }} MB)
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-6 text-center text-zinc-400 text-xs">
                    Belum ada syarat berkas yang dikonfigurasi.
                </div>
            @endforelse
        </div>
    </div>

</x-layouts.admin>
