<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    public function seo()
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
