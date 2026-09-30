<x-layouts.student title="Detail Pendaftaran">

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <a href="{{ route('registration.index') }}" class="text-xs text-slate-500 hover:text-slate-800 flex items-center gap-1 mb-2">← Kembali</a>
            <h1 class="text-xl font-bold text-slate-900">Detail Pendaftaran Bakal Calon</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ $registration->election->name }}</p>
        </div>

        @php
            $statusColor = match($registration->status->value) {
                'draft' => 'bg-slate-100 text-slate-700',
                'submitted', 'resubmitted' => 'bg-blue-100 text-blue-700',
                'under_review' => 'bg-yellow-100 text-yellow-700',
                'revision_required' => 'bg-orange-100 text-orange-700',
                'verified' => 'bg-emerald-100 text-emerald-700',
                'established' => 'bg-indigo-100 text-indigo-700',
                'rejected' => 'bg-rose-100 text-rose-700',
                default => 'bg-slate-100 text-slate-700',
            };
        @endphp
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold {{ $statusColor }} shrink-0">
            {{ $registration->status->label() }}
        </span>
    </div>

    {{-- Notif Perlu Perbaikan --}}
    @if ($registration->status->value === 'revision_required')
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6">
            <h3 class="font-semibold text-orange-900 text-sm">Pendaftaran Perlu Perbaikan</h3>
            <p class="text-sm text-orange-800 mt-1">{{ $registration->revision_notes ?? 'Silakan periksa dokumen yang ditandai dan perbaiki sesuai arahan panitia.' }}</p>
            @if ($registration->revision_deadline)
                <p class="text-xs text-orange-700 mt-2">Batas waktu perbaikan: <strong>{{ $registration->revision_deadline->translatedFormat('d F Y, H:i') }}</strong> WITA</p>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Utama --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Data Pasangan --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Data Pasangan Calon</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <div class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Calon Ketua</div>
                        <div class="font-bold text-slate-900">{{ $registration->chairman->name }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $registration->chairman->nim }}</div>
                        <div class="text-xs text-slate-500">Semester {{ $registration->chairman->semester }}</div>
                        @if ($registration->chairman_phone)
                            <div class="text-xs text-slate-500 mt-1">📱 {{ $registration->chairman_phone }}</div>
                        @endif
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <div class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Calon Wakil Ketua</div>
                        @if ($registration->viceChairman)
                            <div class="font-bold text-slate-900">{{ $registration->viceChairman->name }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $registration->viceChairman->nim }}</div>
                            <div class="text-xs text-slate-500">Semester {{ $registration->viceChairman->semester }}</div>
                            @if ($registration->vice_chairman_phone)
                                <div class="text-xs text-slate-500 mt-1">📱 {{ $registration->vice_chairman_phone }}</div>
                            @endif
                        @else
                            <div class="text-slate-400 text-sm">Belum diisi</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Visi Misi --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Visi, Misi &amp; Program Kerja</h2>
                    @if (in_array($registration->status->value, ['draft', 'revision_required']))
                        <a href="{{ route('registration.edit', $registration) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Edit →</a>
                    @endif
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Visi</div>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $registration->vision ?: '—' }}</p>
                    </div>
                    @if ($registration->mission)
                        <div>
                            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Misi</div>
                            <ol class="list-decimal list-inside space-y-1 text-sm text-slate-700">
                                @foreach ($registration->mission as $poin)
                                    <li>{{ $poin }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                    @if ($registration->programs->isNotEmpty())
                        <div>
                            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Program Kerja</div>
                            <ul class="space-y-2">
                                @foreach ($registration->programs as $prog)
                                    <li class="text-sm">
                                        <span class="font-semibold text-slate-800">{{ $prog->title }}</span>
                                        @if ($prog->description)
                                            <span class="text-slate-500"> — {{ $prog->description }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Dokumen Persyaratan --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Dokumen Persyaratan</h2>
                <div class="space-y-3">
                    @foreach ($registration->election->requirements->where('active', true)->where('type', 'file') as $req)
                        @php
                            $doc = $registration->currentDocuments->firstWhere('requirement_id', $req->id);
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-xl border {{ $doc ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-200 bg-slate-50' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $doc ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-400' }}">
                                    @if ($doc)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-800">
                                        {{ $req->name }}
                                        @if ($req->required) <span class="text-rose-500">*</span> @endif
                                    </div>
                                    @if ($doc)
                                        <div class="text-xs text-slate-400 truncate max-w-xs">{{ $doc->original_filename }}</div>
                                    @else
                                        <div class="text-xs text-slate-400">Belum diunggah</div>
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
                                        class="cursor-pointer px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
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
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Aksi</h2>
                <div class="space-y-2">
                    @if ($registration->status->value === 'draft')
                        <form method="POST" action="{{ route('registration.submit', $registration) }}"
                            onsubmit="return confirm('Yakin ingin mengajukan pendaftaran? Pastikan semua dokumen sudah lengkap.')">
                            @csrf
                            <button type="submit"
                                class="w-full py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                                Ajukan Pendaftaran
                            </button>
                        </form>
                        <a href="{{ route('registration.edit', $registration) }}"
                            class="block w-full py-2.5 text-center text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                            Edit Draft
                        </a>
                    @elseif ($registration->status->value === 'revision_required')
                        <form method="POST" action="{{ route('registration.resubmit', $registration) }}"
                            onsubmit="return confirm('Yakin ingin mengirim ulang pendaftaran?')">
                            @csrf
                            <button type="submit"
                                class="w-full py-2.5 bg-orange-600 text-white text-sm font-semibold rounded-xl hover:bg-orange-700 transition">
                                Kirim Ulang Pendaftaran
                            </button>
                        </form>
                        <a href="{{ route('registration.edit', $registration) }}"
                            class="block w-full py-2.5 text-center text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                            Edit & Perbaiki
                        </a>
                    @endif
                </div>
            </div>

            {{-- Riwayat Status --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Riwayat Status</h2>
                <ol class="relative border-l border-slate-200 space-y-4 ml-2">
                    @foreach ($registration->histories as $history)
                        <li class="ml-4">
                            <div class="absolute w-2.5 h-2.5 bg-blue-500 rounded-full -left-1.5 border border-white"></div>
                            <div class="text-xs font-semibold text-slate-800">{{ RegistrationStatus::from($history->status_to)->label() }}</div>
                            @if ($history->notes)
                                <p class="text-xs text-slate-500 mt-0.5">{{ $history->notes }}</p>
                            @endif
                            <div class="text-xs text-slate-400 mt-0.5">{{ $history->created_at->translatedFormat('d M Y, H:i') }}</div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>

</x-layouts.student>
