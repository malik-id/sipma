<x-layouts.admin title="Tambah Mahasiswa" header="Tambah Data Mahasiswa">

    <div class="max-w-3xl space-y-6">

        {{-- OPSI 1: FORM IMPORT EXCEL / CSV LANGSUNG --}}
        <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-6">
            <div class="flex items-center gap-3 border-b border-zinc-100 pb-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-zinc-950 text-amber-400 flex items-center justify-center font-bold shadow-xs">
                    <span class="material-symbols-outlined text-[20px]">upload_file</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900">Opsi 1: Import Massal (Excel / CSV)</h3>
                    <p class="text-xs text-zinc-500">Unggah berkas data mahasiswa (.xlsx, .xls, .csv) untuk input banyak data sekaligus.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.students.import') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Pilih Berkas Excel (.xlsx / .xls) atau CSV</label>
                    <input
                        type="file"
                        name="file"
                        accept=".xlsx,.xls,.csv,.txt"
                        required
                        class="block w-full text-sm text-zinc-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-900 hover:file:bg-amber-100 border border-zinc-300 rounded-lg p-2.5 bg-zinc-50 cursor-pointer"
                    />
                </div>

                <div class="bg-zinc-50 rounded-lg p-3.5 text-xs text-zinc-600 space-y-1.5 border border-zinc-200">
                    <span class="font-bold text-zinc-800">Format Kolom Header (Baris Pertama):</span>
                    <code class="block font-mono bg-white p-2 rounded border border-zinc-300 text-amber-800 text-xs font-bold">
                        NIM, Nama, Email, Program Studi, Angkatan, Semester, No HP
                    </code>
                    <p class="text-[11px] text-zinc-400">Data dengan NIM atau Email yang sudah terdaftar akan otomatis diperbarui.</p>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-amber-400 font-bold text-xs rounded-lg shadow-sm transition">
                        <span class="material-symbols-outlined text-[18px]">upload</span>
                        Unggah &amp; Mulai Import Data
                    </button>
                </div>
            </form>
        </div>

        {{-- Divider --}}
        <div class="relative flex py-2 items-center">
            <div class="flex-grow border-t border-zinc-200"></div>
            <span class="flex-shrink mx-4 text-xs font-bold text-zinc-400 uppercase tracking-widest bg-zinc-100 px-3 py-1 rounded-full">Atau Input Manual</span>
            <div class="flex-grow border-t border-zinc-200"></div>
        </div>

        {{-- OPSI 2: FORM INPUT MANUAL SATU PER SATU --}}
        <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-6">
            <div class="border-b border-zinc-100 pb-3 mb-5">
                <h3 class="text-base font-bold text-zinc-900">Opsi 2: Tambah Mahasiswa Manual (Satu per satu)</h3>
                <p class="text-xs text-zinc-500">Isi formulir di bawah jika hanya ingin menambahkan satu data mahasiswa saja.</p>
            </div>

            <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">NIM *</label>
                        <input
                            type="text"
                            name="nim"
                            value="{{ old('nim') }}"
                            required
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                            placeholder="Contoh: IK2411019"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                            placeholder="Nama Mahasiswa"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Email Google Mahasiswa *</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        placeholder="email.mhs@gmail.com"
                    />
                    <p class="text-xs text-zinc-400 mt-1">Harus email yang akan dipakai mahasiswa untuk login dengan Google OAuth.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Program Studi *</label>
                        <input
                            type="text"
                            name="study_program"
                            value="{{ old('study_program', 'Ilmu Komputer') }}"
                            required
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Tahun Angkatan *</label>
                        <input
                            type="number"
                            name="class_year"
                            value="{{ old('class_year', date('Y')) }}"
                            required
                            min="2000"
                            max="2100"
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Semester Aktif *</label>
                        <input
                            type="number"
                            name="semester"
                            value="{{ old('semester', 1) }}"
                            required
                            min="1"
                            max="14"
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Status Mahasiswa *</label>
                        <select
                            name="student_status"
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            @foreach ($statuses as $st)
                                <option value="{{ $st->value }}" {{ old('student_status') === $st->value ? 'selected' : '' }}>
                                    {{ ucfirst($st->value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">No. WhatsApp / HP</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                            placeholder="08xxxxxxxxxx"
                        />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100">
                    <a href="{{ route('admin.students.index') }}" class="px-4 py-2 text-xs font-bold text-zinc-600 hover:text-zinc-800">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 text-zinc-950 font-bold text-xs rounded-lg hover:bg-amber-400 shadow-sm transition">
                        Simpan Mahasiswa
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-layouts.admin>
