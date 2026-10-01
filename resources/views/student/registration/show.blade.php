<x-layouts.student title="Detail Pendaftaran">

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <a href="{{ route('registration.index') }}" class="text-xs text-zinc-500 hover:text-zinc-900 flex items-center gap-1 mb-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Kembali</span>
            </a>
            <h1 class="text-xl font-bold text-zinc-900">Detail Pendaftaran Bakal Calon</h1>
            <p class="text-sm text-zinc-500 mt-0.5">{{ $registration->election->name }}</p>
        </div>

        @php
            $statusColor = match($registration->status->value) {
                'draft' => 'bg-zinc-100 text-zinc-700',
                'submitted', 'resubmitted' => 'bg-amber-500/10 text-amber-600 border border-amber-500/20',
                'under_review' => 'bg-yellow-100 text-yellow-800',
                'revision_required' => 'bg-orange-100 text-orange-800',
                'verified' => 'bg-emerald-100 text-emerald-800',
                'established' => 'bg-indigo-100 text-indigo-800',
                'rejected' => 'bg-rose-100 text-rose-800',
                default => 'bg-zinc-100 text-zinc-700',
            };
        @endphp
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold {{ $statusColor }} shrink-0">
            {{ $registration->status->label() }}
        </span>
    </div>

    {{-- Notif Perlu Perbaikan --}}
    @if ($registration->status->value === 'revision_required')
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6 flex items-start gap-3">
            <span class="material-symbols-outlined text-orange-600 text-xl shrink-0 mt-0.5">warning</span>
            <div>
                <h3 class="font-bold text-orange-900 text-sm">Pendaftaran Perlu Perbaikan</h3>
                <p class="text-sm text-orange-800 mt-1">{{ $registration->revision_notes ?? 'Silakan periksa dokumen yang ditandai dan perbaiki sesuai arahan panitia.' }}</p>
                @if ($registration->revision_deadline)
                    <p class="text-xs text-orange-700 mt-2">Batas waktu perbaikan: <strong>{{ $registration->revision_deadline->translatedFormat('d F Y, H:i') }}</strong> WITA</p>
                @endif
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Utama --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Data Pasangan --}}
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xs font-bold text-zinc-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base text-amber-500">group</span>
                        Data Pasangan Calon
                    </h2>
                </div>
                <div class="flex flex-col sm:flex-row gap-5 items-start">
                    <div class="flex flex-col items-center gap-2 shrink-0">
                        <div class="w-32 h-40 rounded-xl bg-zinc-100 border border-zinc-200 overflow-hidden flex flex-col items-center justify-center relative group">
                            @if ($registration->photo_path)
                                <img src="{{ asset('storage/' . $registration->photo_path) }}" alt="Foto Pasangan" class="w-full h-full object-cover" />
                            @else
                                <div class="text-zinc-400 text-center p-2">
                                    <span class="material-symbols-outlined text-3xl mx-auto mb-1 opacity-50">image</span>
                                    <span class="text-[10px] block leading-tight font-medium">Belum ada foto</span>
                                </div>
                            @endif

                            @if (in_array($registration->status->value, ['draft', 'revision_required']))
                                <form method="POST" action="{{ route('registration.upload-photo', $registration) }}" enctype="multipart/form-data" class="absolute inset-0 bg-zinc-950/70 opacity-0 group-hover:opacity-100 flex items-center justify-center transition cursor-pointer">
                                    @csrf
                                    <input type="file" name="photo" id="photo_input" class="hidden" accept="image/png,image/jpeg,image/jpg" onchange="this.form.submit()" />
                                    <label for="photo_input" class="cursor-pointer text-amber-400 text-xs font-bold text-center p-2 hover:underline flex flex-col items-center gap-1">
                                        <span class="material-symbols-outlined text-base">photo_camera</span>
                                        Ganti Foto
                                    </label>
                                </form>
                            @endif
                        </div>

                        @if (in_array($registration->status->value, ['draft', 'revision_required']))
                            <label for="photo_input" class="sm:hidden cursor-pointer text-[11px] font-bold text-amber-700 hover:text-amber-800 bg-amber-500/10 border border-amber-500/20 px-3 py-1 rounded-lg flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">photo_camera</span>
                                <span>{{ $registration->photo_path ? 'Ganti Foto' : 'Unggah Foto' }}</span>
                            </label>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1 w-full">
                        <div class="bg-zinc-50 border border-zinc-100 rounded-xl p-4">
                            <div class="text-xs text-zinc-400 font-semibold uppercase tracking-wider mb-1">Calon Ketua</div>
                            <div class="font-bold text-zinc-900">{{ $registration->chairman->name }}</div>
                            <div class="text-xs text-zinc-500 mt-0.5">{{ $registration->chairman->nim }}</div>
                            <div class="text-xs text-zinc-500">Semester {{ $registration->chairman->semester }}</div>
                            @if ($registration->chairman_phone)
                                <div class="text-xs text-zinc-500 mt-1 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs text-emerald-600">call</span>
                                    {{ $registration->chairman_phone }}
                                </div>
                            @endif
                        </div>
                        <div class="bg-zinc-50 border border-zinc-100 rounded-xl p-4">
                            <div class="text-xs text-zinc-400 font-semibold uppercase tracking-wider mb-1">Calon Wakil Ketua</div>
                            @if ($registration->viceChairman)
                                <div class="font-bold text-zinc-900">{{ $registration->viceChairman->name }}</div>
                                <div class="text-xs text-zinc-500 mt-0.5">{{ $registration->viceChairman->nim }}</div>
                                <div class="text-xs text-zinc-500">Semester {{ $registration->viceChairman->semester }}</div>
                                @if ($registration->vice_chairman_phone)
                                    <div class="text-xs text-zinc-500 mt-1 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs text-emerald-600">call</span>
                                        {{ $registration->vice_chairman_phone }}
                                    </div>
                                @endif
                            @else
                                <div class="text-zinc-400 text-sm">Belum diisi</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Visi Misi --}}
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xs font-bold text-zinc-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base text-amber-500">lightbulb</span>
                        Visi, Misi &amp; Program Kerja
                    </h2>
                    @if (in_array($registration->status->value, ['draft', 'revision_required']))
                        <a href="{{ route('registration.edit', $registration) }}" class="text-xs text-amber-600 hover:text-amber-500 font-bold flex items-center gap-0.5">
                            <span>Edit</span>
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </a>
                    @endif
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Visi</div>
                        <p class="text-sm text-zinc-700 leading-relaxed">{{ $registration->vision ?: '—' }}</p>
                    </div>
                    @if ($registration->mission)
                        <div>
                            <div class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Misi</div>
                            <ol class="list-decimal list-inside space-y-1 text-sm text-zinc-700">
                                @foreach ($registration->mission as $poin)
                                    <li>{{ $poin }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                    @if ($registration->programs->isNotEmpty())
                        <div>
                            <div class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Program Kerja</div>
                            <ul class="space-y-2">
                                @foreach ($registration->programs as $prog)
                                    <li class="text-sm">
                                        <span class="font-bold text-zinc-900">{{ $prog->title }}</span>
                                        @if ($prog->description)
                                            <span class="text-zinc-500"> — {{ $prog->description }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Dokumen Persyaratan --}}
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-5">
                <h2 class="text-xs font-bold text-zinc-800 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-amber-500">folder</span>
                    Dokumen Persyaratan
                </h2>
                <div class="space-y-3">
                    @foreach ($registration->election->requirements->where('active', true)->where('type', 'file') as $req)
                        @php
                            $doc = $registration->currentDocuments->firstWhere('requirement_id', $req->id);
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-xl border {{ $doc ? 'border-emerald-200 bg-emerald-50/50' : 'border-zinc-200 bg-zinc-50' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $doc ? 'bg-emerald-100 text-emerald-600' : 'bg-zinc-200 text-zinc-400' }}">
                                    @if ($doc)
                                        <span class="material-symbols-outlined text-lg text-emerald-600">check_circle</span>
                                    @else
                                        <span class="material-symbols-outlined text-lg text-zinc-400">upload_file</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-zinc-800">
                                        {{ $req->name }}
                                        @if ($req->required) <span class="text-rose-500">*</span> @endif
                                    </div>
                                    @if ($doc)
                                        <div class="text-xs text-zinc-400 truncate max-w-xs">{{ $doc->original_filename }}</div>
                                    @else
                                        <div class="text-xs text-zinc-400">Belum diunggah</div>
                                    @endif
                                </div>
                            </div>

                            @if (in_array($registration->status->value, ['draft', 'revision_required']))
                                <form method="POST"
                                      action="{{ route('registration.upload-document', [$registration, $req]) }}"
                                      enctype="multipart/form-data"
                                      class="flex items-center gap-2">
                                    @csrf
                                    <input type="file" name="document" id="doc_{{ $req->id }}"
                                        accept="{{ implode(',', array_map(fn($e) => '.'.$e, $req->allowed_extensions ?? ['pdf'])) }}"
                                        class="hidden"
                                        onchange="this.form.submit()" />
                                    <label for="doc_{{ $req->id }}"
                                        class="cursor-pointer px-3 py-1.5 text-xs font-bold text-zinc-950 bg-amber-500 hover:bg-amber-400 rounded-lg shadow-xs transition flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">upload</span>
                                        {{ $doc ? 'Ganti' : 'Unggah' }}
                                    </label>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            {{-- Aksi --}}
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-5">
                <h2 class="text-xs font-bold text-zinc-800 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-amber-500">bolt</span>
                    Aksi
                </h2>
                <div class="space-y-2">
                    @if ($registration->status->value === 'draft')
                        <form method="POST" action="{{ route('registration.submit', $registration) }}"
                            onsubmit="return confirm('Yakin ingin mengajukan pendaftaran? Pastikan semua dokumen sudah lengkap.')">
                            @csrf
                            <button type="submit"
                                class="w-full py-2.5 bg-amber-500 text-zinc-950 text-sm font-bold rounded-xl hover:bg-amber-400 shadow-xs transition cursor-pointer flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-lg">send</span>
                                Ajukan Pendaftaran
                            </button>
                        </form>
                        <a href="{{ route('registration.edit', $registration) }}"
                            class="block w-full py-2.5 text-center text-xs font-bold text-zinc-700 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition">
                            Edit Draft
                        </a>
                    @elseif ($registration->status->value === 'revision_required')
                        <form method="POST" action="{{ route('registration.resubmit', $registration) }}"
                            onsubmit="return confirm('Yakin ingin mengirim ulang pendaftaran?')">
                            @csrf
                            <button type="submit"
                                class="w-full py-2.5 bg-amber-500 text-zinc-950 text-sm font-bold rounded-xl hover:bg-amber-400 shadow-xs transition cursor-pointer flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-lg">replay</span>
                                Kirim Ulang Pendaftaran
                            </button>
                        </form>
                        <a href="{{ route('registration.edit', $registration) }}"
                            class="block w-full py-2.5 text-center text-xs font-bold text-zinc-700 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition">
                            Edit & Perbaiki
                        </a>
                    @endif
                </div>
            </div>

            {{-- Riwayat Status --}}
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-5">
                <h2 class="text-xs font-bold text-zinc-800 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-amber-500">history</span>
                    Riwayat Status
                </h2>
                <ol class="relative border-l border-zinc-200 space-y-4 ml-2">
                    @foreach ($registration->histories as $history)
                        <li class="ml-4">
                            <div class="absolute w-2.5 h-2.5 bg-amber-500 rounded-full -left-1.5 border-2 border-white"></div>
                            <div class="text-xs font-bold text-zinc-800">{{ \App\Enums\RegistrationStatus::tryFrom($history->status_to)?->label() ?? $history->status_to }}</div>
                            @if ($history->notes)
                                <p class="text-xs text-zinc-500 mt-0.5">{{ $history->notes }}</p>
                            @endif
                            <div class="text-xs text-zinc-400 mt-0.5">{{ $history->created_at->translatedFormat('d M Y, H:i') }}</div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>

</x-layouts.student>
