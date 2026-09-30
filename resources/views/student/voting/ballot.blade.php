<x-layouts.student title="Bilik Suara: {{ $election->name }}">

    <div class="mb-6">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
            Pemungutan Suara Sedang Berlangsung
        </div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $election->name }}</h1>
        <p class="text-sm text-slate-500 mt-1">
            Gunakan hak pilih Anda dengan bijak. Pilihlah salah satu pasangan calon di bawah ini. Pilihan Anda bersifat <strong>rahasia dan final</strong>.
        </p>
    </div>

    {{-- Alert Info Kerahasiaan --}}
    <div class="mb-8 p-4 rounded-xl bg-blue-50 border border-blue-200 flex items-start gap-3 text-xs text-blue-800">
        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <div>
            <strong class="font-bold">Prinsip Kerahasiaan Suara (Luber Jurdil):</strong>
            Sistem SIPMA tidak mengaitkan identitas Anda dengan surat suara yang Anda pilih. Suara Anda disimpan secara anonim dalam tabel terpisah yang dienkripsi dengan SHA-256 HMAC integrity hash.
        </div>
    </div>

    {{-- Grid Kartu Kandidat --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($candidates as $cand)
            <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between hover:border-blue-400 hover:shadow-md transition">
                <div>
                    {{-- Header Card dengan Nomor Urut --}}
                    <div class="bg-slate-900 px-6 py-4 flex items-center justify-between text-white">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Nomor Urut</span>
                        <span class="w-10 h-10 rounded-full bg-blue-600 text-white font-black text-lg flex items-center justify-center shadow-xs">
                            {{ $cand->candidate_number }}
                        </span>
                    </div>

                    {{-- Foto Pasangan --}}
                    <div class="p-6">
                        <div class="w-full h-56 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden mb-5 flex items-center justify-center">
                            @if ($cand->photo_path)
                                <img src="{{ asset('storage/' . $cand->photo_path) }}" alt="Pasangan No {{ $cand->candidate_number }}" class="w-full h-full object-cover" />
                            @else
                                <div class="text-slate-400 text-xs text-center p-4">
                                    Foto resmi
                                </div>
                            @endif
                        </div>

                        {{-- Identitas Ketua & Wakil --}}
                        <div class="space-y-3 pb-4 border-b border-slate-100 text-center">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Calon Ketua</span>
                                <div class="font-bold text-slate-900 text-base leading-snug">{{ $cand->chairman->name }}</div>
                                <div class="text-xs text-slate-500">{{ $cand->chairman->study_program }}</div>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Calon Wakil Ketua</span>
                                <div class="font-bold text-slate-900 text-base leading-snug">{{ $cand->viceChairman?->name ?? '—' }}</div>
                                <div class="text-xs text-slate-500">{{ $cand->viceChairman?->study_program ?? '—' }}</div>
                            </div>
                        </div>

                        {{-- Visi Singkat --}}
                        @if ($cand->vision)
                            <div class="pt-4 text-xs text-slate-600">
                                <span class="font-bold text-slate-800 block mb-1">Visi:</span>
                                <p class="italic bg-slate-50 p-2.5 rounded-lg border border-slate-100 leading-relaxed line-clamp-3">
                                    "{{ $cand->vision }}"
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Action Vote Button --}}
                <div class="p-6 bg-slate-50 border-t border-slate-100">
                    <form method="POST" action="{{ route('student.voting.vote', $election) }}"
                        onsubmit="return confirm('KONFIRMASI PILIHAN:\n\nApakah Anda yakin ingin memilih pasangan No. Urut {{ $cand->candidate_number }} ({{ $cand->chairman->name }} & {{ $cand->viceChairman?->name }})?\n\nSuara yang telah diberikan bersifat FINAL dan tidak dapat diubah.')">
                        @csrf
                        <input type="hidden" name="candidate_id" value="{{ $cand->id }}" />
                        <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                            <span>Coblos / Pilih Pasangan Ini</span>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

</x-layouts.student>
