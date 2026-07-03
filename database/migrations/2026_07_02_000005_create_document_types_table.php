<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: document_types
 * Tujuan: Master jenis dokumen (SK, SOP, Surat Masuk, Surat Keluar, dll).
 * Mendukung penomoran otomatis dengan format konfigurabel.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()
                ->comment('Kode singkat: SM, SK-OUT, SK, SOP, DL');
            $table->string('name', 100)->comment('Nama lengkap jenis dokumen');
            $table->text('description')->nullable();

            // Auto-numbering
            $table->string('prefix', 10)
                ->comment('Prefix kode dokumen: SM-, SK-, SOP-, DL-');
            $table->string('numbering_format', 100)
                ->default('{prefix}{seq}/{mm}/{yyyy}')
                ->comment('Format penomoran: {prefix}, {seq}, {dd}, {mm}, {yyyy}');
            $table->unsignedTinyInteger('seq_length')
                ->default(4)
                ->comment('Panjang padding angka urut, mis: 4 → 0001');

            // Detail table linkage
            $table->boolean('has_detail')
                ->default(false)
                ->comment('Apakah jenis ini punya tabel detail (sk_details, sop_details)');
            $table->string('detail_table', 100)
                ->nullable()
                ->comment('Nama tabel detail yang digunakan, mis: sk_details');

            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('is_active');
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
