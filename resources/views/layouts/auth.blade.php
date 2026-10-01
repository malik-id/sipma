<!DOCTYPE html>
<html lang="id" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'Masuk' }} — SIPMA Universitas Mega Buana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-zinc-900 bg-zinc-50">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">

        {{-- Logo / Judul --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <div class="mx-auto flex items-center justify-center w-20 h-20 rounded-2xl bg-white border border-zinc-200 shadow-xs mb-4 p-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo UMB Palopo" class="w-full h-full object-contain" />
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-950">SIPMA UMB</h1>
            <p class="mt-1 text-sm font-semibold text-amber-600">Sistem Informasi Pemilihan Mahasiswa</p>
            <p class="text-xs text-zinc-500">Universitas Mega Buana Palopo</p>
        </div>

        {{-- Card --}}
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xs border border-zinc-200 rounded-2xl sm:px-10">
                {{ $slot }}
            </div>
        </div>

    </div>
</body>
</html>
