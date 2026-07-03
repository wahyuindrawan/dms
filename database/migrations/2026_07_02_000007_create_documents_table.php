<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: documents
 * Tujuan: Tabel transaksi utama — menggantikan surat_masuk, surat_keluar, dokumen_lain.
 *
 * Model Hybrid:
 *   - Kolom `direction` membedakan masuk/keluar/internal.
 *   - Kolom `document_type_id` menentukan jenis (SK, SOP, SM, dll).
 *   - Tabel detail (sk_details, sop_details) menyimpan kolom spesifik per jenis.
 *   - Source & Destination mendukung unit internal maupun pihak eksternal.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Identitas Dokumen
            $table->string('doc_number', 100)->unique()
                ->comment('Nomor dokumen auto-generate, mis: SM-0001/07/2026');
            $table->string('reference_number', 100)->nullable()
                ->comment('Nomor referensi asli dari pengirim/penerima');
            $table->string('title', 255)->comment('Judul dokumen');
            $table->string('subject', 255)->nullable()->comment('Perihal');

            // Klasifikasi
            $table->foreignId('document_type_id')
                ->constrained('document_types')
                ->comment('Jenis dokumen: SK, SOP, SM, dll');
            $table->foreignId('document_category_id')
                ->nullable()
                ->constrained('document_categories')
                ->nullOnDelete()
                ->comment('Kategori dokumen');
            $table->enum('direction', ['incoming', 'outgoing', 'internal'])
                ->comment('Arah: incoming=masuk, outgoing=keluar, internal=internal');

            // Asal Dokumen
            $table->enum('source_type', ['unit', 'external'])->default('external')
                ->comment('Tipe asal: unit (internal) atau external');
            $table->foreignId('source_unit_id')
                ->nullable()
                ->constrained('units')
                ->nullOnDelete()
                ->comment('Unit asal jika source_type=unit');
            $table->string('source_name', 255)->nullable()
                ->comment('Nama asal jika source_type=external (instansi/orang)');

            // Tujuan Dokumen
            $table->enum('destination_type', ['unit', 'external', 'person'])->default('unit')
                ->comment('Tipe tujuan');
            $table->foreignId('destination_unit_id')
                ->nullable()
                ->constrained('units')
                ->nullOnDelete()
                ->comment('Unit tujuan jika destination_type=unit');
            $table->string('destination_name', 255)->nullable()
                ->comment('Nama tujuan jika external atau person');

            // Tanggal
            $table->date('document_date')->comment('Tanggal tertera pada dokumen');
            $table->date('received_date')->nullable()
                ->comment('Tanggal diterima (untuk incoming)');
            $table->date('issued_date')->nullable()
                ->comment('Tanggal dikeluarkan/dikirim (untuk outgoing)');

            // Status & Catatan
            $table->enum('status', ['draft', 'active', 'archived', 'void'])
                ->default('draft')
                ->comment('Status dokumen');
            $table->text('notes')->nullable()->comment('Keterangan tambahan');

            // Audit Trail
            $table->foreignId('created_by')
                ->constrained('users')
                ->comment('User yang membuat');
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('User yang terakhir mengubah');

            $table->softDeletes();
            $table->timestamps();

            // === INDEXES ===
            $table->index('document_type_id');
            $table->index('document_category_id');
            $table->index('direction');
            $table->index('status');
            $table->index('document_date');
            $table->index('received_date');
            $table->index('issued_date');
            $table->index('source_unit_id');
            $table->index('destination_unit_id');
            $table->index('created_by');
            $table->index('deleted_at');

            // Composite indexes untuk query umum
            $table->index(['direction', 'status'], 'idx_direction_status');
            $table->index(['document_type_id', 'direction'], 'idx_type_direction');
            $table->index(['document_type_id', 'status'], 'idx_type_status');
            $table->index(['source_type', 'source_unit_id'], 'idx_source');
            $table->index(['destination_type', 'destination_unit_id'], 'idx_destination');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
