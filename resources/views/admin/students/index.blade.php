<x-layouts.admin title="Data Mahasiswa" header="Master Data Mahasiswa">

    {{-- Toolbar & Actions --}}
    <div class="space-y-4 mb-6">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Data Mahasiswa</h1>
                <p class="text-xs sm:text-sm text-zinc-500 mt-0.5">Kelola data master mahasiswa, status keaktifan, dan impor data akademik.</p>
            </div>

            <div class="flex items-center gap-2">
                {{-- Tombol Modal Import CSV / Excel --}}
                <button
                    type="button"
                    onclick="document.getElementById('modal-import').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-zinc-700 bg-white border border-zinc-300 rounded-lg hover:bg-zinc-50 shadow-sm transition">
                    <span class="material-symbols-outlined text-[18px] text-zinc-500">upload_file</span>
                    Import Excel / CSV
                </button>

                {{-- Tambah Mahasiswa Manual --}}
                <a href="{{ route('admin.students.create') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-zinc-950 bg-amber-500 rounded-lg hover:bg-amber-400 shadow-sm transition">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Tambah Mahasiswa
                </a>
            </div>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.students.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                {{-- Search --}}
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-zinc-600 uppercase tracking-wider mb-1">Pencarian</label>
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari NIM, Nama, Email..."
                            class="w-full pl-9 pr-3 py-2 text-xs bg-zinc-50 border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        />
                        <span class="material-symbols-outlined text-zinc-400 text-[18px] absolute left-2.5 top-2">search</span>
                    </div>
                </div>

                {{-- Filter Angkatan --}}
                <div>
                    <label class="block text-[11px] font-bold text-zinc-600 uppercase tracking-wider mb-1">Angkatan</label>
                    <select name="class_year" onchange="this.form.submit()" class="w-full text-xs font-bold bg-zinc-50 border border-zinc-300 rounded-lg px-3 py-2 text-zinc-700 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Semua Angkatan</option>
                        @foreach ($classYears as $year)
                            <option value="{{ $year }}" {{ request('class_year') == $year ? 'selected' : '' }}>
                                Angkatan {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Status (Aktif / Nonaktif) --}}
                <div>
                    <label class="block text-[11px] font-bold text-zinc-600 uppercase tracking-wider mb-1">Status Mahasiswa</label>
                    <select name="status" onchange="this.form.submit()" class="w-full text-xs font-bold bg-zinc-50 border border-zinc-300 rounded-lg px-3 py-2 text-zinc-700 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>✓ Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>✕ Nonaktif</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>⚠ Suspended</option>
                        <option value="graduated" {{ request('status') === 'graduated' ? 'selected' : '' }}>🎓 Lulus</option>
                    </select>
                </div>

                {{-- Actions Button --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-3.5 py-2 bg-zinc-950 text-amber-400 text-xs font-bold rounded-lg hover:bg-zinc-800 transition shadow-sm">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'class_year', 'status', 'study_program', 'semester']))
                        <a href="{{ route('admin.students.index') }}" class="px-3 py-2 text-xs text-zinc-500 hover:text-zinc-800 font-bold bg-zinc-100 rounded-lg hover:bg-zinc-200 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Mahasiswa --}}
    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-zinc-600">
                <thead class="bg-zinc-50 border-b border-zinc-200 text-xs font-bold text-zinc-500 uppercase tracking-wider">
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
                <tbody class="divide-y divide-zinc-200">
                    @forelse ($students as $student)
                        <tr class="hover:bg-zinc-50/75 transition">
                            <td class="px-6 py-4 font-mono font-bold text-zinc-900">{{ $student->nim }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-zinc-900">{{ $student->name }}</div>
                                <div class="text-xs text-zinc-400">{{ $student->email }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $student->study_program }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-bold text-zinc-700">{{ $student->class_year }}</span>
                                <span class="text-xs text-zinc-400">/ Sem {{ $student->semester }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($student->student_status->value === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @elseif ($student->student_status->value === 'suspended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-zinc-100 text-zinc-700">
                                        {{ ucfirst($student->student_status->value) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($student->google_id)
                                    <span class="inline-flex items-center gap-1 text-xs text-emerald-700 font-semibold" title="Google ID: {{ $student->google_id }}">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Terhubung
                                    </span>
                                @else
                                    <span class="text-xs text-zinc-400">Belum login</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.students.edit', $student) }}" class="p-1.5 text-zinc-500 hover:text-amber-600 rounded hover:bg-zinc-100 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Hapus mahasiswa {{ $student->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-zinc-500 hover:text-rose-600 rounded hover:bg-zinc-100 transition" title="Hapus">
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
                            <td colspan="7" class="px-6 py-12 text-center text-zinc-400">
                                Belum ada data mahasiswa yang cocok dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="p-4 border-t border-zinc-200">
                {{ $students->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Import CSV / Excel --}}
    <div id="modal-import" class="hidden fixed inset-0 bg-zinc-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6 border border-zinc-200">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <h3 class="text-base font-bold text-zinc-900">Import Data Mahasiswa (Excel / CSV)</h3>
                <button type="button" onclick="document.getElementById('modal-import').classList.add('hidden')" class="text-zinc-400 hover:text-zinc-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.students.import') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Pilih File Excel / CSV (.xlsx, .xls, .csv)</label>
                    <input
                        type="file"
                        name="file"
                        accept=".xlsx,.xls,.csv,.txt"
                        required
                        class="block w-full text-sm text-zinc-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100 border border-zinc-300 rounded-lg p-2"
                    />
                </div>

                <div class="bg-zinc-50 rounded-lg p-3 text-xs text-zinc-500 space-y-1.5 border border-zinc-200">
                    <div class="font-bold text-zinc-800">Format Kolom Header:</div>
                    <code class="block font-mono bg-white p-2 rounded border border-zinc-200 text-amber-700 text-[11px]">
                        nim,name,email,study_program,class_year,semester,phone
                    </code>
                    <p class="text-[11px] text-zinc-500">Mendukung format Microsoft Excel (.xlsx / .xls) dan CSV / Text (.csv / .txt).</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-100">
                    <button type="button" onclick="document.getElementById('modal-import').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-zinc-600 hover:text-zinc-800">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-zinc-950 text-amber-400 hover:bg-zinc-800 font-bold text-xs rounded-lg transition shadow-sm">
                        Mulai Import
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
