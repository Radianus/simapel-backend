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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Kunci pengaturan (misal: app_name, app_logo, contact_email)
            $table->text('value')->nullable(); // Nilai pengaturan
            $table->text('description')->nullable(); // Deskripsi singkat pengaturan (untuk UI admin)
            $table->string('type')->default('text'); // Tipe input di form (text, textarea, number, file, email)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};