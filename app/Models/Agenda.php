<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Agenda extends Model
{
    protected $table = 'agenda';
    protected $fillable = [
        'judul',
        'deskripsi',
        'waktu',
        'tempat',
        'dokumen_path',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    public function workers()
    {
        return $this->belongsToMany(Worker::class, 'agenda_worker', 'agenda_id', 'worker_id');
    }
}
