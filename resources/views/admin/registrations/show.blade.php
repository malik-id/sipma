<x-layouts.admin title="Pemeriksaan Berkas: {{ $registration->registration_number ?? 'Draft' }}">

    {{-- Breadcrumb & Back --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.registrations.index') }}" class="text-xs text-slate-500 hover:text-slate-800 flex items-center gap-1 mb-2 font-medium">
                ← Kembali ke Daftar Pendaftaran
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-800">
                    {{ $registration->chairman->name }} &amp; {{ $registration->viceChairman?->name ?? '—' }}
                </h1>
                @php
                    $badge = match($registration->status->value) {
                        'draft' => 'bg-slate-100 text-slate-700',
                        'submitted', 'resubmitted' => 'bg-blue-100 text-blue-800',
                        'under_review' => 'bg-amber-100 text-amber-800',
                        'revision_required' => 'bg-orange-100 text-orange-800',
                        'verified' => 'bg-emerald-100 text-emerald-800',
                        'established' => 'bg-indigo-100 text-indigo-800',
                        'rejected' => 'bg-rose-100 text-rose-800',
                        default => 'bg-slate-100 text-slate-800'
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                    {{ $registration->status->label() }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1 font-mono">
                No. Registrasi: {{ $registration->registration_number ?? 'DRAFT' }} &bull; {{ $registration->election->name }}
            </p>
        </div>

        {{-- Action Buttons Header --}}
        <div class="flex items-center gap-2">
            @if (in_array($registration->status->value, ['submitted', 'resubmitted']))
                <form method="POST" action="{{ route('admin.registrations.start-review', $registration) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                        Mulai Pemeriksaan Berkas
                    </button>
                </form>
            @endif

            @if ($registration->status->value === 'under_review')
                <form method="POST" action="{{ route('admin.registrations.verify', $registration) }}"
                    onsubmit="return confirm('Apakah Anda yakin semua dokumen dan data sudah valid dan siap diverifikasi?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                        ✓ Verifikasi &amp; Setujui
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Alert Messages --}}
    @if ($registration->status->value === 'revision_required')
        <div class="mb-6 rounded-xl bg-orange-50 border border-orange-200 p-4 text-sm text-orange-800">
            <div class="font-bold">Pendaftaran Dalam Status Revisi</div>
            <p class="mt-1">{{ $registration->revision_notes }}</p>
            @if ($registration->revision_deadline)
                <p class="text-xs text-orange-700 mt-2 font-medium">
                    Batas Perbaikan: {{ $registration->revision_deadline->translatedFormat('d F Y, H:i') }} WITA
                </p>
            @endif
        </div>
    @elseif ($registration->status->value === 'rejected')
        <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 p-4 text-sm text-rose-800">
            <div class="font-bold">Pendaftaran Ditolak</div>
            <p class="mt-1">{{ $registration->rejection_reason }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Columns: Data Pasangan & Berkas --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- 1. Identitas Pasangan --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Profil Pasangan Calon</h3>
                <div class="flex flex-col sm:flex-row gap-6 items-start">
                    <div class="w-36 h-48 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                        @if ($registration->photo_path)
                            <img src="{{ asset('storage/' . $registration->photo_path) }}" alt="Foto Pasangan" class="w-full h-full object-cover" />
                        @else
                            <div class="text-slate-400 text-center p-3 text-xs">
                                Belum ada foto resmi
                            </div>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1 w-full">
                        <div class="bg-slate-50 p-4 rounded-xl">
                            <span class="text-xs text-blue-600 font-bold uppercase tracking-wider block mb-1">Calon Ketua</span>
                            <div class="font-bold text-slate-800 text-base">{{ $registration->chairman->name }}</div>
                            <div class="text-xs text-slate-500 mt-1 font-mono">NIM: {{ $registration->chairman->nim }}</div>
                            <div class="text-xs text-slate-500">Prodi: {{ $registration->chairman->study_program }} &bull; Semester {{ $registration->chairman->semester }}</div>
                            <div class="text-xs text-slate-500 mt-2">📱 {{ $registration->chairman_phone ?: '—' }}</div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl">
                            <span class="text-xs text-indigo-600 font-bold uppercase tracking-wider block mb-1">Calon Wakil Ketua</span>
                            @if ($registration->viceChairman)
                                <div class="font-bold text-slate-800 text-base">{{ $registration->viceChairman->name }}</div>
                                <div class="text-xs text-slate-500 mt-1 font-mono">NIM: {{ $registration->viceChairman->nim }}</div>
                                <div class="text-xs text-slate-500">Prodi: {{ $registration->viceChairman->study_program }} &bull; Semester {{ $registration->viceChairman->semester }}</div>
                                <div class="text-xs text-slate-500 mt-2">📱 {{ $registration->vice_chairman_phone ?: '—' }}</div>
                            @else
                                <div class="text-slate-400 text-sm italic">Belum ditentukan</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Visi, Misi & Program Kerja --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Visi, Misi &amp; Program Kerja</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Visi</h4>
                        <p class="text-sm text-slate-800 leading-relaxed bg-slate-50 p-3 rounded-lg">{{ $registration->vision ?: '—' }}</p>
                    </div>

                    @if ($registration->mission)
                        <div>
                            <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Misi</h4>
                            <div class="bg-slate-50 p-3 rounded-lg">
                                <ol class="list-decimal list-inside space-y-1 text-sm text-slate-800">
                                    @foreach ($registration->mission as $m)
                                        <li>{{ $m }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    @endif

                    @if ($registration->programs->isNotEmpty())
                        <div>
                            <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Program Kerja Unggulan</h4>
                            <div class="space-y-2">
                                @foreach ($registration->programs as $prog)
                                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 text-sm">
                                        <div class="font-semibold text-slate-800">{{ $prog->title }}</div>
                                        @if ($prog->description)
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $prog->description }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 3. Verifikasi Dokumen Persyaratan --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Verifikasi Dokumen Persyaratan</h3>
                    <span class="text-xs text-slate-400">Status dokumen harus valid semua sebelum pendaftaran disetujui.</span>
                </div>

                <div class="space-y-4">
                    @forelse ($registration->election->requirements->where('active', true)->where('type', 'document') as $req)
                        @php
                            $doc = $registration->currentDocuments->firstWhere('requirement_id', $req->id);
                            $docBadge = match($doc?->verification_status) {
                                'valid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'invalid' => 'bg-rose-100 text-rose-800 border-rose-200',
                                'revision_required' => 'bg-orange-100 text-orange-800 border-orange-200',
                                default => 'bg-slate-100 text-slate-600 border-slate-200',
                            };
                        @endphp
                        <div class="p-4 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2">
                                <div>
                                    <div class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                        {{ $req->name }}
                                        @if ($req->required)
                                            <span class="text-rose-500 text-xs">*wajib</span>
                                        @endif
                                    </div>
                                    @if ($req->description)
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $req->description }}</div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $docBadge }}">
                                        {{ $doc ? strtoupper(str_replace('_', ' ', $doc->verification_status)) : 'BELUM DIUNGGAH' }}
                                    </span>
                                    @if ($doc)
                                        <a href="{{ route('admin.registrations.download-document', $doc) }}"
                                           class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition inline-flex items-center gap-1">
                                            Unduh Berkas
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if ($doc && $doc->verification_note)
                                <div class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-lg mb-3 border border-slate-100">
                                    <span class="font-semibold text-slate-700">Catatan Panitia:</span> {{ $doc->verification_note }}
                                </div>
                            @endif

                            {{-- Form Review Dokumen (Hanya saat status Under Review) --}}
                            @if ($doc && $registration->status->value === 'under_review')
                                <form method="POST" action="{{ route('admin.registrations.review-document', $doc) }}" class="mt-3 pt-3 border-t border-slate-100">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-center">
                                        <div>
                                            <select name="status" class="w-full text-xs border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50 font-semibold" required>
                                                <option value="valid" {{ $doc->verification_status === 'valid' ? 'selected' : '' }}>✓ Valid (Memenuhi Syarat)</option>
                                                <option value="revision_required" {{ $doc->verification_status === 'revision_required' ? 'selected' : '' }}>⚠ Perlu Perbaikan (Revisi)</option>
                                                <option value="invalid" {{ $doc->verification_status === 'invalid' ? 'selected' : '' }}>✕ Tidak Valid (Gugur)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <input type="text" name="note" value="{{ $doc->verification_note }}" placeholder="Catatan perbaikan (jika ada)..."
                                                class="w-full text-xs border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50" />
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition">
                                                Simpan Status Dokumen
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-slate-400 text-xs italic">Tidak ada dokumen persyaratan yang terdaftar pada pemilihan ini.</div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Right Column: Keputusan & Riwayat --}}
        <div class="space-y-6">

            {{-- Panel Keputusan Verifikasi --}}
            @if ($registration->status->value === 'under_review')
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Keputusan Verifikasi</h3>

                    {{-- Form Minta Revisi --}}
                    <div class="pt-2 border-t border-slate-100">
                        <div class="text-xs font-bold text-orange-800 mb-2">Minta Perbaikan Berkas</div>
                        <form method="POST" action="{{ route('admin.registrations.request-revision', $registration) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Catatan Instruksi Perbaikan *</label>
                                <textarea name="notes" rows="3" required placeholder="Tuliskan alasan dan bagian yang harus diperbaiki pendaftar..."
                                    class="w-full text-xs border-slate-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 bg-slate-50"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Batas Waktu Perbaikan</label>
                                <input type="datetime-local" name="deadline"
                                    class="w-full text-xs border-slate-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 bg-slate-50" />
                            </div>
                            <button type="submit" class="w-full py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-lg transition">
                                Kirim Permintaan Revisi
                            </button>
                        </form>
                    </div>

                    {{-- Form Tolak Pendaftaran --}}
                    <div class="pt-4 border-t border-slate-100">
                        <div class="text-xs font-bold text-rose-800 mb-2">Tolak Pendaftaran</div>
                        <form method="POST" action="{{ route('admin.registrations.reject', $registration) }}"
                            onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran bakal calon ini?')">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-xs font-medium text-slate-600 mb-1">Alasan Penolakan *</label>
                                <textarea name="notes" rows="2" required placeholder="Alasan gugur/penolakan..."
                                    class="w-full text-xs border-slate-200 rounded-lg focus:ring-rose-500 focus:border-rose-500 bg-slate-50"></textarea>
                            </div>
                            <button type="submit" class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition">
                                Tolak Pendaftaran
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Riwayat Status Pendaftaran --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Riwayat Aktivitas</h3>
                <div class="space-y-4 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-slate-100">
                    @forelse ($registration->histories as $hist)
                        <div class="relative flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-blue-50 border-2 border-white text-blue-600 flex items-center justify-center shrink-0 z-10 text-xs font-bold shadow-sm">
                                •
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800">
                                    {{ \App\Enums\RegistrationStatus::tryFrom($hist->status_to)?->label() ?? $hist->status_to }}
                                </div>
                                @if ($hist->notes)
                                    <p class="text-xs text-slate-600 mt-0.5">{{ $hist->notes }}</p>
                                @endif
                                <div class="text-[10px] text-slate-400 mt-1">
                                    {{ $hist->created_at->translatedFormat('d M Y, H:i') }} &bull; oleh {{ $hist->user?->name ?? 'Sistem' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-slate-400 text-xs italic">Belum ada riwayat tercatat.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</x-layouts.admin>
