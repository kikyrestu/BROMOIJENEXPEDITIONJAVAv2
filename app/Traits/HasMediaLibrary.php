<?php

namespace App\Traits;

use App\Models\Media;

trait HasMediaLibrary
{
    /**
     * Boot the trait
     */
    protected static function bootHasMediaLibrary()
    {
        // When saving, if media_id is set, copy the file_path from Media
        static::saving(function ($model) {
            // Handle thumbnail_path from thumbnail_media_id
            if (isset($model->thumbnail_media_id) && $model->thumbnail_media_id) {
                $media = Media::find($model->thumbnail_media_id);
                if ($media) {
                    $model->thumbnail_path = $media->file_path;
                }
            }

            // Handle image_path from image_media_id (for galleries)
            if (isset($model->image_media_id) && $model->image_media_id) {
                $media = Media::find($model->image_media_id);
                if ($media) {
                    $model->image_path = $media->file_path;
                }
            }
        });
    }

    /**
     * Get the thumbnail URL (works for both direct upload and library selection)
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_media_id) {
            $media = Media::find($this->thumbnail_media_id);
            return $media ? $media->url : null;
        }

        return $this->thumbnail_path 
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->thumbnail_path)
            : null;
    }

    /**
     * Relationship to thumbnail media
     */
    public function thumbnailMedia()
    {
        return $this->belongsTo(Media::class, 'thumbnail_media_id');
    }

    /**
     * Relationship to image media (for galleries)
     */
    public function imageMedia()
    {
        return $this->belongsTo(Media::class, 'image_media_id');
    }
}
