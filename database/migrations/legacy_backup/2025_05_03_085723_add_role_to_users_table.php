<?php
// DEPRECATED — 2026-07-02
// Digantikan oleh: 2026_07_02_000004_refactor_users_add_unit_and_role_id (kolom role_id FK)
// Kolom string 'role' tetap ada untuk kompatibilitas data lama.
// Rekomendasi: Pindahkan ke database/migrations/legacy/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('karyawan')->after('worker_id'); // default: user biasa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
