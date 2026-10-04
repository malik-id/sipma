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

                {{-- Pasangan Calon (Ketua & Wakil) dengan Fitur Pencarian Real-Time --}}
                <div class="space-y-6 pt-2 border-t border-zinc-100">
                    {{-- Pilihan Calon Ketua --}}
                    <div id="chairman-picker" class="student-picker space-y-2" data-field-name="chairman_student_id" data-field-label="Calon Ketua" data-required="true">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider">
                                Calon Ketua <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-zinc-400">Cari berdasarkan Nama, NIM, atau Email</span>
                        </div>

                        <input type="hidden" name="chairman_student_id" id="chairman_student_id" value="{{ old('chairman_student_id') }}" required>

                        {{-- Selected Student Card View (Shown when a student is selected) --}}
                        <div id="chairman-selected-box" class="hidden p-3.5 bg-amber-50/80 border border-amber-300/80 rounded-xl relative">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-amber-500 text-white font-bold flex items-center justify-center shrink-0 shadow-xs">
                                        <span class="material-symbols-outlined text-[20px]">person</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-zinc-900 leading-tight" id="chairman-selected-name">-</h4>
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-zinc-600 mt-1">
                                            <span class="font-mono font-bold text-amber-900 bg-amber-100/80 px-1.5 py-0.5 rounded text-[11px]" id="chairman-selected-nim">-</span>
                                            <span class="text-zinc-400">•</span>
                                            <span id="chairman-selected-prodi">-</span>
                                            <span class="text-zinc-400">•</span>
                                            <span class="text-zinc-500 flex items-center gap-1" id="chairman-selected-email">
                                                <span class="material-symbols-outlined text-[13px] text-zinc-400">mail</span>
                                                <span>-</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" onclick="clearStudentSelection('chairman')" class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Ganti Mahasiswa">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                </button>
                            </div>
                        </div>

                        {{-- Search Box & Dropdown Menu --}}
                        <div id="chairman-search-container" class="relative">
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400 text-[18px] pointer-events-none">search</span>
                                <input
                                    type="text"
                                    id="chairman-search-input"
                                    placeholder="Ketik Nama, NIM, atau Email Calon Ketua..."
                                    autocomplete="off"
                                    onfocus="openStudentDropdown('chairman')"
                                    oninput="filterStudents('chairman')"
                                    class="w-full pl-10 pr-10 py-2.5 text-xs sm:text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 font-medium"
                                />
                                <button type="button" onclick="toggleStudentDropdown('chairman')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 p-1">
                                    <span class="material-symbols-outlined text-[18px]">unfold_more</span>
                                </button>
                            </div>

                            {{-- Dropdown Result List --}}
                            <div id="chairman-dropdown-list" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-zinc-200 rounded-xl shadow-xl max-h-64 overflow-y-auto z-30 divide-y divide-zinc-100">
                                <div id="chairman-dropdown-items">
                                    @foreach ($students as $stu)
                                        <div
                                            class="student-option p-3 hover:bg-amber-50/70 cursor-pointer transition flex items-center justify-between gap-3 text-left"
                                            data-id="{{ $stu->id }}"
                                            data-name="{{ $stu->name }}"
                                            data-nim="{{ $stu->nim }}"
                                            data-email="{{ $stu->email }}"
                                            data-prodi="{{ $stu->study_program }}"
                                            data-search="{{ strtolower($stu->name . ' ' . $stu->nim . ' ' . $stu->email . ' ' . $stu->study_program) }}"
                                            onclick="selectStudent('chairman', {{ $stu->id }})"
                                        >
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs sm:text-sm font-bold text-zinc-900 truncate">{{ $stu->name }}</span>
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-amber-100 text-amber-900 shrink-0">{{ $stu->nim }}</span>
                                                </div>
                                                <div class="flex flex-wrap items-center gap-x-2 text-[11px] text-zinc-500 mt-0.5">
                                                    <span class="truncate">{{ $stu->study_program }}</span>
                                                    <span class="text-zinc-300">•</span>
                                                    <span class="truncate text-zinc-400">{{ $stu->email }}</span>
                                                </div>
                                            </div>
                                            <span class="material-symbols-outlined text-[16px] text-zinc-300">chevron_right</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="chairman-dropdown-empty" class="hidden p-6 text-center text-xs text-zinc-400">
                                    <span class="material-symbols-outlined text-zinc-300 text-[28px] block mb-1">person_search</span>
                                    Tidak ada mahasiswa yang cocok dengan kata kunci tersebut.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pilihan Calon Wakil Ketua --}}
                    <div id="vice-picker" class="student-picker space-y-2" data-field-name="vice_chairman_student_id" data-field-label="Calon Wakil Ketua" data-required="false">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider">
                                Calon Wakil Ketua <span class="text-zinc-400 font-normal">(Opsional)</span>
                            </label>
                            <span class="text-[11px] text-zinc-400">Cari berdasarkan Nama, NIM, atau Email</span>
                        </div>

                        <input type="hidden" name="vice_chairman_student_id" id="vice_chairman_student_id" value="{{ old('vice_chairman_student_id') }}">

                        {{-- Selected Student Card View (Shown when a student is selected) --}}
                        <div id="vice-selected-box" class="hidden p-3.5 bg-blue-50/80 border border-blue-300/80 rounded-xl relative">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white font-bold flex items-center justify-center shrink-0 shadow-xs">
                                        <span class="material-symbols-outlined text-[20px]">person</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-zinc-900 leading-tight" id="vice-selected-name">-</h4>
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-zinc-600 mt-1">
                                            <span class="font-mono font-bold text-blue-900 bg-blue-100/80 px-1.5 py-0.5 rounded text-[11px]" id="vice-selected-nim">-</span>
                                            <span class="text-zinc-400">•</span>
                                            <span id="vice-selected-prodi">-</span>
                                            <span class="text-zinc-400">•</span>
                                            <span class="text-zinc-500 flex items-center gap-1" id="vice-selected-email">
                                                <span class="material-symbols-outlined text-[13px] text-zinc-400">mail</span>
                                                <span>-</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" onclick="clearStudentSelection('vice')" class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus Pilihan">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                </button>
                            </div>
                        </div>

                        {{-- Search Box & Dropdown Menu --}}
                        <div id="vice-search-container" class="relative">
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400 text-[18px] pointer-events-none">search</span>
                                <input
                                    type="text"
                                    id="vice-search-input"
                                    placeholder="Ketik Nama, NIM, atau Email Calon Wakil Ketua..."
                                    autocomplete="off"
                                    onfocus="openStudentDropdown('vice')"
                                    oninput="filterStudents('vice')"
                                    class="w-full pl-10 pr-10 py-2.5 text-xs sm:text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 font-medium"
                                />
                                <button type="button" onclick="toggleStudentDropdown('vice')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 p-1">
                                    <span class="material-symbols-outlined text-[18px]">unfold_more</span>
                                </button>
                            </div>

                            {{-- Dropdown Result List --}}
                            <div id="vice-dropdown-list" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-zinc-200 rounded-xl shadow-xl max-h-64 overflow-y-auto z-30 divide-y divide-zinc-100">
                                <div id="vice-dropdown-items">
                                    @foreach ($students as $stu)
                                        <div
                                            class="student-option p-3 hover:bg-blue-50/70 cursor-pointer transition flex items-center justify-between gap-3 text-left"
                                            data-id="{{ $stu->id }}"
                                            data-name="{{ $stu->name }}"
                                            data-nim="{{ $stu->nim }}"
                                            data-email="{{ $stu->email }}"
                                            data-prodi="{{ $stu->study_program }}"
                                            data-search="{{ strtolower($stu->name . ' ' . $stu->nim . ' ' . $stu->email . ' ' . $stu->study_program) }}"
                                            onclick="selectStudent('vice', {{ $stu->id }})"
                                        >
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs sm:text-sm font-bold text-zinc-900 truncate">{{ $stu->name }}</span>
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-100 text-blue-900 shrink-0">{{ $stu->nim }}</span>
                                                </div>
                                                <div class="flex flex-wrap items-center gap-x-2 text-[11px] text-zinc-500 mt-0.5">
                                                    <span class="truncate">{{ $stu->study_program }}</span>
                                                    <span class="text-zinc-300">•</span>
                                                    <span class="truncate text-zinc-400">{{ $stu->email }}</span>
                                                </div>
                                            </div>
                                            <span class="material-symbols-outlined text-[16px] text-zinc-300">chevron_right</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="vice-dropdown-empty" class="hidden p-6 text-center text-xs text-zinc-400">
                                    <span class="material-symbols-outlined text-zinc-300 text-[28px] block mb-1">person_search</span>
                                    Tidak ada mahasiswa yang cocok dengan kata kunci tersebut.
                                </div>
                            </div>
                        </div>
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

    <script>
        function openStudentDropdown(role) {
            const dropdown = document.getElementById(`${role}-dropdown-list`);
            if (dropdown) {
                dropdown.classList.remove('hidden');
                filterStudents(role);
            }
        }

        function closeStudentDropdown(role) {
            const dropdown = document.getElementById(`${role}-dropdown-list`);
            if (dropdown) {
                dropdown.classList.add('hidden');
            }
        }

        function toggleStudentDropdown(role) {
            const dropdown = document.getElementById(`${role}-dropdown-list`);
            if (dropdown) {
                if (dropdown.classList.contains('hidden')) {
                    openStudentDropdown(role);
                    const input = document.getElementById(`${role}-search-input`);
                    if (input) input.focus();
                } else {
                    closeStudentDropdown(role);
                }
            }
        }

        function filterStudents(role) {
            const input = document.getElementById(`${role}-search-input`);
            const filter = input ? input.value.toLowerCase().trim() : '';
            const itemsContainer = document.getElementById(`${role}-dropdown-items`);
            const emptyState = document.getElementById(`${role}-dropdown-empty`);
            if (!itemsContainer) return;

            const options = itemsContainer.querySelectorAll('.student-option');
            let visibleCount = 0;

            options.forEach(opt => {
                const searchData = opt.getAttribute('data-search') || '';
                if (filter === '' || searchData.includes(filter)) {
                    opt.classList.remove('hidden');
                    visibleCount++;
                } else {
                    opt.classList.add('hidden');
                }
            });

            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            }
        }

        function selectStudent(role, studentId) {
            const hiddenInput = document.getElementById(role === 'chairman' ? 'chairman_student_id' : 'vice_chairman_student_id');
            const itemsContainer = document.getElementById(`${role}-dropdown-items`);
            if (!itemsContainer || !hiddenInput) return;

            const selectedOption = itemsContainer.querySelector(`.student-option[data-id="${studentId}"]`);
            if (!selectedOption) return;

            const name = selectedOption.getAttribute('data-name');
            const nim = selectedOption.getAttribute('data-nim');
            const email = selectedOption.getAttribute('data-email');
            const prodi = selectedOption.getAttribute('data-prodi');

            // Set hidden input value
            hiddenInput.value = studentId;

            // Fill selected card view
            const nameEl = document.getElementById(`${role}-selected-name`);
            const nimEl = document.getElementById(`${role}-selected-nim`);
            const prodiEl = document.getElementById(`${role}-selected-prodi`);
            const emailEl = document.getElementById(`${role}-selected-email`);

            if (nameEl) nameEl.textContent = name;
            if (nimEl) nimEl.textContent = nim;
            if (prodiEl) prodiEl.textContent = prodi;
            if (emailEl) {
                emailEl.innerHTML = `
                    <span class="material-symbols-outlined text-[13px] text-zinc-400">mail</span>
                    <span>${email || '-'}</span>
                `;
            }

            // Show selected box and hide search container
            const selectedBox = document.getElementById(`${role}-selected-box`);
            const searchContainer = document.getElementById(`${role}-search-container`);

            if (selectedBox) selectedBox.classList.remove('hidden');
            if (searchContainer) searchContainer.classList.add('hidden');

            closeStudentDropdown(role);
        }

        function clearStudentSelection(role) {
            const hiddenInput = document.getElementById(role === 'chairman' ? 'chairman_student_id' : 'vice_chairman_student_id');
            const selectedBox = document.getElementById(`${role}-selected-box`);
            const searchContainer = document.getElementById(`${role}-search-container`);
            const searchInput = document.getElementById(`${role}-search-input`);

            if (hiddenInput) hiddenInput.value = '';
            if (selectedBox) selectedBox.classList.add('hidden');
            if (searchContainer) searchContainer.classList.remove('hidden');
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }

            filterStudents(role);
            openStudentDropdown(role);
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            ['chairman', 'vice'].forEach(role => {
                const picker = document.getElementById(`${role}-picker`);
                if (picker && !picker.contains(e.target)) {
                    closeStudentDropdown(role);
                }
            });
        });

        // Initialize pre-selected values (from old input or editing)
        document.addEventListener('DOMContentLoaded', function () {
            const chairmanVal = document.getElementById('chairman_student_id')?.value;
            if (chairmanVal) {
                selectStudent('chairman', chairmanVal);
            }

            const viceVal = document.getElementById('vice_chairman_student_id')?.value;
            if (viceVal) {
                selectStudent('vice', viceVal);
            }
        });
    </script>

</x-layouts.admin>
