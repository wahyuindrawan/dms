<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disposisi', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            // (it may not exist if create_disposisi_table was modified)
            if (Schema::hasColumn('disposisi', 'surat_masuk_id')) {
                // Only drop foreign key if it exists
                try {
                    $table->dropForeign(['surat_masuk_id']);
                } catch (\Exception $e) {
                    // Foreign key doesn't exist, continue
                }
                $table->dropColumn('surat_masuk_id');
            }
            
            if (!Schema::hasColumn('disposisi', 'deskripsi_dokumen')) {
                $table->string('deskripsi_dokumen')->nullable()->after('dari_worker_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('disposisi', function (Blueprint $table) {
            if (!Schema::hasColumn('disposisi', 'surat_masuk_id')) {
                $table->foreignId('surat_masuk_id')->nullable()->after('id');
                $table->foreign('surat_masuk_id')->references('id')->on('surat_masuk')->onDelete('cascade');
            }
            
            if (Schema::hasColumn('disposisi', 'deskripsi_dokumen')) {
                $table->dropColumn('deskripsi_dokumen');
            }
        });
    }
};
