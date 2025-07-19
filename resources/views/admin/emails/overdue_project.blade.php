@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Proyek Terlambat - SIMAPEL</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f6f6f6; padding: 20px; color: #333;">
    <table width="100%" cellpadding="0" cellspacing="0"
        style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
        <tr>
            <td style="padding: 20px; background-color: #2d3748; color: #ffffff;">
                <h2>⚠️ Notifikasi Proyek Terlambat</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <p>Yth. Admin SIMAPEL,</p>
                <p>Proyek berikut telah melebihi batas waktu yang ditetapkan:</p>

                <table width="100%" style="margin-top: 10px; font-size: 14px;">
                    <tr>
                        <td><strong>📌 Nama Proyek:</strong></td>
                        <td>{{ $project->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>🏢 Dinas Penanggung Jawab:</strong></td>
                        <td>{{ $project->responsible_agency ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>📅 Target Selesai:</strong></td>
                        <td>{{ $project->end_date ? $project->end_date->format('d M Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>📊 Progres Terakhir:</strong></td>
                        <td>{{ $project->progress_percentage }}%</td>
                    </tr>
                    <tr>
                        <td><strong>📍 Status:</strong></td>
                        <td>{{ Str::title($project->status) }}</td>
                    </tr>
                </table>

                <p style="margin-top: 20px;">
                    <a href="{{ url('/admin/projects/' . $project->id . '/edit') }}"
                        style="background-color: #3182ce; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">🔍
                        Lihat Detail Proyek</a>
                </p>

                <p style="margin-top: 30px;">
                    Mohon tindak lanjuti segera agar keterlambatan tidak semakin berdampak pada pelaksanaan proyek
                    lainnya.
                </p>

                <p>Hormat kami,<br><strong>SIMAPEL - Sumba Barat Daya</strong></p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #edf2f7; text-align: center; font-size: 12px; padding: 10px; color: #718096;">
                Email ini dikirim otomatis oleh sistem SIMAPEL.
            </td>
        </tr>
    </table>
</body>

</html>
