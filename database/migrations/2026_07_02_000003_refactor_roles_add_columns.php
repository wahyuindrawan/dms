<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Refactor: roles
 * Menambah kolom is_active dan sort_order ke tabel roles yang sudah ada.
 * Tabel roles dibuat di: 2025_12_08_013746_create_roles_table (TETAP DIPAKAI).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('color');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('is_active');

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropColumn(['is_active', 'sort_order']);
        });
    }
};
