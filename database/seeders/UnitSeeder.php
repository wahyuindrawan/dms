<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        // Lembaga induk
        $lembaga = Unit::updateOrCreate(
            ['code' => 'PUSK'],
            [
                'name'          => 'Puskesmas Contoh',
                'type'          => 'lembaga',
                'head_name'     => 'Dr. Kepala',
                'head_position' => 'Kepala Puskesmas',
                'is_active'     => true,
                'sort_order'    => 1,
            ]
        );

        // Sub-unit level 1
        $tu = Unit::updateOrCreate(
            ['code' => 'TU'],
            [
                'name'          => 'Tata Usaha',
                'parent_id'     => $lembaga->id,
                'type'          => 'bagian',
                'head_name'     => 'Kepala TU',
                'head_position' => 'Kepala Tata Usaha',
                'is_active'     => true,
                'sort_order'    => 10,
            ]
        );

        Unit::updateOrCreate(
            ['code' => 'PROM-KES'],
            [
                'name'          => 'Promosi Kesehatan',
                'parent_id'     => $lembaga->id,
                'type'          => 'bagian',
                'is_active'     => true,
                'sort_order'    => 20,
            ]
        );

        Unit::updateOrCreate(
            ['code' => 'GIZI'],
            [
                'name'          => 'Gizi',
                'parent_id'     => $lembaga->id,
                'type'          => 'bagian',
                'is_active'     => true,
                'sort_order'    => 30,
            ]
        );

        Unit::updateOrCreate(
            ['code' => 'KIA'],
            [
                'name'          => 'KIA / KB',
                'parent_id'     => $lembaga->id,
                'type'          => 'bagian',
                'is_active'     => true,
                'sort_order'    => 40,
            ]
        );

        Unit::updateOrCreate(
            ['code' => 'P2P'],
            [
                'name'          => 'Pencegahan & Pengendalian Penyakit',
                'parent_id'     => $lembaga->id,
                'type'          => 'bagian',
                'is_active'     => true,
                'sort_order'    => 50,
            ]
        );
    }
}
