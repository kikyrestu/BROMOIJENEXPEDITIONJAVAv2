<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'icon',
        'description',
        'sort_order',
        'show_in_navbar',
    ];

    protected $casts = [
        'show_in_navbar' => 'boolean',
    ];

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function scopePackageType($query)
    {
        return $query->where('type', 'package');
    }

    public function scopeNavbar($query)
    {
        return $query->where('show_in_navbar', true)->orderBy('sort_order');
    }

    public function publishedPackages(): HasMany
    {
        return $this->hasMany(Package::class)->where('status', 'published');
    }
}
