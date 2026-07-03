<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Document extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'documents';

    protected $fillable = [
        'doc_number',
        'reference_number',
        'title',
        'subject',
        'document_type_id',
        'document_category_id',
        'direction',
        'source_type',
        'source_unit_id',
        'source_name',
        'destination_type',
        'destination_unit_id',
        'destination_name',
        'document_date',
        'received_date',
        'issued_date',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'document_date' => 'date',
        'received_date' => 'date',
        'issued_date'   => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'doc_number', 'status', 'document_type_id', 'document_category_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('document')
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => "Dokumen \"{$this->title}\" ditambahkan",
                'updated' => "Dokumen \"{$this->title}\" diubah",
                'deleted' => "Dokumen \"{$this->title}\" dihapus",
                default   => "Dokumen \"{$this->title}\" {$eventName}",
            });
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function sourceUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'source_unit_id');
    }

    public function destinationUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'destination_unit_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function skDetail(): HasOne
    {
        return $this->hasOne(SkDetail::class, 'document_id');
    }

    public function sopDetail(): HasOne
    {
        return $this->hasOne(SopDetail::class, 'document_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(DocumentFile::class, 'document_id');
    }

    // Scopes untuk pembeda query resource baru
    public function scopeIncoming($query)
    {
        return $query->where('direction', 'incoming');
    }

    public function scopeOutgoing($query)
    {
        return $query->where('direction', 'outgoing');
    }

    public function scopeInternal($query)
    {
        return $query->where('direction', 'internal');
    }

    public function scopeOfType($query, string $code)
    {
        return $query->whereHas('documentType', function ($q) use ($code) {
            $q->where('code', $code);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    protected static function booted()
    {
        static::creating(function ($document) {
            if (!$document->doc_number) {
                $document->doc_number = static::generateNumber($document);
            }
            if (auth()->check() && !$document->created_by) {
                $document->created_by = auth()->id();
            }
        });

        static::updating(function ($document) {
            if (auth()->check()) {
                $document->updated_by = auth()->id();
            }
        });
    }

    public static function generateNumber($document): string
    {
        $type = DocumentType::find($document->document_type_id);
        if (!$type) {
            return 'DOC-' . uniqid();
        }

        $date = $document->document_date ? \Carbon\Carbon::parse($document->document_date) : now();
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');

        $seq = static::where('document_type_id', $document->document_type_id)
            ->whereYear('document_date', $year)
            ->count() + 1;

        $paddedSeq = str_pad((string)$seq, $type->seq_length ?? 4, '0', STR_PAD_LEFT);

        $format = $type->numbering_format ?? '{prefix}{seq}/{mm}/{yyyy}';
        
        $replacements = [
            '{prefix}' => $type->prefix ?? 'DOC-',
            '{seq}' => $paddedSeq,
            '{dd}' => $day,
            '{mm}' => $month,
            '{yyyy}' => $year,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $format);
    }
}
