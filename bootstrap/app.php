<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        // Tambahkan log untuk melihat apakah scheduler ini dipanggil
        Log::info('Scheduler is running and defining tasks.');

        // Jadwalkan perintah untuk mengecek proyek terlambat
        $schedule->command('projects:check-overdue')
            ->everyMinute() // UBAH KE everyMinute() UNTUK PENGUJIAN
            ->onSuccess(function () {
                Log::info('projects:check-overdue command succeeded.');
            })
            ->onFailure(function () {
                Log::error('projects:check-overdue command failed.');
            });
    })
    ->create();