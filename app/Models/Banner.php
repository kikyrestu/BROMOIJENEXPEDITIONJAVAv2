<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'heading',
        'subheading',
        'description',
        'cta_text',
        'cta_url',
        'type',
        'html_content',
        'is_active',
        'image_path',
        'bg_color',
        'overlay_color',
        'placements',
        'start_date',
        'end_date',
        'popup_enabled',
        'popup_delay_seconds',
        'popup_frequency',
        'popup_interval_days',
        'popup_pages',
        'popup_device',
        'popup_max_width',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'placements' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'popup_enabled' => 'boolean',
        'popup_pages' => 'array',
    ];
}
