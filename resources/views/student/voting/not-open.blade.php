<x-layouts.student title="Pemungutan Suara">

    <div class="max-w-xl mx-auto py-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 text-amber-500 border border-amber-500/20 flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl">schedule</span>
        </div>

        <h1 class="text-2xl font-bold text-zinc-900">Pemungutan Suara Belum Dibuka</h1>
        @if ($election)
            <p class="text-sm text-zinc-500 mt-2">
                Pemilihan <strong>{{ $election->name }}</strong> dijadwalkan dibuka pada:<br/>
                <span class="font-semibold text-zinc-800 text-base mt-1 inline-block">
                    {{ $election->voting_start->translatedFormat('d F Y, H:i') }} WITA
                </span>
            </p>
            <p class="text-xs text-zinc-400 mt-2">
                Batas akhir pemungutan suara: {{ $election->voting_end->translatedFormat('d F Y, H:i') }} WITA.
            </p>
        @else
            <p class="text-sm text-zinc-500 mt-2">
                Saat ini belum ada periode pemilihan yang sedang berlangsung atau dijadwalkan.
            </p>
        @endif

        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('public.candidates.index') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">groups</span>
                Lihat Profil Kandidat
            </a>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-semibold rounded-xl transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">dashboard</span>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

</x-layouts.student>
