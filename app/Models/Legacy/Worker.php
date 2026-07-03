<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @deprecated Use App\Models\Unit instead.
 */
class Worker extends Model
{
    protected $fillable = [
        'kode_worker',
        'nama',
        'jabatan',
        'email',
        'telepon',
        'keterangan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
