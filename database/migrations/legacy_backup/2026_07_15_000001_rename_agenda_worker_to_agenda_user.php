<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agenda_worker', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
        });

        DB::statement('RENAME TABLE agenda_worker TO agenda_user');

        Schema::table('agenda_user', function (Blueprint $table) {
            $table->renameColumn('worker_id', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('agenda_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        DB::statement('RENAME TABLE agenda_user TO agenda_worker');

        Schema::table('agenda_worker', function (Blueprint $table) {
            $table->renameColumn('user_id', 'worker_id');
            $table->foreign('worker_id')->references('id')->on('workers')->cascadeOnDelete();
        });
    }
};
