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
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'placements' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
