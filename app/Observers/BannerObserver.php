<?php

namespace App\Observers;

use App\Jobs\OptimizeImageJob;
use App\Models\Banner;

class BannerObserver
{
    public function saved(Banner $banner): void
    {
        if ($banner->wasChanged('image_path') && !empty($banner->image_path)) {
            if (!str_starts_with($banner->image_path, 'http') && !str_contains($banner->image_path, '_optimized.')) {
                OptimizeImageJob::dispatch(Banner::class, $banner->id, $banner->image_path, 'public', 'in_place', 'image_path');
            }
        }
    }
}
