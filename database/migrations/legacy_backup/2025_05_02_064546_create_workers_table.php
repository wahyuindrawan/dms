<?php
// DEPRECATED — 2026-07-02
// Digantikan oleh: 2026_07_02_000001_create_units_table
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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_worker')->unique();
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
