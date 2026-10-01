<x-layouts.student title="Dashboard Mahasiswa">

    {{-- Sambutan Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Halo, {{ auth()->user()->student?->name ?? auth()->user()->name }} 👋</h1>
            <p class="text-zinc-500 text-xs sm:text-sm mt-0.5">Selamat datang di Portal Pemilihan Mahasiswa Universitas Mega Buana Palopo.</p>
        </div>
        <div class="self-start sm:self-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                NIM: {{ auth()->user()->student?->nim ?? 'Tamu' }}
            </span>
        </div>
    </div>

    {{-- Status Pemilihan Aktif --}}
    @if ($activeElection)
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden mb-6">
            {{-- Header Pemilihan (Zinc-950 + Gold Accent) --}}
            <div class="bg-zinc-950 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-zinc-800">
                <div>
                    <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest block">Periode Pemilihan Berlangsung</span>
                    <h2 class="text-base sm:text-lg font-bold text-white mt-0.5">{{ $activeElection->name }}</h2>
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-zinc-800 text-amber-300 border border-zinc-700">
                        {{ $phaseInfo['badge'] ?? ucfirst($activeElection->status->value) }}
                    </span>
                </div>
            </div>

            {{-- Info Cards 3 Kolom --}}
            <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Status Pemilih --}}
                <div class="rounded-xl border p-4 {{ $voterRecord && $voterRecord->voter_status->value === 'eligible' ? 'border-emerald-200 bg-emerald-50/50' : 'border-zinc-200 bg-zinc-50/50' }}">
                    <div class="text-[11px] font-bold uppercase tracking-wider {{ $voterRecord && $voterRecord->voter_status->value === 'eligible' ? 'text-emerald-700' : 'text-zinc-500' }} mb-1">
                        Status Hak Pilih
                    </div>
                    @if ($voterRecord && $voterRecord->voter_status->value === 'eligible')
                        <div class="font-bold text-emerald-800 text-sm">✓ Terdaftar di DPT</div>
                        @if ($hasVoted)
                            <div class="text-xs text-emerald-700 mt-1 font-medium">Suara Anda sudah terekam</div>
                        @else
                            <div class="text-xs text-amber-700 mt-1 font-medium">Belum memberikan suara</div>
                        @endif
                    @else
                        <div class="font-bold text-zinc-700 text-sm">Belum Terdaftar DPT</div>
                        <a href="{{ route('check-voter') }}" class="text-xs text-amber-600 hover:text-amber-700 font-semibold mt-1 inline-block">Periksa status DPT →</a>
                    @endif
                </div>

                {{-- Status Pendaftaran Calon --}}
                <div class="rounded-xl border border-zinc-200 bg-zinc-50/50 p-4">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Pendaftaran Calon</div>
                    @if ($candidateRegistration)
                        <div class="font-bold text-zinc-900 text-sm">{{ $candidateRegistration->status->label() }}</div>
                        <a href="{{ route('registration.show', $candidateRegistration) }}" class="text-xs text-amber-600 hover:text-amber-700 font-semibold mt-1 inline-block">Lihat berkas pendaftaran →</a>
                    @elseif ($activeElection->registrationOpen())
                        <div class="font-bold text-amber-700 text-sm">Pendaftaran Sedang Dibuka</div>
                        <a href="{{ route('registration.create') }}" class="text-xs text-amber-600 hover:text-amber-700 font-semibold mt-1 inline-block">Daftar sebagai calon →</a>
                    @else
                        <div class="font-bold text-zinc-600 text-sm">Pendaftaran Ditutup</div>
                        <div class="text-xs text-zinc-400 mt-1">Bukan dalam rentang pendaftaran</div>
                    @endif
                </div>

                {{-- Linimasa Waktu --}}
                <div class="rounded-xl border border-zinc-200 bg-zinc-50/50 p-4">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 mb-1">
                        {{ $phaseInfo['target_label'] ?? 'Jadwal Voting' }}
                    </div>
                    @if ($phaseInfo['target_time'] ?? null)
                        <div class="font-bold text-zinc-900 text-sm">
                            {{ $phaseInfo['target_time']->translatedFormat('d M Y') }}
                        </div>
                        <div class="text-xs text-zinc-500 mt-0.5">
                            Pukul {{ $phaseInfo['target_time']->translatedFormat('H:i') }} WITA
                        </div>
                    @else
                        <div class="font-bold text-zinc-700 text-sm">{{ $phaseInfo['phase'] ?? '-' }}</div>
                    @endif
                </div>
            </div>

            {{-- Linimasa Ringkas --}}
            <div class="px-5 sm:px-6 py-4 bg-zinc-50 border-t border-zinc-200">
                <span class="text-[11px] font-bold text-zinc-600 uppercase tracking-wider block mb-2.5">Tahapan &amp; Linimasa Pemilihan</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3 bg-white rounded-lg border border-zinc-200">
                        <span class="text-zinc-400 font-medium block">Pendaftaran Paslon:</span>
                        <span class="font-bold text-zinc-800">{{ $activeElection->registration_start->translatedFormat('d M') }} – {{ $activeElection->registration_end->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="p-3 bg-white rounded-lg border border-zinc-200">
                        <span class="text-zinc-400 font-medium block">Verifikasi Berkas:</span>
                        <span class="font-bold text-zinc-800">{{ $activeElection->verification_start->translatedFormat('d M') }} – {{ $activeElection->verification_end->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="p-3 bg-white rounded-lg border border-zinc-200">
                        <span class="text-zinc-400 font-medium block">Pemungutan Suara:</span>
                        <span class="font-bold text-zinc-800">{{ $activeElection->voting_start->translatedFormat('d M Y, H:i') }} WITA</span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-zinc-200 p-8 text-center mb-6">
            <div class="w-12 h-12 rounded-full bg-zinc-100 text-zinc-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="font-bold text-zinc-800 text-sm sm:text-base">Belum Ada Pemilihan Aktif</h3>
            <p class="text-xs sm:text-sm text-zinc-400 mt-1 max-w-sm mx-auto">Panitia pemilihan belum membuka periode aktif. Silakan pantau informasi secara berkala.</p>
        </div>
    @endif

    {{-- Aksi Cepat Mahasiswa --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Bilik Suara --}}
        <a href="{{ route('student.voting.index') }}"
           class="bg-white rounded-xl border border-zinc-200 p-4 sm:p-5 flex items-center gap-4 hover:border-amber-400 hover:shadow-sm transition group">
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-zinc-950 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <div class="text-sm font-bold text-zinc-900">Bilik Suara Digital</div>
                <p class="text-xs text-zinc-400 mt-0.5">Gunakan hak suara Anda</p>
            </div>
        </a>

        {{-- Cek DPT --}}
        <a href="{{ route('check-voter') }}"
           class="bg-white rounded-xl border border-zinc-200 p-4 sm:p-5 flex items-center gap-4 hover:border-amber-400 hover:shadow-sm transition group">
            <div class="w-11 h-11 rounded-xl bg-zinc-100 text-zinc-700 flex items-center justify-center shrink-0 group-hover:bg-zinc-900 group-hover:text-amber-400 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-sm font-bold text-zinc-900">Cek Status DPT</div>
                <p class="text-xs text-zinc-400 mt-0.5">Periksa hak suara Anda</p>
            </div>
        </a>

        {{-- Lihat Kandidat --}}
        <a href="{{ route('public.candidates.index') }}"
           class="bg-white rounded-xl border border-zinc-200 p-4 sm:p-5 flex items-center gap-4 hover:border-amber-400 hover:shadow-sm transition group">
            <div class="w-11 h-11 rounded-xl bg-zinc-100 text-zinc-700 flex items-center justify-center shrink-0 group-hover:bg-zinc-900 group-hover:text-amber-400 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-sm font-bold text-zinc-900">Daftar Kandidat</div>
                <p class="text-xs text-zinc-400 mt-0.5">Visi &amp; misi calon pemimpin</p>
            </div>
        </a>
    </div>

</x-layouts.student>
