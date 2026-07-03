<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $sk = DocumentType::where('code', 'SK')->first();
        $sop = DocumentType::where('code', 'SOP')->first();

        // Klaster umum (berlaku untuk semua jenis)
        $generalCategories = [
            ['code' => 'UMUM',     'name' => 'Umum',               'color' => '#6B7280', 'sort_order' => 10],
            ['code' => 'KEUANGAN', 'name' => 'Keuangan',           'color' => '#F59E0B', 'sort_order' => 20],
            ['code' => 'SDM',      'name' => 'Sumber Daya Manusia', 'color' => '#3B82F6', 'sort_order' => 30],
            ['code' => 'ASET',     'name' => 'Aset & Logistik',    'color' => '#8B5CF6', 'sort_order' => 40],
            ['code' => 'PROG',     'name' => 'Program & Kegiatan', 'color' => '#10B981', 'sort_order' => 50],
            ['code' => 'MUTU',     'name' => 'Mutu & Akreditasi',  'color' => '#EC4899', 'sort_order' => 60],
        ];

        foreach ($generalCategories as $cat) {
            DocumentCategory::updateOrCreate(
                ['code' => $cat['code']],
                array_merge($cat, ['document_type_id' => null, 'is_active' => true])
            );
        }

        // Klaster khusus SK
        if ($sk) {
            $skCategories = [
                ['code' => 'SK-JABATAN', 'name' => 'SK Jabatan',      'color' => '#EF4444', 'sort_order' => 100],
                ['code' => 'SK-TUGAS',   'name' => 'SK Tugas',        'color' => '#F97316', 'sort_order' => 110],
                ['code' => 'SK-SANKSI',  'name' => 'SK Sanksi',       'color' => '#DC2626', 'sort_order' => 120],
                ['code' => 'SK-UMK',     'name' => 'SK UMK / Insentif','color' => '#16A34A', 'sort_order' => 130],
            ];

            foreach ($skCategories as $cat) {
                DocumentCategory::updateOrCreate(
                    ['code' => $cat['code']],
                    array_merge($cat, ['document_type_id' => $sk->id, 'is_active' => true])
                );
            }
        }

        // Klaster khusus SOP
        if ($sop) {
            $sopCategories = [
                ['code' => 'SOP-KLINIS',   'name' => 'SOP Klinis',      'color' => '#0EA5E9', 'sort_order' => 200],
                ['code' => 'SOP-ADMIN',    'name' => 'SOP Administrasi','color' => '#7C3AED', 'sort_order' => 210],
                ['code' => 'SOP-FARMASI',  'name' => 'SOP Farmasi',     'color' => '#059669', 'sort_order' => 220],
                ['code' => 'SOP-LABORAT',  'name' => 'SOP Laboratorium','color' => '#D97706', 'sort_order' => 230],
            ];

            foreach ($sopCategories as $cat) {
                DocumentCategory::updateOrCreate(
                    ['code' => $cat['code']],
                    array_merge($cat, ['document_type_id' => $sop->id, 'is_active' => true])
                );
            }
        }
    }
}
