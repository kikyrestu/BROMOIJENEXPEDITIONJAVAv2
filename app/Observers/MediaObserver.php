<?php

namespace App\Observers;

use App\Jobs\OptimizeImageJob;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaObserver
{
    public function saving(Media $media): void
    {
        if ($media->isDirty('file_path') && !empty($media->file_path)) {
            // Skip external URLs
            if (str_starts_with($media->file_path, 'http')) {
                return;
            }

            try {
                $disk = config('filament.default_filesystem_disk', 'public');

                if (Storage::disk($disk)->exists($media->file_path)) {
                    $media->size = Storage::disk($disk)->size($media->file_path);
                    $media->mime_type = Storage::disk($disk)->mimeType($media->file_path);
                }
            } catch (\Exception $e) {
                \Log::error('MediaObserver error: ' . $e->getMessage());
            }
        }
    }

    public function saved(Media $media): void
    {
        if ($media->wasChanged('file_path') && !empty($media->file_path)) {
            if (str_starts_with($media->file_path, 'http')) {
                return;
            }

            if ($media->mime_type && str_starts_with($media->mime_type, 'image/')) {
                $disk = config('filament.default_filesystem_disk', 'public');
                OptimizeImageJob::dispatch(Media::class, $media->id, $media->file_path, $disk);
            }
        }
    }

    public function deleting(Media $media): void
    {
        $disk = config('filament.default_filesystem_disk', 'public');

        // Delete optimized and thumbnail files when media is deleted
        if ($media->optimized_path && Storage::disk($disk)->exists($media->optimized_path)) {
            Storage::disk($disk)->delete($media->optimized_path);
        }
        if ($media->thumbnail_path && Storage::disk($disk)->exists($media->thumbnail_path)) {
            Storage::disk($disk)->delete($media->thumbnail_path);
        }
    }
}
