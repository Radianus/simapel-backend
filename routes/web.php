<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Admin\UserController; // Import User Controller
use Illuminate\Http\Request; // Tambahkan ini jika belum ada
use Illuminate\Support\Facades\Auth;

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

// --- Rute Halaman Utama ---
Route::get('/', function () {
    // Jika user sudah login, arahkan ke dashboard admin
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    // Jika belum login, arahkan ke halaman login
    return redirect()->route('login');
})->name('home');

// Rute dashboard bawaan Laravel Breeze, kita akan ganti fungsinya
// Sekarang, setelah login, user akan diarahkan ke admin.dashboard
Route::get('/dashboard', function (Request $request) {
    // Ini adalah rute default /dashboard dari Breeze
    // Kita bisa redirect ke admin.dashboard atau menampilkannya sendiri
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

    });

    // --- Rute untuk Download Media Terproteksi ---
    Route::get('/media/download/{id}/{filename}', [MediaController::class, 'download'])->name('media.download');
});


require __DIR__ . '/auth.php';
