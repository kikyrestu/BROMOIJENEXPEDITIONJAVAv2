<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use \App\Traits\HasMediaLibrary;

    protected $fillable = [
        'title',
        'image_path',
        'image_media_id',
        'category',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
