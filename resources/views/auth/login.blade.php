<x-layouts.auth title="Masuk Mahasiswa">

    <div class="text-center mb-6">
        <h2 class="text-base font-bold text-zinc-900 tracking-tight">Portal Mahasiswa</h2>
        <p class="text-xs text-zinc-500 mt-0.5">Silakan masuk menggunakan akun Google terdaftar</p>
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

    {{-- Tombol Login Google --}}
    <a href="{{ route('login.google') }}"
       class="flex w-full items-center justify-center gap-3 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-white px-4 py-3 text-sm font-semibold transition border border-zinc-900 shadow-xs active:scale-[0.99]">
        {{-- Google Icon --}}
        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Masuk dengan Akun Google
    </a>

    <div class="mt-5 text-center">
        <p class="text-[11px] text-zinc-400 leading-relaxed">
            Pastikan email Google Anda telah terdaftar di database mahasiswa Universitas Mega Buana Palopo.
        </p>
    </div>

    {{-- Link ke halaman admin --}}
    <div class="mt-6 pt-4 border-t border-zinc-100 text-center flex items-center justify-between text-xs">
        <a href="{{ route('home') }}" class="text-zinc-500 hover:text-zinc-900 transition font-medium">
            &larr; Beranda
        </a>
        <a href="{{ route('admin.login') }}" class="text-amber-600 hover:text-amber-700 font-semibold transition">
            Login Panitia &rarr;
        </a>
    </div>

</x-layouts.auth>
