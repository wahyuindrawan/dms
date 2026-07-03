<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'document_types';

    protected $fillable = [
        'code',
        'name',
        'description',
        'prefix',
        'numbering_format',
        'seq_length',
        'has_detail',
        'detail_table',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'has_detail' => 'boolean',
        'is_active' => 'boolean',
        'seq_length' => 'integer',
        'sort_order' => 'integer',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'document_type_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(DocumentCategory::class, 'document_type_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
