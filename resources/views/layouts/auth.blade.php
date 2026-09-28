<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'Masuk' }} — SIPMA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">

        {{-- Logo / Judul --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold shadow-md mb-4">
                H
            </div>
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">SIPMA</h1>
            <p class="mt-1 text-sm text-gray-500">Sistem Informasi Pemilihan Mahasiswa</p>
            <p class="text-sm text-gray-500">Fakultas Ilmu Komputer</p>
        </div>

        {{-- Card --}}
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-sm ring-1 ring-gray-200 rounded-xl sm:px-10">
                {{ $slot }}
            </div>
        </div>

    </div>
</body>
</html>
