<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
