<?php

namespace App\Console\Commands;

use App\Models\Package;
use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizePackageImages extends Command
{
    protected $signature = 'packages:optimize-images {--id= : Optimize specific package ID} {--dry-run : Show what would be optimized without making changes}';
    protected $description = 'Optimize all existing package images (thumbnail + gallery) to JPEG';

    public function handle(): int
    {
        $optimizer = new ImageOptimizationService();
        $disk = 'public';
        $dryRun = $this->option('dry-run');

        $query = Package::query();
        if ($this->option('id')) {
            $query->where('id', $this->option('id'));
        }

        $packages = $query->get();
        $totalSaved = 0;
        $totalProcessed = 0;
        $totalOriginalSize = 0;

        $this->info("Found {$packages->count()} package(s) to process...");
        if ($dryRun) {
            $this->warn('🔍 DRY RUN — no changes will be made');
        }
        $this->newLine();

        foreach ($packages as $package) {
            $this->info("📦 #{$package->id} {$package->name}");
            $changed = false;

            // Process thumbnail
            if ($package->thumbnail && !str_starts_with($package->thumbnail, 'http')) {
                $result = $this->processImage($optimizer, $package->thumbnail, $disk, $dryRun);
                if ($result) {
                    $totalProcessed++;
                    $totalOriginalSize += $result['original_size'];
                    $totalSaved += $result['saved'];
                    if (!$dryRun && $result['new_path'] !== $package->thumbnail) {
                        $package->thumbnail = $result['new_path'];
                        $changed = true;
                    }
                }
            }

            // Process gallery images
            if (is_array($package->gallery) && !empty($package->gallery)) {
                $newGallery = [];
                foreach ($package->gallery as $galleryPath) {
                    if (!$galleryPath || str_starts_with($galleryPath, 'http')) {
                        $newGallery[] = $galleryPath;
                        continue;
                    }

                    $result = $this->processImage($optimizer, $galleryPath, $disk, $dryRun);
                    if ($result) {
                        $totalProcessed++;
                        $totalOriginalSize += $result['original_size'];
                        $totalSaved += $result['saved'];
                        $newGallery[] = $dryRun ? $galleryPath : $result['new_path'];
                        if (!$dryRun && $result['new_path'] !== $galleryPath) {
                            $changed = true;
                        }
                    } else {
                        $newGallery[] = $galleryPath;
                    }
                }

                if (!$dryRun && $changed) {
                    $package->gallery = $newGallery;
                }
            }

            if ($changed && !$dryRun) {
                $package->saveQuietly(); // Skip observer to avoid re-optimization
            }
            
            $this->newLine();
        }

        $this->newLine();
        $this->info('═══════════════════════════════════');
        $this->info("✅ Processed: {$totalProcessed} images");
        $this->info("📁 Original total: " . $this->formatBytes($totalOriginalSize));
        $this->info("💾 Total saved: " . $this->formatBytes($totalSaved));
        if ($totalOriginalSize > 0) {
            $pct = round($totalSaved / $totalOriginalSize * 100);
            $this->info("📉 Savings: {$pct}%");
        }
        $this->info('═══════════════════════════════════');

        return 0;
    }

    protected function processImage(ImageOptimizationService $optimizer, string $path, string $disk, bool $dryRun): ?array
    {
        if (!Storage::disk($disk)->exists($path)) {
            $this->warn("  ⚠️  Not found: {$path}");
            return null;
        }

        // Skip already optimized
        if (str_contains($path, '_optimized.')) {
            $this->line("  ⏭️  Already optimized: " . basename($path));
            return null;
        }

        $originalSize = Storage::disk($disk)->size($path);
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($dryRun) {
            $this->line("  📸 Would optimize: " . basename($path) . " (" . $this->formatBytes($originalSize) . ", .{$ext})");
            return ['original_size' => $originalSize, 'saved' => (int)($originalSize * 0.5), 'new_path' => $path];
        }

        $newPath = $optimizer->optimizeInPlace($path, $disk);
        if (!$newPath) {
            $this->warn("  ⚠️  Failed: " . basename($path));
            return null;
        }

        $newSize = Storage::disk($disk)->size($newPath);
        $saved = $originalSize - $newSize;
        $pct = $originalSize > 0 ? round($saved / $originalSize * 100) : 0;

        $this->line("  ✅ " . basename($path) . " → " . basename($newPath));
        $this->line("     " . $this->formatBytes($originalSize) . " → " . $this->formatBytes($newSize) . " (saved {$pct}%)");

        return ['original_size' => $originalSize, 'saved' => $saved, 'new_path' => $newPath];
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
