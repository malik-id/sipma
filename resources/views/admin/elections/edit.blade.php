<x-layouts.admin title="Edit Periode Pemilihan" header="Edit Periode Pemilihan">

    <div class="max-w-4xl">
        <a href="{{ route('admin.elections.show', $election) }}" class="inline-flex items-center gap-1.5 text-xs text-zinc-500 hover:text-zinc-900 mb-6 font-bold transition">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <span>Kembali ke Detail Pemilihan</span>
        </a>

        <div class="bg-white rounded-2xl shadow-xs border border-zinc-200 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-zinc-100 bg-zinc-50/50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-zinc-900">Perbarui Jadwal & Data Pemilihan</h3>
                        <p class="text-xs text-zinc-500 mt-1">Ubah nama, deskripsi, atau sesuaikan linimasa tahapan pemilihan.</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $election->status->badgeClasses() }}">
                        {{ $election->status->label() }}
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.elections.update', $election) }}" class="p-6 sm:p-8 space-y-8">
                @csrf
                @method('PUT')

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
                            value="{{ old('name', $election->name) }}"
                            required
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
                            class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                        >{{ old('description', $election->description) }}</textarea>
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
                                value="{{ old('registration_start', $election->registration_start?->format('Y-m-d\TH:i')) }}"
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
                                value="{{ old('registration_end', $election->registration_end?->format('Y-m-d\TH:i')) }}"
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
                                value="{{ old('verification_start', $election->verification_start?->format('Y-m-d\TH:i')) }}"
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
                                value="{{ old('verification_end', $election->verification_end?->format('Y-m-d\TH:i')) }}"
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
                            value="{{ old('candidate_finalization_at', $election->candidate_finalization_at?->format('Y-m-d\TH:i')) }}"
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
                                value="{{ old('campaign_start', $election->campaign_start?->format('Y-m-d\TH:i')) }}"
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
                                value="{{ old('campaign_end', $election->campaign_end?->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="voting_start" class="block text-xs font-semibold text-zinc-700">
                                Mulai Pemungutan Suara (Buka TPS Online) <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="voting_start"
                                name="voting_start"
                                value="{{ old('voting_start', $election->voting_start?->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="voting_end" class="block text-xs font-semibold text-zinc-700">
                                Selesai Pemungutan Suara (Tutup TPS Online) <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="voting_end"
                                name="voting_end"
                                value="{{ old('voting_end', $election->voting_end?->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>

                        <div class="space-y-1 sm:col-span-2">
                            <label for="result_publish_at" class="block text-xs font-semibold text-zinc-700">
                                Tanggal Publikasi Hasil Resmi Rekapitulasi
                            </label>
                            <input
                                type="datetime-local"
                                id="result_publish_at"
                                name="result_publish_at"
                                value="{{ old('result_publish_at', $election->result_publish_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full sm:w-1/2 px-4 py-2 text-sm bg-white border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            />
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-zinc-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.elections.show', $election) }}" class="px-4 py-2 text-xs font-bold text-zinc-600 hover:text-zinc-900">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 text-zinc-950 font-bold text-xs rounded-xl hover:bg-amber-400 shadow-xs transition cursor-pointer flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
