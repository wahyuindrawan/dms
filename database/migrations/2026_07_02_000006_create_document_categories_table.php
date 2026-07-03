<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: document_categories
 * Tujuan: Master kategori dokumen (menggantikan kategori_dokumen).
 * Dapat dikaitkan ke document_type tertentu, atau berlaku untuk semua jenis (nullable).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()
                ->comment('Kode unik kategori, mis: KEUANGAN, SDM, UMUM');
            $table->string('name', 100)->comment('Nama kategori');
            $table->text('description')->nullable();
            $table->foreignId('document_type_id')
                ->nullable()
                ->constrained('document_types')
                ->nullOnDelete()
                ->comment('NULL = berlaku untuk semua jenis dokumen');
            $table->string('color', 7)
                ->nullable()
                ->comment('Warna HEX untuk badge, mis: #3B82F6');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('document_type_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_categories');
    }
};
