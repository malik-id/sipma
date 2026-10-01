<x-layouts.auth title="Belum Terdaftar">

    <div class="text-center">
        <div class="mx-auto flex items-center justify-center w-14 h-14 rounded-full bg-amber-500/10 text-amber-500 border border-amber-500/20 mb-4">
            <span class="material-symbols-outlined text-3xl">warning</span>
        </div>

        <h2 class="text-lg font-bold text-zinc-900 mb-2">Email Tidak Terdaftar</h2>
        <p class="text-xs text-zinc-500 mb-6 leading-relaxed">
            Email Google Anda belum terdaftar pada database Universitas Mega Buana Palopo.<br>
            Hubungi panitia pemilihan (KPU Mahasiswa) untuk mendaftarkan data pemilih Anda.
        </p>

        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-2.5 text-xs font-bold text-zinc-950 hover:bg-amber-400 transition shadow-xs">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali ke Halaman Login</span>
        </a>
    </div>

</x-layouts.auth>
