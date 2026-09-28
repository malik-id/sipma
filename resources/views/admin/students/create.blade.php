<x-layouts.admin title="Tambah Mahasiswa" header="Tambah Mahasiswa Baru">

    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-200 p-6">
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

</x-layouts.admin>
