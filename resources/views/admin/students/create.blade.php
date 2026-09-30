<x-layouts.admin title="Tambah Mahasiswa" header="Tambah Data Mahasiswa">

    <div class="max-w-3xl space-y-6">

        {{-- OPSI 1: FORM IMPORT EXCEL / CSV LANGSUNG --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Opsi 1: Import Massal (Excel / CSV)</h3>
                    <p class="text-xs text-slate-500">Unggah berkas data mahasiswa (.xlsx, .xls, .csv) untuk input banyak data sekaligus.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.students.import') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Pilih Berkas Excel (.xlsx / .xls) atau CSV</label>
                    <input
                        type="file"
                        name="file"
                        accept=".xlsx,.xls,.csv,.txt"
                        required
                        class="block w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 border border-slate-300 rounded-lg p-2.5 bg-slate-50 cursor-pointer"
                    />
                </div>

                <div class="bg-slate-50 rounded-lg p-3.5 text-xs text-slate-600 space-y-1.5 border border-slate-200">
                    <span class="font-bold text-slate-800">Format Kolom Header (Baris Pertama):</span>
                    <code class="block font-mono bg-white p-2 rounded border border-slate-300 text-blue-700 text-xs font-bold">
                        NIM, Nama, Email, Program Studi, Angkatan, Semester, No HP
                    </code>
                    <p class="text-[11px] text-slate-400">Data dengan NIM atau Email yang sudah terdaftar akan otomatis diperbarui.</p>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Unggah &amp; Mulai Import Data
                    </button>
                </div>
            </form>
        </div>

        {{-- Divider --}}
        <div class="relative flex py-2 items-center">
            <div class="flex-grow border-t border-slate-200"></div>
            <span class="flex-shrink mx-4 text-xs font-bold text-slate-400 uppercase tracking-widest bg-slate-100 px-3 py-1 rounded-full">Atau Input Manual</span>
            <div class="flex-grow border-t border-slate-200"></div>
        </div>

        {{-- OPSI 2: FORM INPUT MANUAL SATU PER SATU --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h3 class="text-base font-bold text-slate-900">Opsi 2: Tambah Mahasiswa Manual (Satu per satu)</h3>
                <p class="text-xs text-slate-500">Isi formulir di bawah jika hanya ingin menambahkan satu data mahasiswa saja.</p>
            </div>

            <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">NIM *</label>
                        <input
                            type="text"
                            name="nim"
                            value="{{ old('nim') }}"
                            required
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: IK2411019"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nama Mahasiswa"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Google Mahasiswa *</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="email.mhs@gmail.com"
                    />
                    <p class="text-xs text-slate-400 mt-1">Harus email yang akan dipakai mahasiswa untuk login dengan Google OAuth.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Program Studi *</label>
                        <input
                            type="text"
                            name="study_program"
                            value="{{ old('study_program', 'Ilmu Komputer') }}"
                            required
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Tahun Angkatan *</label>
                        <input
                            type="number"
                            name="class_year"
                            value="{{ old('class_year', date('Y')) }}"
                            required
                            min="2000"
                            max="2100"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Semester Aktif *</label>
                        <input
                            type="number"
                            name="semester"
                            value="{{ old('semester', 1) }}"
                            required
                            min="1"
                            max="14"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Status Mahasiswa *</label>
                        <select
                            name="student_status"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($statuses as $st)
                                <option value="{{ $st->value }}" {{ old('student_status') === $st->value ? 'selected' : '' }}>
                                    {{ ucfirst($st->value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">No. WhatsApp / HP</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="08xxxxxxxxxx"
                        />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.students.index') }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 shadow-sm transition">
                        Simpan Mahasiswa
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-layouts.admin>
