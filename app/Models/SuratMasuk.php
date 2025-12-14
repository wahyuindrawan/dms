<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratMasuk extends Model
{
    use SoftDeletes;
    protected $table = 'surat_masuk';
    protected static function booted()
    {
        static::creating(function ($surat) {
            $surat->kode_surat = 'SM-' . str_pad((static::withTrashed()->max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);
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
        'tanggal_masuk',
        'ditujukan_id',
        'file_path',
        'file_original',
        'keterangan',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriDokumen::class, 'kategori_id');
    }

    public function sumber()
    {
        return $this->belongsTo(SumberDokumen::class, 'sumber_id');
    }

    public function ditujukan()
    {
        return $this->belongsTo(Worker::class, 'ditujukan_id');
    }
}
