<x-layouts.admin title="Tambah Pasangan Calon Resmi">

    {{-- Breadcrumb & Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.candidates.index', ['election_id' => $activeElection?->id]) }}" class="text-xs text-zinc-500 hover:text-zinc-900 flex items-center gap-1.5 mb-2 font-bold transition">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Daftar Calon
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Pendaftaran Calon Langsung (Admin)</h1>
            <p class="text-xs sm:text-sm text-zinc-500 mt-1">Daftarkan pasangan calon ketua dan wakil ketua langsung ke sistem pemilihan tanpa melalui alur verifikasi berkas mandiri.</p>
        </div>
    </div>

    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-zinc-100 bg-zinc-50/50">
                <h2 class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500 text-[20px]">how_to_reg</span>
                    Formulir Calon Resmi
                </h2>
                <p class="text-xs text-zinc-400 mt-0.5">Isi data mahasiswa yang akan didaftarkan sebagai pasangan calon resmi.</p>
            </div>

            <form method="POST" action="{{ route('admin.candidates.store') }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                @csrf

                {{-- Periode Pemilihan --}}
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                        Periode Pemilihan <span class="text-rose-500">*</span>
                    </label>
                    <select name="election_id" class="w-full text-xs sm:text-sm rounded-xl border-zinc-300 focus:border-amber-500 focus:ring-amber-500 bg-zinc-50 font-bold" required>
                        @foreach ($elections as $el)
                            <option value="{{ $el->id }}" {{ old('election_id', $activeElection?->id) == $el->id ? 'selected' : '' }}>
                                {{ $el->name }} (Status: {{ ucfirst($el->status->value) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Pasangan Calon (Ketua & Wakil) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2 border-t border-zinc-100">
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            Calon Ketua <span class="text-rose-500">*</span>
                        </label>
                        <select name="chairman_student_id" class="w-full text-xs sm:text-sm rounded-xl border-zinc-300 focus:border-amber-500 focus:ring-amber-500 bg-zinc-50 font-medium" required>
                            <option value="">-- Pilih Mahasiswa Calon Ketua --</option>
                            @foreach ($students as $stu)
                                <option value="{{ $stu->id }}" {{ old('chairman_student_id') == $stu->id ? 'selected' : '' }}>
                                    {{ $stu->nim }} — {{ $stu->name }} ({{ $stu->study_program }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            Calon Wakil Ketua (Opsional)
                        </label>
                        <select name="vice_chairman_student_id" class="w-full text-xs sm:text-sm rounded-xl border-zinc-300 focus:border-amber-500 focus:ring-amber-500 bg-zinc-50 font-medium">
                            <option value="">-- Pilih Mahasiswa Calon Wakil (Jika Ada) --</option>
                            @foreach ($students as $stu)
                                <option value="{{ $stu->id }}" {{ old('vice_chairman_student_id') == $stu->id ? 'selected' : '' }}>
                                    {{ $stu->nim }} — {{ $stu->name }} ({{ $stu->study_program }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Nomor Urut & Nomor Kontak --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2 border-t border-zinc-100">
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            Nomor Urut
                        </label>
                        <input type="number" name="candidate_number" value="{{ old('candidate_number') }}" min="1" placeholder="Contoh: 1"
                            class="w-full text-xs sm:text-sm rounded-xl border-zinc-300 focus:border-amber-500 focus:ring-amber-500 bg-zinc-50 font-bold" />
                        <p class="text-[11px] text-zinc-400 mt-1">Dapat diatur nanti jika belum diundi.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            No. WhatsApp Ketua
                        </label>
                        <input type="text" name="chairman_phone" value="{{ old('chairman_phone') }}" placeholder="08xxxxxxxxxx"
                            class="w-full text-xs sm:text-sm rounded-xl border-zinc-300 focus:border-amber-500 focus:ring-amber-500 bg-zinc-50" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            No. WhatsApp Wakil
                        </label>
                        <input type="text" name="vice_chairman_phone" value="{{ old('vice_chairman_phone') }}" placeholder="08xxxxxxxxxx"
                            class="w-full text-xs sm:text-sm rounded-xl border-zinc-300 focus:border-amber-500 focus:ring-amber-500 bg-zinc-50" />
                    </div>
                </div>

                {{-- Pas Foto --}}
                <div class="pt-2 border-t border-zinc-100">
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                        Foto Resmi Pasangan Calon (JPG / PNG)
                    </label>
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg"
                        class="block w-full text-xs text-zinc-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100 border border-zinc-300 rounded-xl p-2 bg-zinc-50" />
                    <p class="text-[11px] text-zinc-400 mt-1">Gunakan foto formal berdampingan dengan orientasi vertikal (Maksimal 3MB).</p>
                </div>

                {{-- Visi --}}
                <div class="pt-2 border-t border-zinc-100">
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                        Visi Pasangan Calon <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="vision" rows="3" required placeholder="Tuliskan rumusan visi pasangan calon..."
                        class="w-full text-xs sm:text-sm rounded-xl border-zinc-300 focus:border-amber-500 focus:ring-amber-500 bg-zinc-50 leading-relaxed">{{ old('vision') }}</textarea>
                </div>

                {{-- Misi --}}
                <div class="pt-2 border-t border-zinc-100">
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                        Misi Pasangan Calon <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="mission" rows="4" required placeholder="Tuliskan misi calon (Satu butir misi per baris)..."
                        class="w-full text-xs sm:text-sm rounded-xl border-zinc-300 focus:border-amber-500 focus:ring-amber-500 bg-zinc-50 leading-relaxed">{{ old('mission') }}</textarea>
                    <p class="text-[11px] text-zinc-400 mt-1">Tekan Enter untuk memisahkan setiap poin misi.</p>
                </div>

                {{-- Submit Actions --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-zinc-200">
                    <a href="{{ route('admin.candidates.index', ['election_id' => $activeElection?->id]) }}"
                        class="px-4 py-2.5 text-xs font-bold text-zinc-600 hover:text-zinc-900 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-amber-400 font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        Daftarkan Calon Resmi
                    </button>
                </div>

            </form>
        </div>
    </div>

</x-layouts.admin>
