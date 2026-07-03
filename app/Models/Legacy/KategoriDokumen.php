<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

/**
 * @deprecated Use App\Models\DocumentCategory instead.
 */
class KategoriDokumen extends Model
{
    protected $table = 'kategori_dokumen';

    protected $fillable = [
        'nama',
        'keterangan',
    ];
}
