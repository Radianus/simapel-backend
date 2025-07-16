<?php

namespace App\Exports;

use App\Models\Project; // Pastikan ini ada
use Maatwebsite\Excel\Concerns\FromCollection; // Import ini
use Maatwebsite\Excel\Concerns\WithHeadings;   // Import ini
use Illuminate\Support\Collection; // Tambahkan ini jika ada masalah dengan Collection

class ProjectsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil semua data proyek yang ingin diekspor
        // Pastikan Anda memilih kolom yang ingin Anda tampilkan di Excel
        return Project::select(
            'id',
            'name',
            'responsible_agency',
            'sector',
            'budget',
            'start_date',
            'end_date',
            'description',
            'progress_percentage',
            'status',
            'latitude',
            'longitude',
            'created_at',
            'updated_at'
        )->get();
    }

    /**
     * Menambahkan baris header di file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Proyek',
            'Dinas Penanggung Jawab',
            'Sektor',
            'Pagu Anggaran',
            'Tanggal Mulai',
            'Tanggal Selesai Target',
            'Deskripsi',
            'Progres (%)',
            'Status',
            'Latitude',
            'Longitude',
            'Dibuat Pada',
            'Diperbarui Pada',
        ];
    }
}
