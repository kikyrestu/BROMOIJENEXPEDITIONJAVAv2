<?php

namespace App\Observers;

use App\Jobs\OptimizeImageJob;
use App\Models\Blog;

class BlogObserver
{
    public function saved(Blog $blog): void
    {
        if ($blog->wasChanged('thumbnail_path') && !empty($blog->thumbnail_path)) {
            if (!str_starts_with($blog->thumbnail_path, 'http') && !str_contains($blog->thumbnail_path, '_optimized.')) {
                OptimizeImageJob::dispatch(Blog::class, $blog->id, $blog->thumbnail_path, 'public', 'in_place', 'thumbnail_path');
            }
        }
    }
}
