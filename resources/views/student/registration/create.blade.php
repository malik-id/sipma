<x-layouts.student title="Daftar Bakal Calon">

    <div class="mb-6">
        <a href="{{ route('registration.index') }}" class="text-xs text-slate-500 hover:text-slate-800 flex items-center gap-1 mb-3">
            ← Kembali ke Daftar Pendaftaran
        </a>
        <h1 class="text-xl font-bold text-slate-900">Formulir Pendaftaran Bakal Calon</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $election->name }}</p>
        <p class="text-xs text-slate-400">Batas pendaftaran: <strong>{{ $election->registration_end->translatedFormat('d F Y, H:i') }}</strong> WITA</p>
    </div>

    {{-- Info Persyaratan --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 text-sm text-amber-800">
        <strong>Persyaratan Bakal Calon:</strong> Mahasiswa aktif, semester 3–5, dan tidak terdaftar pada pasangan lain dalam pemilihan ini.
        Ketua dan Wakil harus merupakan mahasiswa yang berbeda.
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('registration.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="election_id" value="{{ $election->id }}" />

            {{-- Data Ketua (otomatis dari user login) --}}
            <div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">
                    Data Ketua (Anda)
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Ketua</label>
                        <div class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 font-medium">
                            {{ auth()->user()->student->name }}
                        </div>
                        <p class="text-xs text-slate-400 mt-1">NIM: {{ auth()->user()->student->nim }} | Semester {{ auth()->user()->student->semester }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">No. WhatsApp Ketua *</label>
                        <input type="text" name="chairman_phone" value="{{ old('chairman_phone') }}" required
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="08xxxxxxxxxx" />
                    </div>
                </div>
            </div>

            {{-- Data Wakil --}}
            <div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">
                    Data Wakil Ketua
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">NIM atau Email Wakil *</label>
                        <input type="text" name="vice_lookup" value="{{ old('vice_lookup') }}"
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: IK2411020 atau email@gmail.com" />
                        <p class="text-xs text-slate-400 mt-1">Sistem akan otomatis memverifikasi kelayakan wakil.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">No. WhatsApp Wakil</label>
                        <input type="text" name="vice_chairman_phone" value="{{ old('vice_chairman_phone') }}"
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="08xxxxxxxxxx" />
                    </div>
                </div>
            </div>

            {{-- Visi & Misi --}}
            <div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">
                    Visi, Misi &amp; Program Kerja
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Visi</label>
                        <textarea name="vision" rows="3"
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            placeholder="Tuliskan visi pasangan calon Anda...">{{ old('vision') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Misi</label>
                        <textarea name="mission_text" rows="5"
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            placeholder="Tuliskan setiap poin misi dalam baris baru...">{{ old('mission_text') }}</textarea>
                        <p class="text-xs text-slate-400 mt-1">Satu baris = satu poin misi.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Program Kerja Unggulan</label>
                        <textarea name="programs_text" rows="5"
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            placeholder="Format: Nama Program | Deskripsi singkat (satu baris per program)">{{ old('programs_text') }}</textarea>
                        <p class="text-xs text-slate-400 mt-1">Format: <code class="bg-slate-100 px-1 rounded">Nama Program | Deskripsi</code>. Satu baris = satu program.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('registration.index') }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">Batal</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-sm transition">
                    Simpan sebagai Draft
                </button>
            </div>
        </form>
    </div>

</x-layouts.student>
