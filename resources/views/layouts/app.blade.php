<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))"
    :class="{ 'dark': darkMode }" class="transition-colors duration-500 ease-in-out">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMAPEL SBD') }}</title>

    <!-- Fonts & Styles -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Tambahan agar transisi terasa smooth di seluruh halaman */
        html {
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        body {
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        .dark .transition-bg {
            transition: background-color 0.5s ease;
        }

        .dark .transition-text {
            transition: color 0.5s ease;
        }
    </style>

</head>

<body :class="{ 'dark': darkMode }"
    class="font-sans antialiased bg-gray-50 text-gray-700 dark:bg-gray-900 dark:text-gray-200 transition-colors duration-300">

    <div x-data="{ sidebarOpen: window.innerWidth >= 768 }" @resize.window="sidebarOpen = window.innerWidth >= 768"
        @toggle-sidebar.window="sidebarOpen = !sidebarOpen" class="min-h-screen flex">
        <!-- Sidebar -->
        <x-sidebar-nav />
        <!-- Main Content -->
        <div :class="{ 'md:ml-0': !sidebarOpen && window.innerWidth >= 768, 'md:ml-64': sidebarOpen }"
            class="flex-1 flex flex-col transition-all duration-300 ease-in-out bg-white dark:bg-gray-900">

            <!-- Top Navbar -->
            <nav
                class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-3 sticky top-0 z-20 shadow-sm flex justify-between items-center">

                <!-- Hamburger -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden p-2 rounded-md text-gray-600 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Toggle Dark Mode -->
                <div class="mr-4">
                    <button @click="darkMode = !darkMode" id="toggleTheme"
                        class="rounded-full p-2 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none transition">
                        <!-- Icon Light Mode (Sun - Outline) -->
                        <svg x-show="!darkMode" x-cloak class="h-6 w-6 text-yellow-400" fill="none"
                            stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v1.5m0 15V21m9-9h-1.5M4.5 12H3m16.364-7.364l-1.06 1.06M6.697 17.303l-1.06 1.06m12.727 0l-1.06-1.06M6.697 6.697l-1.06-1.06M12 7.5A4.5 4.5 0 1112 16.5A4.5 4.5 0 0112 7.5z" />
                        </svg>

                        <!-- Icon Dark Mode (Moon - Outline) -->
                        <svg x-show="darkMode" x-cloak class="h-6 w-6 text-gray-300" fill="none"
                            stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                        </svg>

                    </button>
                </div>


                <!-- User Dropdown -->
                <div class="ml-auto">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @endauth
                </div>
            </nav>

            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow-sm">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Slot Utama -->
            <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>

    <script>
        const toggleButton = document.getElementById('toggleTheme');

        // Apply mode based on saved preference
        function applyTheme(theme) {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ?
            'dark' : 'light');
        applyTheme(savedTheme);

        toggleButton.addEventListener('click', () => {
            const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            applyTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });
    </script>
</body>

</html>
