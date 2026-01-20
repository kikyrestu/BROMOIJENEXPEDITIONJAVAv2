<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'destination_id',
        'name',
        'slug',
        'thumbnail',
        'gallery',
        'location',
        'map_embed_url',
        'category',
        'rating',
        'review_count',
        'price_start_from',
        'duration_days',
        'duration_nights',
        'departure_date',
        'return_date',
        'max_participants',
        'destinations_list',
        'short_description',
        'long_description',
        'highlights',
        'itinerary',
        'inclusions',
        'exclusions',
        'faqs',
        'is_exclusive',
        'status',
        'wa_template_message',
    ];

    protected $casts = [
        'gallery' => 'array',
        'highlights' => 'array',
        'faqs' => 'array',
        'destinations_list' => 'array',
        'itinerary' => 'array',
        'is_exclusive' => 'boolean',
        'price_start_from' => 'decimal:2',
        'rating' => 'decimal:2',
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    public function priceAdjustments(): HasMany
    {
        return $this->hasMany(PriceAdjustment::class);
    }

    public function inquiryLogs(): HasMany
    {
        return $this->hasMany(InquiryLog::class);
    }
}
