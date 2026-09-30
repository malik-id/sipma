<x-layouts.student title="Edit Pendaftaran">

    <div class="mb-6">
        <a href="{{ route('registration.show', $registration) }}" class="text-xs text-slate-500 hover:text-slate-800 flex items-center gap-1 mb-3">← Kembali ke Detail</a>
        <h1 class="text-xl font-bold text-slate-900">Edit Draft Pendaftaran</h1>
        <p class="text-sm text-slate-500 mt-0.5">{{ $registration->election->name }}</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('registration.update', $registration) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Data Ketua --}}
            <div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">Data Ketua (Anda)</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Ketua</label>
                        <div class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 font-medium">
                            {{ $registration->chairman->name }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">No. WhatsApp Ketua *</label>
                        <input type="text" name="chairman_phone" value="{{ old('chairman_phone', $registration->chairman_phone) }}" required
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="08xxxxxxxxxx" />
                    </div>
                </div>
            </div>

            {{-- Data Wakil --}}
            <div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">Data Wakil Ketua</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">NIM atau Email Wakil</label>
                        <input type="text" name="vice_lookup"
                            value="{{ old('vice_lookup', $registration->viceChairman?->nim) }}"
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: IK2411020" />
                        @if ($registration->viceChairman)
                            <p class="text-xs text-emerald-600 mt-1">Saat ini: {{ $registration->viceChairman->name }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">No. WhatsApp Wakil</label>
                        <input type="text" name="vice_chairman_phone"
                            value="{{ old('vice_chairman_phone', $registration->vice_chairman_phone) }}"
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="08xxxxxxxxxx" />
                    </div>
                </div>
            </div>

            {{-- Visi Misi --}}
            <div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">Visi, Misi &amp; Program Kerja</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Visi</label>
                        <textarea name="vision" rows="3"
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            placeholder="Tuliskan visi pasangan calon Anda...">{{ old('vision', $registration->vision) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Misi</label>
                        <textarea name="mission_text" rows="5"
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            placeholder="Satu baris = satu poin misi...">{{ old('mission_text', implode("\n", $registration->mission ?? [])) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1">Program Kerja Unggulan</label>
                        <textarea name="programs_text" rows="5"
                            class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            placeholder="Nama Program | Deskripsi">{{ old('programs_text', $registration->programs->map(fn($p) => $p->title . ($p->description ? ' | ' . $p->description : ''))->implode("\n")) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('registration.show', $registration) }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">Batal</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</x-layouts.student>
