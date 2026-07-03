<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: sk_details
 * Tujuan: Kolom spesifik untuk dokumen jenis Surat Keputusan (SK).
 * Relasi 1-to-1 dengan documents (document_id UNIQUE).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sk_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                ->unique()
                ->constrained('documents')
                ->cascadeOnDelete()
                ->comment('Referensi ke tabel documents (1-to-1)');

            $table->date('effective_date')->comment('Tanggal mulai berlaku SK');
            $table->date('expiry_date')->nullable()
                ->comment('Tanggal berakhir SK (null = tidak terbatas)');

            $table->enum('decree_type', [
                'pengangkatan',
                'pemberhentian',
                'penugasan',
                'penetapan',
                'kebijakan',
                'peraturan',
                'lainnya',
            ])->default('penetapan')->comment('Jenis keputusan');

            $table->enum('decree_scope', ['internal', 'external'])
                ->default('internal')
                ->comment('Ruang lingkup SK');

            $table->text('regarding')->nullable()
                ->comment('Tentang / isi pokok SK');
            $table->text('consideration')->nullable()
                ->comment('Menimbang / Mengingat');

            $table->string('signer_name', 100)->nullable()
                ->comment('Nama penandatangan');
            $table->string('signer_position', 100)->nullable()
                ->comment('Jabatan penandatangan');

            $table->timestamps();

            $table->index('effective_date');
            $table->index('expiry_date');
            $table->index('decree_type');
            $table->index('decree_scope');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sk_details');
    }
};
