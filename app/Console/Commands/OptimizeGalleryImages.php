<?php

namespace App\Console\Commands;

use App\Models\Gallery;
use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeGalleryImages extends Command
{
    protected $signature = 'gallery:optimize {--id= : Optimize specific gallery ID} {--all : Optimize all unoptimized gallery images}';
    protected $description = 'Optimize existing gallery images (generate WebP + thumbnails)';

    public function handle(): int
    {
        $optimizer = new ImageOptimizationService();
        $disk = 'public';

        $query = Gallery::whereNull('optimized_path')
            ->where('image_path', 'not like', 'http%')
            ->whereNotNull('image_path');

        if ($this->option('id')) {
            $query = Gallery::where('id', $this->option('id'))
                ->where('image_path', 'not like', 'http%');
        }

        $images = $query->get();

        if ($images->isEmpty()) {
            $this->info('No gallery images to optimize.');
            return 0;
        }

        $this->info("Found {$images->count()} gallery image(s) to optimize...");
        $bar = $this->output->createProgressBar($images->count());
        $totalSaved = 0;

        foreach ($images as $gallery) {
            $this->line('');
            $this->info("Processing: {$gallery->title} (#{$gallery->id})");

            if (!Storage::disk($disk)->exists($gallery->image_path)) {
                $this->warn("  ⚠️  File not found: {$gallery->image_path}");
                $bar->advance();
                continue;
            }

            $originalSize = Storage::disk($disk)->size($gallery->image_path);
            $result = $optimizer->optimize($gallery->image_path, $disk);

            if ($result['optimized_path']) {
                $gallery->optimized_path = $result['optimized_path'];
                $gallery->thumbnail_path = $result['thumbnail_path'];
                $gallery->saveQuietly();

                $optSize = Storage::disk($disk)->size($result['optimized_path']);
                $savings = $originalSize > 0 ? round((1 - $optSize / $originalSize) * 100) : 0;
                $totalSaved += ($originalSize - $optSize);

                $this->info("  ✅ Original: " . $this->formatBytes($originalSize));
                $this->info("  ✅ Optimized: " . $this->formatBytes($optSize) . " (saved {$savings}%)");
            } else {
                $this->warn("  ⚠️ Skipped (unsupported format)");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->line('');
        $this->info("✅ Done! Total saved: " . $this->formatBytes($totalSaved));

        return 0;
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
