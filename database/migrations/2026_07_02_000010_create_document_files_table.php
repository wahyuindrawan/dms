<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: document_files
 * Tujuan: Penyimpanan file pendukung dokumen.
 * Menggantikan kolom file_path / file_original yang tersebar di setiap tabel lama.
 * Satu dokumen dapat memiliki banyak file (mis: draft + final + lampiran).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                ->constrained('documents')
                ->cascadeOnDelete()
                ->comment('Dokumen induk');

            $table->string('file_name', 255)
                ->comment('Nama file asli saat upload');
            $table->string('file_path', 500)
                ->comment('Path file yang tersimpan di storage disk');
            $table->unsignedBigInteger('file_size')->nullable()
                ->comment('Ukuran file dalam bytes');
            $table->string('mime_type', 100)->nullable()
                ->comment('MIME type, mis: application/pdf, image/jpeg');
            $table->string('disk', 50)->default('public')
                ->comment('Storage disk: public, s3, dll');
            $table->string('file_hash', 64)->nullable()
                ->comment('SHA-256 hash untuk verifikasi integritas file');

            $table->boolean('is_primary')->default(false)
                ->comment('File utama/induk dokumen (hanya satu per dokumen)');

            $table->enum('file_type', ['original', 'signed', 'attachment', 'draft'])
                ->default('original')
                ->comment('Jenis file: original, signed, attachment, draft');

            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->comment('User yang mengupload file');

            $table->timestamps();

            // Indexes
            $table->index('document_id');
            $table->index('is_primary');
            $table->index('file_type');
            $table->index('file_hash');
            $table->index(['document_id', 'is_primary'], 'idx_document_primary');
            $table->index(['document_id', 'file_type'], 'idx_document_file_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_files');
    }
};
