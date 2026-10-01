<x-layouts.student title="Edit Pendaftaran">

    <div class="mb-6">
        <a href="{{ route('registration.show', $registration) }}" class="text-xs text-zinc-500 hover:text-zinc-900 flex items-center gap-1 mb-3">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali ke Detail</span>
        </a>
        <h1 class="text-xl font-bold text-zinc-900">Edit Draft Pendaftaran</h1>
        <p class="text-sm text-zinc-500 mt-0.5">{{ $registration->election->name }}</p>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('registration.update', $registration) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Data Ketua --}}
            <div>
                <h2 class="text-xs font-bold text-zinc-800 uppercase tracking-wider mb-3 pb-2 border-b border-zinc-100 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-amber-500">person</span>
                    Data Ketua (Anda)
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 mb-1">Nama Ketua</label>
                        <div class="px-3 py-2.5 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-800 font-medium">
                            {{ $registration->chairman->name }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 uppercase tracking-wider mb-1">No. WhatsApp Ketua *</label>
                        <input type="text" name="chairman_phone" value="{{ old('chairman_phone', $registration->chairman_phone) }}" required
                            class="w-full px-3 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            placeholder="08xxxxxxxxxx" />
                    </div>
                </div>
            </div>

            {{-- Data Wakil --}}
            <div>
                <h2 class="text-xs font-bold text-zinc-800 uppercase tracking-wider mb-3 pb-2 border-b border-zinc-100 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-amber-500">group</span>
                    Data Wakil Ketua
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 uppercase tracking-wider mb-1">NIM atau Email Wakil</label>
                        <input type="text" name="vice_lookup"
                            value="{{ old('vice_lookup', $registration->viceChairman?->nim) }}"
                            class="w-full px-3 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            placeholder="Contoh: IK2411020" />
                        @if ($registration->viceChairman)
                            <p class="text-xs text-emerald-600 mt-1">Saat ini: {{ $registration->viceChairman->name }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 uppercase tracking-wider mb-1">No. WhatsApp Wakil</label>
                        <input type="text" name="vice_chairman_phone"
                            value="{{ old('vice_chairman_phone', $registration->vice_chairman_phone) }}"
                            class="w-full px-3 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            placeholder="08xxxxxxxxxx" />
                    </div>
                </div>
            </div>

            {{-- Visi Misi --}}
            <div>
                <h2 class="text-xs font-bold text-zinc-800 uppercase tracking-wider mb-3 pb-2 border-b border-zinc-100 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-amber-500">lightbulb</span>
                    Visi, Misi &amp; Program Kerja
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 uppercase tracking-wider mb-1">Visi</label>
                        <textarea name="vision" rows="3"
                            class="w-full px-3 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 resize-none"
                            placeholder="Tuliskan visi pasangan calon Anda...">{{ old('vision', $registration->vision) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 uppercase tracking-wider mb-1">Misi</label>
                        <textarea name="mission_text" rows="5"
                            class="w-full px-3 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 resize-none"
                            placeholder="Satu baris = satu poin misi...">{{ old('mission_text', implode("\n", $registration->mission ?? [])) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 uppercase tracking-wider mb-1">Program Kerja Unggulan</label>
                        <textarea name="programs_text" rows="5"
                            class="w-full px-3 py-2.5 text-sm border border-zinc-300 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 resize-none"
                            placeholder="Nama Program | Deskripsi">{{ old('programs_text', $registration->programs->map(fn($p) => $p->title . ($p->description ? ' | ' . $p->description : ''))->implode("\n")) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100">
                <a href="{{ route('registration.show', $registration) }}" class="px-4 py-2 text-sm text-zinc-600 hover:text-zinc-800">Batal</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-amber-500 text-zinc-950 text-sm font-bold rounded-xl hover:bg-amber-400 shadow-xs transition cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</x-layouts.student>
