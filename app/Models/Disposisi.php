<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposisi extends Model
{
    protected $table = 'disposisi';
    protected $fillable = [
        'surat_masuk_id', 
        'dari_worker_id', 
        'ke_worker_id',
        'catatan', 
        'status', 
        'dibaca_pada',
    ];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function dariWorker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'dari_worker_id');
    }

    public function keWorker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'ke_worker_id');
    }
}
