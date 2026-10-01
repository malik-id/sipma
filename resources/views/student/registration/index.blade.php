<x-layouts.student title="Pendaftaran Bakal Calon">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-zinc-900">Pendaftaran Bakal Calon</h1>
            <p class="text-sm text-zinc-500 mt-0.5">Riwayat pendaftaran Anda sebagai bakal calon ketua / wakil</p>
        </div>
        @if ($openElection)
            <a href="{{ route('registration.create') }}"
               class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-amber-500 text-zinc-950 text-sm font-bold rounded-xl hover:bg-amber-400 shadow-xs transition cursor-pointer"
               title="Daftar Sekarang">
                <span class="material-symbols-outlined text-lg">add_circle</span>
                <span class="hidden sm:inline">Daftar Sekarang</span>
            </a>
        @endif
    </div>

    @if ($openElection && $registrations->isEmpty())
        <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-6 mb-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-amber-500 text-zinc-950 flex items-center justify-center shrink-0 mt-0.5 font-bold shadow-xs">
                <span class="material-symbols-outlined text-xl">info</span>
            </div>
            <div>
                <h3 class="font-bold text-zinc-900">Pendaftaran Sedang Dibuka!</h3>
                <p class="text-sm text-zinc-600 mt-1">
                    Anda belum mendaftar untuk <strong>{{ $openElection->name }}</strong>.
                    Pendaftaran ditutup pada <strong>{{ $openElection->registration_end->translatedFormat('d F Y, H:i') }}</strong> WITA.
                </p>
                <a href="{{ route('registration.create') }}" class="inline-flex items-center gap-1.5 mt-3 text-sm font-bold text-amber-600 hover:text-amber-500">
                    <span>Mulai Pendaftaran</span>
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>
        </div>
    @endif

    @forelse ($registrations as $reg)
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs overflow-hidden mb-4 hover:border-amber-400 transition">
            <div class="p-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-zinc-100 flex items-center justify-center text-zinc-600 shrink-0">
                        <span class="material-symbols-outlined text-2xl text-amber-500">description</span>
                    </div>
                    <div>
                        <div class="font-bold text-zinc-900">{{ $reg->election->name }}</div>
                        <div class="text-xs text-zinc-500 mt-0.5">
                            {{ $reg->chairman->name }} &amp; {{ $reg->viceChairman?->name ?? '—' }}
                        </div>
                        @if ($reg->registration_number)
                            <div class="text-xs font-mono text-zinc-400">No. {{ $reg->registration_number }}</div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    @php
                        $statusColor = match($reg->status->value) {
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
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                        {{ $reg->status->label() }}
                    </span>
                    <a href="{{ route('registration.show', $reg) }}"
                       class="px-3.5 py-1.5 text-xs font-semibold text-zinc-700 bg-zinc-100 hover:bg-zinc-200 rounded-lg transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        @if (! $openElection)
            <div class="bg-white rounded-2xl border border-zinc-200 p-10 text-center">
                <div class="w-14 h-14 rounded-full bg-zinc-100 text-zinc-400 flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-3xl">folder_off</span>
                </div>
                <h3 class="font-bold text-zinc-700">Belum ada pendaftaran</h3>
                <p class="text-sm text-zinc-400 mt-1">Pendaftaran bakal calon belum dibuka oleh panitia.</p>
            </div>
        @endif
    @endforelse

</x-layouts.student>
