<x-layouts.student title="Hak Pilih Telah Digunakan">

    <div class="max-w-xl mx-auto py-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-800">Anda Sudah Menggunakan Hak Pilih!</h1>
        <p class="text-sm text-slate-500 mt-2">
            Terima kasih telah berpartisipasi dalam <strong>{{ $election->name }}</strong>.
        </p>

        @if ($participation)
            <div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 max-w-sm mx-auto">
                <div class="font-semibold text-slate-700">Waktu Partisipasi:</div>
                <div class="text-sm font-bold text-slate-900 mt-0.5">
                    {{ $participation->voted_at ? $participation->voted_at->translatedFormat('d F Y, H:i:s') : 'Tercatat' }} WITA
                </div>
                <p class="text-[10px] text-slate-400 mt-2">
                    Sesuai prinsip kerahasiaan, data pilihan Anda tersimpan aman dan terpisah dari identitas akun Anda.
                </p>
            </div>
        @endif

        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('public.results.index') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
                Pantau Hasil Pemilihan
            </a>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                Kembali ke Dashboard
            </a>
        </div>
    </div>

</x-layouts.student>
