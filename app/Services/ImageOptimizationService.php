<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizationService
{
    protected ImageManager $manager;
    protected bool $supportsWebp;

    // Max dimensions for optimized images
    const MAX_WIDTH = 1920;
    const MAX_HEIGHT = 1080;
    const OPTIMIZED_QUALITY = 82;

    // Thumbnail dimensions  
    const THUMB_WIDTH = 400;
    const THUMB_HEIGHT = 400;
    const THUMB_QUALITY = 78;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
        $this->supportsWebp = function_exists('imagewebp');
    }

    /**
     * Optimize an uploaded image and generate thumbnail.
     * Returns array with optimized_path and thumbnail_path.
     */
    public function optimize(string $originalPath, string $disk = 'public'): array
    {
        // Only process local files (not external URLs)
        if (str_starts_with($originalPath, 'http')) {
            return ['optimized_path' => null, 'thumbnail_path' => null];
        }

        if (!Storage::disk($disk)->exists($originalPath)) {
            return ['optimized_path' => null, 'thumbnail_path' => null];
        }

        $fullPath = Storage::disk($disk)->path($originalPath);
        $mime = Storage::disk($disk)->mimeType($originalPath);

        // Only process images
        if (!str_starts_with($mime, 'image/')) {
            return ['optimized_path' => null, 'thumbnail_path' => null];
        }

        // Skip SVGs and GIFs
        if (in_array($mime, ['image/svg+xml', 'image/gif'])) {
            return ['optimized_path' => null, 'thumbnail_path' => null];
        }

        $directory = dirname($originalPath);
        $filename = pathinfo($originalPath, PATHINFO_FILENAME);
        $ext = $this->supportsWebp ? 'webp' : 'jpg';

        $optimizedPath = $directory . '/' . $filename . '_optimized.' . $ext;
        $thumbnailPath = $directory . '/' . $filename . '_thumb.' . $ext;

        try {
            // Generate optimized version
            $this->createOptimized($fullPath, $optimizedPath, $disk);

            // Generate thumbnail
            $this->createThumbnail($fullPath, $thumbnailPath, $disk);

            return [
                'optimized_path' => $optimizedPath,
                'thumbnail_path' => $thumbnailPath,
            ];
        } catch (\Exception $e) {
            \Log::error('Image optimization failed: ' . $e->getMessage(), [
                'path' => $originalPath,
                'error' => $e->getMessage(),
            ]);

            return ['optimized_path' => null, 'thumbnail_path' => null];
        }
    }

    /**
     * Create an optimized version (max 1920px wide, max 1080px tall).
     */
    protected function createOptimized(string $sourcePath, string $destPath, string $disk): void
    {
        $image = $this->manager->read(file_get_contents($sourcePath));

        // Scale down if exceeds max dimensions, maintain aspect ratio
        $width = $image->width();
        $height = $image->height();

        if ($width > self::MAX_WIDTH || $height > self::MAX_HEIGHT) {
            $image->scaleDown(width: self::MAX_WIDTH, height: self::MAX_HEIGHT);
        }

        $encoded = $this->encode($image, self::OPTIMIZED_QUALITY);
        Storage::disk($disk)->put($destPath, (string) $encoded);
    }

    /**
     * Create a thumbnail (400x400 cover crop).
     */
    protected function createThumbnail(string $sourcePath, string $destPath, string $disk): void
    {
        $image = $this->manager->read(file_get_contents($sourcePath));

        // Cover crop to square thumbnail
        $image->cover(self::THUMB_WIDTH, self::THUMB_HEIGHT);

        $encoded = $this->encode($image, self::THUMB_QUALITY);
        Storage::disk($disk)->put($destPath, (string) $encoded);
    }

    /**
     * Encode image to the best available format.
     */
    protected function encode($image, int $quality)
    {
        if ($this->supportsWebp) {
            return $image->toWebp(quality: $quality);
        }
        
        return $image->toJpeg(quality: $quality);
    }

    /**
     * Get the best available URL for display (optimized > original).
     */
    public static function getDisplayUrl(string $originalPath, ?string $optimizedPath, string $disk = 'public'): string
    {
        if ($optimizedPath && Storage::disk($disk)->exists($optimizedPath)) {
            return Storage::disk($disk)->url($optimizedPath);
        }

        if (str_starts_with($originalPath, 'http')) {
            return $originalPath;
        }

        return Storage::disk($disk)->url($originalPath);
    }

    /**
     * Get thumbnail URL (thumbnail > optimized > original).
     */
    public static function getThumbnailUrl(string $originalPath, ?string $thumbnailPath, ?string $optimizedPath = null, string $disk = 'public'): string
    {
        if ($thumbnailPath && Storage::disk($disk)->exists($thumbnailPath)) {
            return Storage::disk($disk)->url($thumbnailPath);
        }

        return self::getDisplayUrl($originalPath, $optimizedPath, $disk);
    }

    /**
     * Optimize an image IN-PLACE: convert to optimized JPEG, delete original, return new path.
     * Used for package images where we don't need to keep the original.
     */
    public function optimizeInPlace(string $originalPath, string $disk = 'public'): ?string
    {
        if (str_starts_with($originalPath, 'http')) {
            return null;
        }

        if (!Storage::disk($disk)->exists($originalPath)) {
            return null;
        }

        $fullPath = Storage::disk($disk)->path($originalPath);
        $mime = Storage::disk($disk)->mimeType($originalPath);

        if (!str_starts_with($mime, 'image/')) {
            return null;
        }

        if (in_array($mime, ['image/svg+xml', 'image/gif'])) {
            return null;
        }

        try {
            $originalSize = Storage::disk($disk)->size($originalPath);
            $image = $this->manager->read(file_get_contents($fullPath));

            // Scale down if too large
            if ($image->width() > self::MAX_WIDTH) {
                $image->scaleDown(width: self::MAX_WIDTH);
            } elseif ($image->height() > self::MAX_HEIGHT) {
                $image->scaleDown(height: self::MAX_HEIGHT);
            }

            // Encode as optimized JPEG
            $encoded = $image->toJpeg(quality: self::OPTIMIZED_QUALITY);
            $encodedString = (string) $encoded;

            // Only keep optimized if it's actually smaller
            if (strlen($encodedString) >= $originalSize) {
                // Original is already well-optimized, keep it
                // But still convert to .jpg extension if needed
                $ext = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));
                if ($ext !== 'jpg' && $ext !== 'jpeg') {
                    $directory = dirname($originalPath);
                    $filename = pathinfo($originalPath, PATHINFO_FILENAME);
                    $newPath = $directory . '/' . $filename . '.jpg';
                    // Re-encode at higher quality to preserve original look
                    $encoded = $image->toJpeg(quality: 95);
                    Storage::disk($disk)->put($newPath, (string) $encoded);
                    Storage::disk($disk)->delete($originalPath);
                    return $newPath;
                }
                return $originalPath; // Already a JPEG and well-optimized
            }

            // Build new path with .jpg extension
            $directory = dirname($originalPath);
            $filename = pathinfo($originalPath, PATHINFO_FILENAME);
            $newPath = $directory . '/' . $filename . '_optimized.jpg';

            // Save optimized version
            Storage::disk($disk)->put($newPath, $encodedString);

            // Delete original if different path
            if ($newPath !== $originalPath) {
                Storage::disk($disk)->delete($originalPath);
            }

            return $newPath;
        } catch (\Exception $e) {
            \Log::error('In-place optimization failed: ' . $e->getMessage(), [
                'path' => $originalPath,
            ]);
            return null;
        }
    }
}
