<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratKeluar extends Model
{
    use SoftDeletes;
    
    protected static function booted()
    {
        static::creating(function ($surat) {
            $surat->kode_surat = 'SK-' . str_pad((static::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    protected $fillable = [
        'kode_surat',
        'nomor_surat',
        'judul',
        'perihal',
        'kategori_id',
        'sumber_id',
        'tanggal_surat',
        'tanggal_keluar',
        'ditujukan',
        'file_path',
        'file_original',
        'deskripsi',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriDokumen::class);
    }

    public function sumber()
    {
        return $this->belongsTo(SumberDokumen::class);
    }
}
