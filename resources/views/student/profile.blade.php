<x-layouts.student title="Profil Saya">
    <div class="max-w-4xl mx-auto space-y-6 py-6">

        {{-- Profile Header Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 h-28"></div>
            <div class="px-6 pb-6 -mt-10">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div class="flex items-end gap-4">
                        <div class="w-20 h-20 rounded-2xl bg-white border-4 border-white shadow-md flex items-center justify-center text-blue-700 font-bold text-3xl">
                            {{ mb_strtoupper(mb_substr($student?->name ?? $user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-slate-900">{{ $student?->name ?? $user->name }}</h1>
                            <p class="text-sm text-slate-500">{{ $student?->nim ?? 'NIM tidak tersedia' }}</p>
                        </div>
                    </div>
                        @php
                            $statusVal = $student?->student_status instanceof \BackedEnum ? $student->student_status->value : (string) ($student?->student_status ?? 'inactive');
                        @endphp
                        <span class="self-start sm:self-auto inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            {{ $statusVal === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ strtoupper($statusVal) }}
                        </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Informasi Akun</h2>
                @if ($student)
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-xs text-slate-400">Email</dt>
                            <dd class="font-medium text-slate-800 break-all">{{ $student->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400">Program Studi</dt>
                            <dd class="font-medium text-slate-800">{{ $student->study_program }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400">Angkatan</dt>
                            <dd class="font-medium text-slate-800">{{ $student->class_year }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400">Semester Saat Ini</dt>
                            <dd class="font-medium text-slate-800">Semester {{ $student->semester }}</dd>
                        </div>
                        @if ($student->phone)
                            <div>
                                <dt class="text-xs text-slate-400">Nomor Telepon</dt>
                                <dd class="font-medium text-slate-800">{{ $student->phone }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs text-slate-400">Google Terhubung</dt>
                            <dd class="font-medium {{ $student->google_id ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $student->google_id ? '✓ Terhubung' : 'Belum terhubung' }}
                            </dd>
                        </div>
                    </dl>
                @else
                    <p class="text-sm text-slate-500 italic">Data mahasiswa tidak ditemukan pada database.</p>
                @endif
            </div>

            {{-- Status DPT & Voting per Election --}}
            <div class="lg:col-span-2 space-y-4">
                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Status Pemilih &amp; Riwayat Pemilihan</h2>

                @forelse ($elections as $election)
                    @php
                        $voterRecord = $voterRecords->get($election->id);
                        $participated = $participations->get($election->id);
                    @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">{{ $election->name }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    Voting: {{ $election->voting_start->format('d/m/Y H:i') }} — {{ $election->voting_end->format('d/m/Y H:i') }} WITA
                                </div>
                            </div>
                            <span class="self-start inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                {{ $election->status->value === 'voting' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $election->status->value === 'published' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ in_array($election->status->value, ['draft', 'registration']) ? 'bg-slate-100 text-slate-600' : '' }}
                                {{ $election->status->value === 'closed' ? 'bg-amber-100 text-amber-700' : '' }}
                            ">
                                {{ strtoupper($election->status->value) }}
                            </span>
                        </div>

                        @php
                            $voterStatusVal = $voterRecord?->voter_status instanceof \BackedEnum ? $voterRecord->voter_status->value : (string) ($voterRecord?->voter_status ?? '');
                        @endphp
                        <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                            <div class="bg-slate-50 rounded-lg p-3">
                                <div class="text-slate-400">Status DPT</div>
                                <div class="font-semibold mt-0.5 {{ $voterStatusVal === 'eligible' ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $voterRecord ? strtoupper($voterStatusVal) : 'TIDAK TERDAFTAR' }}
                                </div>
                            </div>
                            <div class="bg-slate-50 rounded-lg p-3">
                                <div class="text-slate-400">Status Suara</div>
                                <div class="font-semibold mt-0.5 {{ $participated ? 'text-blue-600' : 'text-slate-400' }}">
                                    {{ $participated ? 'SUDAH MEMILIH' : 'BELUM MEMILIH' }}
                                </div>
                            </div>
                            @if ($participated)
                                <div class="bg-emerald-50 rounded-lg p-3">
                                    <div class="text-emerald-500">Waktu Memilih</div>
                                    <div class="font-semibold text-emerald-700 mt-0.5">{{ $participated->voted_at->format('d/m H:i') }}</div>
                                </div>
                            @endif
                        </div>

                        @if ($voterStatusVal === 'eligible' && ! $participated && $election->status->value === 'voting')
                            <div class="mt-4">
                                <a href="{{ route('student.voting.show', $election) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    Gunakan Hak Pilih Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-8 text-center text-slate-400 italic text-sm">
                        Belum ada periode pemilihan yang aktif.
                    </div>
                @endforelse

                {{-- Riwayat Pendaftaran Bakal Calon --}}
                @if ($registrations->isNotEmpty())
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider pt-2">Riwayat Pendaftaran Bakal Calon</h2>
                    @foreach ($registrations as $reg)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">{{ $reg->election->name }}</div>
                                    <div class="text-xs text-slate-500">No. Pendaftaran: {{ $reg->registration_number ?? 'Draft' }}</div>
                                </div>
                                <span class="self-start inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ in_array($reg->status, ['verified', 'established']) ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $reg->status === 'rejected' ? 'bg-rose-100 text-rose-700' : '' }}
                                    {{ in_array($reg->status, ['draft', 'submitted']) ? 'bg-slate-100 text-slate-600' : '' }}
                                    {{ $reg->status === 'revision_required' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $reg->status === 'under_review' ? 'bg-blue-100 text-blue-700' : '' }}
                                ">
                                    {{ strtoupper($reg->status) }}
                                </span>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('registration.show', $reg) }}" class="text-xs text-blue-600 hover:underline">
                                    Lihat detail pendaftaran →
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-layouts.student>
