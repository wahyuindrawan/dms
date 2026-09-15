<?php
// DEPRECATED — 2026-07-02
// Fitur disposisi akan didesain ulang sebagai document_routing di fase berikutnya.
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
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            // Note: surat_masuk_id foreign key removed - table surat_masuk no longer exists
            // This will be handled by migration 2026_07_15_000002_update_disposisi_table_for_independence.php
            $table->unsignedBigInteger('surat_masuk_id')->nullable();
            $table->foreignId('dari_worker_id')->nullable()->constrained('workers')->nullOnDelete();
            $table->foreignId('ke_worker_id')->nullable()->constrained('workers')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'selesai'])->default('pending');
            $table->boolean('dibaca')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};
