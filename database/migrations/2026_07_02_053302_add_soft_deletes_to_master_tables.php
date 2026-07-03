<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan soft delete (deleted_at) ke tabel master data:
 * units, document_types, document_categories
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->softDeletes()->after('sort_order');
        });

        Schema::table('document_types', function (Blueprint $table) {
            $table->softDeletes()->after('sort_order');
        });

        Schema::table('document_categories', function (Blueprint $table) {
            $table->softDeletes()->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('document_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('document_categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
