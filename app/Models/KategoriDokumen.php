<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriDokumen extends Model
{
    protected $table = 'kategori_dokumens';

    protected $fillable = [
        'nama',
        'keterangan',
    ];
}
