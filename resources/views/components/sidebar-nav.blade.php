<nav x-data="{ isMobile: window.innerWidth < 768 }" x-init="window.addEventListener('resize', () => { isMobile = window.innerWidth < 768 })"
    class="fixed top-0 left-0 h-full bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 w-64 p-4 z-30 transition-transform duration-300 ease-in-out transform shadow-lg"
    :class="{
        '-translate-x-full': !sidebarOpen && isMobile,
        'translate-x-0': sidebarOpen || !isMobile
    }">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-blue-700 dark:text-blue-400">SIMAPEL</h1>
        <!-- Tombol tutup di mobile -->
        <button @click="sidebarOpen = false"
            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 md:hidden">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigasi -->
    <ul class="space-y-2 text-sm font-medium">
        <li>
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                class="flex items-center p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition group">
                <svg class="w-5 h-5 text-blue-500 mr-3 group-hover:text-blue-600 dark:text-blue-400 dark:group-hover:text-blue-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7m7-7v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Dashboard
            </x-nav-link>
        </li>

        <li>
            <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold mt-4 mb-2">Data</h3>
        </li>
        <li>
            <x-nav-link :href="route('admin.projects.index')" :active="request()->routeIs('admin.projects.*')"
                class="flex items-center p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition group">
                <svg class="w-5 h-5 text-blue-500 mr-3 group-hover:text-blue-600 dark:text-blue-400 dark:group-hover:text-blue-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
                Proyek Pembangunan
            </x-nav-link>
        </li>

        <li>
            <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold mt-4 mb-2">Akun</h3>
        </li>
        <li>
            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')"
                class="flex items-center p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition group">
                <svg class="w-5 h-5 text-blue-500 mr-3 group-hover:text-blue-600 dark:text-blue-400 dark:group-hover:text-blue-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M10 20v-2a3 3 0 013-3h4a3 3 0 013 3v2M3 8h18l-1 12H4L3 8z"></path>
                </svg>
                <span class="ms-1">Manajemen Pengguna</span>
            </x-nav-link>
        </li>
        <li>
            <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')"
                class="flex items-center p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-blue-400 transition group">
                <svg class="w-5 h-5 text-blue-500 mr-1 group-hover:text-blue-600 dark:text-blue-400 dark:group-hover:text-blue-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="ms-3">Pengaturan Sistem</span>
            </x-nav-link>
        </li>
    </ul>

    <!-- Logout -->
    <div class="absolute bottom-4 left-4 right-4">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <x-danger-button class="ms-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg> {{ __('Logout') }}
            </x-danger-button>
        </form>
    </div>
</nav>

<!-- Overlay untuk Mobile -->
<div x-show="sidebarOpen && isMobile" x-transition.opacity
    class="fixed inset-0 bg-black bg-opacity-30 dark:bg-opacity-60 z-20 md:hidden" @click="sidebarOpen = false">
</div>
