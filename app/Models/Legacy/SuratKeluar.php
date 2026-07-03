<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ManageDocumentFileName;

/**
 * @deprecated Use App\Models\Document instead.
 */
class SuratKeluar extends Model
{
    use SoftDeletes, ManageDocumentFileName;
    
    protected $table = 'surat_keluar';
    protected static function booted()
    {
        static::creating(function ($surat) {
            $surat->kode_surat = 'SK-' . str_pad((static::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);
        });
        
        static::created(function ($surat) {
            $surat->renameUploadedFile();
        });
        
        static::updated(function ($surat) {
            if ($surat->isDirty('judul') || $surat->isDirty('file_path')) {
                $surat->renameUploadedFile();
            }
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
