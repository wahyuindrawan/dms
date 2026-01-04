<?php

namespace Database\Seeders;

use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            [
                'nama' => 'admin',
                'display_name' => 'Administrator',
                'deskripsi' => 'Administrator sistem',
                'color' => 'blue',
            ],
            [
                'nama' => 'pimpinan',
                'display_name' => 'Pimpinan',
                'deskripsi' => 'Pimpinan organisasi',
                'color' => 'green',
            ],
            [
                'nama' => 'tu',
                'display_name' => 'Tata Usaha',
                'deskripsi' => 'Tata Usaha',
                'color' => 'yellow',
            ],
            [
                'nama' => 'karyawan',
                'display_name' => 'Karyawan',
                'deskripsi' => 'Karyawan',
                'color' => 'gray',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['nama' => $role['nama']],
                $role
            );
        }
    }
}
