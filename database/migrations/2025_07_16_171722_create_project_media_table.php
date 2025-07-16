<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Foreign key ke tabel projects
            $table->string('file_path'); // Path file di storage
            $table->string('file_name'); // Nama asli file
            $table->string('file_type'); // Mime type (image/jpeg, application/pdf)
            $table->string('media_type'); // Tipe media (foto, dokumen)
            $table->text('description')->nullable(); // Deskripsi singkat media
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_media');
    }
};
