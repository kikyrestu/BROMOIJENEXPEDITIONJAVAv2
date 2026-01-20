<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'role',
        'content',
        'rating',
        'photo_path',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];
}
