<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMetadata extends Model
{
    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'canonical_url',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
