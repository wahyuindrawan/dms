<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: units
 * Tujuan: Master unit/satuan organisasi (menggantikan tabel workers dan sumber_dokumen).
 * Mendukung hierarki organisasi (self-referential parent_id).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Kode unik unit, mis: DIR-01, DIV-02');
            $table->string('name', 150)->comment('Nama lengkap unit');
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('units')
                ->nullOnDelete()
                ->comment('Referensi unit induk untuk hierarki');
            $table->enum('type', [
                'lembaga',
                'direktorat',
                'divisi',
                'bagian',
                'seksi',
                'unit',
            ])->default('unit')->comment('Jenis unit organisasi');
            $table->string('head_name', 100)->nullable()->comment('Nama kepala unit');
            $table->string('head_position', 100)->nullable()->comment('Jabatan kepala unit');
            $table->string('phone', 25)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('parent_id');
            $table->index('type');
            $table->index('is_active');
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
