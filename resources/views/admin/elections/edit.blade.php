<x-layouts.admin title="Edit Periode Pemilihan" header="Edit Periode Pemilihan">

    <div class="max-w-4xl">
        <a href="{{ route('admin.elections.show', $election) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 mb-6 font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Detail Pemilihan
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Perbarui Jadwal & Data Pemilihan</h3>
                        <p class="text-sm text-slate-500 mt-1">Ubah nama, deskripsi, atau sesuaikan linimasa tahapan pemilihan.</p>
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
                    <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs">1</span>
                        Informasi Umum Pemilihan
                    </h4>

                    <div class="space-y-1">
                        <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                            Nama Periode Pemilihan <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $election->name) }}"
                            required
                            class="w-full px-4 py-2.5 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        />
                    </div>

                    <div class="space-y-1">
                        <label for="description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                            Deskripsi / Keterangan Tambahan
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            class="w-full px-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        >{{ old('description', $election->description) }}</textarea>
                    </div>
                </div>

                {{-- Linimasa Pendaftaran & Verifikasi --}}
                <div class="space-y-4 pt-6 border-t border-slate-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs">2</span>
                        Linimasa Pendaftaran & Verifikasi Berkas
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="registration_start" class="block text-xs font-semibold text-slate-700">
                                Mulai Pendaftaran Calon <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="registration_start"
                                name="registration_start"
                                value="{{ old('registration_start', $election->registration_start?->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="registration_end" class="block text-xs font-semibold text-slate-700">
                                Batas Akhir Pendaftaran Calon <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="registration_end"
                                name="registration_end"
                                value="{{ old('registration_end', $election->registration_end?->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="verification_start" class="block text-xs font-semibold text-slate-700">
                                Mulai Verifikasi Administrasi <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="verification_start"
                                name="verification_start"
                                value="{{ old('verification_start', $election->verification_start?->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="verification_end" class="block text-xs font-semibold text-slate-700">
                                Selesai Verifikasi Administrasi <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="verification_end"
                                name="verification_end"
                                value="{{ old('verification_end', $election->verification_end?->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            />
                        </div>
                    </div>

                    <div class="space-y-1 sm:w-1/2">
                        <label for="candidate_finalization_at" class="block text-xs font-semibold text-slate-700">
                            Tanggal Penetapan Nomor Urut & Calon Resmi
                        </label>
                        <input
                            type="datetime-local"
                            id="candidate_finalization_at"
                            name="candidate_finalization_at"
                            value="{{ old('candidate_finalization_at', $election->candidate_finalization_at?->format('Y-m-d\TH:i')) }}"
                            class="w-full px-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        />
                    </div>
                </div>

                {{-- Linimasa Kampanye & Pemungutan Suara --}}
                <div class="space-y-4 pt-6 border-t border-slate-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs">3</span>
                        Linimasa Kampanye & Pemungutan Suara (E-Voting)
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="campaign_start" class="block text-xs font-semibold text-slate-700">
                                Mulai Masa Kampanye
                            </label>
                            <input
                                type="datetime-local"
                                id="campaign_start"
                                name="campaign_start"
                                value="{{ old('campaign_start', $election->campaign_start?->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="campaign_end" class="block text-xs font-semibold text-slate-700">
                                Selesai Masa Kampanye (Mulai Masa Tenang)
                            </label>
                            <input
                                type="datetime-local"
                                id="campaign_end"
                                name="campaign_end"
                                value="{{ old('campaign_end', $election->campaign_end?->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="voting_start" class="block text-xs font-semibold text-slate-700">
                                Mulai Pemungutan Suara (Buka TPS Online) <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="voting_start"
                                name="voting_start"
                                value="{{ old('voting_start', $election->voting_start?->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="voting_end" class="block text-xs font-semibold text-slate-700">
                                Selesai Pemungutan Suara (Tutup TPS Online) <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                id="voting_end"
                                name="voting_end"
                                value="{{ old('voting_end', $election->voting_end?->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full px-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            />
                        </div>

                        <div class="space-y-1 sm:col-span-2">
                            <label for="result_publish_at" class="block text-xs font-semibold text-slate-700">
                                Tanggal Publikasi Hasil Resmi Rekapitulasi
                            </label>
                            <input
                                type="datetime-local"
                                id="result_publish_at"
                                name="result_publish_at"
                                value="{{ old('result_publish_at', $election->result_publish_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full sm:w-1/2 px-4 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            />
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.elections.show', $election) }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
