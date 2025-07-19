<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk query agregasi

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil data statistik dasar
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

        // Data untuk grafik: Proyek per Status
        $projectsPerStatus = Project::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // --- DATA BARU UNTUK DASHBOARD ---
        // Data untuk grafik: Proyek per Dinas Penanggung Jawab
        $projectsPerAgency = Project::select('responsible_agency', DB::raw('count(*) as total'))
            ->groupBy('responsible_agency')
            ->orderBy('total', 'desc')
            ->get();

        // Data untuk grafik: Pagu Anggaran per Sektor
        $budgetPerSector = Project::select('sector', DB::raw('sum(budget) as total_budget'))
            ->groupBy('sector')
            ->orderBy('total_budget', 'desc')
            ->get();
        // --- AKHIR DATA BARU ---
        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalProjects',
            'totalBudget',
            'completedProjects',
            'lateProjects',
            'onTrackProjects',
            'projectsPerSector',
            'projectsPerStatus',
            'projectsPerAgency', // Tambahkan ini
            'budgetPerSector'    // Tambahkan ini
        ));
    }
}