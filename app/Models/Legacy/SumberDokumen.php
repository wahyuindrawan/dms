<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

/**
 * @deprecated Use App\Models\Unit instead.
 */
class SumberDokumen extends Model
{
    protected $table = 'sumber_dokumen';

    protected $fillable = [
        'nama',
        'kode_sumber',
        'tipe', // internal / eksternal
        'keterangan',
    ];
}
