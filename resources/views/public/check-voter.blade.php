<x-layouts.app title="Cek Daftar Pemilih Tetap (DPT)">

    <div class="min-h-screen bg-slate-50 flex flex-col justify-between">
        {{-- Navbar --}}
        <header class="bg-white border-b border-slate-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center font-bold text-white shadow-sm">
                        H
                    </div>
                    <div>
                        <a href="{{ route('home') }}" class="font-bold text-slate-900 tracking-tight">SIPMA HIMAKOM</a>
                        <p class="text-xs text-slate-500">Portal Pemilihan Mahasiswa</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Dashboard Admin →</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Dashboard Saya →</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        {{-- Main Container --}}
        <main class="flex-1 max-w-3xl w-full mx-auto px-4 sm:px-6 py-10">
            <div class="text-center mb-8">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 mb-3">
                    Pencarian DPT Terbuka
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                    Cek Status Daftar Pemilih (DPT)
                </h1>
                <p class="mt-2 text-sm text-slate-500 max-w-lg mx-auto">
                    Masukkan NIM atau Email Google Anda untuk memeriksa status hak pilih Anda pada pemilihan HIMAKOM.
                </p>
            </div>

            {{-- Form Pencarian --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 mb-6">
                <form method="GET" action="{{ route('check-voter') }}" class="space-y-4">
                    @if ($elections->count() > 1)
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Periode Pemilihan</label>
                            <select
                                name="election_id"
                                class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">NIM atau Email Mahasiswa</label>
                        <div class="relative">
                            <input
                                type="text"
                                name="keyword"
                                value="{{ $keyword }}"
                                required
                                autofocus
                                placeholder="Contoh: IK2411019 atau email@gmail.com"
                                class="w-full pl-11 pr-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            />
                            <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 shadow-sm transition">
                        Periksa Status Sekarang
                    </button>
                </form>
            </div>

            {{-- Hasil Pencarian --}}
            @if ($searched)
                @if (! $student)
                    {{-- Mahasiswa Tidak Ditemukan --}}
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-6 text-center">
                        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-rose-900">Data Mahasiswa Tidak Ditemukan</h3>
                        <p class="text-sm text-rose-700 mt-1 max-w-md mx-auto">
                            NIM atau email <span class="font-mono font-semibold">{{ $keyword }}</span> belum terdaftar pada pangkalan data mahasiswa Fakultas Ilmu Komputer.
                        </p>
                        <p class="text-xs text-rose-600 mt-3">Silakan hubungi panitia pemilihan untuk verifikasi identitas Anda.</p>
                    </div>
                @else
                    {{-- Mahasiswa Ditemukan --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        {{-- Status Banner --}}
                        <div class="p-6 border-b border-slate-100 flex items-center gap-4 {{ $voter && $voter->voter_status->value === 'eligible' ? 'bg-emerald-50/60' : 'bg-amber-50/60' }}">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {{ $voter && $voter->voter_status->value === 'eligible' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                @if ($voter && $voter->voter_status->value === 'eligible')
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wider {{ $voter && $voter->voter_status->value === 'eligible' ? 'text-emerald-700' : 'text-amber-700' }}">
                                    Status Hak Pilih
                                </div>
                                <h3 class="text-lg font-bold text-slate-900">
                                    @if ($voter && $voter->voter_status->value === 'eligible')
                                        Terdaftar sebagai Pemilih (Eligible)
                                    @elseif ($voter && $voter->voter_status->value === 'suspended')
                                        Hak Pilih Ditangguhkan
                                    @elseif ($voter)
                                        Tidak Memenuhi Syarat Pemilih
                                    @else
                                        Belum Masuk ke Daftar Pemilih (DPT)
                                    @endif
                                </h3>
                                @if ($selectedElection)
                                    <p class="text-xs text-slate-500 mt-0.5">Pemilihan: {{ $selectedElection->name }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Rincian Data --}}
                        <div class="p-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                                <div>
                                    <dt class="text-xs text-slate-400 font-medium uppercase tracking-wider">Nama Lengkap</dt>
                                    <dd class="text-slate-900 font-semibold mt-0.5">{{ $student->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-400 font-medium uppercase tracking-wider">NIM</dt>
                                    <dd class="text-slate-900 font-mono font-semibold mt-0.5">{{ $student->nim }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-400 font-medium uppercase tracking-wider">Program Studi</dt>
                                    <dd class="text-slate-900 mt-0.5">{{ $student->study_program }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-400 font-medium uppercase tracking-wider">Angkatan / Semester</dt>
                                    <dd class="text-slate-900 mt-0.5">{{ $student->class_year }} / Semester {{ $student->semester }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-400 font-medium uppercase tracking-wider">Status Akademik</dt>
                                    <dd class="text-slate-900 mt-0.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $student->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ ucfirst($student->student_status->value) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-slate-400 font-medium uppercase tracking-wider">Email Terdaftar</dt>
                                    <dd class="text-slate-900 text-xs font-mono mt-0.5">{{ $student->email }}</dd>
                                </div>
                            </dl>

                            @if ($voter && $voter->voter_status->value === 'eligible')
                                <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                                    <p class="text-xs text-slate-500">Anda siap menggunakan hak suara saat periode pemungutan suara dibuka.</p>
                                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                                        Masuk ke SIPMA
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
            SIPMA &copy; {{ date('Y') }} HIMAKOM — Fakultas Ilmu Komputer. Semua hak dilindungi.
        </footer>
    </div>

</x-layouts.app>
