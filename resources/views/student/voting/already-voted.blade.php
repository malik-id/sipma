<x-layouts.student title="Hak Pilih Telah Digunakan">

    <div class="max-w-xl mx-auto py-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl">how_to_vote</span>
        </div>

        <h1 class="text-2xl font-bold text-zinc-900">Anda Sudah Menggunakan Hak Pilih!</h1>
        <p class="text-sm text-zinc-500 mt-2">
            Terima kasih telah berpartisipasi dalam <strong>{{ $election->name }}</strong>.
        </p>

        @if ($participation)
            <div class="mt-6 p-4 rounded-xl bg-zinc-50 border border-zinc-200 text-xs text-zinc-600 max-w-sm mx-auto">
                <div class="font-semibold text-zinc-800">Waktu Partisipasi:</div>
                <div class="text-sm font-bold text-zinc-900 mt-0.5">
                    {{ $participation->voted_at ? $participation->voted_at->translatedFormat('d F Y, H:i:s') : 'Tercatat' }} WITA
                </div>
                <p class="text-[10px] text-zinc-400 mt-2">
                    Sesuai prinsip kerahasiaan, data pilihan Anda tersimpan aman dan terpisah dari identitas akun Anda.
                </p>
            </div>
        @endif

        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('public.results.index') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">leaderboard</span>
                Pantau Hasil Pemilihan
            </a>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-semibold rounded-xl transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">dashboard</span>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

</x-layouts.student>
