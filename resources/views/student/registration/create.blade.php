<x-layouts.student title="Pendaftaran Bakal Calon">

    <div class="mb-6">
        <a href="{{ route('registration.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-zinc-500 hover:text-zinc-900 transition mb-3">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <span>Kembali ke Riwayat Pendaftaran</span>
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight">Formulir Pendaftaran Bakal Calon</h1>
                <p class="text-xs sm:text-sm text-zinc-500 mt-0.5">Pemilihan: <strong class="text-zinc-800">{{ $election->name }}</strong></p>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-xs font-bold text-amber-700 w-fit">
                <span class="material-symbols-outlined text-base text-amber-600">timer</span>
                <span>Batas: {{ $election->registration_end->translatedFormat('d F Y, H:i') }} WITA</span>
            </div>
        </div>
    </div>

    {{-- Info Panduan Konteks Pencalonan --}}
    <div class="bg-zinc-950 text-white rounded-2xl p-5 sm:p-6 mb-6 border border-zinc-850 shadow-xs">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-zinc-950 flex items-center justify-center font-bold shrink-0 shadow-xs">
                <span class="material-symbols-outlined text-2xl">how_to_reg</span>
            </div>
            <div class="space-y-1">
                <h3 class="font-bold text-sm sm:text-base text-white">Struktur Pencalonan Pasangan Calon</h3>
                <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed">
                    Sebagai pemilik akun yang mendaftar, Anda secara otomatis ditetapkan sebagai <strong class="text-amber-400">Calon Ketua</strong>. Selanjutnya, silakan pilih dan masukkan data rekan mahasiswa aktif UMB Palopo yang bersedia mendampingi Anda sebagai <strong class="text-amber-400">Calon Wakil Ketua</strong>.
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-5 sm:p-8">
        <form method="POST" action="{{ route('registration.store') }}" class="space-y-8">
            @csrf
            <input type="hidden" name="election_id" value="{{ $election->id }}" />

            {{-- 1. Data Ketua (User Login) --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-amber-500 text-zinc-950 flex items-center justify-center text-xs font-bold">1</span>
                        <h2 class="text-sm font-bold text-zinc-900 uppercase tracking-wider">Calon Ketua (Anda)</h2>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Mahasiswa Aktif Terverifikasi
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 mb-1">Nama Lengkap Ketua</label>
                        <div class="px-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 font-bold flex items-center justify-between">
                            <span>{{ auth()->user()->student->name }}</span>
                            <span class="text-xs font-mono text-zinc-400 font-normal">NIM: {{ auth()->user()->student->nim }}</span>
                        </div>
                        <p class="text-[11px] text-zinc-400 mt-1">Program Studi: {{ auth()->user()->student->study_program ?? 'Informatika' }} &bull; Semester {{ auth()->user()->student->semester }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">No. WhatsApp / Kontak Aktif Ketua *</label>
                        <input type="text" name="chairman_phone" value="{{ old('chairman_phone') }}" required
                            class="w-full px-3.5 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            placeholder="Contoh: 081234567890" />
                        <p class="text-[11px] text-zinc-400 mt-1">Digunakan panitia untuk konfirmasi berkas dan jadwal verifikasi.</p>
                    </div>
                </div>
            </div>

            {{-- 2. Data Wakil Ketua --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-zinc-900 text-amber-400 flex items-center justify-center text-xs font-bold">2</span>
                        <h2 class="text-sm font-bold text-zinc-900 uppercase tracking-wider">Calon Wakil Ketua (Pasangan Anda)</h2>
                    </div>
                    <span class="text-xs text-zinc-400">Wajib Mahasiswa Aktif</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">NIM atau Email Google Wakil *</label>
                        <input type="text" name="vice_lookup" value="{{ old('vice_lookup') }}" required
                            class="w-full px-3.5 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            placeholder="Ketik NIM atau email rekan mahasiswa..." />
                        <p class="text-[11px] text-zinc-400 mt-1">Sistem akan memvalidasi keaktifan dan memastikan calon belum terdaftar di pasangan lain.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">No. WhatsApp Wakil</label>
                        <input type="text" name="vice_chairman_phone" value="{{ old('vice_chairman_phone') }}"
                            class="w-full px-3.5 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            placeholder="Contoh: 089876543210" />
                        <p class="text-[11px] text-zinc-400 mt-1">Kontak alternatif untuk koordinasi panitia.</p>
                    </div>
                </div>
            </div>

            {{-- 3. Visi & Misi --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-zinc-100">
                    <span class="w-6 h-6 rounded-full bg-zinc-900 text-amber-400 flex items-center justify-center text-xs font-bold">3</span>
                    <h2 class="text-sm font-bold text-zinc-900 uppercase tracking-wider">Visi, Misi &amp; Gagasan Program</h2>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">Visi Pasangan Calon</label>
                        <textarea name="vision" rows="3"
                            class="w-full px-3.5 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 resize-none"
                            placeholder="Tuliskan gagasan besar dan arah kepemimpinan pasangan Anda...">{{ old('vision') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">Misi Pasangan Calon</label>
                        <textarea name="mission_text" rows="4"
                            class="w-full px-3.5 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 resize-none"
                            placeholder="Tuliskan butir-butir misi. Tekan Enter untuk butir misi baru...">{{ old('mission_text') }}</textarea>
                        <p class="text-[11px] text-zinc-400 mt-1">Setiap baris baru akan dihitung sebagai satu butir misi resmi.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1">Program Kerja Unggulan</label>
                        <textarea name="programs_text" rows="4"
                            class="w-full px-3.5 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 resize-none"
                            placeholder="Format: Nama Program | Deskripsi program">{{ old('programs_text') }}</textarea>
                        <p class="text-[11px] text-zinc-400 mt-1">Format: <code class="bg-zinc-100 text-zinc-700 px-1 py-0.5 rounded font-mono text-[10px]">Nama Program | Ringkasan Deskripsi</code> (satu program per baris).</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-5 border-t border-zinc-100">
                <a href="{{ route('registration.index') }}" class="px-5 py-2.5 text-xs font-bold text-zinc-600 hover:text-zinc-900 text-center transition">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-amber-500 text-zinc-950 text-xs sm:text-sm font-bold rounded-xl hover:bg-amber-400 shadow-xs transition cursor-pointer">
                    <span class="material-symbols-outlined text-lg">save</span>
                    <span>Simpan sebagai Draft</span>
                </button>
            </div>
        </form>
    </div>

</x-layouts.student>
