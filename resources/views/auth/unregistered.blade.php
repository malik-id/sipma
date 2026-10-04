<x-layouts.auth title="Belum Terdaftar">

    @php
        $contactPhone = \App\Models\SystemSetting::get('contact_phone', '081234567890');
        $contactEmail = \App\Models\SystemSetting::get('contact_email', 'kpu@megabuana.ac.id');
        $cleanPhone = preg_replace('/[^0-9]/', '', (string) $contactPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        $unregEmail = session('unregistered_email') ?? '';
        $waMsg = "Halo Panitia KPU SIPMA UMB, akun Google saya " . ($unregEmail ? "({$unregEmail}) " : "") . "belum terdaftar di sistem. Mohon bantuannya untuk verifikasi/pendaftaran data mahasiswa saya.";
        $waUrl = !empty($cleanPhone) ? "https://wa.me/{$cleanPhone}?text=" . rawurlencode($waMsg) : null;
    @endphp

    <div class="text-center">
        <div class="mx-auto flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-500 border border-amber-500/20 mb-4">
            <span class="material-symbols-outlined text-3xl">person_off</span>
        </div>

        <h2 class="text-lg font-bold text-zinc-900 mb-1">Akun Google Belum Terdaftar</h2>
        <p class="text-xs text-zinc-500 mb-6 leading-relaxed max-w-sm mx-auto">
            Email Google Anda belum tercatat pada basis data mahasiswa Universitas Mega Buana Palopo. Silakan hubungi admin / panitia pemilihan (KPU Mahasiswa) secara langsung untuk pendaftaran.
        </p>

        {{-- Direct Contact Buttons --}}
        <div class="space-y-2.5 mb-6">
            @if ($waUrl)
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                   class="w-full flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-3 text-xs font-bold transition shadow-xs">
                    <span class="material-symbols-outlined text-lg">chat</span>
                    <span>Hubungi Admin via WhatsApp</span>
                </a>
            @endif

            @if ($contactEmail)
                <a href="mailto:{{ $contactEmail }}?subject={{ rawurlencode('Permohonan Pendaftaran Akun Mahasiswa SIPMA') }}&body={{ rawurlencode($waMsg) }}"
                   class="w-full flex items-center justify-center gap-2 rounded-xl bg-white hover:bg-zinc-100 text-zinc-800 border border-zinc-200 px-4 py-2.5 text-xs font-semibold transition shadow-2xs">
                    <span class="material-symbols-outlined text-base text-zinc-500">mail</span>
                    <span>Hubungi via Email Panitia</span>
                </a>
            @endif
        </div>

        <div class="pt-4 border-t border-zinc-100 flex items-center justify-center gap-4 text-xs">
            <a href="{{ route('check-voter') }}" class="text-zinc-600 hover:text-zinc-950 font-semibold transition flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">how_to_vote</span>
                <span>Cek DPT Mandiri</span>
            </a>
            <span class="text-zinc-300">&bull;</span>
            <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-semibold transition flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Kembali ke Login</span>
            </a>
        </div>
    </div>

</x-layouts.auth>
