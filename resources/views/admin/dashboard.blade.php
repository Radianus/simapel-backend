<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Dashboard SIMAPEL SBD') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-6">Ringkasan Pembangunan Sumba Barat Daya</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div
                            class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg shadow-md text-blue-800 dark:text-blue-200">
                            <h4 class="text-lg font-semibold">Total Proyek</h4>
                            <p class="text-3xl font-bold mt-2">{{ $totalProjects }}</p>
                        </div>
                        <div
                            class="bg-green-100 dark:bg-green-900 p-4 rounded-lg shadow-md text-green-800 dark:text-green-200">
                            <h4 class="text-lg font-semibold">Proyek Selesai</h4>
                            <p class="text-3xl font-bold mt-2">{{ $completedProjects }}</p>
                        </div>
                        <div
                            class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-lg shadow-md text-yellow-800 dark:text-yellow-200">
                            <h4 class="text-lg font-semibold">Proyek On-Track</h4>
                            <p class="text-3xl font-bold mt-2">{{ $onTrackProjects }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900 p-4 rounded-lg shadow-md text-red-800 dark:text-red-200">
                            <h4 class="text-lg font-semibold">Proyek Terlambat</h4>
                            <p class="text-3xl font-bold mt-2">{{ $lateProjects }}</p>
                        </div>
                    </div>

                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-inner">
                        <h4 class="text-lg font-semibold mb-2">Total Pagu Anggaran</h4>
                        <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">
                            Rp{{ number_format($totalBudget, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex justify-end mb-8">
                        <a href="{{ route('admin.reports.summary-projects.pdf') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Generate PDF Report
                        </a>
                    </div>
                    {{-- Area Grafik --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md">
                            <h4 class="text-xl font-bold mb-4 text-center text-gray-800 dark:text-gray-100">Proyek
                                Berdasarkan Sektor</h4>
                            <canvas id="projectsPerSectorChart" class="w-full h-full"></canvas>
                        </div>
                        <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md">
                            <h4 class="text-xl font-bold mb-4 text-center text-gray-800 dark:text-gray-100">Proyek
                                Berdasarkan Status</h4>
                            <canvas id="projectsPerStatusChart" class="w-full h-full"></canvas>
                        </div>
                    </div>

                    {{-- AREA GRAFIK BARU --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md">
                            <h4 class="text-xl font-bold mb-4 text-center text-gray-800 dark:text-gray-100">Proyek
                                Berdasarkan Dinas Penanggung Jawab</h4>
                            <canvas id="projectsPerAgencyChart" class="w-full h-full"></canvas>
                        </div>
                        <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md">
                            <h4 class="text-xl font-bold mb-4 text-center text-gray-800 dark:text-gray-100">Pagu
                                Anggaran
                                Berdasarkan Sektor</h4>
                            <canvas id="budgetPerSectorChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                    {{-- AKHIR AREA GRAFIK BARU --}}

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let sectorChart, statusChart, agencyChart, budgetChart;

        const primaryColors = [
            'rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)',
            'rgba(255, 206, 86, 0.8)', 'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)', 'rgba(255, 159, 64, 0.8)',
            'rgba(199, 199, 199, 0.8)', 'rgba(50, 205, 50, 0.8)',
            'rgba(175, 50, 50, 0.8)', 'rgba(100, 100, 100, 0.8)'
        ];

        const statusColors = {
            'Selesai': 'rgba(75, 192, 192, 0.8)',
            'On-Track': 'rgba(255, 206, 86, 0.8)',
            'Terlambat': 'rgba(255, 99, 132, 0.8)',
            'default': 'rgba(199, 199, 199, 0.8)'
        };

        const projectsPerSectorData = @json($projectsPerSector);
        const projectsPerStatusData = @json($projectsPerStatus);
        const projectsPerAgencyData = @json($projectsPerAgency);
        const budgetPerSectorData = @json($budgetPerSector);

        function renderAllCharts() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#e5e7eb' : '#1f2937';
            const gridColor = isDarkMode ? '#4b5563' : '#e5e7eb';

            // Destroy old charts if exist
            sectorChart?.destroy();
            statusChart?.destroy();
            agencyChart?.destroy();
            budgetChart?.destroy();


            // Chart 1: Sector
            sectorChart = new Chart(document.getElementById('projectsPerSectorChart'), {
                type: 'pie',
                data: {
                    labels: projectsPerSectorData.map(d => d.sector ?? 'Lain-lain'),
                    datasets: [{
                        data: projectsPerSectorData.map(d => d.total),
                        backgroundColor: primaryColors,
                        borderColor: primaryColors.map(c => c.replace('0.8', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            labels: {
                                color: textColor
                            }
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Proyek Berdasarkan Sektor',
                            color: textColor
                        }
                    }
                }
            });

            // Chart 2: Status
            statusChart = new Chart(document.getElementById('projectsPerStatusChart'), {
                type: 'bar',
                data: {
                    labels: projectsPerStatusData.map(d => d.status ?? 'Tidak Diketahui'),
                    datasets: [{
                        data: projectsPerStatusData.map(d => d.total),
                        label: 'Jumlah Proyek',
                        backgroundColor: projectsPerStatusData.map(d => statusColors[d.status] ||
                            statusColors.default),
                        borderColor: projectsPerStatusData.map(d => (statusColors[d.status] || statusColors
                            .default).replace('0.8', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Proyek Berdasarkan Status',
                            color: textColor
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: textColor,
                                precision: 0
                            },
                            grid: {
                                color: gridColor
                            }
                        },
                        x: {
                            ticks: {
                                color: textColor
                            },
                            grid: {
                                color: gridColor
                            }
                        }
                    }
                }
            });

            // Chart 3: Agency
            agencyChart = new Chart(document.getElementById('projectsPerAgencyChart'), {
                type: 'bar',
                data: {
                    labels: projectsPerAgencyData.map(d => d.responsible_agency ?? 'Tidak Diketahui'),
                    datasets: [{
                        data: projectsPerAgencyData.map(d => d.total),
                        label: 'Jumlah Proyek',
                        backgroundColor: primaryColors,
                        borderColor: primaryColors.map(c => c.replace('0.8', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Proyek Berdasarkan Dinas Penanggung Jawab',
                            color: textColor
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: textColor
                            },
                            grid: {
                                color: gridColor
                            }
                        },
                        x: {
                            ticks: {
                                color: textColor,
                                precision: 0
                            },
                            grid: {
                                color: gridColor
                            }
                        }
                    }
                }
            });

            // Chart 4: Budget
            budgetChart = new Chart(document.getElementById('budgetPerSectorChart'), {
                type: 'doughnut',
                data: {
                    labels: budgetPerSectorData.map(d => d.sector ?? 'Lain-lain'),
                    datasets: [{
                        data: budgetPerSectorData.map(d => d.total_budget),
                        backgroundColor: primaryColors,
                        borderColor: primaryColors.map(c => c.replace('0.8', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: textColor
                            }
                        },
                        title: {
                            display: true,
                            text: 'Pagu Anggaran Berdasarkan Sektor',
                            color: textColor
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    let label = ctx.label || '';
                                    label += ': Rp' + Number(ctx.parsed).toLocaleString('id-ID');
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
        // Auto run saat page load
        document.addEventListener('DOMContentLoaded', function() {
            renderAllCharts();

            // Listen for dark mode toggle via class change
            const observer = new MutationObserver(() => {
                renderAllCharts();
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>
</x-app-layout>
