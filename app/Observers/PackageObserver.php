<?php

namespace App\Observers;

use App\Jobs\OptimizeImageJob;
use App\Models\Package;

class PackageObserver
{
    public function saved(Package $package): void
    {
        if ($package->wasChanged('thumbnail') && !empty($package->thumbnail)) {
            if (!str_starts_with($package->thumbnail, 'http') && !str_contains($package->thumbnail, '_optimized.')) {
                OptimizeImageJob::dispatch(Package::class, $package->id, $package->thumbnail, 'public', 'in_place');
            }
        }

        if ($package->wasChanged('gallery') && is_array($package->gallery) && !empty($package->gallery)) {
            foreach ($package->gallery as $path) {
                if (!str_starts_with($path, 'http') && !str_contains($path, '_optimized.')) {
                    OptimizeImageJob::dispatch(Package::class, $package->id, $path, 'public', 'in_place');
                }
            }
        }
    }
}
