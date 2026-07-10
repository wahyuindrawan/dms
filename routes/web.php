<?php

use App\Models\DocumentFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/document-file/{file}', function (DocumentFile $file) {
    if (!$file->file_path || !Storage::disk('public')->exists($file->file_path)) {
        abort(404);
    }

    $path = Storage::disk('public')->path($file->file_path);

    return response()->file($path, [
        'Content-Disposition' => 'inline; filename="' . ($file->file_name ?: basename($file->file_path)) . '"',
    ]);
})->name('document-file.preview');
