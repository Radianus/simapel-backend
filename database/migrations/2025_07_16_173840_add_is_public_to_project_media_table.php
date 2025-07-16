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
        Schema::table('project_media', function (Blueprint $table) {
            // Tambahkan kolom is_public, default true untuk foto, false untuk dokumen nanti
            $table->boolean('is_public')->default(true)->after('media_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_media', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
};
