<x-layouts.admin title="Pemeriksaan Berkas: {{ $registration->registration_number ?? 'Draft' }}">

    {{-- Breadcrumb & Back --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.registrations.index') }}" class="text-xs text-zinc-500 hover:text-zinc-900 flex items-center gap-1.5 mb-2 font-bold transition">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Daftar Pendaftaran
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">
                    {{ $registration->chairman->name }} &amp; {{ $registration->viceChairman?->name ?? '—' }}
                </h1>
                @php
                    $badge = match($registration->status->value) {
                        'draft' => 'bg-zinc-100 text-zinc-700',
                        'submitted', 'resubmitted' => 'bg-amber-50 text-amber-900 border border-amber-200',
                        'under_review' => 'bg-amber-100 text-amber-900 border border-amber-300',
                        'revision_required' => 'bg-orange-50 text-orange-800 border border-orange-200',
                        'verified' => 'bg-emerald-50 text-emerald-800 border border-emerald-200',
                        'established' => 'bg-zinc-950 text-amber-400',
                        'rejected' => 'bg-rose-50 text-rose-800 border border-rose-200',
                        default => 'bg-zinc-100 text-zinc-800'
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                    {{ $registration->status->label() }}
                </span>
            </div>
            <p class="text-xs text-zinc-400 mt-1 font-mono">
                No. Registrasi: {{ $registration->registration_number ?? 'DRAFT' }} &bull; {{ $registration->election->name }}
            </p>
        </div>

        {{-- Action Buttons Header --}}
        <div class="flex items-center gap-2">
            @if (in_array($registration->status->value, ['submitted', 'resubmitted']))
                <form method="POST" action="{{ route('admin.registrations.start-review', $registration) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-zinc-950 font-bold text-xs rounded-lg shadow-sm transition">
                        Mulai Pemeriksaan Berkas
                    </button>
                </form>
            @endif

            @if ($registration->status->value === 'under_review')
                <form method="POST" action="{{ route('admin.registrations.verify', $registration) }}"
                    onsubmit="return confirm('Apakah Anda yakin semua dokumen dan data sudah valid dan siap diverifikasi?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm transition">
                        ✓ Verifikasi &amp; Setujui
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Alert Messages --}}
    @if ($registration->status->value === 'revision_required')
        <div class="mb-6 rounded-xl bg-orange-50 border border-orange-200 p-4 text-xs sm:text-sm text-orange-900">
            <div class="font-bold">Pendaftaran Dalam Status Revisi</div>
            <p class="mt-1">{{ $registration->revision_notes }}</p>
            @if ($registration->revision_deadline)
                <p class="text-xs text-orange-800 mt-2 font-bold">
                    Batas Perbaikan: {{ $registration->revision_deadline->translatedFormat('d F Y, H:i') }} WITA
                </p>
            @endif
        </div>
    @elseif ($registration->status->value === 'rejected')
        <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 p-4 text-xs sm:text-sm text-rose-900">
            <div class="font-bold">Pendaftaran Ditolak</div>
            <p class="mt-1">{{ $registration->rejection_reason }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Columns: Data Pasangan & Berkas --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- 1. Identitas Pasangan --}}
            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-4">Profil Pasangan Calon</h3>
                <div class="flex flex-col sm:flex-row gap-6 items-start">
                    <div class="w-36 h-48 rounded-xl bg-zinc-100 border border-zinc-200 overflow-hidden shrink-0 flex items-center justify-center">
                        @if ($registration->photo_path)
                            <img src="{{ asset('storage/' . $registration->photo_path) }}" alt="Foto Pasangan" class="w-full h-full object-cover" />
                        @else
                            <div class="text-zinc-400 text-center p-3 text-xs">
                                Belum ada foto resmi
                            </div>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1 w-full">
                        <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200">
                            <span class="text-xs text-amber-800 font-bold uppercase tracking-wider block mb-1">Calon Ketua</span>
                            <div class="font-bold text-zinc-900 text-base">{{ $registration->chairman->name }}</div>
                            <div class="text-xs text-zinc-500 mt-1 font-mono">NIM: {{ $registration->chairman->nim }}</div>
                            <div class="text-xs text-zinc-500">Prodi: {{ $registration->chairman->study_program }} &bull; Semester {{ $registration->chairman->semester }}</div>
                            <div class="text-xs text-zinc-500 mt-2">📱 {{ $registration->chairman_phone ?: '—' }}</div>
                        </div>

                        <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200">
                            <span class="text-xs text-zinc-600 font-bold uppercase tracking-wider block mb-1">Calon Wakil Ketua</span>
                            @if ($registration->viceChairman)
                                <div class="font-bold text-zinc-900 text-base">{{ $registration->viceChairman->name }}</div>
                                <div class="text-xs text-zinc-500 mt-1 font-mono">NIM: {{ $registration->viceChairman->nim }}</div>
                                <div class="text-xs text-zinc-500">Prodi: {{ $registration->viceChairman->study_program }} &bull; Semester {{ $registration->viceChairman->semester }}</div>
                                <div class="text-xs text-zinc-500 mt-2">📱 {{ $registration->vice_chairman_phone ?: '—' }}</div>
                            @else
                                <div class="text-zinc-400 text-sm italic">Belum ditentukan</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Visi, Misi & Program Kerja --}}
            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-4">Visi, Misi &amp; Program Kerja</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Visi</h4>
                        <p class="text-xs sm:text-sm text-zinc-800 leading-relaxed bg-zinc-50 p-3 rounded-lg border border-zinc-100">{{ $registration->vision ?: '—' }}</p>
                    </div>

                    @if ($registration->mission)
                        <div>
                            <h4 class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Misi</h4>
                            <div class="bg-zinc-50 p-3 rounded-lg border border-zinc-100">
                                <ol class="list-decimal list-inside space-y-1 text-xs sm:text-sm text-zinc-800">
                                    @foreach ($registration->mission as $m)
                                        <li>{{ $m }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    @endif

                    @if ($registration->programs->isNotEmpty())
                        <div>
                            <h4 class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Program Kerja Unggulan</h4>
                            <div class="space-y-2">
                                @foreach ($registration->programs as $prog)
                                    <div class="p-3 bg-zinc-50 rounded-lg border border-zinc-100 text-xs sm:text-sm">
                                        <div class="font-bold text-zinc-900">{{ $prog->title }}</div>
                                        @if ($prog->description)
                                            <p class="text-xs text-zinc-500 mt-0.5">{{ $prog->description }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 3. Verifikasi Dokumen Persyaratan --}}
            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400">Verifikasi Dokumen Persyaratan</h3>
                    <span class="text-xs text-zinc-400">Status dokumen harus valid semua sebelum pendaftaran disetujui.</span>
                </div>

                <div class="space-y-4">
                    @forelse ($registration->election->requirements->where('active', true)->where('type', 'document') as $req)
                        @php
                            $doc = $registration->currentDocuments->firstWhere('requirement_id', $req->id);
                            $docBadge = match($doc?->verification_status) {
                                'valid' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                'invalid' => 'bg-rose-50 text-rose-800 border-rose-200',
                                'revision_required' => 'bg-orange-50 text-orange-800 border-orange-200',
                                default => 'bg-zinc-100 text-zinc-600 border-zinc-200',
                            };
                        @endphp
                        <div class="p-4 rounded-xl border border-zinc-200 bg-white hover:border-zinc-300 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2">
                                <div>
                                    <div class="font-bold text-zinc-900 text-sm flex items-center gap-2">
                                        {{ $req->name }}
                                        @if ($req->required)
                                            <span class="text-rose-500 text-xs">*wajib</span>
                                        @endif
                                    </div>
                                    @if ($req->description)
                                        <div class="text-xs text-zinc-400 mt-0.5">{{ $req->description }}</div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $docBadge }}">
                                        {{ $doc ? strtoupper(str_replace('_', ' ', $doc->verification_status)) : 'BELUM DIUNGGAH' }}
                                    </span>
                                    @if ($doc)
                                        <a href="{{ route('admin.registrations.download-document', $doc) }}"
                                           class="px-3 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 text-xs font-bold rounded-lg transition inline-flex items-center gap-1">
                                            Unduh Berkas
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if ($doc && $doc->verification_note)
                                <div class="text-xs text-zinc-600 bg-zinc-50 p-2.5 rounded-lg mb-3 border border-zinc-100">
                                    <span class="font-bold text-zinc-700">Catatan Panitia:</span> {{ $doc->verification_note }}
                                </div>
                            @endif

                            {{-- Form Review Dokumen --}}
                            @if ($doc && $registration->status->value === 'under_review')
                                <form method="POST" action="{{ route('admin.registrations.review-document', $doc) }}" class="mt-3 pt-3 border-t border-zinc-100">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-center">
                                        <div>
                                            <select name="status" class="w-full text-xs border-zinc-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-zinc-50 font-bold" required>
                                                <option value="valid" {{ $doc->verification_status === 'valid' ? 'selected' : '' }}>✓ Valid (Memenuhi Syarat)</option>
                                                <option value="revision_required" {{ $doc->verification_status === 'revision_required' ? 'selected' : '' }}>⚠ Perlu Perbaikan (Revisi)</option>
                                                <option value="invalid" {{ $doc->verification_status === 'invalid' ? 'selected' : '' }}>✕ Tidak Valid (Gugur)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <input type="text" name="note" value="{{ $doc->verification_note }}" placeholder="Catatan perbaikan (jika ada)..."
                                                class="w-full text-xs border-zinc-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-zinc-50" />
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="px-3 py-1.5 bg-zinc-950 hover:bg-zinc-800 text-amber-400 font-bold text-xs rounded-lg transition shadow-2xs">
                                                Simpan Status Dokumen
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-zinc-400 text-xs italic">Tidak ada dokumen persyaratan yang terdaftar pada pemilihan ini.</div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Right Column: Keputusan & Riwayat --}}
        <div class="space-y-6">

            {{-- Panel Keputusan Verifikasi --}}
            @if ($registration->status->value === 'under_review')
                <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6 space-y-5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400">Keputusan Verifikasi</h3>

                    {{-- Form Minta Revisi --}}
                    <div class="pt-2 border-t border-zinc-100">
                        <div class="text-xs font-bold text-orange-900 mb-2">Minta Perbaikan Berkas</div>
                        <form method="POST" action="{{ route('admin.registrations.request-revision', $registration) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-zinc-600 mb-1">Catatan Instruksi Perbaikan *</label>
                                <textarea name="notes" rows="3" required placeholder="Tuliskan alasan dan bagian yang harus diperbaiki pendaftar..."
                                    class="w-full text-xs border-zinc-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-zinc-50"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-zinc-600 mb-1">Batas Waktu Perbaikan</label>
                                <input type="datetime-local" name="deadline"
                                    class="w-full text-xs border-zinc-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-zinc-50" />
                            </div>
                            <button type="submit" class="w-full py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-lg transition">
                                Kirim Permintaan Revisi
                            </button>
                        </form>
                    </div>

                    {{-- Form Tolak Pendaftaran --}}
                    <div class="pt-4 border-t border-zinc-100">
                        <div class="text-xs font-bold text-rose-900 mb-2">Tolak Pendaftaran</div>
                        <form method="POST" action="{{ route('admin.registrations.reject', $registration) }}"
                            onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran bakal calon ini?')">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-xs font-bold text-zinc-600 mb-1">Alasan Penolakan *</label>
                                <textarea name="notes" rows="2" required placeholder="Alasan gugur/penolakan..."
                                    class="w-full text-xs border-zinc-300 rounded-lg focus:ring-rose-500 focus:border-rose-500 bg-zinc-50"></textarea>
                            </div>
                            <button type="submit" class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition">
                                Tolak Pendaftaran
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Riwayat Status Pendaftaran --}}
            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-4">Riwayat Aktivitas</h3>
                <div class="space-y-4 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-zinc-100">
                    @forelse ($registration->histories as $hist)
                        <div class="relative flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-amber-50 border-2 border-white text-amber-700 flex items-center justify-center shrink-0 z-10 text-xs font-bold shadow-2xs">
                                •
                            </div>
                            <div>
                                <div class="text-xs font-bold text-zinc-900">
                                    {{ \App\Enums\RegistrationStatus::tryFrom($hist->status_to)?->label() ?? $hist->status_to }}
                                </div>
                                @if ($hist->notes)
                                    <p class="text-xs text-zinc-600 mt-0.5">{{ $hist->notes }}</p>
                                @endif
                                <div class="text-[10px] text-zinc-400 mt-1">
                                    {{ $hist->created_at->translatedFormat('d M Y, H:i') }} &bull; oleh {{ $hist->user?->name ?? 'Sistem' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-zinc-400 text-xs italic">Belum ada riwayat tercatat.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</x-layouts.admin>
