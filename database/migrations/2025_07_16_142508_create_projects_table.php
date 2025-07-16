<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahkan ini agar DB::statement bisa dipakai

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Proyek
            $table->string('responsible_agency')->nullable(); // Dinas Penanggung Jawab
            $table->string('sector')->nullable(); // Sektor Pembangunan (Infrastruktur, Pertanian, dll.)
            $table->decimal('budget', 15, 2)->nullable(); // Pagu Anggaran
            $table->date('start_date')->nullable(); // Tanggal Mulai
            $table->date('end_date')->nullable(); // Tanggal Selesai Target
            $table->text('description')->nullable(); // Deskripsi Proyek
            $table->decimal('progress_percentage', 5, 2)->default(0); // Progres Fisik (%)
            $table->string('status')->default('On-Track'); // Status Proyek (On-Track, Terlambat, Selesai)
            $table->decimal('latitude', 10, 7)->nullable(); // Kolom Latitude
            $table->decimal('longitude', 10, 7)->nullable(); // Kolom Longitude
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
