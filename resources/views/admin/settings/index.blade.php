<x-layouts.admin title="Pengaturan Sistem">
    <x-slot:header>
        Pengaturan Sistem Aplikasi
    </x-slot:header>

    <div class="max-w-4xl space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200 bg-slate-50">
                <h3 class="text-base font-semibold text-slate-900">Konfigurasi Identitas &amp; Parameter Sistem</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Atur nama institusi, kontak bantuan mahasiswa/pemilih, pengumuman, dan domain autentikasi.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Aplikasi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="app_name"
                               value="{{ old('app_name', $settings['app_name'] ?? 'Sistem Informasi Pemilihan Mahasiswa') }}"
                               required
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-[11px] text-slate-400 mt-1">Ditampilkan pada judul halaman dan header.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Organisasi / Lembaga <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="organization_name"
                               value="{{ old('organization_name', $settings['organization_name'] ?? 'HIMAKOM') }}"
                               required
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-[11px] text-slate-400 mt-1">Contoh: HIMAKOM / BEM FASILKOM.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Fakultas / Universitas <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="faculty_name"
                               value="{{ old('faculty_name', $settings['faculty_name'] ?? 'Fakultas Ilmu Komputer') }}"
                               required
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Pembatasan Domain Email Google
                        </label>
                        <input type="text" name="allowed_email_domain"
                               value="{{ old('allowed_email_domain', $settings['allowed_email_domain'] ?? '') }}"
                               placeholder="Contoh: student.ac.id (kosongkan jika bebas)"
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika mahasiswa dapat login dengan Gmail pribadi terdaftar.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Email Kontak Panitia
                        </label>
                        <input type="email" name="contact_email"
                               value="{{ old('contact_email', $settings['contact_email'] ?? 'panitia@himakom.ac.id') }}"
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Nomor WhatsApp / Hotline Panitia
                        </label>
                        <input type="text" name="contact_phone"
                               value="{{ old('contact_phone', $settings['contact_phone'] ?? '081234567890') }}"
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Pengumuman Banner (Dashboard Mahasiswa &amp; Pemilih)
                    </label>
                    <textarea name="announcement_banner" rows="3"
                              placeholder="Pesan penting atau arahan tata tertib pemilihan untuk pemilih..."
                              class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">{{ old('announcement_banner', $settings['announcement_banner'] ?? '') }}</textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Akan tampil di dashboard mahasiswa saat ada informasi penting.</p>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
