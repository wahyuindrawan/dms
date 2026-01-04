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
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat')->unique();
            $table->string('nomor_surat')->nullable();
            $table->string('judul');
            $table->string('perihal')->nullable();
            $table->date('tanggal_surat');
            $table->date('tanggal_masuk');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_dokumen')->onDelete('set null');
            $table->foreignId('sumber_id')->nullable()->constrained('sumber_dokumen')->onDelete('set null');
            $table->text('deskripsi')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_original')->nullable();
            $table->foreignId('ditujukan_id')->nullable()->constrained('workers')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
