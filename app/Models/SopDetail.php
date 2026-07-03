<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SopDetail extends Model
{
    use HasFactory;

    protected $table = 'sop_details';

    protected $fillable = [
        'document_id',
        'version',
        'revision_number',
        'effective_date',
        'review_date',
        'process_owner_unit_id',
        'scope',
        'purpose',
        'reference',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'review_date' => 'date',
        'revision_number' => 'integer',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function processOwner(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'process_owner_unit_id');
    }
}
