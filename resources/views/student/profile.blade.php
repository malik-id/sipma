<x-layouts.student title="Profil Saya">
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Profile Header Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden">
            <div class="bg-zinc-950 h-24 border-b border-zinc-800"></div>
            <div class="px-6 pb-6 -mt-10">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div class="flex items-end gap-4">
                        <div class="w-20 h-20 rounded-2xl bg-white border-4 border-white shadow-md flex items-center justify-center text-zinc-900 font-extrabold text-2xl bg-amber-400">
                            {{ mb_strtoupper(mb_substr($student?->name ?? $user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-zinc-900">{{ $student?->name ?? $user->name }}</h1>
                            <p class="text-xs sm:text-sm text-zinc-500 font-mono">{{ $student?->nim ?? 'NIM tidak tersedia' }}</p>
                        </div>
                    </div>
                    @php
                        $statusVal = $student?->student_status instanceof \BackedEnum ? $student->student_status->value : (string) ($student?->student_status ?? 'inactive');
                    @endphp
                    <span class="self-start sm:self-auto inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                        {{ $statusVal === 'active' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
                        {{ strtoupper($statusVal) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Informasi Mahasiswa</h2>
                @if ($student)
                    <dl class="space-y-3 text-xs sm:text-sm">
                        <div>
                            <dt class="text-[11px] text-zinc-400 font-bold uppercase">Email</dt>
                            <dd class="font-semibold text-zinc-900 break-all">{{ $student->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] text-zinc-400 font-bold uppercase">Program Studi</dt>
                            <dd class="font-semibold text-zinc-900">{{ $student->study_program }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] text-zinc-400 font-bold uppercase">Angkatan</dt>
                            <dd class="font-semibold text-zinc-900">{{ $student->class_year }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] text-zinc-400 font-bold uppercase">Semester Saat Ini</dt>
                            <dd class="font-semibold text-zinc-900">Semester {{ $student->semester }}</dd>
                        </div>
                        @if ($student->phone)
                            <div>
                                <dt class="text-[11px] text-zinc-400 font-bold uppercase">Nomor Telepon</dt>
                                <dd class="font-semibold text-zinc-900">{{ $student->phone }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-[11px] text-zinc-400 font-bold uppercase">Google Terhubung</dt>
                            <dd class="font-bold {{ $student->google_id ? 'text-emerald-700' : 'text-zinc-400' }}">
                                {{ $student->google_id ? '✓ Terhubung' : 'Belum terhubung' }}
                            </dd>
                        </div>
                    </dl>
                @else
                    <p class="text-xs text-zinc-500 italic">Data mahasiswa tidak ditemukan pada database.</p>
                @endif
            </div>

            {{-- Status DPT & Voting per Election --}}
            <div class="lg:col-span-2 space-y-4">
                <h2 class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Status Hak Suara &amp; Riwayat</h2>

                @forelse ($elections as $election)
                    @php
                        $voterRecord = $voterRecords->get($election->id);
                        $participated = $participations->get($election->id);
                    @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-5">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold text-zinc-900">{{ $election->name }}</div>
                                <div class="text-xs text-zinc-500 mt-0.5">
                                    Voting: {{ $election->voting_start->format('d/m/Y H:i') }} — {{ $election->voting_end->format('d/m/Y H:i') }} WITA
                                </div>
                            </div>
                            <span class="self-start inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                {{ $election->status->value === 'voting' ? 'bg-amber-100 text-amber-900 border border-amber-300' : '' }}
                                {{ $election->status->value === 'published' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ in_array($election->status->value, ['draft', 'registration']) ? 'bg-zinc-100 text-zinc-700' : '' }}
                                {{ $election->status->value === 'closed' ? 'bg-zinc-200 text-zinc-800' : '' }}
                            ">
                                {{ strtoupper($election->status->value) }}
                            </span>
                        </div>

                        @php
                            $voterStatusVal = $voterRecord?->voter_status instanceof \BackedEnum ? $voterRecord->voter_status->value : (string) ($voterRecord?->voter_status ?? '');
                        @endphp
                        <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                            <div class="bg-zinc-50 rounded-lg p-3 border border-zinc-100">
                                <div class="text-zinc-400 font-medium">Status DPT</div>
                                <div class="font-bold mt-0.5 {{ $voterStatusVal === 'eligible' ? 'text-emerald-700' : 'text-zinc-600' }}">
                                    {{ $voterRecord ? strtoupper($voterStatusVal) : 'TIDAK TERDAFTAR' }}
                                </div>
                            </div>
                            <div class="bg-zinc-50 rounded-lg p-3 border border-zinc-100">
                                <div class="text-zinc-400 font-medium">Status Suara</div>
                                <div class="font-bold mt-0.5 {{ $participated ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $participated ? 'SUDAH MEMILIH' : 'BELUM MEMILIH' }}
                                </div>
                            </div>
                            @if ($participated)
                                <div class="bg-emerald-50 rounded-lg p-3 border border-emerald-100">
                                    <div class="text-emerald-600 font-medium">Waktu Memilih</div>
                                    <div class="font-bold text-emerald-800 mt-0.5">{{ $participated->voted_at->format('d/m H:i') }}</div>
                                </div>
                            @endif
                        </div>

                        @if ($voterStatusVal === 'eligible' && ! $participated && $election->status->value === 'voting')
                            <div class="mt-4">
                                <a href="{{ route('student.voting.show', $election) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-bold rounded-lg transition shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">how_to_vote</span>
                                    Gunakan Hak Pilih Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-zinc-50 rounded-2xl border border-zinc-200 p-8 text-center text-zinc-400 italic text-xs sm:text-sm">
                        Belum ada periode pemilihan yang aktif.
                    </div>
                @endforelse

                {{-- Riwayat Pendaftaran Bakal Calon --}}
                @if ($registrations->isNotEmpty())
                    <h2 class="text-xs font-bold text-zinc-500 uppercase tracking-wider pt-2">Riwayat Pendaftaran Bakal Calon</h2>
                    @foreach ($registrations as $reg)
                        <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-5">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div>
                                    <div class="text-sm font-bold text-zinc-900">{{ $reg->election->name }}</div>
                                    <div class="text-xs text-zinc-500 font-mono">No. Pendaftaran: {{ $reg->registration_number ?? 'Draft' }}</div>
                                </div>
                                <span class="self-start inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                    {{ in_array($reg->status, ['verified', 'established']) ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : '' }}
                                    {{ $reg->status === 'rejected' ? 'bg-rose-50 text-rose-800 border border-rose-200' : '' }}
                                    {{ in_array($reg->status, ['draft', 'submitted']) ? 'bg-zinc-100 text-zinc-700' : '' }}
                                    {{ $reg->status === 'revision_required' ? 'bg-amber-50 text-amber-800 border border-amber-200' : '' }}
                                    {{ $reg->status === 'under_review' ? 'bg-amber-100 text-amber-900 border border-amber-300' : '' }}
                                ">
                                    {{ strtoupper($reg->status) }}
                                </span>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('registration.show', $reg) }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">
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
