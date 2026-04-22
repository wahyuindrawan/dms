<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ManageDocumentFileName
{
    /**
     * Generate filename based on document title
     * Format: judul_slug-timestamp.ext
     */
    public static function generateFileName(string $judul, string $originalFileName): string
    {
        $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $slug = Str::slug($judul, '-');
        $timestamp = now()->format('YmdHis');
        
        return "{$slug}-{$timestamp}.{$extension}";
    }

    /**
     * Rename uploaded file to match document title
     * Called after model is saved
     */
    public function renameUploadedFile(string $disk = 'public'): void
    {
        if (!$this->file_path || !$this->judul) {
            return;
        }

        $oldPath = $this->file_path;
        $newFileName = self::generateFileName($this->judul, $this->file_original ?: $oldPath);
        $newPath = str_replace(basename($oldPath), $newFileName, $oldPath);

        // Jika path sudah mengikuti format yang diinginkan, skip
        if ($oldPath === $newPath) {
            return;
        }

        try {
            if (Storage::disk($disk)->exists($oldPath)) {
                Storage::disk($disk)->move($oldPath, $newPath);
                $this->file_path = $newPath;
                $this->file_original = $this->file_original ?: basename($oldPath);
                // Update without triggering events untuk menghindari infinite loop
                $this->updateQuietly(['file_path' => $newPath]);
            }
        } catch (\Exception $e) {
            \Log::warning("Failed to rename file from {$oldPath} to {$newPath}: " . $e->getMessage());
        }
    }
}
