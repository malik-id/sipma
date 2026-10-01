<x-layouts.student title="Suara Berhasil Direkam">

    <div class="max-w-xl mx-auto py-12 text-center">
        <div class="w-20 h-20 rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 flex items-center justify-center mx-auto mb-5 shadow-xs">
            <span class="material-symbols-outlined text-4xl">check_circle</span>
        </div>

        <h1 class="text-3xl font-bold text-zinc-900 tracking-tight">Suara Berhasil Direkam!</h1>
        <p class="text-sm text-zinc-500 mt-2">
            Terima kasih telah berpartisipasi dan menyukseskan <strong>{{ $election->name }}</strong>.
        </p>

        <div class="mt-8 p-6 rounded-2xl bg-white border border-zinc-200 shadow-xs max-w-md mx-auto text-left space-y-3">
            <div class="text-xs font-bold uppercase tracking-wider text-zinc-400">Bukti Keikutsertaan</div>

            <div class="flex items-center justify-between text-xs py-1 border-b border-zinc-100">
                <span class="text-zinc-500">Nama Pemilih</span>
                <span class="font-bold text-zinc-900">{{ auth()->user()->student?->name }}</span>
            </div>

            <div class="flex items-center justify-between text-xs py-1 border-b border-zinc-100">
                <span class="text-zinc-500">NIM</span>
                <span class="font-mono font-bold text-zinc-900">{{ auth()->user()->student?->nim }}</span>
            </div>

            <div class="flex items-center justify-between text-xs py-1 border-b border-zinc-100">
                <span class="text-zinc-500">Waktu Tercatat</span>
                <span class="font-bold text-zinc-900">{{ now()->translatedFormat('d F Y, H:i:s') }} WITA</span>
            </div>

            <div class="p-3 bg-emerald-50 rounded-xl text-[11px] text-emerald-800 leading-relaxed mt-2 border border-emerald-100 flex items-start gap-2">
                <span class="material-symbols-outlined text-emerald-600 text-base shrink-0 mt-0.5">lock</span>
                <span>Surat suara Anda telah dimasukkan ke kotak suara digital secara anonim. Pilihan pasangan calon Anda tidak terhubung ke identitas pemilih.</span>
            </div>
        </div>

        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('public.results.index') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">leaderboard</span>
                Pantau Hasil Pemilihan
            </a>
            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-semibold rounded-xl transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">dashboard</span>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

</x-layouts.student>
