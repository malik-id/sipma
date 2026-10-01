<x-layouts.admin title="Edit Mahasiswa" header="Edit Data Mahasiswa">

    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-zinc-200 p-6">
        <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">NIM *</label>
                    <input
                        type="text"
                        name="nim"
                        value="{{ old('nim', $student->nim) }}"
                        required
                        class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $student->name) }}"
                        required
                        class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    />
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Email Google Mahasiswa *</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $student->email) }}"
                    required
                    class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Program Studi *</label>
                    <input
                        type="text"
                        name="study_program"
                        value="{{ old('study_program', $student->study_program) }}"
                        required
                        class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">Tahun Angkatan *</label>
                    <input
                        type="number"
                        name="class_year"
                        value="{{ old('class_year', $student->class_year) }}"
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
                        value="{{ old('semester', $student->semester) }}"
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
                            <option value="{{ $st->value }}" {{ old('student_status', $student->student_status->value) === $st->value ? 'selected' : '' }}>
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
                        value="{{ old('phone', $student->phone) }}"
                        class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100">
                <a href="{{ route('admin.students.index') }}" class="px-4 py-2 text-xs font-bold text-zinc-600 hover:text-zinc-800">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-amber-500 text-zinc-950 font-bold text-xs rounded-lg hover:bg-amber-400 shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin>
