<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Destination extends Model
{
    use \App\Traits\HasMediaLibrary;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail_path',
        'thumbnail_media_id',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function hotspots(): HasMany
    {
        return $this->hasMany(Hotspot::class);
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
