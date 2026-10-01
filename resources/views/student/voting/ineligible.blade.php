<x-layouts.student title="Hak Pilih Belum Memenuhi Syarat">

    <div class="max-w-xl mx-auto py-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-rose-500/10 text-rose-500 border border-rose-500/20 flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl">block</span>
        </div>

        <h1 class="text-2xl font-bold text-zinc-900">Status Hak Pilih Tidak Memenuhi Syarat</h1>
        <p class="text-sm text-zinc-500 mt-2">
            Akun mahasiswa Anda belum terdaftar sebagai pemilih yang memenuhi syarat (eligible) pada <strong>{{ $election->name }}</strong>.
        </p>

        @if ($voter)
            <div class="mt-4 inline-block px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-500 border border-amber-500/20">
                Status Pemilih: {{ $voter->voter_status->label() }}
            </div>
        @else
            <div class="mt-4 inline-block px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-500 border border-rose-500/20">
                Belum Terdaftar pada DPT
            </div>
        @endif

        <p class="text-xs text-zinc-400 mt-4 max-w-md mx-auto">
            Jika Anda adalah mahasiswa aktif HIMAKOM dan merasa berhak memilih, silakan hubungi Panitia Pemilihan (KPU Mahasiswa) untuk verifikasi data DPT.
        </p>

        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('check-voter') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">how_to_reg</span>
                Cek Data Pemilih (DPT)
            </a>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-semibold rounded-xl transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">dashboard</span>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

</x-layouts.student>
