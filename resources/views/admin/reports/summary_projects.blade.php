<!DOCTYPE html>
<html>

<head>
    <title>Laporan Ringkasan Proyek SIMAPEL</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .header p {
            margin: 2px 0;
            font-size: 12px;
            color: #666;
        }

        .summary-cards {
            display: block;
            width: 100%;
            margin-bottom: 20px;
        }

        .card {
            width: 48%;
            /* Untuk 2 kolom */
            display: inline-block;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ddd;
            border-left: 4px solid #007bff;
            border-radius: 4px;
            margin: 1%;
            vertical-align: top;
            /* Penting untuk inline-block */
        }

        .card.blue {
            border-color: #007bff;
        }

        .card.green {
            border-color: #28a745;
        }

        .card.yellow {
            border-color: #ffc107;
        }

        .card.red {
            border-color: #dc3545;
        }

        .card h4 {
            font-size: 12px;
            margin: 0 0 5px 0;
            color: #555;
        }

        .card p {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: #333;
        }

        .total-budget {
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .total-budget h4 {
            margin: 0 0 5px 0;
            color: #555;
            font-size: 14px;
        }

        .total-budget p {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Ringkasan Proyek Pembangunan</h1>
        <p>{{ $appNameDisplay }}</p>
        <p>{{ $appSlogan }}</p>
        <p>Tanggal Laporan: {{ date('d M Y H:i:s') }}</p>
    </div>

    <div class="section-title">Statistik Umum Proyek</div>
    <div class="summary-cards">
        <div class="card blue">
            <h4>Total Proyek</h4>
            <p>{{ $totalProjects }}</p>
        </div>
        <div class="card green">
            <h4>Proyek Selesai</h4>
            <p>{{ $completedProjects }}</p>
        </div>
        <div class="card yellow" style="border-color:#ffc107;">
            <h4>Proyek On-Track</h4>
            <p>{{ $onTrackProjects }}</p>
        </div>
        <div class="card red" style="border-color:#dc3545;">
            <h4>Proyek Terlambat</h4>
            <p>{{ $lateProjects }}</p>
        </div>
    </div>

    <div class="total-budget">
        <h4>Total Pagu Anggaran</h4>
        <p>Rp{{ number_format($totalBudget, 0, ',', '.') }}</p>
    </div>

    <div style="page-break-after: always;"></div> {{-- Memaksa pindah halaman --}}

    <div class="section-title">Distribusi Proyek Berdasarkan Sektor</div>
    <table>
        <thead>
            <tr>
                <th>Sektor</th>
                <th>Jumlah Proyek</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projectsPerSector as $data)
                <tr>
                    <td>{{ $data->sector ?? 'Lain-lain' }}</td>
                    <td>{{ $data->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Pagu Anggaran Berdasarkan Sektor</div>
    <table>
        <thead>
            <tr>
                <th>Sektor</th>
                <th>Total Anggaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($budgetPerSector as $data)
                <tr>
                    <td>{{ $data->sector ?? 'Lain-lain' }}</td>
                    <td>Rp{{ number_format($data->total_budget, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dihasilkan oleh Sistem Informasi Manajemen Data Pembangunan (SIMAPEL) Sumba Barat Daya
    </div>
</body>

</html>
