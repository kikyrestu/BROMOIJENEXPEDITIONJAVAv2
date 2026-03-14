<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeExistingImages extends Command
{
    protected $signature = 'media:optimize {--id= : Optimize specific media ID} {--all : Optimize all unoptimized images}';
    protected $description = 'Optimize existing uploaded images (generate WebP + thumbnails)';

    public function handle(): int
    {
        $optimizer = new ImageOptimizationService();
        $disk = config('filament.default_filesystem_disk', 'public');

        $query = Media::where('type', 'image')
            ->whereNull('optimized_path')
            ->where('file_path', 'not like', 'http%');

        if ($this->option('id')) {
            $query = Media::where('id', $this->option('id'))
                ->where('file_path', 'not like', 'http%');
        }

        $images = $query->get();
        
        if ($images->isEmpty()) {
            $this->info('No images to optimize.');
            return 0;
        }

        $this->info("Found {$images->count()} image(s) to optimize...");
        $bar = $this->output->createProgressBar($images->count());

        foreach ($images as $media) {
            $originalSize = $media->size;
            $this->line('');
            $this->info("Processing: {$media->name}");
            
            $result = $optimizer->optimize($media->file_path, $disk);

            if ($result['optimized_path']) {
                $media->optimized_path = $result['optimized_path'];
                $media->thumbnail_path = $result['thumbnail_path'];
                $media->saveQuietly(); // Skip observer to avoid re-optimization

                $optSize = Storage::disk($disk)->size($result['optimized_path']);
                $thumbSize = Storage::disk($disk)->size($result['thumbnail_path']);
                $savings = $originalSize > 0 ? round((1 - $optSize / $originalSize) * 100) : 0;

                $this->info("  ✅ Original: " . number_format($originalSize) . " bytes");
                $this->info("  ✅ Optimized: " . number_format($optSize) . " bytes (saved {$savings}%)");
                $this->info("  ✅ Thumbnail: " . number_format($thumbSize) . " bytes");
            } else {
                $this->warn("  ⚠️ Skipped (external URL or unsupported format)");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->line('');
        $this->info('✅ Done! All images optimized.');

        return 0;
    }
}
