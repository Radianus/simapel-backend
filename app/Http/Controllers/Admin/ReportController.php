<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project; // Import model Project
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App; // Untuk facade App::make('dompdf.wrapper')
use Illuminate\Support\Facades\DB; // Untuk query agregasi
use Illuminate\Support\Facades\Log; // Untuk debugging log
use App\Models\Setting; // Import model Setting

class ReportController extends Controller
{
    /**
     * Menampilkan form pilihan laporan atau halaman default laporan.
     * Untuk saat ini, kita akan langsung membuat laporan ringkasan proyek.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Anda bisa membuat halaman dengan filter/pilihan laporan di sini
        return view('admin.reports.index');
    }

    /**
     * Menghasilkan laporan ringkasan proyek dalam format PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function summaryProjectsPdf(Request $request)
    {
        try {
            // Ambil data statistik dasar (sama seperti di DashboardController)
            $totalProjects = Project::count();
            $totalBudget = Project::sum('budget');
            $completedProjects = Project::where('status', 'Selesai')->count();
            $lateProjects = Project::where('status', 'Terlambat')->count();
            $onTrackProjects = Project::where('status', 'On-Track')->count();

            // Data untuk grafik: Proyek per Sektor
            $projectsPerSector = Project::select('sector', DB::raw('count(*) as total'))
                ->groupBy('sector')
                ->orderBy('total', 'desc')
                ->get();

            // Data untuk grafik: Pagu Anggaran per Sektor
            $budgetPerSector = Project::select('sector', DB::raw('sum(budget) as total_budget'))
                ->groupBy('sector')
                ->orderBy('total_budget', 'desc')
                ->get();

            // Ambil pengaturan aplikasi
            $settings = Setting::all()->keyBy('key');
            $appNameDisplay = $settings['app_name_display']->value ?? config('app.name');
            $appSlogan = $settings['app_slogan']->value ?? 'Sistem Informasi Manajemen Pembangunan';


            $data = compact(
                'totalProjects',
                'totalBudget',
                'completedProjects',
                'lateProjects',
                'onTrackProjects',
                'projectsPerSector',
                'budgetPerSector',
                'appNameDisplay',
                'appSlogan'
            );

            // Load view Blade untuk laporan
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('admin.reports.summary_projects', $data);

            // Opsional: Atur ukuran kertas dan orientasi
            // $pdf->setPaper('A4', 'landscape');

            // Return PDF sebagai download atau tampilkan di browser
            return $pdf->stream('laporan_ringkasan_proyek_' . date('Ymd_His') . '.pdf');
        } catch (\Exception $e) {
            Log::error("Error generating PDF report: " . $e->getMessage() . " at " . $e->getFile() . " line " . $e->getLine());
            return redirect()->back()->with('error', 'Gagal menghasilkan laporan PDF: ' . $e->getMessage());
        }
    }
}
