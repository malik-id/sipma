<x-layouts.app title="Cek Daftar Pemilih Tetap (DPT)">

    <div class="min-h-screen bg-zinc-50 flex flex-col justify-between">
        {{-- Navbar --}}
        <header class="bg-white border-b border-zinc-200 sticky top-0 z-30">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo UMB Palopo" class="w-9 h-9 object-contain" />
                    <div>
                        <span class="font-bold text-zinc-900 tracking-tight text-sm block">SIPMA UMB</span>
                        <p class="text-[10px] text-zinc-500 leading-none">Universitas Mega Buana Palopo</p>
                    </div>
                </a>

                {{-- Desktop Navigation --}}
                <nav class="hidden sm:flex items-center gap-2">
                    <a href="{{ route('home') }}" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100 transition flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-lg">home</span>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('public.candidates.index') }}" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100 transition flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-lg">badge</span>
                        <span>Kandidat</span>
                    </a>
                    <a href="{{ route('public.results.index') }}" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-lg hover:bg-zinc-100 transition flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-lg">leaderboard</span>
                        <span>Hasil</span>
                    </a>
                    @auth
                        <a href="{{ auth()->user()->canAccessAdminPanel() ? route('admin.dashboard') : route('dashboard') }}" class="px-3.5 py-1.5 bg-zinc-900 text-amber-400 hover:bg-zinc-800 text-xs font-bold rounded-lg shadow-xs transition flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-lg">dashboard</span>
                            <span>Panel</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-zinc-950 bg-amber-500 hover:bg-amber-400 transition shadow-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-lg">login</span>
                            <span>Masuk</span>
                        </a>
                    @endauth
                </nav>

                {{-- Mobile Action + Hamburger Toggle --}}
                <div class="flex items-center gap-2 sm:hidden">
                    @auth
                        <a href="{{ auth()->user()->canAccessAdminPanel() ? route('admin.dashboard') : route('dashboard') }}" class="px-2.5 py-1.5 bg-zinc-900 text-amber-400 text-xs font-bold rounded-lg shadow-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">dashboard</span>
                            <span>Panel</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-zinc-950 bg-amber-500 hover:bg-amber-400 transition shadow-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">login</span>
                            <span>Masuk</span>
                        </a>
                    @endauth

                    <button type="button" onclick="togglePublicMobileMenu()" class="p-2 text-zinc-700 hover:text-zinc-950 hover:bg-zinc-100 rounded-lg transition" aria-label="Menu">
                        <span id="public-menu-icon" class="material-symbols-outlined text-2xl">menu</span>
                    </button>
                </div>
            </div>

            {{-- Mobile Dropdown Menu Drawer --}}
            <div id="public-mobile-menu" class="hidden sm:hidden border-t border-zinc-200 bg-white/95 backdrop-blur-sm px-4 py-3 space-y-1 shadow-md">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                    <span class="material-symbols-outlined text-xl text-amber-500">home</span>
                    <span>Beranda Utama</span>
                </a>
                <a href="{{ route('public.candidates.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                    <span class="material-symbols-outlined text-xl text-amber-500">badge</span>
                    <span>Daftar Pasangan Calon</span>
                </a>
                <a href="{{ route('public.results.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-amber-50 hover:text-amber-700 transition">
                    <span class="material-symbols-outlined text-xl text-amber-500">leaderboard</span>
                    <span>Hasil Perolehan Suara</span>
                </a>
            </div>
        </header>

        <script>
            function togglePublicMobileMenu() {
                const menu = document.getElementById('public-mobile-menu');
                const icon = document.getElementById('public-menu-icon');
                if (menu) {
                    const isHidden = menu.classList.contains('hidden');
                    menu.classList.toggle('hidden');
                    if (icon) {
                        icon.textContent = isHidden ? 'close' : 'menu';
                    }
                }
            }
        </script>

        {{-- Main Container --}}
        <main class="flex-1 max-w-2xl w-full mx-auto px-4 sm:px-6 py-10">
            <div class="text-center mb-8">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 mb-3">
                    Pencarian DPT Terbuka
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 tracking-tight">
                    Cek Status Hak Pilih (DPT)
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-zinc-500 max-w-md mx-auto">
                    Masukkan NIM atau Email Google Anda untuk memverifikasi apakah Anda berhak memilih pada pemilihan mahasiswa.
                </p>
            </div>

            {{-- Form Pencarian --}}
            <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-6 sm:p-8 mb-6">
                <form method="GET" action="{{ route('check-voter') }}" class="space-y-4">
                    @if ($elections->count() > 1)
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">Periode Pemilihan</label>
                            <select
                                name="election_id"
                                class="w-full px-3.5 py-2.5 text-sm bg-zinc-50 border border-zinc-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                @foreach ($elections as $el)
                                    <option value="{{ $el->id }}" {{ ($selectedElection?->id == $el->id) ? 'selected' : '' }}>
                                        {{ $el->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        @if ($selectedElection)
                            <input type="hidden" name="election_id" value="{{ $selectedElection->id }}" />
                        @endif
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">NIM atau Email Mahasiswa</label>
                        <div class="relative">
                            <input
                                type="text"
                                name="keyword"
                                value="{{ $keyword }}"
                                required
                                autofocus
                                placeholder="Contoh: IK2411019 atau nama@gmail.com"
                                class="w-full pl-11 pr-4 py-3 text-sm bg-zinc-50 border border-zinc-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                            />
                            <span class="material-symbols-outlined text-zinc-400 absolute left-3.5 top-3.5 text-xl">search</span>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3 bg-zinc-950 hover:bg-zinc-800 text-amber-400 font-bold text-sm rounded-xl shadow-sm transition flex items-center justify-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-base">how_to_reg</span>
                        <span>Periksa Status Sekarang</span>
                    </button>
                </form>
            </div>

            {{-- Hasil Pencarian --}}
            @php
                $contactPhone = \App\Models\SystemSetting::get('contact_phone', '081234567890');
                $contactEmail = \App\Models\SystemSetting::get('contact_email', 'kpu@megabuana.ac.id');
                $cleanPhone = preg_replace('/[^0-9]/', '', (string) $contactPhone);
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }
            @endphp

            @if ($searched)
                @if (! $student)
                    {{-- Mahasiswa Tidak Ditemukan --}}
                    @php
                        $waNotFoundMsg = "Halo Panitia KPU SIPMA UMB, saya mengecek status DPT dengan keyword (" . $keyword . ") namun data mahasiswa tidak ditemukan. Mohon bantuannya untuk verifikasi data pemilih saya.";
                        $waNotFoundUrl = !empty($cleanPhone) ? "https://wa.me/{$cleanPhone}?text=" . rawurlencode($waNotFoundMsg) : null;
                    @endphp
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-6 text-center shadow-xs">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-2xl">person_off</span>
                        </div>
                        <h3 class="text-sm font-bold text-rose-900">Data Mahasiswa Tidak Ditemukan</h3>
                        <p class="text-xs text-rose-700 mt-1 max-w-md mx-auto">
                            NIM atau email <span class="font-mono font-semibold">{{ $keyword }}</span> belum terdaftar di database master mahasiswa.
                        </p>
                        
                        <div class="mt-4 pt-4 border-t border-rose-100/80 flex flex-col sm:flex-row items-center justify-center gap-2">
                            @if ($waNotFoundUrl)
                                <a href="{{ $waNotFoundUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition shadow-xs">
                                    <span class="material-symbols-outlined text-base">chat</span>
                                    <span>Hubungi Admin via WhatsApp</span>
                                </a>
                            @endif
                            @if ($contactEmail)
                                <a href="mailto:{{ $contactEmail }}?subject={{ rawurlencode('Kendala Cek DPT SIPMA: ' . $keyword) }}&body={{ rawurlencode($waNotFoundMsg) }}"
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white hover:bg-rose-50 text-rose-900 border border-rose-200 rounded-xl text-xs font-semibold transition">
                                    <span class="material-symbols-outlined text-base text-rose-600">mail</span>
                                    <span>Kirim Email</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- Mahasiswa Ditemukan --}}
                    @php
                        $isEligible = $voter && $voter->voter_status->value === 'eligible';
                        $waIneligibleMsg = "Halo Panitia KPU SIPMA UMB, nama saya " . $student->name . " (NIM: " . $student->nim . ") mengecek DPT pada pemilihan " . ($selectedElection?->name ?? 'Mahasiswa') . " dengan status: " . ($voter ? $voter->voter_status->value : 'Belum masuk DPT') . ". Mohon bantuannya untuk verifikasi hak pilih saya.";
                        $waIneligibleUrl = !empty($cleanPhone) ? "https://wa.me/{$cleanPhone}?text=" . rawurlencode($waIneligibleMsg) : null;
                    @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden">
                        {{-- Status Banner --}}
                        <div class="p-5 sm:p-6 border-b border-zinc-100 flex items-center gap-4 {{ $isEligible ? 'bg-emerald-50/70' : 'bg-amber-50/70' }}">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 {{ $isEligible ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                @if ($isEligible)
                                    <span class="material-symbols-outlined text-2xl">verified</span>
                                @else
                                    <span class="material-symbols-outlined text-2xl">info</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider {{ $isEligible ? 'text-emerald-800' : 'text-amber-800' }} block">
                                    Status Hak Suara
                                </span>
                                <h3 class="text-base font-bold text-zinc-900 mt-0.5">
                                    @if ($isEligible)
                                        Terdaftar sebagai Pemilih (Eligible)
                                    @elseif ($voter && $voter->voter_status->value === 'suspended')
                                        Hak Pilih Ditangguhkan
                                    @elseif ($voter)
                                        Tidak Memenuhi Syarat Pemilih
                                    @else
                                        Belum Masuk Daftar Pemilih Tetap (DPT)
                                    @endif
                                </h3>
                                @if ($selectedElection)
                                    <p class="text-xs text-zinc-500 mt-0.5">Pemilihan: {{ $selectedElection->name }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Rincian Data Mahasiswa --}}
                        <div class="p-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-xs sm:text-sm">
                                <div>
                                    <dt class="text-[11px] text-zinc-400 font-semibold uppercase tracking-wider">Nama Mahasiswa</dt>
                                    <dd class="text-zinc-900 font-bold mt-0.5">{{ $student->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] text-zinc-400 font-semibold uppercase tracking-wider">NIM</dt>
                                    <dd class="text-zinc-900 font-mono font-bold mt-0.5">{{ $student->nim }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] text-zinc-400 font-semibold uppercase tracking-wider">Program Studi</dt>
                                    <dd class="text-zinc-800 mt-0.5">{{ $student->study_program }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] text-zinc-400 font-semibold uppercase tracking-wider">Angkatan / Semester</dt>
                                    <dd class="text-zinc-800 mt-0.5">{{ $student->class_year }} / Semester {{ $student->semester }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] text-zinc-400 font-semibold uppercase tracking-wider">Status Akademik</dt>
                                    <dd class="mt-0.5">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold {{ $student->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ ucfirst($student->student_status->value) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] text-zinc-400 font-semibold uppercase tracking-wider">Email Terdaftar</dt>
                                    <dd class="text-zinc-800 text-xs font-mono mt-0.5">{{ $student->email }}</dd>
                                </div>
                            </dl>

                            @if ($isEligible)
                                <div class="mt-6 pt-5 border-t border-zinc-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                                    <p class="text-xs text-zinc-500">Anda berhak menggunakan hak pilih saat pemungutan suara dibuka.</p>
                                    <a href="{{ route('login') }}" class="w-full sm:w-auto text-center px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5">
                                        <span class="material-symbols-outlined text-sm">login</span>
                                        <span>Masuk &amp; Voting</span>
                                    </a>
                                </div>
                            @else
                                <div class="mt-6 pt-5 border-t border-zinc-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-amber-50/50 p-4 rounded-xl border border-amber-100">
                                    <div class="text-xs text-amber-900">
                                        <strong>Merasa berhak memilih namun belum terdaftar di DPT?</strong>
                                        <p class="text-amber-700 text-[11px] mt-0.5">Hubungi panitia KPU Mahasiswa untuk konfirmasi dan penetapan hak pilih Anda.</p>
                                    </div>
                                    @if ($waIneligibleUrl)
                                        <a href="{{ $waIneligibleUrl }}" target="_blank" rel="noopener noreferrer"
                                           class="w-full sm:w-auto shrink-0 text-center px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5">
                                            <span class="material-symbols-outlined text-sm">chat</span>
                                            <span>Chat Panitia WA</span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif

            {{-- Helpdesk Banner --}}
            <div class="mt-8 p-4 sm:p-5 rounded-2xl bg-white border border-zinc-200 text-left shadow-2xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-xl">support_agent</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900">Pusat Bantuan &amp; Helpdesk Pemilihan</h4>
                        <p class="text-[11px] text-zinc-500 mt-0.5">
                            Ada pertanyaan seputar DPT, pendaftaran calon, atau tata cara pemungutan suara?
                        </p>
                    </div>
                </div>

                @if (!empty($cleanPhone))
                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ rawurlencode('Halo Panitia KPU SIPMA UMB, saya ingin menanyakan informasi seputar pemilihan mahasiswa.') }}"
                       target="_blank" rel="noopener noreferrer"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-xl text-xs font-bold transition shrink-0">
                        <span class="material-symbols-outlined text-sm text-emerald-400">chat</span>
                        <span>Hubungi Helpdesk</span>
                    </a>
                @endif
            </div>

        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-zinc-200 py-6 text-center text-xs text-zinc-400">
            SIPMA &copy; {{ date('Y') }} Universitas Mega Buana Palopo. Seluruh hak cipta dilindungi.
        </footer>
    </div>

</x-layouts.app>
