<?php

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
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained('surat_masuk')->onDelete('cascade');
            $table->foreignId('dari_worker_id')->nullable()->constrained('workers')->nullOnDelete();
            $table->foreignId('ke_worker_id')->constrained('workers')->onDelete('cascade');
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'selesai'])->default('pending');
            $table->boolean('dibaca')->default(false);;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};
