<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkDetail extends Model
{
    use HasFactory;

    protected $table = 'sk_details';

    protected $fillable = [
        'document_id',
        'effective_date',
        'expiry_date',
        'decree_type',
        'decree_scope',
        'regarding',
        'consideration',
        'signer_name',
        'signer_position',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
