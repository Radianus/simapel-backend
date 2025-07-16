<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk query agregasi

class DashboardController extends Controller
{
    // Untuk saat ini, kita tidak akan menerapkan middleware permission di sini
    // agar admin dan staf bisa melihat dashboard tanpa masalah izin.
    // Jika nanti ada kebutuhan, kita bisa tambahkan:
    // public function __construct()
    // {
    //     $this->middleware('permission:view dashboard'); // Contoh izin baru
    // }

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

        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalProjects',
            'totalBudget',
            'completedProjects',
            'lateProjects',
            'onTrackProjects',
            'projectsPerSector',
            'projectsPerStatus'
        ));
    }
}
