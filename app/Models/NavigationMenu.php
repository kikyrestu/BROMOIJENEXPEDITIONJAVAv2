<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationMenu extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'url',
        'parent_id',
        'sort_order',
        'is_active',
        'target',
        'navigable_type',
        'navigable_id',
    ];

    public function navigable()
    {
        return $this->morphTo();
    }

    public function getLinkAttribute()
    {
        if ($this->navigable_type && $this->navigable) {
            switch ($this->navigable_type) {
                case 'App\Models\Package':
                    return route('packages.show', $this->navigable->slug);
                case 'App\Models\Blog':
                    return route('blogs.show', $this->navigable->slug);
                case 'App\Models\Destination':
                    // Assuming a route exists, or anchor on home
                    // Check if 'destinations.show' exists, otherwise use home anchor
                    if (\Illuminate\Support\Facades\Route::has('destinations.show')) {
                        return route('destinations.show', $this->navigable->slug);
                    }
                    return route('home') . '#destination-' . $this->navigable->slug;
                default:
                    return '#';
            }
        }
        return $this->url;
    }

    public function parent()
    {
        return $this->belongsTo(NavigationMenu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavigationMenu::class, 'parent_id')->orderBy('sort_order');
    }
}
