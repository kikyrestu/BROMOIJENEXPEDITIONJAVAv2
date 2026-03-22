<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    protected static function booted(): void
    {
        static::updating(function (Page $page) {
            if ($page->getOriginal('slug') === 'home' && $page->isDirty('slug')) {
                throw ValidationException::withMessages([
                    'slug' => 'The home page slug is protected and cannot be changed.',
                ]);
            }
        });

        static::deleting(function (Page $page) {
            if ($page->slug === 'home') {
                throw ValidationException::withMessages([
                    'page' => 'The home page is protected and cannot be deleted.',
                ]);
            }
        });
    }

    public function seo()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
