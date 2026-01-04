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
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat')->nullable(); // auto increment logic via observer/boot
            $table->string('nomor_surat')->nullable();
            $table->string('judul');
            $table->string('perihal')->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_dokumen')->nullOnDelete();
            $table->foreignId('sumber_id')->nullable()->constrained('sumber_dokumen')->nullOnDelete();
            $table->date('tanggal_surat');
            $table->date('tanggal_keluar');
            $table->string('ditujukan')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_original')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
