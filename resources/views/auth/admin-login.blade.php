<x-layouts.auth title="Masuk Admin">

    <h2 class="text-center text-lg font-semibold text-gray-800 mb-6">Masuk sebagai Admin</h2>

    {{-- Error --}}
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.25a.75.75 0 011.5 0v4.5a.75.75 0 01-1.5 0v-4.5zm.75 7.5a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('email') border-red-400 @enderror"
                placeholder="admin@example.com"
            />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                placeholder="••••••••"
            />
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full flex justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
            Masuk
        </button>
    </form>

    {{-- Kembali ke login mahasiswa --}}
    <div class="mt-6 pt-5 border-t border-gray-100 text-center">
        <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-gray-600 transition">
            ← Kembali ke halaman login mahasiswa
        </a>
    </div>

</x-layouts.auth>
