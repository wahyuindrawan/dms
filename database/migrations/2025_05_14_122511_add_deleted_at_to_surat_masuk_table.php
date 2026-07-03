<?php
// DEPRECATED — 2026-07-02
// Parent table (surat_masuk) sudah deprecated. SoftDelete ada di tabel documents.
// Rekomendasi: Pindahkan ke database/migrations/legacy/

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
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
