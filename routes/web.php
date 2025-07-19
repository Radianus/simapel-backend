<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PublicController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PublicController::class, 'index'])->name('public.home');
Route::get('/dashboard', function (Request $request) {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Rute Admin untuk Manajemen SIMAPEL ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Dashboard kita
        Route::resource('projects', ProjectController::class);
        Route::resource('users', UserController::class);
        // Rute untuk Export Proyek
        Route::get('/projects/export', [ProjectController::class, 'export'])
            ->name('projects.export')
            ->middleware('permission:view projects'); // User harus punya izin 'view projects' untuk bisa export
        // --- Rute Pengaturan Sistem ---
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        // --- Rute Laporan PDF ---
        Route::get('/reports/summary-projects-pdf', [ReportController::class, 'summaryProjectsPdf'])->name('reports.summary-projects.pdf');
    });
    // --- Rute untuk Download Media Terproteksi ---
    Route::get('/media/download/{id}/{filename}', [MediaController::class, 'download'])->name('media.download');
});


require __DIR__ . '/auth.php';