<x-layouts.auth title="Belum Terdaftar">

    <div class="text-center">
        <div class="mx-auto flex items-center justify-center w-14 h-14 rounded-full bg-yellow-100 mb-4">
            <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-gray-900 mb-2">Email Tidak Terdaftar</h2>
        <p class="text-sm text-gray-500 mb-6">
            Email Google Anda belum terdaftar pada database Fakultas Ilmu Komputer.<br>
            Hubungi panitia pemilihan untuk mendaftarkan akun Anda.
        </p>

        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            ← Kembali ke Halaman Login
        </a>
    </div>

</x-layouts.auth>
