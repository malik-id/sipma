<x-layouts.app title="Dashboard">

    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">Halo, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-500 mb-6">Selamat datang di SIPMA.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-700 transition">Keluar</button>
            </form>
        </div>
    </div>

</x-layouts.app>
