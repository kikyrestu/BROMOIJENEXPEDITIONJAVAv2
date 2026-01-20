<?php

namespace App\Observers;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaObserver
{
    public function saving(Media $media): void
    {
        if ($media->isDirty('file_path') && !empty($media->file_path)) {
            try {
                // Determine disk (defaulting to public/media or local/media based on setup)
                // Filament uses default filesystem disk usually.
                $disk = config('filament.default_filesystem_disk', 'public');
                
                if (Storage::disk($disk)->exists($media->file_path)) {
                    $media->size = Storage::disk($disk)->size($media->file_path);
                    $media->mime_type = Storage::disk($disk)->mimeType($media->file_path);
                }
            } catch (\Exception $e) {
                // Log error or ignore
            }
        }
    }
}
