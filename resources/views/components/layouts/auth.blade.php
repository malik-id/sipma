<!DOCTYPE html>
<html lang="id" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'Masuk' }} — SIPMA UMB Palopo</title>

    {{-- Google Font: Quicksand --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    {{-- Google Material Symbols Outlined --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-zinc-800 bg-zinc-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center px-4">
        <a href="{{ route('home') }}" class="inline-block transition hover:opacity-90">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Universitas Mega Buana Palopo" class="w-20 h-20 object-contain mx-auto drop-shadow-xs mb-3">
        </a>
        <h1 class="text-xl font-bold tracking-tight text-zinc-900 uppercase">SIPMA UMB PALOPO</h1>
        <p class="text-xs text-zinc-500 mt-0.5 font-medium">Sistem Informasi Pemilihan Mahasiswa</p>
        <p class="text-xs text-amber-600 font-semibold tracking-wide">Universitas Mega Buana Palopo</p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 sm:px-8 border border-zinc-200 rounded-2xl shadow-xs">
            {{ $slot }}
        </div>
    </div>

    <footer class="mt-8 text-center text-xs text-zinc-400">
        &copy; {{ date('Y') }} Universitas Mega Buana Palopo. Seluruh Hak Cipta Dilindungi.
    </footer>
</body>
</html>
