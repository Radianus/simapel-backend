<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard SIMAPEL SBD') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl ">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6">Ringkasan Pembangunan Sumba Barat Daya</h3>

                    {{-- Kartu Statistik --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-100 p-4 rounded-lg shadow-md text-blue-800">
                            <h4 class="text-lg font-semibold">Total Proyek</h4>
                            <p class="text-3xl font-bold mt-2">{{ $totalProjects }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg shadow-md text-green-800">
                            <h4 class="text-lg font-semibold">Proyek Selesai</h4>
                            <p class="text-3xl font-bold mt-2">{{ $completedProjects }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg shadow-md text-yellow-800">
                            <h4 class="text-lg font-semibold">Proyek On-Track</h4>
                            <p class="text-3xl font-bold mt-2">{{ $onTrackProjects }}</p>
                        </div>
                        <div class="bg-red-100 p-4 rounded-lg shadow-md text-red-800">
                            <h4 class="text-lg font-semibold">Proyek Terlambat</h4>
                            <p class="text-3xl font-bold mt-2">{{ $lateProjects }}</p>
                        </div>
                    </div>

                    <div class="mb-8 p-4 bg-gray-50 rounded-lg shadow-inner">
                        <h4 class="text-lg font-semibold mb-2">Total Pagu Anggaran</h4>
                        <p class="text-3xl font-bold text-gray-800">Rp{{ number_format($totalBudget, 0, ',', '.') }}</p>
                    </div>

                    {{-- Area Grafik --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h4 class="text-xl font-bold mb-4 text-center">Proyek Berdasarkan Sektor</h4>
                            <canvas id="projectsPerSectorChart"></canvas>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h4 class="text-xl font-bold mb-4 text-center">Proyek Berdasarkan Status</h4>
                            <canvas id="projectsPerStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari Laravel untuk Chart.js
            const projectsPerSectorData = @json($projectsPerSector);
            const projectsPerStatusData = @json($projectsPerStatus);

            // Grafik Proyek per Sektor
            const sectorLabels = projectsPerSectorData.map(data => data.sector ?? 'Lain-lain');
            const sectorCounts = projectsPerSectorData.map(data => data.total);
            const sectorColors = [
                'rgba(255, 99, 132, 0.6)', // Merah muda
                'rgba(54, 162, 235, 0.6)', // Biru
                'rgba(255, 206, 86, 0.6)', // Kuning
                'rgba(75, 192, 192, 0.6)', // Hijau
                'rgba(153, 102, 255, 0.6)', // Ungu
                'rgba(255, 159, 64, 0.6)', // Oranye
                'rgba(199, 199, 199, 0.6)' // Abu-abu
            ];

            new Chart(document.getElementById('projectsPerSectorChart'), {
                type: 'pie', // Bisa 'bar', 'pie', 'doughnut'
                data: {
                    labels: sectorLabels,
                    datasets: [{
                        label: 'Jumlah Proyek',
                        data: sectorCounts,
                        backgroundColor: sectorColors,
                        borderColor: sectorColors.map(color => color.replace('0.6',
                            '1')), // Border lebih gelap
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Proyek Berdasarkan Sektor'
                        }
                    }
                }
            });

            // Grafik Proyek per Status
            const statusLabels = projectsPerStatusData.map(data => data.status ?? 'Tidak Diketahui');
            const statusCounts = projectsPerStatusData.map(data => data.total);
            const statusColors = {
                'Selesai': 'rgba(75, 192, 192, 0.6)', // Hijau
                'On-Track': 'rgba(255, 206, 86, 0.6)', // Kuning
                'Terlambat': 'rgba(255, 99, 132, 0.6)', // Merah muda
                'default': 'rgba(199, 199, 199, 0.6)' // Abu-abu
            };
            const orderedStatusColors = statusLabels.map(label => statusColors[label] || statusColors['default']);


            new Chart(document.getElementById('projectsPerStatusChart'), {
                type: 'bar',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        label: 'Jumlah Proyek',
                        data: statusCounts,
                        backgroundColor: orderedStatusColors,
                        borderColor: orderedStatusColors.map(color => color.replace('0.6', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false, // Tidak perlu legend jika label ada di bawah
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Proyek Berdasarkan Status'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0 // Pastikan sumbu Y adalah bilangan bulat
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
