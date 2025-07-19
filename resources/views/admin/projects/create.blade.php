<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">

            {{ __('Tambah Proyek Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data"
                        class="mt-6 space-y-6">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Proyek')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="responsible_agency" :value="__('Dinas Penanggung Jawab')" />
                            <x-text-input id="responsible_agency" class="block mt-1 w-full" type="text"
                                name="responsible_agency" :value="old('responsible_agency')" />
                            <x-input-error :messages="$errors->get('responsible_agency')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="sector" :value="__('Sektor Pembangunan')" />
                            <x-text-input id="sector" class="block mt-1 w-full" type="text" name="sector"
                                :value="old('sector')" />
                            <x-input-error :messages="$errors->get('sector')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="budget" :value="__('Pagu Anggaran')" />
                            <x-text-input id="budget" class="block mt-1 w-full" type="number" step="0.01"
                                name="budget" :value="old('budget')" />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date"
                                :value="old('start_date')" />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="end_date" :value="__('Tanggal Selesai Target')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date"
                                :value="old('end_date')" />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi Proyek')" />
                            <textarea id="description" name="description" rows="4"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="progress_percentage" :value="__('Progres Fisik (%)')" />
                            <x-text-input id="progress_percentage" class="block mt-1 w-full" type="number"
                                step="0.01" min="0" max="100" name="progress_percentage"
                                :value="old('progress_percentage', 0)" />
                            <x-input-error :messages="$errors->get('progress_percentage')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status Proyek')" />
                            <select id="status" name="status"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="On-Track"
                                    {{ old('status', 'On-Track') == 'On-Track' ? 'selected' : '' }}>On-Track</option>
                                <option value="Terlambat" {{ old('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat
                                </option>
                                <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        {{-- Input untuk Unggah Foto --}}
                        <div class="mb-4">
                            <x-input-label for="photos" :value="__('Unggah Foto Proyek (Maks 2MB per foto, JPG, PNG, GIF)')" />
                            <input id="photos"
                                class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                type="file" name="photos[]" multiple accept="image/*"
                                onchange="previewImages(event, 'photos-preview-container')" /> {{-- Tambah onchange --}}
                            @error('photos')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @foreach ($errors->get('photos.*') as $message)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @endforeach
                            {{-- Container Pratinjau Foto --}}
                            <div id="photos-preview-container"
                                class="mt-2 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
                        </div>

                        {{-- Input untuk Unggah Dokumen --}}
                        <div class="mb-4">
                            <x-input-label for="documents" :value="__('Unggah Dokumen Proyek (Maks 5MB per dokumen, PDF, DOC, XLS)')" />
                            <input id="documents"
                                class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx" />
                            @error('documents')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @foreach ($errors->get('documents.*') as $message)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @endforeach
                        </div>

                        {{-- Peta dan Koordinat --}}
                        <div class="mb-4">
                            <x-input-label for="location_map" :value="__('Pilih Lokasi di Peta (Klik Peta)')" />
                            <div id="location_map_create"
                                style="height: 350px; width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; margin-top: 0.5rem;">
                            </div>
                            <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="latitude" :value="__('Latitude')" />
                            <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude"
                                :value="old('latitude')" required readonly />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="longitude" :value="__('Longitude')" />
                            <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude"
                                :value="old('longitude')" required readonly />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.projects.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Proyek') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Fungsi untuk pratinjau gambar
            function previewImages(event, containerId) {
                const container = document.getElementById(containerId);
                container.innerHTML = ''; // Hapus pratinjau lama

                if (event.target.files && event.target.files.length > 0) {
                    Array.from(event.target.files).forEach(file => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const imgDiv = document.createElement('div');
                                imgDiv.className =
                                    'relative group bg-white border rounded-lg shadow-sm overflow-hidden';
                                imgDiv.innerHTML = `
                                    <img src="${e.target.result}" class="w-full h-32 object-cover object-center">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs p-1 truncate">
                                        ${file.name}
                                    </div>
                                `;
                                container.appendChild(imgDiv);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const defaultLat = {{ old('latitude', -9.6667) }};
                const defaultLon = {{ old('longitude', 119.2667) }};

                const map = L.map('location_map_create').setView([defaultLat, defaultLon], 10);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                let marker;

                if (defaultLat !== -9.6667 || defaultLon !== 119.2667) {
                    marker = L.marker([defaultLat, defaultLon]).addTo(map);
                }

                map.on('click', function(e) {
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker(e.latlng).addTo(map);
                    document.getElementById('latitude').value = e.latlng.lat.toFixed(7);
                    document.getElementById('longitude').value = e.latlng.lng.toFixed(7);
                });
            });
        </script>
</x-app-layout>
