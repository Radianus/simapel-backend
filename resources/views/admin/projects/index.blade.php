<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Manajemen Proyek Pembangunan') }}
        </h2>
    </x-slot>
    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-end mb-4 space-x-2">
                        @can('view projects')
                            <a href="{{ route('admin.projects.export', request()->query()) }}"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Export to Excel
                            </a>
                        @endcan
                        @can('create project')
                            <a href="{{ route('admin.projects.create') }}"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Proyek Baru
                            </a>
                        @endcan
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Berhasil!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-semibold mb-3">Peta Sebaran Proyek</h3>
                    <div id="map"
                        style="height: 400px; width: 100%; border-radius: 0.5rem; margin-bottom: 1.5rem;"></div>

                    {{-- Form Filter dan Pencarian --}}
                    <form method="GET" action="{{ route('admin.projects.index') }}"
                        class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-inner">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <x-input-label for="search" :value="__('Cari Nama/Deskripsi')" />
                                <x-text-input id="search" class="block mt-1 w-full" type="text" name="search"
                                    value="{{ request('search') }}" placeholder="Cari proyek..." />
                            </div>
                            <div>
                                <x-input-label for="status_filter" :value="__('Filter Status')" />
                                <select id="status_filter" name="status_filter"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
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
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua Sektor</option>
                                    @foreach ($availableSectors as $sector)
                                        <option value="{{ $sector }}"
                                            {{ request('sector_filter') == $sector ? 'selected' : '' }}>
                                            {{ $sector }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- FILTER TAHUN --}}
                            <div>
                                <x-input-label for="year_filter" :value="__('Filter Tahun')" />
                                <select id="year_filter" name="year_filter"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}"
                                            {{ request('year_filter') == $year ? 'selected' : '' }}>
                                            {{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- AKHIR FILTER TAHUN --}}
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button type="submit">
                                {{ __('Terapkan Filter') }}
                            </x-primary-button>
                            @if (request('search') || request('status_filter') || request('sector_filter') || request('year_filter'))
                                <a href="{{ route('admin.projects.index') }}"
                                    class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Reset Filter
                                </a>
                            @endif
                        </div>
                    </form>
                    {{-- End Form Filter dan Pencarian --}}

                    <h3 class="text-lg font-semibold mb-3 mt-8">Daftar Proyek</h3>
                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-spacing-0 border-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/3">
                                        Nama Proyek
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">
                                        Dinas Penanggung Jawab
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Progres (%)
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                                @forelse ($projects as $project)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis max-w-xs min-w-0">
                                            {{ $project->name }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis max-w-xs min-w-0">
                                            {{ $project->responsible_agency ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $project->progress_percentage }}%
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClass = '';
                                                if ($project->status == 'Selesai') {
                                                    $statusClass =
                                                        'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100';
                                                } elseif ($project->status == 'Terlambat') {
                                                    $statusClass =
                                                        'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100';
                                                } else {
                                                    $statusClass =
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100';
                                                }
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $project->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @can('edit project')
                                                <a href="{{ route('admin.projects.edit', $project) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-3">Edit</a>
                                            @endcan
                                            @can('delete project')
                                                <form action="{{ route('admin.projects.destroy', $project) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">Hapus</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada
                                            proyek pembangunan yang ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $projects->links() }}
                    </div>
                </div>
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

            function getCustomIcon(status) {
                let iconUrl = '';
                let shadowUrl = 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png';

                switch (status) {
                    case 'Selesai':
                        iconUrl =
                            'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png';
                        break;
                    case 'Terlambat':
                        iconUrl =
                            'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png';
                        break;
                    case 'On-Track':
                    default:
                        iconUrl =
                            'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png';
                        break;
                }

                return L.icon({
                    iconUrl: iconUrl,
                    shadowUrl: shadowUrl,
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
            }


            projects.forEach(project => {
                if (project.latitude && project.longitude) {
                    const customIcon = getCustomIcon(project.status);
                    const marker = L.marker([project.latitude, project.longitude], {
                        icon: customIcon
                    }).addTo(map);
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
