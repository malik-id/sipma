<x-layouts.student title="Pemungutan Suara">

    <div class="max-w-xl mx-auto py-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-800">Pemungutan Suara Belum Dibuka</h1>
        @if ($election)
            <p class="text-sm text-slate-500 mt-2">
                Pemilihan <strong>{{ $election->name }}</strong> dijadwalkan dibuka pada:<br/>
                <span class="font-semibold text-slate-700 text-base mt-1 inline-block">
                    {{ $election->voting_start->translatedFormat('d F Y, H:i') }} WITA
                </span>
            </p>
            <p class="text-xs text-slate-400 mt-2">
                Batas akhir pemungutan suara: {{ $election->voting_end->translatedFormat('d F Y, H:i') }} WITA.
            </p>
        @else
            <p class="text-sm text-slate-500 mt-2">
                Saat ini belum ada periode pemilihan yang sedang berlangsung atau dijadwalkan.
            </p>
        @endif

        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('public.candidates.index') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
                Lihat Profil Kandidat
            </a>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                Kembali ke Dashboard
            </a>
        </div>
    </div>

</x-layouts.student>
