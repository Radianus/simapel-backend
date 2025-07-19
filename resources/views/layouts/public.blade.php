<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMAPEL SBD') }} - Publik</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow-sm border-b border-gray-100 py-4 px-6 flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-800">SIMAPEL SBD</a>
                <span class="ml-4 text-gray-600 hidden md:block">Sistem Informasi Manajemen Pembangunan</span>
            </div>
            <div>
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 mr-4">Login Admin</a>
                {{-- Contoh link lain: <a href="#" class="text-gray-600 hover:text-gray-900">Tentang Kami</a> --}}
            </div>
        </nav>

        <main>
            @yield('content') {{-- PERBAIKAN DI SINI: Gunakan @yield('content') --}}
        </main>

        <footer class="bg-gray-800 text-white py-6 text-center mt-auto">
            <p>&copy; {{ date('Y') }} SIMAPEL Sumba Barat Daya. All rights reserved.</p>
        </footer>
    </div>
</body>

</html>
