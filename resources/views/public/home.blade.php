@extends('layouts.public') {{-- PERBAIKAN DI SINI: Gunakan @extends --}}

@section('content')
    {{-- Tambahkan ini untuk membungkus konten --}}
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-4">{{ $appNameDisplay }}</h1>
        <p class="text-xl text-center text-gray-600 mb-8">{{ $appSlogan }}</p>

        {{-- Kartu Statistik Publik --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-4 rounded-lg shadow-md text-blue-800 border-l-4 border-blue-500">
                <h4 class="text-lg font-semibold">Total Proyek</h4>
                <p class="text-3xl font-bold mt-2">{{ $totalProjects }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md text-green-800 border-l-4 border-green-500">
                <h4 class="text-lg font-semibold">Proyek Selesai</h4>
                <p class="text-3xl font-bold mt-2">{{ $completedProjects }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md text-yellow-800 border-l-4 border-yellow-500">
                <h4 class="text-lg font-semibold">Proyek On-Track</h4>
                <p class="text-3xl font-bold mt-2">{{ $onTrackProjects }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md text-gray-800 border-l-4 border-gray-500">
                <h4 class="text-lg font-semibold">Total Anggaran Proyek</h4>
                <p class="text-3xl font-bold mt-2">Rp{{ number_format($totalBudget, 0, ',', '.') }}</p>
            </div>
        </div>

        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Peta Sebaran Proyek</h2>
        <div id="public-map"
            style="height: 550px; width: 100%; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('public-map').setView([-9.6667, 119.2667], 10); // Tengah Sumba Barat Daya

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const projects = @json($projects); // Ambil data proyek dari controller

            // Fungsi untuk mendapatkan ikon kustom berdasarkan status (sama seperti di admin)
            function getCustomIcon(status) {
                let iconUrl = '';
                let shadowUrl = 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png';

                switch (status) {
                    case 'Selesai':
                        iconUrl =
                            'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png';
                        break;
                    case 'Terlambat': // Meskipun kita filter 'On-Track' & 'Selesai', ini untuk jaga-jaga
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
                        ${project.description ? `<p>${project.description.substring(0, 100)}...</p>` : ''}
                    `);
                }
            });

            // Sesuaikan peta ke semua marker jika ada
            if (projects.length > 0) {
                const latLngs = projects.filter(p => p.latitude && p.longitude).map(p => [p.latitude, p.longitude]);
                if (latLngs.length > 0) {
                    map.fitBounds(L.latLngBounds(latLngs).pad(0.1)); // Padding 0.1 untuk zoom yang lebih rapat
                }
            }
        });
    </script>
@endsection {{-- Tambahkan ini untuk menutup section --}}
