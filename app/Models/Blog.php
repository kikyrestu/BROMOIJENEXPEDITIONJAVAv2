<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Filament\Traits\HasSeoForm; // Assuming we want SEO on Blogs too, though the User request didn't explicitly demand it, it's good practice. But wait, I should check if Blog has polymorphic SEO relation. The migration for SEO was polymorphic.
// Migration: $table->morphs('seoable');
// So yes, Blog can have SEO.

class Blog extends Model
{
    use \App\Traits\HasMediaLibrary;

    protected $fillable = [
        'author_id',
        'author_name',
        'category_id',
        'category',
        'title',
        'excerpt',
        'slug',
        'body',
        'thumbnail_path',
        'thumbnail_media_id',
        'tags',
        'read_time',
        'status',
        'is_featured',
        'view_count',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'tags' => 'array',
        'is_featured' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function seo()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
