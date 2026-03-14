<?php

namespace App\Observers;

use App\Jobs\OptimizeImageJob;
use App\Models\Destination;

class DestinationObserver
{
    public function saved(Destination $destination): void
    {
        if ($destination->wasChanged('thumbnail_path') && !empty($destination->thumbnail_path)) {
            if (!str_starts_with($destination->thumbnail_path, 'http') && !str_contains($destination->thumbnail_path, '_optimized.')) {
                OptimizeImageJob::dispatch(Destination::class, $destination->id, $destination->thumbnail_path, 'public', 'in_place', 'thumbnail_path');
            }
        }
    }
}
