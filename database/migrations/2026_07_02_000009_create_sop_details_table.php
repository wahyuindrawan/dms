<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: sop_details
 * Tujuan: Kolom spesifik untuk dokumen jenis SOP (Standar Operasional Prosedur).
 * Relasi 1-to-1 dengan documents (document_id UNIQUE).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sop_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                ->unique()
                ->constrained('documents')
                ->cascadeOnDelete()
                ->comment('Referensi ke tabel documents (1-to-1)');

            $table->string('version', 20)->default('1.0')
                ->comment('Versi SOP, mis: 1.0, 2.1');
            $table->unsignedSmallInteger('revision_number')->default(0)
                ->comment('Nomor revisi ke-N');

            $table->date('effective_date')->comment('Tanggal mulai berlaku SOP');
            $table->date('review_date')->nullable()
                ->comment('Tanggal review/evaluasi berikutnya');

            $table->foreignId('process_owner_unit_id')
                ->nullable()
                ->constrained('units')
                ->nullOnDelete()
                ->comment('Unit pemilik/penanggung jawab proses');

            $table->text('scope')->nullable()
                ->comment('Ruang lingkup SOP');
            $table->text('purpose')->nullable()
                ->comment('Tujuan SOP');
            $table->text('reference')->nullable()
                ->comment('Referensi/dasar hukum yang digunakan');

            $table->timestamps();

            $table->index('effective_date');
            $table->index('review_date');
            $table->index('version');
            $table->index('process_owner_unit_id');
            $table->index(['version', 'revision_number'], 'idx_version_revision');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sop_details');
    }
};
