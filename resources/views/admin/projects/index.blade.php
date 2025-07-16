<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Proyek Pembangunan') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                        <h3 class="text-lg font-semibold">Peta Sebaran Proyek</h3>
                        <a href="{{ route('admin.projects.create') }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition text-sm w-full md:w-auto text-center">
                            + Tambah Proyek Baru
                        </a>
                    </div>

                    @if (session('success'))
                        <div
                            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            <strong class="font-bold">Berhasil!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div id="map" class="rounded-lg mb-6 w-full" style="height: 400px;"></div>

                    <form method="GET" action="{{ route('admin.projects.index') }}"
                        class="mb-6 p-4 bg-gray-50 rounded-lg shadow-inner">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="search" :value="__('Cari Nama/Deskripsi')" />
                                <x-text-input id="search" class="block mt-1 w-full" type="text" name="search"
                                    value="{{ request('search') }}" placeholder="Cari proyek..." />
                            </div>
                            <div>
                                <x-input-label for="status_filter" :value="__('Filter Status')" />
                                <select id="status_filter" name="status_filter"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua Status</option>
                                    <option value="On-Track"
                                        {{ request('status_filter') == 'On-Track' ? 'selected' : '' }}>On-Track</option>
                                    <option value="Terlambat"
                                        {{ request('status_filter') == 'Terlambat' ? 'selected' : '' }}>Terlambat
                                    </option>
                                    <option value="Selesai"
                                        {{ request('status_filter') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="sector_filter" :value="__('Filter Sektor')" />
                                <select id="sector_filter" name="sector_filter"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua Sektor</option>
                                    @php
                                        $uniqueSectors = \App\Models\Project::select('sector')
                                            ->distinct()
                                            ->pluck('sector')
                                            ->filter()
                                            ->sort();
                                    @endphp
                                    @foreach ($uniqueSectors as $sector)
                                        <option value="{{ $sector }}"
                                            {{ request('sector_filter') == $sector ? 'selected' : '' }}>
                                            {{ $sector }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center justify-end mt-4 gap-2">
                            <x-primary-button type="submit">{{ __('Terapkan Filter') }}</x-primary-button>
                            @if (request('search') || request('status_filter') || request('sector_filter'))
                                <a href="{{ route('admin.projects.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm text-gray-700 transition">
                                    Reset Filter
                                </a>
                            @endif
                        </div>
                    </form>

                    <h3 class="text-lg font-semibold mb-3 mt-8">Daftar Proyek</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm table-auto">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Nama Proyek</th>
                                    <th class="px-4 py-3 text-left font-semibold">Dinas</th>
                                    <th class="px-4 py-3 text-left font-semibold">Progres</th>
                                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                                    <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($projects as $project)
                                    <tr>
                                        <td class="px-4 py-3 truncate max-w-xs">{{ $project->name }}</td>
                                        <td class="px-4 py-3 truncate max-w-xs">
                                            {{ $project->responsible_agency ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $project->progress_percentage }}%</td>
                                        <td class="px-4 py-3">
                                            @php
                                                $statusClass = match ($project->status) {
                                                    'Selesai' => 'bg-green-100 text-green-800',
                                                    'Terlambat' => 'bg-red-100 text-red-800',
                                                    default => 'bg-yellow-100 text-yellow-800',
                                                };
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs font-semibold rounded-full {{ $statusClass }}">
                                                {{ $project->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 space-x-2 whitespace-nowrap">
                                            <a href="{{ route('admin.projects.edit', $project) }}"
                                                class="text-indigo-600 hover:underline">Edit</a>
                                            <form action="{{ route('admin.projects.destroy', $project) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Yakin hapus proyek ini?');">
                                                @csrf @method('DELETE')
                                                <button class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">Tidak ada proyek
                                            ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $projects->links() }}</div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = L.map('map').setView([-9.6667, 119.2667], 10);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                const projects = @json($projects->items());

                // Fungsi untuk mendapatkan ikon kustom berdasarkan status
                function getCustomIcon(status) {
                    let iconUrl = '';
                    let shadowUrl =
                        'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png'; // Shadow default Leaflet

                    switch (status) {
                        case 'Selesai':
                            // Contoh: Ikon hijau
                            iconUrl =
                                'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png';
                            break;
                        case 'Terlambat':
                            // Contoh: Ikon merah
                            iconUrl =
                                'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png';
                            break;
                        case 'On-Track':
                        default:
                            // Contoh: Ikon kuning (atau default biru jika tidak cocok)
                            iconUrl =
                                'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png';
                            break;
                    }

                    return L.icon({
                        iconUrl: iconUrl,
                        shadowUrl: shadowUrl,
                        iconSize: [25, 41], // Ukuran ikon
                        iconAnchor: [12, 41], // Titik "ujung" ikon yang menunjuk lokasi
                        popupAnchor: [1, -34], // Titik popup muncul relatif terhadap ikon
                        shadowSize: [41, 41] // Ukuran bayangan
                    });
                }


                projects.forEach(project => {
                    if (project.latitude && project.longitude) {
                        const customIcon = getCustomIcon(project.status); // Dapatkan ikon sesuai status
                        const marker = L.marker([project.latitude, project.longitude], {
                            icon: customIcon
                        }).addTo(map); // Gunakan ikon kustom
                        marker.bindPopup(`
                        <b>${project.name}</b><br>
                        Dinas: ${project.responsible_agency ?? '-'}<br>
                        Progres: ${project.progress_percentage}%<br>
                        Status: ${project.status}<br>
                        <a href="/admin/projects/${project.id}/edit">Edit Proyek</a>
                    `);
                    }
                });

                if (projects.length > 0) {
                    const latLngs = projects.filter(p => p.latitude && p.longitude).map(p => [p.latitude, p.longitude]);
                    if (latLngs.length > 0) {
                        map.fitBounds(L.latLngBounds(latLngs).pad(0.5));
                    }
                }
            });
        </script>
</x-app-layout>
