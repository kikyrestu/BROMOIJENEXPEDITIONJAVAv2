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
        'auto_load',
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

    /**
     * Get effective children: manual children + auto-loaded items (WordPress style).
     * When auto_load = 'destination_packages', injects Destinations as virtual children,
     * each with their published Packages as sub-children.
     */
    public function getEffectiveChildrenAttribute()
    {
        $manualChildren = $this->children;

        if ($this->auto_load === 'destination_packages') {
            $destinations = Destination::has('packages')
                ->with(['packages' => function ($q) {
                    $q->where('status', 'published')->orderBy('name');
                }])
                ->orderBy('name')
                ->get();

            $autoChildren = collect();
            foreach ($destinations as $dest) {
                // Create a virtual NavigationMenu for the Destination
                $destMenu = new self();
                $destMenu->name = $dest->name;
                $destMenu->url = route('destinations.show', $dest->slug);
                $destMenu->target = '_self';
                $destMenu->is_active = true;
                $destMenu->auto_load = 'none';

                // Create virtual children for each Package
                $packageMenus = collect();
                foreach ($dest->packages as $pkg) {
                    $pkgMenu = new self();
                    $pkgMenu->name = $pkg->name;
                    $pkgMenu->url = route('packages.show', $pkg->slug);
                    $pkgMenu->target = '_self';
                    $pkgMenu->is_active = true;
                    $pkgMenu->auto_load = 'none';
                    $pkgMenu->setRelation('children', collect());
                    $packageMenus->push($pkgMenu);
                }

                $destMenu->setRelation('children', $packageMenus);
                $autoChildren->push($destMenu);
            }

            return $manualChildren->toBase()->merge($autoChildren);
        }

        return $manualChildren;
    }
}
