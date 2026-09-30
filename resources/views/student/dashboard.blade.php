<x-layouts.app title="Dashboard Mahasiswa">

    {{-- Top Navigation Bar --}}
    <nav class="bg-slate-900 border-b border-slate-800 text-white sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center font-black text-white shadow-md">
                    H
                </div>
                <div>
                    <div class="text-sm font-bold tracking-wide text-white">SIPMA HIMAKOM</div>
                    <div class="text-[11px] text-slate-400">Portal Pemilihan Mahasiswa</div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden sm:block text-right">
                    <div class="text-xs font-semibold text-white">{{ $user->name }}</div>
                    <div class="text-[11px] text-slate-400 font-mono">{{ $student?->nim ?? $user->email }}</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition" title="Keluar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Welcome Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Halo, {{ $student?->name ?? $user->name }} 👋
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Selamat datang di Sistem Informasi Pemilihan Mahasiswa (SIPMA). Gunakan hak suara dan pantau tahapan pemilihan dengan transparan.
                </p>
            </div>

            @if ($voterRecord && $voterRecord->voter_status->value === 'eligible')
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold shrink-0">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>Terdaftar di DPT (Berhak Memilih)</span>
                </div>
            @elseif ($voterRecord)
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold shrink-0">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <span>Status DPT: {{ ucfirst($voterRecord->voter_status->value) }}</span>
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-medium shrink-0">
                    <span>Belum terdaftar di DPT aktif</span>
                </div>
            @endif
        </div>

        @if ($activeElection)
            {{-- Active Election Hero & Countdown Widget --}}
            <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-900 rounded-3xl p-6 sm:p-10 text-white shadow-xl relative overflow-hidden mb-8">
                {{-- Decorative background glows --}}
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-500/20 border border-blue-400/30 text-blue-200">
                            {{ $activeElection->status->label() }}
                        </span>
                        <span class="text-xs text-blue-300 font-mono">Periode {{ $activeElection->created_at->format('Y') }}</span>
                    </div>

                    <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white mb-3 max-w-3xl">
                        {{ $activeElection->name }}
                    </h2>

                    @if ($activeElection->description)
                        <p class="text-sm text-blue-100 max-w-2xl leading-relaxed mb-6">
                            {{ $activeElection->description }}
                        </p>
                    @endif

                    {{-- Dynamic Countdown Timer Section --}}
                    @if ($phaseInfo && $phaseInfo['target_time'])
                        <div class="mt-6 pt-6 border-t border-white/10"
                             x-data="countdownTimer('{{ $phaseInfo['target_time']->toIso8601String() }}')">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-wider text-blue-300">
                                        {{ $phaseInfo['target_label'] }}
                                    </div>
                                    <div class="text-lg font-bold text-white mt-0.5">
                                        {{ $phaseInfo['target_time']->translatedFormat('l, d F Y — H:i') }} WIB
                                    </div>
                                </div>

                                {{-- Countdown Blocks --}}
                                <div class="flex items-center gap-2 sm:gap-3 font-mono">
                                    <div class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl p-3 sm:p-4 text-center min-w-[64px] sm:min-w-[76px]">
                                        <div class="text-2xl sm:text-3xl font-black text-white" x-text="days">00</div>
                                        <div class="text-[10px] uppercase font-sans text-blue-300 font-semibold tracking-wider mt-1">Hari</div>
                                    </div>
                                    <span class="text-2xl font-bold text-blue-400">:</span>
                                    <div class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl p-3 sm:p-4 text-center min-w-[64px] sm:min-w-[76px]">
                                        <div class="text-2xl sm:text-3xl font-black text-white" x-text="hours">00</div>
                                        <div class="text-[10px] uppercase font-sans text-blue-300 font-semibold tracking-wider mt-1">Jam</div>
                                    </div>
                                    <span class="text-2xl font-bold text-blue-400">:</span>
                                    <div class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl p-3 sm:p-4 text-center min-w-[64px] sm:min-w-[76px]">
                                        <div class="text-2xl sm:text-3xl font-black text-white" x-text="minutes">00</div>
                                        <div class="text-[10px] uppercase font-sans text-blue-300 font-semibold tracking-wider mt-1">Menit</div>
                                    </div>
                                    <span class="text-2xl font-bold text-blue-400">:</span>
                                    <div class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl p-3 sm:p-4 text-center min-w-[64px] sm:min-w-[76px]">
                                        <div class="text-2xl sm:text-3xl font-black text-emerald-400" x-text="seconds">00</div>
                                        <div class="text-[10px] uppercase font-sans text-emerald-300 font-semibold tracking-wider mt-1">Detik</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Timeline Stepper & Milestones --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 mb-8">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Tahapan & Linimasa Pemilihan</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pantau jadwal dan progres resmi setiap tahapan pemilihan secara realtime.</p>
                    </div>
                    <span class="text-xs font-mono font-medium text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                        Jam Server: {{ now()->translatedFormat('H:i:s') }} WIB
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- 1. Pendaftaran --}}
                    @php
                        $isRegActive = now()->between($activeElection->registration_start, $activeElection->registration_end);
                        $isRegPassed = now()->gt($activeElection->registration_end);
                    @endphp
                    <div class="p-4 rounded-xl border {{ $isRegActive ? 'border-amber-400 bg-amber-50/50 ring-2 ring-amber-200' : ($isRegPassed ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 bg-slate-50/50') }} transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold font-mono {{ $isRegActive ? 'text-amber-700' : ($isRegPassed ? 'text-emerald-700' : 'text-slate-500') }}">Tahap 1</span>
                            @if ($isRegActive)
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                            @elseif ($isRegPassed)
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <h4 class="text-sm font-bold text-slate-900">Pendaftaran Calon</h4>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $activeElection->registration_start->translatedFormat('d M') }} - {{ $activeElection->registration_end->translatedFormat('d M Y') }}
                        </p>
                    </div>

                    {{-- 2. Verifikasi Administrasi --}}
                    @php
                        $isVerActive = now()->between($activeElection->verification_start, $activeElection->verification_end);
                        $isVerPassed = now()->gt($activeElection->verification_end);
                    @endphp
                    <div class="p-4 rounded-xl border {{ $isVerActive ? 'border-indigo-400 bg-indigo-50/50 ring-2 ring-indigo-200' : ($isVerPassed ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 bg-slate-50/50') }} transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold font-mono {{ $isVerActive ? 'text-indigo-700' : ($isVerPassed ? 'text-emerald-700' : 'text-slate-500') }}">Tahap 2</span>
                            @if ($isVerActive)
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-ping"></span>
                            @elseif ($isVerPassed)
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <h4 class="text-sm font-bold text-slate-900">Verifikasi Berkas</h4>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $activeElection->verification_start->translatedFormat('d M') }} - {{ $activeElection->verification_end->translatedFormat('d M Y') }}
                        </p>
                    </div>

                    {{-- 3. Pemungutan Suara --}}
                    @php
                        $isVoteActive = now()->between($activeElection->voting_start, $activeElection->voting_end);
                        $isVotePassed = now()->gt($activeElection->voting_end);
                    @endphp
                    <div class="p-4 rounded-xl border {{ $isVoteActive ? 'border-emerald-500 bg-emerald-50/60 ring-2 ring-emerald-300' : ($isVotePassed ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 bg-slate-50/50') }} transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold font-mono {{ $isVoteActive ? 'text-emerald-700 font-black' : ($isVotePassed ? 'text-emerald-700' : 'text-slate-500') }}">Tahap 3</span>
                            @if ($isVoteActive)
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                            @elseif ($isVotePassed)
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <h4 class="text-sm font-bold text-slate-900">Pemungutan Suara</h4>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $activeElection->voting_start->translatedFormat('d M H:i') }} - {{ $activeElection->voting_end->translatedFormat('d M H:i') }}
                        </p>
                    </div>

                    {{-- 4. Pengumuman Hasil --}}
                    @php
                        $isPubPassed = $activeElection->result_publish_at && now()->gte($activeElection->result_publish_at);
                    @endphp
                    <div class="p-4 rounded-xl border {{ $isPubPassed ? 'border-blue-300 bg-blue-50/40' : 'border-slate-200 bg-slate-50/50' }} transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold font-mono text-slate-500">Tahap 4</span>
                            @if ($isPubPassed)
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <h4 class="text-sm font-bold text-slate-900">Publikasi Hasil</h4>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $activeElection->result_publish_at ? $activeElection->result_publish_at->translatedFormat('d M Y H:i') : 'Menyesuaikan' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Candidate Requirements Preview for Students --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Syarat Pendaftaran Pasangan Calon</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Persyaratan dokumen resmi yang perlu disiapkan bagi mahasiswa yang berminat mendaftarkan diri.</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                        {{ $activeElection->requirements->count() }} Dokumen
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($activeElection->requirements as $req)
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between mb-2">
                                <span class="w-6 h-6 rounded-lg bg-white border border-slate-200 font-mono font-bold text-xs flex items-center justify-center text-slate-700">
                                    {{ $loop->iteration }}
                                </span>
                                @if ($req->required)
                                    <span class="text-[10px] uppercase font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">Wajib</span>
                                @else
                                    <span class="text-[10px] uppercase font-bold text-slate-500 bg-slate-200/60 px-2 py-0.5 rounded">Opsional</span>
                                @endif
                            </div>
                            <h4 class="text-sm font-bold text-slate-900">{{ $req->name }}</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $req->description ?: 'Dokumen kelengkapan pendaftaran calon.' }}</p>
                            <div class="mt-3 pt-2 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-400">
                                <span class="uppercase font-semibold text-slate-600">{{ $req->type }}</span>
                                @if ($req->type !== 'text')
                                    <span>Maks {{ number_format(($req->max_file_size ?? 5120) / 1024, 1) }} MB</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                            Belum ada dokumen syarat yang ditentukan panitia.
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 shadow-sm">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Belum Ada Periode Pemilihan Aktif</h2>
                <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto">
                    Saat ini panitia belum membuka periode pemilihan baru. Pantau terus halaman ini atau saluran informasi resmi HIMAKOM.
                </p>
            </div>
        @endif

    </main>

    {{-- Countdown Alpine.js Script Component --}}
    <script>
        function countdownTimer(targetIsoDate) {
            return {
                targetTime: new Date(targetIsoDate).getTime(),
                days: '00',
                hours: '00',
                minutes: '00',
                seconds: '00',
                interval: null,
                init() {
                    this.update();
                    this.interval = setInterval(() => {
                        this.update();
                    }, 1000);
                },
                update() {
                    const now = new Date().getTime();
                    const distance = this.targetTime - now;

                    if (distance <= 0) {
                        this.days = '00';
                        this.hours = '00';
                        this.minutes = '00';
                        this.seconds = '00';
                        if (this.interval) clearInterval(this.interval);
                        return;
                    }

                    const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((distance % (1000 * 60)) / 1000);

                    this.days = String(d).padStart(2, '0');
                    this.hours = String(h).padStart(2, '0');
                    this.minutes = String(m).padStart(2, '0');
                    this.seconds = String(s).padStart(2, '0');
                }
            }
        }
    </script>

</x-layouts.app>
