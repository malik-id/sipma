<x-layouts.admin title="Pengaturan Sistem">
    <x-slot:header>
        Pengaturan Sistem Aplikasi
    </x-slot:header>

    <div class="max-w-4xl space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-zinc-200 overflow-hidden">
            <div class="p-6 border-b border-zinc-200 bg-zinc-50">
                <h3 class="text-base font-bold text-zinc-900">Konfigurasi Identitas &amp; Parameter Sistem</h3>
                <p class="text-xs text-zinc-500 mt-1">
                    Atur nama institusi, kontak bantuan mahasiswa/pemilih, pengumuman, dan domain autentikasi.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            Nama Aplikasi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="app_name"
                               value="{{ old('app_name', $settings['app_name'] ?? 'Sistem Informasi Pemilihan Mahasiswa') }}"
                               required
                               class="w-full text-sm rounded-lg border-zinc-300 focus:border-amber-500 focus:ring-amber-500">
                        <p class="text-[11px] text-zinc-400 mt-1">Ditampilkan pada judul halaman dan header.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            Nama Organisasi / Lembaga <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="organization_name"
                               value="{{ old('organization_name', $settings['organization_name'] ?? 'BEM') }}"
                               required
                               class="w-full text-sm rounded-lg border-zinc-300 focus:border-amber-500 focus:ring-amber-500">
                        <p class="text-[11px] text-zinc-400 mt-1">Contoh: BEM / DPM Universitas Mega Buana Palopo.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            Universitas <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="faculty_name"
                               value="{{ old('faculty_name', $settings['faculty_name'] ?? 'Universitas Mega Buana Palopo') }}"
                               required
                               class="w-full text-sm rounded-lg border-zinc-300 focus:border-amber-500 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            Pembatasan Domain Email Google
                        </label>
                        <input type="text" name="allowed_email_domain"
                               value="{{ old('allowed_email_domain', $settings['allowed_email_domain'] ?? '') }}"
                               placeholder="Contoh: student.ac.id (kosongkan jika bebas)"
                               class="w-full text-sm rounded-lg border-zinc-300 focus:border-amber-500 focus:ring-amber-500">
                        <p class="text-[11px] text-zinc-400 mt-1">Kosongkan jika mahasiswa dapat login dengan Gmail pribadi terdaftar.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            Email Kontak Panitia
                        </label>
                        <input type="email" name="contact_email"
                               value="{{ old('contact_email', $settings['contact_email'] ?? 'panitia@megabuana.ac.id') }}"
                               class="w-full text-sm rounded-lg border-zinc-300 focus:border-amber-500 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            Nomor WhatsApp / Hotline Panitia
                        </label>
                        <input type="text" name="contact_phone"
                               value="{{ old('contact_phone', $settings['contact_phone'] ?? '081234567890') }}"
                               class="w-full text-sm rounded-lg border-zinc-300 focus:border-amber-500 focus:ring-amber-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                        Pengumuman Banner (Dashboard Mahasiswa &amp; Pemilih)
                    </label>
                    <textarea name="announcement_banner" rows="3"
                              placeholder="Pesan penting atau arahan tata tertib pemilihan untuk pemilih..."
                              class="w-full text-sm rounded-lg border-zinc-300 focus:border-amber-500 focus:ring-amber-500">{{ old('announcement_banner', $settings['announcement_banner'] ?? '') }}</textarea>
                    <p class="text-[11px] text-zinc-400 mt-1">Akan tampil di dashboard mahasiswa saat ada informasi penting.</p>
                </div>

                <div class="pt-4 border-t border-zinc-200 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-lg transition shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">check</span>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- Danger Zone: Reset Database --}}
        <div class="bg-white rounded-xl shadow-sm border border-rose-200 overflow-hidden">
            <div class="p-6 border-b border-rose-100 bg-rose-50/60">
                <div class="flex items-center gap-2 text-rose-900">
                    <span class="material-symbols-outlined text-rose-600 text-[22px]">warning</span>
                    <h3 class="text-base font-bold">Zona Bahaya: Reset Database Sistem</h3>
                </div>
                <p class="text-xs text-rose-800 mt-1">
                    Gunakan fitur ini jika Anda ingin mengosongkan seluruh data transaksi pemilihan, pendaftaran calon, surat suara, pemilih, dan data master mahasiswa untuk memulai pemilihan periode baru dari awal.
                </p>
            </div>

            <div class="p-6 space-y-4">
                <div class="rounded-lg bg-zinc-50 p-4 border border-zinc-200 text-xs text-zinc-700 space-y-2">
                    <div class="font-bold text-zinc-900">Data yang akan dihapus secara permanen:</div>
                    <ul class="list-disc list-inside space-y-1 text-zinc-600">
                        <li>Seluruh periode pemilihan, syarat berkas, dan DPT (daftar pemilih).</li>
                        <li>Seluruh pendaftaran bakal calon, berkas dokumen, visi-misi, dan calon resmi.</li>
                        <li>Seluruh surat suara anonim (ballots) dan partisipasi voting.</li>
                        <li>Seluruh data mahasiswa dan akun login mahasiswa.</li>
                    </ul>
                    <div class="font-bold text-emerald-800 pt-1">
                        ✓ Akun Admin (Super Admin, Admin, Dosen Pendamping) TIDAK AKAN DIHAPUS.
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.settings.reset') }}" class="pt-2 space-y-4"
                    data-confirm="PERINGATAN AKHIR: Apakah Anda benar-benar yakin ingin me-reset dan menghapus seluruh data database sekarang? Tindakan ini tidak dapat dibatalkan!">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                            Ketik <span class="font-mono text-rose-600 font-black">RESET-DATA</span> untuk konfirmasi:
                        </label>
                        <input type="text" name="confirm_reset" placeholder="RESET-DATA" required
                            class="w-full max-w-xs text-xs font-mono font-bold rounded-lg border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50/30" />
                    </div>

                    <div>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">delete_forever</span>
                            Hapus Semua Data &amp; Reset Database
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.admin>
