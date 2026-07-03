<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ManageDocumentFileName;

/**
 * @deprecated Use App\Models\Document instead.
 */
class DokumenLain extends Model
{
    use ManageDocumentFileName;
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
        
        static::created(function ($model) {
            $model->renameUploadedFile();
        });
        
        static::updated(function ($model) {
            if ($model->isDirty('judul') || $model->isDirty('file_path')) {
                $model->renameUploadedFile();
            }
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
