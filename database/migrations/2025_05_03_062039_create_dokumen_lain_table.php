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
        Schema::create('dokumen_lain', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dokumen')->unique();
            $table->string('judul');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_dokumens')->nullOnDelete();
            $table->foreignId('sumber_id')->nullable()->constrained('sumber_dokumens')->nullOnDelete();
            $table->date('tanggal_dokumen');
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
        Schema::dropIfExists('dokumen_lain');
    }
};
