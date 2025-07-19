<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project; // Import model Project
use App\Notifications\OverdueProjectNotification; // Import kelas Notifikasi
use App\Models\User; // Import model User (untuk mengirim notifikasi ke admin)
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class CheckOverdueProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:check-overdue'; // Nama perintah yang akan kita panggil

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for overdue projects and sends notifications to admin.'; // Deskripsi perintah

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai pengecekan proyek terlambat...');

        // Ambil semua proyek yang berstatus 'Terlambat'
        // Anda bisa menambahkan kriteria lain jika diperlukan, misalnya:
        // - Proyek yang target_end_date-nya sudah lewat DAN statusnya bukan 'Selesai'
        $overdueProjects = Project::where('status', 'Terlambat')->get();

        if ($overdueProjects->isEmpty()) {
            $this->info('Tidak ada proyek yang berstatus Terlambat saat ini.');
            return Command::SUCCESS;
        }

        // Dapatkan user admin yang akan menerima notifikasi
        // Anda bisa mengganti ini dengan role atau email spesifik
        $adminUsers = User::role('super_admin')->get(); // Mengambil semua user dengan peran 'super_admin'
        // Atau jika hanya ingin mengirim ke satu email tertentu:
        // $adminUser = User::where('email', 'admin@simapel.com')->first();
        // if (!$adminUser) {
        //     $this->error('User admin@simapel.com tidak ditemukan. Notifikasi tidak dapat dikirim.');
        //     return Command::FAILURE;
        // }

        if ($adminUsers->isEmpty()) {
            $this->error('Tidak ada user admin ditemukan untuk menerima notifikasi.');
            return Command::FAILURE;
        }

        foreach ($overdueProjects as $project) {
            // Kirim notifikasi untuk setiap proyek yang terlambat
            foreach ($adminUsers as $admin) {
                $admin->notify(new OverdueProjectNotification($project));
                $this->info("Notifikasi untuk proyek '{$project->name}' dikirim ke {$admin->email}.");
            }
        }

        $this->info('Pengecekan proyek terlambat selesai. Notifikasi telah dikirim.');

        return Command::SUCCESS;
    }
}