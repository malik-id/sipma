<x-layouts.admin title="Buat Periode Pemilihan" header="Buat Periode Pemilihan Baru">

    <div class="max-w-4xl">
        <a href="{{ route('admin.elections.index') }}" class="inline-flex items-center gap-1.5 text-xs text-zinc-500 hover:text-zinc-900 mb-6 font-bold transition">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <span>Kembali ke Daftar Periode</span>
        </a>

        <div class="bg-white rounded-2xl shadow-xs border border-zinc-200 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-zinc-100 bg-zinc-50/50">
                <h3 class="text-base font-bold text-zinc-900">Formulir Periode Pemilihan & Linimasa</h3>
                <p class="text-xs text-zinc-500 mt-1">
                    Atur jadwal lengkap tahapan pemilihan mulai dari pendaftaran bakal calon, verifikasi berkas, masa kampanye, pemungutan suara (e-voting), hingga publikasi hasil resmi.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.elections.store') }}" class="p-6 sm:p-8 space-y-8">
                @csrf

                {{-- Informasi Utama --}}
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-amber-600 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-amber-500/20 text-amber-600 font-bold flex items-center justify-center text-xs">1</span>
                        Informasi Umum Pemilihan
                    </h4>

                    <div class="space-y-1">
                        <label for="name" class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider">
                            Nama Periode Pemilihan <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', 'Pemilihan Ketua & Wakil Ketua HIMAKOM ' . date('Y') . '/' . (date('Y')+1)) }}"
                            required
                            placeholder="Contoh: Pemilihan Ketua & Wakil Ketua HIMAKOM 2026/2027"
                            class="w-full px-4 py-2.5 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                        />
                    </div>

                    <div class="space-y-1">
                        <label for="description" class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider">
                            Deskripsi / Keterangan Tambahan
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Tuliskan petunjuk umum, dasar hukum SK KPU Mahasiswa, atau informasi pengantar pemilihan..."
                            class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                        >{{ old('description') }}</textarea>
                    </div>
                </div>

                {{-- Linimasa Pendaftaran & Verifikasi --}}
                <div class="space-y-4 pt-6 border-t border-zinc-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-amber-600 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-amber-500/20 text-amber-600 font-bold flex items-center justify-center text-xs">2</span>
                        Linimasa Pendaftaran & Verifikasi Berkas
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="registration_start" class="block text-xs font-semibold text-zinc-700">
                                Mulai Pendaftaran Calon <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="registration_start"
                                name="registration_start"
                                value="{{ old('registration_start', now()->addDays(1)->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="registration_end" class="block text-xs font-semibold text-zinc-700">
                                Batas Akhir Pendaftaran Calon <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="registration_end"
                                name="registration_end"
                                value="{{ old('registration_end', now()->addDays(7)->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="verification_start" class="block text-xs font-semibold text-zinc-700">
                                Mulai Verifikasi Administrasi <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="verification_start"
                                name="verification_start"
                                value="{{ old('verification_start', now()->addDays(3)->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="verification_end" class="block text-xs font-semibold text-zinc-700">
                                Selesai Verifikasi Administrasi <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="verification_end"
                                name="verification_end"
                                value="{{ old('verification_end', now()->addDays(9)->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>
                    </div>

                    <div class="space-y-1 sm:w-1/2">
                        <label for="candidate_finalization_at" class="block text-xs font-semibold text-zinc-700">
                            Tanggal Penetapan Nomor Urut & Calon Resmi
                        </label>
                        <input
                            type="datetime-local"
                            id="candidate_finalization_at"
                            name="candidate_finalization_at"
                            value="{{ old('candidate_finalization_at', now()->addDays(10)->format('Y-m-d\TH:i')) }}"
                            class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                        />
                    </div>
                </div>

                {{-- Linimasa Kampanye & Pemungutan Suara --}}
                <div class="space-y-4 pt-6 border-t border-zinc-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-amber-600 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-amber-500/20 text-amber-600 font-bold flex items-center justify-center text-xs">3</span>
                        Linimasa Kampanye & Pemungutan Suara (E-Voting)
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="campaign_start" class="block text-xs font-semibold text-zinc-700">
                                Mulai Masa Kampanye
                            </label>
                            <input
                                type="datetime-local"
                                id="campaign_start"
                                name="campaign_start"
                                value="{{ old('campaign_start', now()->addDays(11)->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="campaign_end" class="block text-xs font-semibold text-zinc-700">
                                Selesai Masa Kampanye (Mulai Masa Tenang)
                            </label>
                            <input
                                type="datetime-local"
                                id="campaign_end"
                                name="campaign_end"
                                value="{{ old('campaign_end', now()->addDays(14)->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="voting_start" class="block text-xs font-semibold text-zinc-700">
                                Mulai Pemungutan Suara (Buka TPS Online) <span class="text-rose-500">*</span>
                                <span class="text-[10px] text-amber-600 font-bold ml-1">(WITA / UTC+8)</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="voting_start"
                                name="voting_start"
                                value="{{ old('voting_start', now()->addDays(15)->setTime(8, 0)->format('Y-m-d\TH:i')) }}"
                                required
                                onchange="calculateVotingDuration()"
                                class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="voting_end" class="block text-xs font-semibold text-zinc-700">
                                Selesai Pemungutan Suara (Tutup TPS Online) <span class="text-rose-500">*</span>
                                <span class="text-[10px] text-amber-600 font-bold ml-1">(WITA / UTC+8)</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="voting_end"
                                name="voting_end"
                                value="{{ old('voting_end', now()->addDays(15)->setTime(16, 0)->format('Y-m-d\TH:i')) }}"
                                required
                                onchange="calculateVotingDuration()"
                                class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>

                        {{-- Live Duration Info & Quick Presets --}}
                        <div class="sm:col-span-2 bg-amber-50/70 border border-amber-200/80 rounded-xl p-3.5 space-y-2">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                                <div class="flex items-center gap-1.5 text-zinc-800 font-bold">
                                    <span class="material-symbols-outlined text-amber-600 text-base">timer</span>
                                    <span>Pratinjau Durasi Voting:</span>
                                    <span id="voting_duration_preview" class="text-amber-800 font-mono font-extrabold bg-white px-2 py-0.5 rounded border border-amber-200">Menghitung...</span>
                                </div>
                                <div class="flex flex-wrap items-center gap-1.5 text-[11px]">
                                    <span class="text-zinc-500">Preset Cepat:</span>
                                    <button type="button" onclick="setVotingQuickPreset(1)" class="px-2 py-1 bg-white hover:bg-amber-100 text-zinc-800 font-semibold rounded border border-zinc-200 transition">+1 Jam</button>
                                    <button type="button" onclick="setVotingQuickPreset(2)" class="px-2 py-1 bg-white hover:bg-amber-100 text-zinc-800 font-semibold rounded border border-zinc-200 transition">+2 Jam</button>
                                    <button type="button" onclick="setVotingQuickPreset(8)" class="px-2 py-1 bg-white hover:bg-amber-100 text-zinc-800 font-semibold rounded border border-zinc-200 transition">+8 Jam</button>
                                    <button type="button" onclick="setVotingQuickPreset(24)" class="px-2 py-1 bg-white hover:bg-amber-100 text-zinc-800 font-semibold rounded border border-zinc-200 transition">+1 Hari</button>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1 sm:col-span-2">
                            <div class="flex items-center justify-between">
                                <label for="result_publish_at" class="block text-xs font-semibold text-zinc-700">
                                    Tanggal &amp; Waktu Publikasi Hasil Resmi Rekapitulasi
                                    <span class="text-[10px] text-amber-600 font-bold ml-1">(WITA / UTC+8)</span>
                                </label>
                                <button type="button" onclick="matchPublishWithVotingEnd()" class="text-[11px] text-amber-700 hover:text-amber-800 font-bold transition">
                                    ⚡ Samakan dengan Waktu Tutup Voting
                                </button>
                            </div>
                            <input
                                type="datetime-local"
                                id="result_publish_at"
                                name="result_publish_at"
                                value="{{ old('result_publish_at', now()->addDays(15)->setTime(18, 0)->format('Y-m-d\TH:i')) }}"
                                class="w-full sm:w-1/2 px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                            <p class="text-xs text-zinc-400 mt-1">Hasil voting tidak akan ditampilkan ke publik sebelum tanggal &amp; jam ini tercapai (atau saat panitia mengklik 'Publikasikan Sekarang').</p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-zinc-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.elections.index') }}" class="px-4 py-2 text-xs font-bold text-zinc-600 hover:text-zinc-900">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 text-zinc-950 font-bold text-xs rounded-xl hover:bg-amber-400 shadow-xs transition cursor-pointer flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">save</span>
                        Simpan &amp; Buat Periode
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calculateVotingDuration() {
            const startInput = document.getElementById('voting_start');
            const endInput = document.getElementById('voting_end');
            const preview = document.getElementById('voting_duration_preview');
            if (!startInput || !endInput || !preview) return;

            const start = new Date(startInput.value);
            const end = new Date(endInput.value);

            if (isNaN(start.getTime()) || isNaN(end.getTime())) {
                preview.textContent = 'Waktu belum valid';
                return;
            }

            const diffMs = end - start;
            if (diffMs <= 0) {
                preview.textContent = '⚠ Waktu selesai harus lebih lambat dari waktu mulai';
                preview.className = 'text-rose-700 font-mono font-extrabold bg-rose-50 px-2 py-0.5 rounded border border-rose-200';
                return;
            }

            preview.className = 'text-amber-900 font-mono font-extrabold bg-white px-2 py-0.5 rounded border border-amber-200';

            const totalMins = Math.floor(diffMs / (1000 * 60));
            const days = Math.floor(totalMins / (60 * 24));
            const hours = Math.floor((totalMins % (60 * 24)) / 60);
            const mins = totalMins % 60;

            let parts = [];
            if (days > 0) parts.push(days + ' hari');
            if (hours > 0) parts.push(hours + ' jam');
            if (mins > 0 || parts.length === 0) parts.push(mins + ' menit');

            preview.textContent = parts.join(' ') + ' (' + formatTimeOfDay(start) + ' s/d ' + formatTimeOfDay(end) + ')';
        }

        function formatTimeOfDay(date) {
            const h = date.getHours().toString().padStart(2, '0');
            const m = date.getMinutes().toString().padStart(2, '0');
            const period = date.getHours() < 12 ? 'Pagi' : (date.getHours() < 15 ? 'Siang' : (date.getHours() < 18 ? 'Sore' : 'Malam'));
            return `${h}:${m} ${period}`;
        }

        function setVotingQuickPreset(hours) {
            const startInput = document.getElementById('voting_start');
            const endInput = document.getElementById('voting_end');
            if (!startInput || !endInput) return;

            let start = new Date(startInput.value);
            if (isNaN(start.getTime())) {
                start = new Date();
                startInput.value = formatDateTimeLocal(start);
            }

            const end = new Date(start.getTime() + hours * 60 * 60 * 1000);
            endInput.value = formatDateTimeLocal(end);
            calculateVotingDuration();
        }

        function matchPublishWithVotingEnd() {
            const endInput = document.getElementById('voting_end');
            const pubInput = document.getElementById('result_publish_at');
            if (endInput && pubInput && endInput.value) {
                pubInput.value = endInput.value;
            }
        }

        function formatDateTimeLocal(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        document.addEventListener('DOMContentLoaded', calculateVotingDuration);
    </script>

</x-layouts.admin>
