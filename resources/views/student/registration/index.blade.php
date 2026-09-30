<x-layouts.student title="Pendaftaran Bakal Calon">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Pendaftaran Bakal Calon</h1>
            <p class="text-sm text-slate-500 mt-0.5">Riwayat pendaftaran Anda sebagai bakal calon</p>
        </div>
        @if ($openElection)
            <a href="{{ route('registration.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Daftar Sekarang
            </a>
        @endif
    </div>

    @if ($openElection && $registrations->isEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-blue-900">Pendaftaran Sedang Dibuka!</h3>
                <p class="text-sm text-blue-700 mt-1">
                    Anda belum mendaftar untuk <strong>{{ $openElection->name }}</strong>.
                    Pendaftaran ditutup pada <strong>{{ $openElection->registration_end->translatedFormat('d F Y, H:i') }}</strong> WITA.
                </p>
                <a href="{{ route('registration.create') }}" class="inline-flex items-center gap-1.5 mt-3 text-sm font-semibold text-blue-700 hover:text-blue-900">
                    Mulai Pendaftaran →
                </a>
            </div>
        </div>
    @endif

    @forelse ($registrations as $reg)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-4">
            <div class="p-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900">{{ $reg->election->name }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">
                            {{ $reg->chairman->name }} &amp; {{ $reg->viceChairman?->name ?? '—' }}
                        </div>
                        @if ($reg->registration_number)
                            <div class="text-xs font-mono text-slate-400">No. {{ $reg->registration_number }}</div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    @php
                        $statusColor = match($reg->status->value) {
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
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                        {{ $reg->status->label() }}
                    </span>
                    <a href="{{ route('registration.show', $reg) }}"
                       class="px-3.5 py-1.5 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        @if (! $openElection)
            <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center">
                <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-700">Belum ada pendaftaran</h3>
                <p class="text-sm text-slate-400 mt-1">Pendaftaran bakal calon belum dibuka oleh panitia.</p>
            </div>
        @endif
    @endforelse

</x-layouts.student>
