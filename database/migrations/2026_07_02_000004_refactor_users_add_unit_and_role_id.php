<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Refactor: users
 * Menambah kolom baru untuk menggantikan sistem role string dan worker_id.
 *
 * Kolom BARU:
 *   - unit_id   → FK ke units (menggantikan worker_id)
 *   - role_id   → FK ke roles (menggantikan kolom string 'role')
 *   - is_active → status akun
 *
 * Kolom LAMA (tetap ada untuk kompatibilitas migrasi data):
 *   - worker_id → DEPRECATED, akan dihapus setelah migrasi data selesai
 *   - role      → DEPRECATED, akan dihapus setelah migrasi data selesai
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('unit_id')
                ->nullable()
                ->constrained('units')
                ->nullOnDelete()
                ->after('worker_id')
                ->comment('Unit organisasi pengguna (menggantikan worker_id)');

            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')
                ->nullOnDelete()
                ->after('unit_id')
                ->comment('FK ke tabel roles (menggantikan kolom string role)');

            $table->boolean('is_active')
                ->default(true)
                ->after('role_id');

            // Indexes
            $table->index('unit_id');
            $table->index('role_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['role_id']);
            $table->dropIndex(['unit_id']);
            $table->dropIndex(['role_id']);
            $table->dropIndex(['is_active']);
            $table->dropColumn(['unit_id', 'role_id', 'is_active']);
        });
    }
};
