<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('surat_masuk');
        Schema::dropIfExists('surat_keluar');
        Schema::dropIfExists('dokumen_lain');
        Schema::dropIfExists('kategori_dokumen');
        Schema::dropIfExists('sumber_dokumen');
    }

    public function down(): void
    {
        // Note: Down migration not provided as these are legacy tables
        // Restore from backup if needed
    }
};
