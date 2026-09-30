<x-layouts.student title="Hak Pilih Belum Memenuhi Syarat">

    <div class="max-w-xl mx-auto py-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-800">Status Hak Pilih Tidak Memenuhi Syarat</h1>
        <p class="text-sm text-slate-500 mt-2">
            Akun mahasiswa Anda belum terdaftar sebagai pemilih yang memenuhi syarat (eligible) pada <strong>{{ $election->name }}</strong>.
        </p>

        @if ($voter)
            <div class="mt-4 inline-block px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                Status Pemilih: {{ $voter->voter_status->label() }}
            </div>
        @else
            <div class="mt-4 inline-block px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                Belum Terdaftar pada DPT
            </div>
        @endif

        <p class="text-xs text-slate-400 mt-4 max-w-md mx-auto">
            Jika Anda adalah mahasiswa aktif HIMAKOM dan merasa berhak memilih, silakan hubungi Panitia Pemilihan (KPU Mahasiswa) untuk verifikasi data DPT.
        </p>

        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('check-voter') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
                Cek Data Pemilih (DPT)
            </a>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                Kembali ke Dashboard
            </a>
        </div>
    </div>

</x-layouts.student>
