<x-layouts.admin title="Data Mahasiswa" header="Master Data Mahasiswa">

    {{-- Toolbar & Actions --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('admin.students.index') }}" class="flex items-center gap-2 flex-1 max-w-md">
            <div class="relative flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari NIM, Nama, Email, atau Prodi..."
                    class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                />
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition">
                Cari
            </button>
            @if(request('search') || request('status') || request('semester'))
                <a href="{{ route('admin.students.index') }}" class="px-3 py-2 text-xs text-slate-600 hover:text-slate-900 font-medium">Reset</a>
            @endif
        </form>

        <div class="flex items-center gap-2">
            {{-- Tombol Modal Import CSV --}}
            <button
                type="button"
                onclick="document.getElementById('modal-import').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-sm transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import CSV
            </button>

            {{-- Tambah Mahasiswa Manual --}}
            <a href="{{ route('admin.students.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Mahasiswa
            </a>
        </div>
    </div>

    {{-- Tabel Mahasiswa --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">NIM</th>
                        <th class="px-6 py-3.5">Nama Mahasiswa</th>
                        <th class="px-6 py-3.5">Program Studi</th>
                        <th class="px-6 py-3.5 text-center">Angkatan / Sem</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                        <th class="px-6 py-3.5 text-center">Google ID</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($students as $student)
                        <tr class="hover:bg-slate-50/75 transition">
                            <td class="px-6 py-4 font-mono font-medium text-slate-900">{{ $student->nim }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $student->name }}</div>
                                <div class="text-xs text-slate-400">{{ $student->email }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $student->study_program }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-semibold text-slate-700">{{ $student->class_year }}</span>
                                <span class="text-xs text-slate-400">/ Sem {{ $student->semester }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($student->student_status->value === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @elseif ($student->student_status->value === 'suspended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ ucfirst($student->student_status->value) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($student->google_id)
                                    <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-medium" title="Google ID: {{ $student->google_id }}">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Terhubung
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">Belum login</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.students.edit', $student) }}" class="p-1.5 text-slate-500 hover:text-blue-600 rounded hover:bg-slate-100 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Hapus mahasiswa {{ $student->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 rounded hover:bg-slate-100 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data mahasiswa yang cocok dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $students->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Import CSV --}}
    <div id="modal-import" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-semibold text-slate-900">Import Data Mahasiswa via CSV</h3>
                <button type="button" onclick="document.getElementById('modal-import').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.students.import') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Pilih File CSV</label>
                    <input
                        type="file"
                        name="file"
                        accept=".csv,.txt"
                        required
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-lg p-2"
                    />
                </div>

                <div class="bg-slate-50 rounded-lg p-3 text-xs text-slate-500 space-y-1">
                    <div class="font-medium text-slate-700">Format Kolom Header Wajib:</div>
                    <code class="block font-mono bg-white p-2 rounded border border-slate-200 text-blue-600 text-xs">
                        nim,name,email,study_program,class_year,semester,phone
                    </code>
                    <p>Baris dengan NIM/Email yang sudah ada akan otomatis diperbarui.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('modal-import').classList.add('hidden')" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 transition">
                        Mulai Import
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
