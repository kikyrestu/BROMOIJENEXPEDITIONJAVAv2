<?php

namespace App\Observers;

use App\Jobs\OptimizeImageJob;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

class GalleryObserver
{
    public function saved(Gallery $gallery): void
    {
        if ($gallery->wasChanged('image_path') && !empty($gallery->image_path)) {
            if (str_starts_with($gallery->image_path, 'http')) {
                return;
            }

            OptimizeImageJob::dispatch(Gallery::class, $gallery->id, $gallery->image_path, 'public');
        }
    }

    public function deleting(Gallery $gallery): void
    {
        $disk = 'public';

        // Clean up optimized files
        if ($gallery->optimized_path && Storage::disk($disk)->exists($gallery->optimized_path)) {
            Storage::disk($disk)->delete($gallery->optimized_path);
        }
        if ($gallery->thumbnail_path && Storage::disk($disk)->exists($gallery->thumbnail_path)) {
            Storage::disk($disk)->delete($gallery->thumbnail_path);
        }
        // Clean up original
        if ($gallery->image_path && !str_starts_with($gallery->image_path, 'http') && Storage::disk($disk)->exists($gallery->image_path)) {
            Storage::disk($disk)->delete($gallery->image_path);
        }
    }
}
