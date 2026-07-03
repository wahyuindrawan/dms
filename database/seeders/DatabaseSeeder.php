<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UnitSeeder::class,
            DocumentCategorySeeder::class,
        ]);

        $docTypes = [
            [
                'code' => 'SK',
                'name' => 'Surat Keputusan (SK)',
                'prefix' => 'SK-',
                'numbering_format' => '{prefix}{seq}/{mm}/{yyyy}',
                'seq_length' => 4,
                'has_detail' => true,
                'detail_table' => 'sk_details',
            ],
            [
                'code' => 'SOP',
                'name' => 'Standar Operasional Prosedur (SOP)',
                'prefix' => 'SOP-',
                'numbering_format' => '{prefix}{seq}/{mm}/{yyyy}',
                'seq_length' => 4,
                'has_detail' => true,
                'detail_table' => 'sop_details',
            ],
            [
                'code' => 'RUK',
                'name' => 'Rencana Usulan Kegiatan (RUK)',
                'prefix' => 'RUK-',
                'numbering_format' => '{prefix}{seq}/{mm}/{yyyy}',
                'seq_length' => 4,
                'has_detail' => false,
                'detail_table' => null,
            ],
            [
                'code' => 'RPK',
                'name' => 'Rencana Pelaksanaan Kegiatan (RPK)',
                'prefix' => 'RPK-',
                'numbering_format' => '{prefix}{seq}/{mm}/{yyyy}',
                'seq_length' => 4,
                'has_detail' => false,
                'detail_table' => null,
            ],
        ];

        foreach ($docTypes as $docType) {
            \App\Models\DocumentType::updateOrCreate(
                ['code' => $docType['code']],
                $docType
            );
        }

        // User::factory(10)->create();

        $adminRole = \App\Models\Role::where('nama', 'admin')->first();

        User::updateOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'role_id' => $adminRole?->id,
            ]
        );
    }
}
