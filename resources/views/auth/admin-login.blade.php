<x-layouts.auth title="Masuk Panitia / Admin">

    <div class="text-center mb-6">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
            Otoritas Khusus
        </span>
        <h2 class="text-base font-bold text-zinc-900 tracking-tight">Panel Panitia &amp; Pengawas</h2>
        <p class="text-xs text-zinc-500 mt-0.5">Masuk dengan kredensial administrator</p>
    </div>

    {{-- Error --}}
    @if ($errors->any())
        <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-xs text-rose-800">
            <div class="flex items-start gap-2.5">
                <svg class="w-4 h-4 text-rose-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.25a.75.75 0 011.5 0v4.5a.75.75 0 01-1.5 0v-4.5zm.75 7.5a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                </svg>
                <div class="space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1.5">Email Administrator</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                class="block w-full rounded-xl border border-zinc-300 px-3.5 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition bg-white"
                placeholder="admin@himakom.ac.id"
            />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-semibold text-zinc-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                class="block w-full rounded-xl border border-zinc-300 px-3.5 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition bg-white"
                placeholder="••••••••"
            />
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full mt-2 flex justify-center items-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-600 active:bg-amber-700 px-4 py-2.5 text-sm font-bold text-zinc-950 transition shadow-xs focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            Masuk ke Panel Admin
        </button>
    </form>

    {{-- Link ke halaman mahasiswa --}}
    <div class="mt-6 pt-4 border-t border-zinc-100 text-center flex items-center justify-between text-xs">
        <a href="{{ route('home') }}" class="text-zinc-500 hover:text-zinc-900 transition font-medium">
            &larr; Beranda
        </a>
        <a href="{{ route('login') }}" class="text-zinc-600 hover:text-zinc-900 font-semibold transition">
            Login Mahasiswa &rarr;
        </a>
    </div>

</x-layouts.auth>
