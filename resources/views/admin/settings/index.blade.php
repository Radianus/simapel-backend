<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Pengaturan Sistem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-2xl font-bold mb-6">Kelola Pengaturan Aplikasi</h3>
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Berhasil!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Gagal!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PUT')

                        @foreach ($settings as $setting)
                            <div class="mb-4">
                                <x-input-label for="{{ $setting->key }}" :value="__($setting->description)" />
                                @if ($setting->type == 'text' || $setting->type == 'email' || $setting->type == 'number')
                                    <x-text-input id="{{ $setting->key }}" class="block mt-1 w-full"
                                        type="{{ $setting->type }}" name="{{ $setting->key }}" :value="old($setting->key, $setting->value)" />
                                @elseif ($setting->type == 'textarea')
                                    <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" rows="4"
                                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old($setting->key, $setting->value) }}</textarea>
                                @elseif ($setting->type == 'checkbox')
                                    <div class="flex items-center mt-2">
                                        <input type="hidden" name="{{ $setting->key }}" value="0">
                                        <input type="checkbox" id="{{ $setting->key }}" name="{{ $setting->key }}"
                                            value="1"
                                            class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                            {{ old($setting->key, $setting->value) == '1' ? 'checked' : '' }}>
                                        <label for="{{ $setting->key }}"
                                            class="ml-2 text-sm text-gray-600 dark:text-gray-400">Aktifkan</label>
                                    </div>
                                @endif
                                <x-input-error :messages="$errors->get($setting->key)" class="mt-2" />
                            </div>
                        @endforeach

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Simpan Pengaturan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
