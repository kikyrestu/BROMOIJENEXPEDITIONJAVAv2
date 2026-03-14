<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use \App\Traits\HasMediaLibrary;

    protected $fillable = [
        'title',
        'alt_text',
        'image_path',
        'optimized_path',
        'thumbnail_path',
        'image_media_id',
        'category',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the display URL (optimized > original).
     */
    public function getDisplayUrlAttribute(): string
    {
        if ($this->optimized_path && \Storage::disk('public')->exists($this->optimized_path)) {
            return \Storage::disk('public')->url($this->optimized_path);
        }

        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        return \Storage::disk('public')->url($this->image_path);
    }

    /**
     * Get the thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail_path && \Storage::disk('public')->exists($this->thumbnail_path)) {
            return \Storage::disk('public')->url($this->thumbnail_path);
        }

        return $this->display_url;
    }

    /**
     * Get the original full-size URL (for lightbox).
     */
    public function getOriginalUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        return \Storage::disk('public')->url($this->image_path);
    }
}
