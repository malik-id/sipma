<x-layouts.student title="Bilik Suara: {{ $election->name }}">

    <div class="mb-6">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-500 border border-amber-500/20 mb-2">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
            Pemungutan Suara Sedang Berlangsung
        </div>
        <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">{{ $election->name }}</h1>
        <p class="text-sm text-zinc-500 mt-1">
            Gunakan hak pilih Anda dengan bijak. Pilihlah salah satu pasangan calon di bawah ini. Pilihan Anda bersifat <strong>rahasia dan final</strong>.
        </p>
    </div>

    {{-- Alert Info Kerahasiaan --}}
    <div class="mb-8 p-4 rounded-xl bg-zinc-900 text-white border border-zinc-800 flex items-start gap-3 text-xs">
        <span class="material-symbols-outlined text-amber-400 text-xl shrink-0">verified_user</span>
        <div>
            <strong class="font-bold text-amber-400">Prinsip Kerahasiaan Suara (Luber Jurdil):</strong>
            <p class="text-zinc-300 mt-0.5">Sistem SIPMA tidak mengaitkan identitas Anda dengan surat suara yang Anda pilih. Suara Anda disimpan secara anonim dalam tabel terpisah yang dienkripsi dengan SHA-256 HMAC integrity hash.</p>
        </div>
    </div>

    {{-- Grid Kartu Kandidat --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($candidates as $cand)
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs overflow-hidden flex flex-col justify-between hover:border-amber-400 hover:shadow-md transition group">
                <div>
                    {{-- Header Card dengan Nomor Urut --}}
                    <div class="bg-zinc-950 px-6 py-4 flex items-center justify-between text-white border-b border-zinc-800">
                        <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Nomor Urut</span>
                        <span class="w-10 h-10 rounded-full bg-amber-500 text-zinc-950 font-black text-lg flex items-center justify-center shadow-xs">
                            {{ $cand->candidate_number }}
                        </span>
                    </div>

                    {{-- Foto Pasangan --}}
                    <div class="p-6">
                        <div class="w-full h-56 rounded-xl bg-zinc-100 border border-zinc-200 overflow-hidden mb-5 flex items-center justify-center">
                            @if ($cand->photo_path)
                                <img src="{{ asset('storage/' . $cand->photo_path) }}" alt="Pasangan No {{ $cand->candidate_number }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                            @else
                                <div class="text-zinc-400 text-xs text-center p-4 flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-3xl">image</span>
                                    <span>Foto Resmi Belum Tersedia</span>
                                </div>
                            @endif
                        </div>

                        {{-- Identitas Ketua & Wakil --}}
                        <div class="space-y-3 pb-4 border-b border-zinc-100 text-center">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 block">Calon Ketua</span>
                                <div class="font-bold text-zinc-900 text-base leading-snug">{{ $cand->chairman->name }}</div>
                                <div class="text-xs text-zinc-500">{{ $cand->chairman->study_program }}</div>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 block">Calon Wakil Ketua</span>
                                <div class="font-bold text-zinc-900 text-base leading-snug">{{ $cand->viceChairman?->name ?? '—' }}</div>
                                <div class="text-xs text-zinc-500">{{ $cand->viceChairman?->study_program ?? '—' }}</div>
                            </div>
                        </div>

                        {{-- Visi Singkat --}}
                        @if ($cand->vision)
                            <div class="pt-4 text-xs text-zinc-600">
                                <span class="font-bold text-zinc-900 block mb-1">Visi:</span>
                                <p class="italic bg-zinc-50 p-2.5 rounded-lg border border-zinc-100 leading-relaxed line-clamp-3">
                                    "{{ $cand->vision }}"
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Action Vote Button --}}
                <div class="p-6 bg-zinc-50 border-t border-zinc-100">
                    <form method="POST" action="{{ route('student.voting.vote', $election) }}"
                        onsubmit="return confirm('KONFIRMASI PILIHAN:\n\nApakah Anda yakin ingin memilih pasangan No. Urut {{ $cand->candidate_number }} ({{ $cand->chairman->name }} & {{ $cand->viceChairman?->name }})?\n\nSuara yang telah diberikan bersifat FINAL dan tidak dapat diubah.')">
                        @csrf
                        <input type="hidden" name="candidate_id" value="{{ $cand->id }}" />
                        <button type="submit" class="w-full py-3 px-4 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-sm font-bold rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                            <span class="material-symbols-outlined text-lg">how_to_vote</span>
                            <span>Coblos / Pilih Pasangan Ini</span>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

</x-layouts.student>
