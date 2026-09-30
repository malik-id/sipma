<x-layouts.admin title="Audit Log">
    <x-slot:header>
        Audit Log &amp; Riwayat Aktivitas Sistem
    </x-slot:header>

    <div class="space-y-6">
        {{-- Intro Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Catatan Aktivitas dan Mutasi Data</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Seluruh tindakan administratif, penetapan calon, perubahan periode, impor data, dan autentikasi terekam secara permanen untuk transparansi dan auditabilitas.
                    </p>
                </div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium self-start md:self-auto">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Audit Log Terkunci &amp; Immutable</span>
                </div>
            </div>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Cari Keyword</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Entitas, IP, Nama..."
                           class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-1.5 px-3">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Jenis Aksi</label>
                    <select name="action" class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-1.5 px-3">
                        <option value="">-- Semua Aksi --</option>
                        @foreach ($distinctActions as $act)
                            <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Pelaksana / User</label>
                    <select name="user_id" class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-1.5 px-3">
                        <option value="">-- Semua User --</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ strtoupper($u->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-1.5 px-3">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-3 rounded-lg transition shadow-sm">
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'action', 'user_id', 'date_from', 'date_to']))
                        <a href="{{ route('admin.audit-logs.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium py-2 px-3 rounded-lg transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Waktu (WITA)</th>
                            <th class="py-3 px-4">Aktor / Pelaksana</th>
                            <th class="py-3 px-4">Aksi</th>
                            <th class="py-3 px-4">Entitas Terkait</th>
                            <th class="py-3 px-4">Detail Perubahan</th>
                            <th class="py-3 px-4">IP / Info</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-slate-50/75 transition">
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-600 font-mono">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if ($log->user)
                                        <div class="font-medium text-slate-900">{{ $log->user->name }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $log->user->email }} ({{ strtoupper($log->user->role) }})</div>
                                    @else
                                        <span class="text-slate-400 italic">Sistem / Anonim</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-medium
                                        {{ str_starts_with($log->action, 'auth') || str_starts_with($log->action, 'admin.login') ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : '' }}
                                        {{ str_starts_with($log->action, 'election') ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                        {{ str_starts_with($log->action, 'candidate') || str_starts_with($log->action, 'registration') ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}
                                        {{ str_starts_with($log->action, 'voter') || str_starts_with($log->action, 'student') ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                        {{ str_starts_with($log->action, 'setting') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                    ">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="text-slate-900 font-medium">{{ class_basename($log->entity_type) }}</div>
                                    @if ($log->entity_id)
                                        <div class="text-[11px] text-slate-400 font-mono">ID: {{ $log->entity_id }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="max-w-xs xl:max-w-md max-h-24 overflow-y-auto text-[11px] font-mono bg-slate-50 border border-slate-200 rounded p-2 text-slate-700 space-y-1">
                                        @if ($log->old_values)
                                            <div><strong class="text-rose-600">Old:</strong> {{ json_encode($log->old_values, JSON_UNESCAPED_UNICODE) }}</div>
                                        @endif
                                        @if ($log->new_values)
                                            <div><strong class="text-emerald-600">New:</strong> {{ json_encode($log->new_values, JSON_UNESCAPED_UNICODE) }}</div>
                                        @endif
                                        @if (! $log->old_values && ! $log->new_values)
                                            <span class="text-slate-400 italic">Tidak ada payload data</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-500 font-mono text-[11px]">
                                    <div>{{ $log->ip_address ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 truncate max-w-[140px]" title="{{ $log->user_agent }}">{{ $log->user_agent ?? '-' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 italic">
                                    Tidak ada catatan audit yang cocok dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
