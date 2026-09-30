<x-layouts.student title="Dashboard">

    {{-- Sambutan --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Halo, {{ auth()->user()->student?->name ?? auth()->user()->name }}! 👋</h1>
        <p class="text-slate-500 text-sm mt-1">Selamat datang di Portal Pemilihan Mahasiswa HIMAKOM.</p>
    </div>

    {{-- Status Pemilihan Aktif --}}
    @if ($activeElection)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-blue-100 uppercase tracking-wider">Pemilihan Aktif</p>
                    <h2 class="text-lg font-bold text-white mt-0.5">{{ $activeElection->name }}</h2>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white">
                    {{ $phaseInfo['badge'] ?? ucfirst($activeElection->status->value) }}
                </span>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Status Pemilih --}}
                <div class="rounded-xl border p-4 {{ $voterRecord && $voterRecord->voter_status->value === 'eligible' ? 'border-emerald-200 bg-emerald-50' : 'border-slate-200 bg-slate-50' }}">
                    <div class="text-xs font-semibold uppercase tracking-wider {{ $voterRecord && $voterRecord->voter_status->value === 'eligible' ? 'text-emerald-600' : 'text-slate-400' }} mb-1">
                        Status Pemilih
                    </div>
                    @if ($voterRecord && $voterRecord->voter_status->value === 'eligible')
                        <div class="font-bold text-emerald-700">✓ Berhak Memilih</div>
                        @if ($hasVoted)
                            <div class="text-xs text-emerald-600 mt-1">Suara sudah diberikan</div>
                        @else
                            <div class="text-xs text-emerald-600 mt-1">Belum memberikan suara</div>
                        @endif
                    @else
                        <div class="font-bold text-slate-600">Belum Terdaftar DPT</div>
                        <a href="{{ route('check-voter') }}" class="text-xs text-blue-600 mt-1 inline-block">Cek status →</a>
                    @endif
                </div>

                {{-- Status Pendaftaran Calon --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Pendaftaran Calon</div>
                    @if ($candidateRegistration)
                        <div class="font-bold text-slate-800">{{ $candidateRegistration->status->label() }}</div>
                        <a href="{{ route('registration.show', $candidateRegistration) }}" class="text-xs text-blue-600 mt-1 inline-block">Lihat detail →</a>
                    @elseif ($activeElection->registrationOpen())
                        <div class="font-bold text-blue-700">Pendaftaran Dibuka!</div>
                        <a href="{{ route('registration.create') }}" class="text-xs text-blue-600 mt-1 inline-block">Daftar sekarang →</a>
                    @else
                        <div class="font-bold text-slate-500">Belum Ada Pendaftaran</div>
                        <div class="text-xs text-slate-400 mt-1">Bukan periode pendaftaran</div>
                    @endif
                </div>

                {{-- Linimasa --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">
                        {{ $phaseInfo['target_label'] ?? 'Jadwal Voting' }}
                    </div>
                    @if ($phaseInfo['target_time'] ?? null)
                        <div class="font-bold text-slate-800 text-sm">
                            {{ $phaseInfo['target_time']->translatedFormat('d M Y') }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            {{ $phaseInfo['target_time']->translatedFormat('H:i') }} WITA
                        </div>
                    @else
                        <div class="font-bold text-slate-500">{{ $phaseInfo['phase'] ?? '-' }}</div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center mb-6">
            <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-slate-700">Belum Ada Pemilihan Aktif</h3>
            <p class="text-sm text-slate-400 mt-1">Panitia belum membuka periode pemilihan. Pantau terus halaman ini.</p>
        </div>
    @endif

    {{-- Aksi Cepat --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('registration.index') }}"
           class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4 hover:border-blue-400 hover:shadow-sm transition group">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <div class="text-sm font-semibold text-slate-800">Pendaftaran Saya</div>
                <p class="text-xs text-slate-400 mt-0.5">Lihat & kelola pendaftaran calon</p>
            </div>
        </a>

        <a href="{{ route('check-voter') }}"
           class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4 hover:border-blue-400 hover:shadow-sm transition group">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-sm font-semibold text-slate-800">Cek Status DPT</div>
                <p class="text-xs text-slate-400 mt-0.5">Periksa hak pilih Anda</p>
            </div>
        </a>

        <a href="/kandidat"
           class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4 hover:border-blue-400 hover:shadow-sm transition group">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-sm font-semibold text-slate-800">Lihat Kandidat</div>
                <p class="text-xs text-slate-400 mt-0.5">Profil pasangan calon resmi</p>
            </div>
        </a>
    </div>

</x-layouts.student>
