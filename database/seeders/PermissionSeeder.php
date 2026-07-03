<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Documents
            ['name' => 'document.view-any', 'display_name' => 'Lihat Semua Dokumen', 'group' => 'Dokumen', 'description' => 'Melihat daftar dokumen'],
            ['name' => 'document.view', 'display_name' => 'Lihat Detail Dokumen', 'group' => 'Dokumen', 'description' => 'Melihat detail dokumen spesifik'],
            ['name' => 'document.create', 'display_name' => 'Buat Dokumen', 'group' => 'Dokumen', 'description' => 'Membuat dokumen baru'],
            ['name' => 'document.update', 'display_name' => 'Ubah Dokumen', 'group' => 'Dokumen', 'description' => 'Mengubah dokumen'],
            ['name' => 'document.delete', 'display_name' => 'Hapus Dokumen', 'group' => 'Dokumen', 'description' => 'Menghapus dokumen'],

            // Units
            ['name' => 'unit.view-any', 'display_name' => 'Lihat Semua Unit', 'group' => 'Unit Organisasi', 'description' => 'Melihat daftar unit organisasi'],
            ['name' => 'unit.view', 'display_name' => 'Lihat Detail Unit', 'group' => 'Unit Organisasi', 'description' => 'Melihat detail unit organisasi'],
            ['name' => 'unit.create', 'display_name' => 'Buat Unit', 'group' => 'Unit Organisasi', 'description' => 'Membuat unit organisasi baru'],
            ['name' => 'unit.update', 'display_name' => 'Ubah Unit', 'group' => 'Unit Organisasi', 'description' => 'Mengubah unit organisasi'],
            ['name' => 'unit.delete', 'display_name' => 'Hapus Unit', 'group' => 'Unit Organisasi', 'description' => 'Menghapus unit organisasi'],

            // Document Types
            ['name' => 'document-type.view-any', 'display_name' => 'Lihat Semua Jenis Dokumen', 'group' => 'Jenis Dokumen', 'description' => 'Melihat daftar jenis dokumen'],
            ['name' => 'document-type.view', 'display_name' => 'Lihat Detail Jenis Dokumen', 'group' => 'Jenis Dokumen', 'description' => 'Melihat detail jenis dokumen'],
            ['name' => 'document-type.create', 'display_name' => 'Buat Jenis Dokumen', 'group' => 'Jenis Dokumen', 'description' => 'Membuat jenis dokumen baru'],
            ['name' => 'document-type.update', 'display_name' => 'Ubah Jenis Dokumen', 'group' => 'Jenis Dokumen', 'description' => 'Mengubah jenis dokumen'],
            ['name' => 'document-type.delete', 'display_name' => 'Hapus Jenis Dokumen', 'group' => 'Jenis Dokumen', 'description' => 'Menghapus jenis dokumen'],

            // Document Categories
            ['name' => 'document-category.view-any', 'display_name' => 'Lihat Semua Kategori Dokumen', 'group' => 'Kategori Dokumen', 'description' => 'Melihat daftar kategori dokumen'],
            ['name' => 'document-category.view', 'display_name' => 'Lihat Detail Kategori Dokumen', 'group' => 'Kategori Dokumen', 'description' => 'Melihat detail kategori dokumen'],
            ['name' => 'document-category.create', 'display_name' => 'Buat Kategori Dokumen', 'group' => 'Kategori Dokumen', 'description' => 'Membuat kategori dokumen baru'],
            ['name' => 'document-category.update', 'display_name' => 'Ubah Kategori Dokumen', 'group' => 'Kategori Dokumen', 'description' => 'Mengubah kategori dokumen'],
            ['name' => 'document-category.delete', 'display_name' => 'Hapus Kategori Dokumen', 'group' => 'Kategori Dokumen', 'description' => 'Menghapus kategori dokumen'],

            // Users
            ['name' => 'user.view-any', 'display_name' => 'Lihat Semua Pengguna', 'group' => 'Pengguna', 'description' => 'Melihat daftar pengguna'],
            ['name' => 'user.view', 'display_name' => 'Lihat Detail Pengguna', 'group' => 'Pengguna', 'description' => 'Melihat detail pengguna'],
            ['name' => 'user.create', 'display_name' => 'Buat Pengguna', 'group' => 'Pengguna', 'description' => 'Membuat pengguna baru'],
            ['name' => 'user.update', 'display_name' => 'Ubah Pengguna', 'group' => 'Pengguna', 'description' => 'Mengubah data pengguna'],
            ['name' => 'user.delete', 'display_name' => 'Hapus Pengguna', 'group' => 'Pengguna', 'description' => 'Menghapus pengguna'],

            // Roles
            ['name' => 'role.view-any', 'display_name' => 'Lihat Semua Peran', 'group' => 'Peran & Izin', 'description' => 'Melihat daftar peran pengguna'],
            ['name' => 'role.view', 'display_name' => 'Lihat Detail Peran', 'group' => 'Peran & Izin', 'description' => 'Melihat detail peran pengguna'],
            ['name' => 'role.create', 'display_name' => 'Buat Peran', 'group' => 'Peran & Izin', 'description' => 'Membuat peran baru'],
            ['name' => 'role.update', 'display_name' => 'Ubah Peran', 'group' => 'Peran & Izin', 'description' => 'Mengubah peran dan izin'],
            ['name' => 'role.delete', 'display_name' => 'Hapus Peran', 'group' => 'Peran & Izin', 'description' => 'Menghapus peran'],
        ];

        $permissionIds = [];
        foreach ($permissions as $perm) {
            $p = Permission::updateOrCreate(
                ['name' => $perm['name']],
                $perm
            );
            $permissionIds[] = $p->id;
        }

        // Assign all permissions to administrator role
        $adminRole = Role::where('nama', 'admin')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync($permissionIds);
        }

        // Assign document permissions to other roles
        $pimpinanRole = Role::where('nama', 'pimpinan')->first();
        if ($pimpinanRole) {
            $pimpinanPerms = Permission::whereIn('name', [
                'document.view-any',
                'document.view',
            ])->pluck('id')->toArray();
            $pimpinanRole->permissions()->sync($pimpinanPerms);
        }

        $tuRole = Role::where('nama', 'tu')->first();
        if ($tuRole) {
            $tuPerms = Permission::whereIn('name', [
                'document.view-any',
                'document.view',
                'document.create',
                'document.update',
            ])->pluck('id')->toArray();
            $tuRole->permissions()->sync($tuPerms);
        }

        $karyawanRole = Role::where('nama', 'karyawan')->first();
        if ($karyawanRole) {
            $karyawanPerms = Permission::whereIn('name', [
                'document.view-any',
                'document.view',
                'document.create',
            ])->pluck('id')->toArray();
            $karyawanRole->permissions()->sync($karyawanPerms);
        }
    }
}
