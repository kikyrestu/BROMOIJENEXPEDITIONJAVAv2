<?php

namespace App\Filament\Pages;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Destination;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Package;
use App\Services\ImageOptimizationService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MediaOptimization extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bolt';
    protected static \UnitEnum|string|null $navigationGroup = 'System';
    protected static ?string $title = 'Media Optimization';
    protected static ?string $navigationLabel = 'Image Optimizer';
    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.media-optimization';

    public array $scanResults = [];
    public array $contentIssues = [];
    public bool $hasScanned = false;
    public bool $isOptimizing = false;

    public function mount(): void
    {
        $this->scanAll();
    }

    public function scanAll(): void
    {
        $this->scanResults = [];
        $this->contentIssues = [];
        $disk = 'public';

        // 1. Media Library
        $mediaTotal = Media::where('type', 'image')->where('file_path', 'not like', 'http%')->count();
        $mediaOptimized = Media::where('type', 'image')->where('file_path', 'not like', 'http%')->whereNotNull('optimized_path')->count();
        $mediaUnoptimized = $mediaTotal - $mediaOptimized;
        $mediaTotalSize = 0;
        $mediaOptSize = 0;
        foreach (Media::where('type', 'image')->where('file_path', 'not like', 'http%')->get() as $m) {
            $mediaTotalSize += $m->size ?? 0;
            if ($m->optimized_path && Storage::disk($disk)->exists($m->optimized_path)) {
                $mediaOptSize += Storage::disk($disk)->size($m->optimized_path);
            }
        }

        $this->scanResults['media'] = [
            'label' => 'Media Library',
            'icon' => 'heroicon-o-photo',
            'total' => $mediaTotal,
            'optimized' => $mediaOptimized,
            'unoptimized' => $mediaUnoptimized,
            'total_size' => $mediaTotalSize,
            'optimized_size' => $mediaOptSize,
            'command' => 'optimizeMedia',
        ];

        // 2. Gallery
        $galleryTotal = Gallery::whereNotNull('image_path')->where('image_path', 'not like', 'http%')->count();
        $galleryOptimized = Gallery::whereNotNull('optimized_path')->count();
        $galleryUnoptimized = $galleryTotal - $galleryOptimized;
        $galleryTotalSize = 0;
        $galleryOptSize = 0;
        foreach (Gallery::whereNotNull('image_path')->where('image_path', 'not like', 'http%')->get() as $g) {
            if (Storage::disk($disk)->exists($g->image_path)) {
                $galleryTotalSize += Storage::disk($disk)->size($g->image_path);
            }
            if ($g->optimized_path && Storage::disk($disk)->exists($g->optimized_path)) {
                $galleryOptSize += Storage::disk($disk)->size($g->optimized_path);
            }
        }

        $this->scanResults['gallery'] = [
            'label' => 'Gallery Images',
            'icon' => 'heroicon-o-squares-2x2',
            'total' => $galleryTotal,
            'optimized' => $galleryOptimized,
            'unoptimized' => $galleryUnoptimized,
            'total_size' => $galleryTotalSize,
            'optimized_size' => $galleryOptSize,
            'command' => 'optimizeGallery',
        ];

        // 3. Package Images
        $packageTotal = 0;
        $packageOptimized = 0;
        $packageTotalSize = 0;
        foreach (Package::all() as $pkg) {
            if ($pkg->thumbnail && !str_starts_with($pkg->thumbnail, 'http')) {
                $packageTotal++;
                if (str_contains($pkg->thumbnail, '_optimized.')) $packageOptimized++;
                if (Storage::disk($disk)->exists($pkg->thumbnail)) {
                    $packageTotalSize += Storage::disk($disk)->size($pkg->thumbnail);
                }
            }
            if (is_array($pkg->gallery)) {
                foreach ($pkg->gallery as $path) {
                    if ($path && !str_starts_with($path, 'http')) {
                        $packageTotal++;
                        if (str_contains($path, '_optimized.')) $packageOptimized++;
                        if (Storage::disk($disk)->exists($path)) {
                            $packageTotalSize += Storage::disk($disk)->size($path);
                        }
                    }
                }
            }
        }

        $this->scanResults['packages'] = [
            'label' => 'Package Images',
            'icon' => 'heroicon-o-cube',
            'total' => $packageTotal,
            'optimized' => $packageOptimized,
            'unoptimized' => $packageTotal - $packageOptimized,
            'total_size' => $packageTotalSize,
            'optimized_size' => 0,
            'command' => 'optimizePackages',
        ];

        // 4. Blog Thumbnails
        $blogTotal = Blog::whereNotNull('thumbnail_path')->where('thumbnail_path', '!=', '')->where('thumbnail_path', 'not like', 'http%')->count();
        $blogOptimized = Blog::whereNotNull('thumbnail_path')->where('thumbnail_path', 'like', '%_optimized.%')->count();
        $blogTotalSize = 0;
        foreach (Blog::whereNotNull('thumbnail_path')->where('thumbnail_path', '!=', '')->where('thumbnail_path', 'not like', 'http%')->get() as $b) {
            if (Storage::disk($disk)->exists($b->thumbnail_path)) {
                $blogTotalSize += Storage::disk($disk)->size($b->thumbnail_path);
            }
        }

        $this->scanResults['blogs'] = [
            'label' => 'Blog Thumbnails',
            'icon' => 'heroicon-o-document-text',
            'total' => $blogTotal,
            'optimized' => $blogOptimized,
            'unoptimized' => $blogTotal - $blogOptimized,
            'total_size' => $blogTotalSize,
            'optimized_size' => 0,
            'command' => 'optimizeBlogs',
        ];

        // 5. Destination Thumbnails
        $destTotal = Destination::whereNotNull('thumbnail_path')->where('thumbnail_path', '!=', '')->where('thumbnail_path', 'not like', 'http%')->count();
        $destOptimized = Destination::whereNotNull('thumbnail_path')->where('thumbnail_path', 'like', '%_optimized.%')->count();
        $destTotalSize = 0;
        foreach (Destination::whereNotNull('thumbnail_path')->where('thumbnail_path', '!=', '')->where('thumbnail_path', 'not like', 'http%')->get() as $d) {
            if (Storage::disk($disk)->exists($d->thumbnail_path)) {
                $destTotalSize += Storage::disk($disk)->size($d->thumbnail_path);
            }
        }

        $this->scanResults['destinations'] = [
            'label' => 'Destination Thumbnails',
            'icon' => 'heroicon-o-map-pin',
            'total' => $destTotal,
            'optimized' => $destOptimized,
            'unoptimized' => $destTotal - $destOptimized,
            'total_size' => $destTotalSize,
            'optimized_size' => 0,
            'command' => 'optimizeDestinations',
        ];

        // 6. Banner Images
        $bannerTotal = Banner::whereNotNull('image_path')->where('image_path', '!=', '')->where('image_path', 'not like', 'http%')->count();
        $bannerOptimized = Banner::whereNotNull('image_path')->where('image_path', 'like', '%_optimized.%')->count();
        $bannerTotalSize = 0;
        foreach (Banner::whereNotNull('image_path')->where('image_path', '!=', '')->where('image_path', 'not like', 'http%')->get() as $ban) {
            if (Storage::disk($disk)->exists($ban->image_path)) {
                $bannerTotalSize += Storage::disk($disk)->size($ban->image_path);
            }
        }

        $this->scanResults['banners'] = [
            'label' => 'Banner Images',
            'icon' => 'heroicon-o-megaphone',
            'total' => $bannerTotal,
            'optimized' => $bannerOptimized,
            'unoptimized' => $bannerTotal - $bannerOptimized,
            'total_size' => $bannerTotalSize,
            'optimized_size' => 0,
            'command' => 'optimizeBanners',
        ];

        // 7. Hero Images (static files)
        $heroDir = public_path('images/heroes');
        $heroTotal = 0;
        $heroOptimized = 0;
        $heroTotalSize = 0;
        $heroOptSize = 0;
        if (File::isDirectory($heroDir)) {
            foreach (File::files($heroDir) as $file) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $heroTotal++;
                    $size = $file->getSize();
                    if (str_contains($file->getFilename(), '_optimized')) {
                        $heroOptimized++;
                        $heroOptSize += $size;
                    } else {
                        $heroTotalSize += $size;
                    }
                }
            }
        }

        $this->scanResults['heroes'] = [
            'label' => 'Hero Images',
            'icon' => 'heroicon-o-star',
            'total' => $heroTotal - $heroOptimized, // originals only
            'optimized' => $heroOptimized,
            'unoptimized' => max(0, ($heroTotal - $heroOptimized) - $heroOptimized), // originals without optimized version
            'total_size' => $heroTotalSize,
            'optimized_size' => $heroOptSize,
            'command' => 'optimizeHeroes',
        ];

        // Content / SEO Issues
        $this->scanContentIssues();

        $this->hasScanned = true;
    }

    protected function scanContentIssues(): void
    {
        $issues = [];

        // 1. Media without alt text
        $missingAlt = Media::where('type', 'image')
            ->where(function ($q) {
                $q->whereNull('alt_text')->orWhere('alt_text', '');
            })->count();
        if ($missingAlt > 0) {
            $issues[] = [
                'type' => 'warning',
                'category' => 'SEO',
                'message' => "{$missingAlt} media image(s) missing alt text",
                'detail' => 'Alt text improves accessibility and image SEO rankings.',
            ];
        }

        // 2. Gallery without alt text
        $galleryNoAlt = Gallery::where(function ($q) {
            $q->whereNull('alt_text')->orWhere('alt_text', '');
        })->count();
        if ($galleryNoAlt > 0) {
            $issues[] = [
                'type' => 'warning',
                'category' => 'SEO',
                'message' => "{$galleryNoAlt} gallery image(s) missing alt text",
                'detail' => 'Add descriptive alt text for better image search visibility.',
            ];
        }

        // 3. Blogs without SEO metadata
        $blogsNoSeo = Blog::where('status', 'published')
            ->whereDoesntHave('seo')
            ->count();
        if ($blogsNoSeo > 0) {
            $issues[] = [
                'type' => 'critical',
                'category' => 'SEO',
                'message' => "{$blogsNoSeo} published blog(s) missing SEO metadata",
                'detail' => 'Each blog needs meta title and description for search engine visibility.',
            ];
        }

        // 4. Packages without SEO metadata
        $packagesNoSeo = Package::where('status', 'published')
            ->whereDoesntHave('seo')
            ->count();
        if ($packagesNoSeo > 0) {
            $issues[] = [
                'type' => 'critical',
                'category' => 'SEO',
                'message' => "{$packagesNoSeo} published package(s) missing SEO metadata",
                'detail' => 'Add meta title and description for better search rankings.',
            ];
        }

        // 5. Large unoptimized images (> 500KB)
        $largeImages = Media::where('type', 'image')
            ->whereNull('optimized_path')
            ->where('size', '>', 512000)
            ->count();
        if ($largeImages > 0) {
            $issues[] = [
                'type' => 'critical',
                'category' => 'Performance',
                'message' => "{$largeImages} image(s) larger than 500KB without optimization",
                'detail' => 'Large images slow down page load and hurt Core Web Vitals scores.',
            ];
        }

        // 6. WebP support check
        $supportsWebp = function_exists('imagewebp');
        if (!$supportsWebp) {
            $issues[] = [
                'type' => 'warning',
                'category' => 'Performance',
                'message' => 'WebP format not supported by server',
                'detail' => 'Install libwebp-dev to enable WebP. Images are 25-35% smaller than JPEG.',
            ];
        }

        // 7. Missing files (broken references)
        $brokenMedia = 0;
        foreach (Media::where('type', 'image')->where('file_path', 'not like', 'http%')->get() as $m) {
            if (!Storage::disk('public')->exists($m->file_path)) {
                $brokenMedia++;
            }
        }
        if ($brokenMedia > 0) {
            $issues[] = [
                'type' => 'critical',
                'category' => 'Integrity',
                'message' => "{$brokenMedia} media record(s) with missing files",
                'detail' => 'Database references files that no longer exist on disk.',
            ];
        }

        $this->contentIssues = $issues;
    }

    public function optimizeMedia(): void
    {
        Artisan::call('media:optimize', ['--all' => true]);
        $output = Artisan::output();

        Notification::make()
            ->title('Media Optimization Complete')
            ->body($this->extractSummary($output))
            ->success()
            ->send();

        $this->scanAll();
    }

    public function optimizeGallery(): void
    {
        Artisan::call('gallery:optimize');
        $output = Artisan::output();

        Notification::make()
            ->title('Gallery Optimization Complete')
            ->body($this->extractSummary($output))
            ->success()
            ->send();

        $this->scanAll();
    }

    public function optimizePackages(): void
    {
        Artisan::call('packages:optimize-images');
        $output = Artisan::output();

        Notification::make()
            ->title('Package Images Optimization Complete')
            ->body($this->extractSummary($output))
            ->success()
            ->send();

        $this->scanAll();
    }

    public function optimizeBlogs(): void
    {
        $optimizer = new ImageOptimizationService();
        $disk = 'public';
        $count = 0;

        $blogs = Blog::whereNotNull('thumbnail_path')
            ->where('thumbnail_path', '!=', '')
            ->where('thumbnail_path', 'not like', 'http%')
            ->where('thumbnail_path', 'not like', '%_optimized.%')
            ->get();

        foreach ($blogs as $blog) {
            if (Storage::disk($disk)->exists($blog->thumbnail_path)) {
                $newPath = $optimizer->optimizeInPlace($blog->thumbnail_path, $disk);
                if ($newPath) {
                    $blog->thumbnail_path = $newPath;
                    $blog->saveQuietly();
                    $count++;
                }
            }
        }

        Notification::make()
            ->title('Blog Thumbnails Optimized')
            ->body("Optimized {$count} blog thumbnail(s).")
            ->success()
            ->send();

        $this->scanAll();
    }

    public function optimizeDestinations(): void
    {
        $optimizer = new ImageOptimizationService();
        $disk = 'public';
        $count = 0;

        $destinations = Destination::whereNotNull('thumbnail_path')
            ->where('thumbnail_path', '!=', '')
            ->where('thumbnail_path', 'not like', 'http%')
            ->where('thumbnail_path', 'not like', '%_optimized.%')
            ->get();

        foreach ($destinations as $dest) {
            if (Storage::disk($disk)->exists($dest->thumbnail_path)) {
                $newPath = $optimizer->optimizeInPlace($dest->thumbnail_path, $disk);
                if ($newPath) {
                    $dest->thumbnail_path = $newPath;
                    $dest->saveQuietly();
                    $count++;
                }
            }
        }

        Notification::make()
            ->title('Destination Thumbnails Optimized')
            ->body("Optimized {$count} destination thumbnail(s).")
            ->success()
            ->send();

        $this->scanAll();
    }

    public function optimizeBanners(): void
    {
        $optimizer = new ImageOptimizationService();
        $disk = 'public';
        $count = 0;

        $banners = Banner::whereNotNull('image_path')
            ->where('image_path', '!=', '')
            ->where('image_path', 'not like', 'http%')
            ->where('image_path', 'not like', '%_optimized.%')
            ->get();

        foreach ($banners as $banner) {
            if (Storage::disk($disk)->exists($banner->image_path)) {
                $newPath = $optimizer->optimizeInPlace($banner->image_path, $disk);
                if ($newPath) {
                    $banner->image_path = $newPath;
                    $banner->saveQuietly();
                    $count++;
                }
            }
        }

        Notification::make()
            ->title('Banner Images Optimized')
            ->body("Optimized {$count} banner image(s).")
            ->success()
            ->send();

        $this->scanAll();
    }

    public function optimizeHeroes(): void
    {
        Artisan::call('heroes:optimize');
        $output = Artisan::output();

        Notification::make()
            ->title('Hero Images Optimization Complete')
            ->body($this->extractSummary($output))
            ->success()
            ->send();

        $this->scanAll();
    }

    public function optimizeAll(): void
    {
        Artisan::call('media:optimize', ['--all' => true]);
        Artisan::call('gallery:optimize');
        Artisan::call('packages:optimize-images');
        Artisan::call('heroes:optimize');
        $this->optimizeBlogs();
        $this->optimizeDestinations();
        $this->optimizeBanners();

        Notification::make()
            ->title('🚀 Full Optimization Complete')
            ->body('All images across the entire site have been optimized.')
            ->success()
            ->duration(8000)
            ->send();

        $this->scanAll();
    }

    protected function extractSummary(string $output): string
    {
        $lines = explode("\n", trim($output));
        $last = end($lines);
        return str_replace(['✅ ', '═'], '', $last) ?: 'Done.';
    }

    public static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
