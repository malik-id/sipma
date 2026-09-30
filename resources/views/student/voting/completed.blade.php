<x-layouts.student title="Suara Berhasil Direkam">

    <div class="max-w-xl mx-auto py-12 text-center">
        <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-5 shadow-sm">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Suara Berhasil Direkam!</h1>
        <p class="text-sm text-slate-500 mt-2">
            Terima kasih telah berpartisipasi dan menyukseskan <strong>{{ $election->name }}</strong>.
        </p>

        <div class="mt-8 p-6 rounded-2xl bg-white border border-slate-200 shadow-xs max-w-md mx-auto text-left space-y-3">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Bukti Keikutsertaan</div>

            <div class="flex items-center justify-between text-xs py-1 border-b border-slate-100">
                <span class="text-slate-500">Nama Pemilih</span>
                <span class="font-bold text-slate-800">{{ auth()->user()->student?->name }}</span>
            </div>

            <div class="flex items-center justify-between text-xs py-1 border-b border-slate-100">
                <span class="text-slate-500">NIM</span>
                <span class="font-mono font-bold text-slate-800">{{ auth()->user()->student?->nim }}</span>
            </div>

            <div class="flex items-center justify-between text-xs py-1 border-b border-slate-100">
                <span class="text-slate-500">Waktu Tercatat</span>
                <span class="font-bold text-slate-800">{{ now()->translatedFormat('d F Y, H:i:s') }} WITA</span>
            </div>

            <div class="p-3 bg-emerald-50 rounded-xl text-[11px] text-emerald-800 leading-relaxed mt-2 border border-emerald-100">
                🔒 Surat suara Anda telah dimasukkan ke kotak suara digital secara anonim. Pilihan pasangan calon Anda tidak terhubung ke identitas pemilih.
            </div>
        </div>

        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('public.results.index') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                Pantau Hasil Pemilihan
            </a>
            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                Kembali ke Dashboard
            </a>
        </div>
    </div>

</x-layouts.student>
