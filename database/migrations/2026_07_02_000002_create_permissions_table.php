<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: permissions & role_permission
 * Tujuan: Sistem izin granular per fitur.
 * Menggantikan pengecekan role string hardcode di Policy.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()
                ->comment('Slug permission, mis: document.create, document.view-any');
            $table->string('display_name', 100)->comment('Nama tampilan');
            $table->string('group', 50)->nullable()
                ->comment('Kelompok permission, mis: document, admin, report');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('group');
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();
            $table->foreignId('permission_id')
                ->constrained('permissions')
                ->cascadeOnDelete();

            $table->primary(['role_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
    }
};
