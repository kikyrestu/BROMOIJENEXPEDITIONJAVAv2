<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'country',
        'role',
        'content',
        'rating',
        'avatar',
        'photo_path',
        'status',
        'review_token_id',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function scopePubliclyVisible($query)
    {
        return $query->whereIn('status', ['approved', 'published']);
    }

    public function getDisplayRoleAttribute(): string
    {
        return $this->country ?: ($this->role ?: 'Traveler');
    }

    public function getDisplayPhotoUrlAttribute(): string
    {
        $path = $this->photo_path ?: $this->avatar;

        if (! $path) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::url($path);
    }
}
