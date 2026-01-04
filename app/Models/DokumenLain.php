<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenLain extends Model
{
    protected $table = 'dokumen_lain';
    protected $fillable = [
        'kode_dokumen',
        'judul',
        'kategori_id',
        'sumber_id',
        'tanggal_dokumen',
        'file_path',
        'file_original',
        'deskripsi',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->kode_dokumen = 'DKM-' . str_pad((static::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriDokumen::class, 'kategori_id');
    }

    public function sumber()
    {
        return $this->belongsTo(SumberDokumen::class, 'sumber_id');
    }
}
