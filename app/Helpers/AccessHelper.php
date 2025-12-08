<?php

namespace App\Helpers;

class AccessHelper
{
    public static function canViewSuratMasuk(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'pimpinan', 'tu', 'karyawan']);
    }

    public static function canCreateSuratMasuk(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'tu']);
    }

    public static function canEditSuratMasuk(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'tu']);
    }

    public static function canDeleteSuratMasuk(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
