<?php

namespace App\Console\Commands;

use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeHeroImages extends Command
{
    protected $signature = 'heroes:optimize {--dry-run : Show what would be optimized without making changes}';
    protected $description = 'Optimize static hero images in public/images/heroes/ directory';

    public function handle(): int
    {
        $heroDir = public_path('images/heroes');

        if (!File::isDirectory($heroDir)) {
            $this->error("Hero images directory not found: {$heroDir}");
            return 1;
        }

        $files = File::files($heroDir);
        $imageFiles = collect($files)->filter(function ($file) {
            return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp']);
        });

        if ($imageFiles->isEmpty()) {
            $this->info('No hero images found.');
            return 0;
        }

        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('🔍 DRY RUN — no changes will be made');
        }

        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $totalSaved = 0;

        foreach ($imageFiles as $file) {
            $originalSize = $file->getSize();
            $filename = $file->getFilename();
            $extension = strtolower($file->getExtension());

            $this->info("📸 {$filename} (" . $this->formatBytes($originalSize) . ")");

            if (str_contains($filename, '_optimized')) {
                $this->line("  ⏭️  Already optimized, skipping.");
                continue;
            }

            if ($dryRun) {
                $this->line("  Would optimize: {$filename}");
                continue;
            }

            try {
                $image = $manager->read($file->getPathname());

                // Scale down if larger than 1920px wide
                if ($image->width() > ImageOptimizationService::MAX_WIDTH) {
                    $image->scaleDown(width: ImageOptimizationService::MAX_WIDTH);
                } elseif ($image->height() > ImageOptimizationService::MAX_HEIGHT) {
                    $image->scaleDown(height: ImageOptimizationService::MAX_HEIGHT);
                }

                // Output as WebP if supported, otherwise JPEG
                $supportsWebp = function_exists('imagewebp');
                $encoded = $supportsWebp
                    ? $image->toWebp(quality: ImageOptimizationService::OPTIMIZED_QUALITY)
                    : $image->toJpeg(quality: ImageOptimizationService::OPTIMIZED_QUALITY);
                $ext = $supportsWebp ? 'webp' : 'jpg';
                $newFilename = pathinfo($filename, PATHINFO_FILENAME) . '_optimized.' . $ext;
                $newPath = $heroDir . '/' . $newFilename;

                file_put_contents($newPath, (string) $encoded);
                $newSize = filesize($newPath);
                $saved = $originalSize - $newSize;
                $pct = $originalSize > 0 ? round($saved / $originalSize * 100) : 0;
                $totalSaved += max(0, $saved);

                $this->line("  ✅ → {$newFilename} (" . $this->formatBytes($newSize) . ", saved {$pct}%)");
            } catch (\Exception $e) {
                $this->error("  ❌ Failed: " . $e->getMessage());
            }
        }

        $this->newLine();
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
