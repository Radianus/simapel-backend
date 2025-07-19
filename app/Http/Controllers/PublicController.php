<?php

namespace App\Http\Controllers;

use App\Models\Project; // Import model Project
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk query agregasi
use App\Models\Setting; // Import model Setting

class PublicController extends Controller
{
    /**
     * Menampilkan halaman utama publik dengan peta proyek dan statistik ringkasan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua proyek yang statusnya 'Selesai' atau 'On-Track' untuk tampilan publik
        // Dan juga hanya proyek yang memiliki koordinat
        $projects = Project::whereIn('status', ['Selesai', 'On-Track'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Ambil data statistik dasar
        $totalProjects = Project::count();
        $completedProjects = Project::where('status', 'Selesai')->count();
        $onTrackProjects = Project::where('status', 'On-Track')->count();
        $totalBudget = Project::sum('budget');

        // Ambil pengaturan aplikasi jika diperlukan (misal: untuk judul, slogan)
        $settings = Setting::all()->keyBy('key');
        $appNameDisplay = $settings['app_name_display']->value ?? config('app.name');
        $appSlogan = $settings['app_slogan']->value ?? 'Sistem Informasi Manajemen Pembangunan';


        return view('public.home', compact(
            'projects',
            'totalProjects',
            'completedProjects',
            'onTrackProjects',
            'totalBudget',
            'appNameDisplay',
            'appSlogan'
        ));
    }

    // Anda bisa menambahkan method lain di sini untuk halaman publik lain
    // public function about() { return view('public.about'); }
    // public function contact() { return view('public.contact'); }
}
