<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <form method="POST" action="{{ route('admin.projects.update', $project) }}"
                        class="p-6 dark:bg-gray-800" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Proyek')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $project->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="responsible_agency" :value="__('Dinas Penanggung Jawab')" />
                            <x-text-input id="responsible_agency" class="block mt-1 w-full" type="text"
                                name="responsible_agency" :value="old('responsible_agency', $project->responsible_agency)" />
                            <x-input-error :messages="$errors->get('responsible_agency')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="sector" :value="__('Sektor Pembangunan')" />
                            <x-text-input id="sector" class="block mt-1 w-full" type="text" name="sector"
                                :value="old('sector', $project->sector)" />
                            <x-input-error :messages="$errors->get('sector')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="budget" :value="__('Pagu Anggaran')" />
                            <x-text-input id="budget" class="block mt-1 w-full" type="number" step="0.01"
                                name="budget" :value="old('budget', $project->budget)" />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date"
                                :value="old('start_date', $project->start_date?->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="end_date" :value="__('Tanggal Selesai Target')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date"
                                :value="old('end_date', $project->end_date?->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi Proyek')" />
                            <textarea id="description" name="description" rows="4"
                                class="block mt-1 w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 rounded-md shadow-sm">{{ old('description', $project->description) }}</textarea>
                            </textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="progress_percentage" :value="__('Progres Fisik (%)')" />
                            <x-text-input id="progress_percentage" class="block mt-1 w-full" type="number"
                                step="0.01" min="0" max="100" name="progress_percentage"
                                :value="old('progress_percentage', $project->progress_percentage)" />
                            <x-input-error :messages="$errors->get('progress_percentage')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status Proyek')" class="text-gray-700 dark:text-gray-200" />
                            <select id="status" name="status"
                                class="block mt-1 w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                                <option value="On-Track"
                                    {{ old('status', $project->status) == 'On-Track' ? 'selected' : '' }}>On-Track
                                </option>
                                <option value="Terlambat"
                                    {{ old('status', $project->status) == 'Terlambat' ? 'selected' : '' }}>Terlambat
                                </option>
                                <option value="Selesai"
                                    {{ old('status', $project->status) == 'Selesai' ? 'selected' : '' }}>Selesai
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>


                        {{-- Tampilan Media yang Sudah Ada --}}
                        @if ($project->media->isNotEmpty())
                            <div class="bg-gray-50 dark:bg-gray-700">
                                <h3 class="font-semibold text-lg mb-3">Media Terunggah</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach ($project->media as $mediaItem)
                                        <div
                                            class="relative group bg-white border rounded-lg shadow-sm flex flex-col items-stretch">
                                            @if ($mediaItem->media_type == 'foto')
                                                <a href="{{ $mediaItem->display_url }}" target="_blank"
                                                    class="block flex-grow overflow-hidden relative h-32">
                                                    <img src="{{ $mediaItem->display_url }}"
                                                        alt="{{ $mediaItem->file_name }}"
                                                        class="w-full h-full object-cover object-center">
                                                    <div
                                                        class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs p-1 truncate">
                                                        {{ $mediaItem->file_name }}
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{ $mediaItem->display_url }}" target="_blank"
                                                    class="flex items-center justify-center flex-grow bg-gray-100 text-gray-500 text-center p-2 hover:bg-gray-200 h-32">
                                                    <svg class="w-8 h-8 mr-2 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    <span
                                                        class="break-words text-sm">{{ Str::limit($mediaItem->file_name, 20) }}</span>
                                                </a>
                                            @endif
                                            <div
                                                class="flex-shrink-0 bg-gray-100 text-gray-700 text-xs p-1 flex justify-between items-center border-t border-gray-200">
                                                <span class="font-medium truncate">{{ $mediaItem->file_name }}</span>
                                                <label class="flex items-center space-x-1 cursor-pointer ml-2">
                                                    <input type="checkbox" name="delete_media_ids[]"
                                                        value="{{ $mediaItem->id }}"
                                                        class="rounded text-red-600 focus:ring-red-500">
                                                    <span class="text-red-600">Hapus</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Input untuk Unggah Foto --}}
                        <div class="mb-4">
                            <x-input-label for="photos" :value="__('Unggah Foto Proyek Baru (Maks 2MB per foto, JPG, PNG, GIF)')" />
                            <input id="photos"
                                class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                type="file" name="photos[]" multiple accept="image/*"
                                onchange="previewImages(event, 'photos-preview-container-edit')" />
                            {{-- Tambah onchange --}}
                            @error('photos')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @foreach ($errors->get('photos.*') as $message)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @endforeach
                            {{-- Container Pratinjau Foto Baru --}}
                            <div id="photos-preview-container-edit"
                                class="mt-2 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
                        </div>

                        {{-- Input untuk Unggah Dokumen --}}
                        <div class="mb-4">
                            <x-input-label for="documents" :value="__('Unggah Dokumen Proyek Baru (Maks 5MB per dokumen, PDF, DOC, XLS)')" />
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
                            <div id="location_map_edit"
                                style="height: 350px; width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; margin-top: 0.5rem;">
                            </div>
                            <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="latitude" :value="__('Latitude')" />
                            <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude"
                                :value="old('latitude', $project->latitude)" required readonly />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="longitude" :value="__('Longitude')" />
                            <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude"
                                :value="old('longitude', $project->longitude)" required readonly />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.projects.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Perbarui Proyek') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Fungsi pratinjau gambar (sama seperti di create.blade.php)
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
                const initialLat = {{ old('latitude', $project->latitude ?? -9.6667) }};
                const initialLon = {{ old('longitude', $project->longitude ?? 119.2667) }};

                const map = L.map('location_map_edit').setView([initialLat, initialLon], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                let marker;

                if (initialLat && initialLon) {
                    marker = L.marker([initialLat, initialLon]).addTo(map);
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
