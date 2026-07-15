<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\ManageDocumentFileName;

/**
 * @deprecated Use App\Models\Document instead.
 */
class Agenda extends Model
{
    use ManageDocumentFileName;
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

    protected static function booted()
    {
        static::created(function ($model) {
            // Gunakan dokumen_path untuk Agenda
            if ($model->dokumen_path && $model->judul) {
                $oldPath = $model->dokumen_path;
                $newFileName = self::generateFileName($model->judul, basename($oldPath));
                $newPath = str_replace(basename($oldPath), $newFileName, $oldPath);

                if ($oldPath !== $newPath) {
                    try {
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->move($oldPath, $newPath);
                            $model->updateQuietly(['dokumen_path' => $newPath]);
                        }
                    } catch (\Exception $e) {
                        \Log::warning("Failed to rename file from {$oldPath} to {$newPath}: " . $e->getMessage());
                    }
                }
            }
        });
        
        static::updated(function ($model) {
            if (($model->isDirty('judul') || $model->isDirty('dokumen_path')) && $model->dokumen_path && $model->judul) {
                $oldPath = $model->dokumen_path;
                $newFileName = self::generateFileName($model->judul, basename($oldPath));
                $newPath = str_replace(basename($oldPath), $newFileName, $oldPath);

                if ($oldPath !== $newPath) {
                    try {
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->move($oldPath, $newPath);
                            $model->updateQuietly(['dokumen_path' => $newPath]);
                        }
                    } catch (\Exception $e) {
                        \Log::warning("Failed to rename file from {$oldPath} to {$newPath}: " . $e->getMessage());
                    }
                }
            }
        });
    }

    public function workers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'agenda_user', 'agenda_id', 'user_id');
    }
}
