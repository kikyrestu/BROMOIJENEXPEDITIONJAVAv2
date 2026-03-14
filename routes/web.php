<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;

// Public Routes
Route::get('/test-debug', function() { return 'Application is Working'; });
Route::get('/', HomeController::class)->name('home');

// Fix for "Method Not Allowed" on POST / (likely bots or analytics)
Route::post('/', function() {
    return redirect('/');
});

// UPLOAD FIX: Bypass signature validation using custom controller
Route::post('/livewire/upload-file', [\App\Http\Controllers\LivewireUploadController::class, 'handle'])
    ->name('livewire.upload-file')
    ->middleware(['web', 'throttle:60,1']);

Route::get('/packages', function () {
    $packages = \App\Models\Package::with(['destination', 'categoryRelation'])->where('status', 'published')->get();
    $categories = \App\Models\Category::packageType()->navbar()->withCount(['publishedPackages'])->get();
    return view('packages.index', compact('packages', 'categories'));
})->name('packages.index');

Route::get('/packages/category/{category:slug}', function (\App\Models\Category $category) {
    abort_if($category->type !== 'package', 404);
    $packages = $category->packages()->with('destination')->where('status', 'published')->get();
    $categories = \App\Models\Category::packageType()->navbar()->withCount(['publishedPackages'])->get();
    return view('packages.category', compact('packages', 'category', 'categories'));
})->name('packages.category');

Route::get('/packages/{package:slug}', function (\App\Models\Package $package) {
    $relatedPackages = \App\Models\Package::with(['destination', 'categoryRelation'])
        ->where('status', 'published')
        ->where('id', '!=', $package->id)
        ->where(function ($q) use ($package) {
            $q->where('category_id', $package->category_id)
              ->orWhere('destination_id', $package->destination_id);
        })
        ->inRandomOrder()
        ->take(4)
        ->get();
    return view('packages.show', compact('package', 'relatedPackages'));
})->name('packages.show');

// Blog Routes
Route::get('/blogs', [\App\Http\Controllers\BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blogs.show');

// Destination Routes
Route::get('/destinations', function () {
    $destinations = \App\Models\Destination::with('packages')->get();
    return view('destinations.index', compact('destinations'));
})->name('destinations.index');

Route::get('/destinations/{destination:slug}', function (\App\Models\Destination $destination) {
    $destination->load(['packages.destination']);
    
    // Try to find related blogs first, then fallback to latest
    $blogs = \App\Models\Blog::where('status', 'published')
        ->where(function($q) use ($destination) {
            $q->where('title', 'like', '%' . $destination->name . '%')
              ->orWhere('body', 'like', '%' . $destination->name . '%');
        })
        ->take(6)
        ->get();
    
    // Fallback to latest blogs if none match
    if ($blogs->isEmpty()) {
        $blogs = \App\Models\Blog::where('status', 'published')
            ->latest()
            ->take(6)
            ->get();
    }
    
    return view('destinations.show', compact('destination', 'blogs'));
})->name('destinations.show');

// Gallery Routes
Route::get('/gallery', function () {
    $images = \App\Models\Gallery::orderBy('sort_order')
        ->paginate(12);
    $categories = \App\Models\Gallery::select('category')
        ->distinct()
        ->whereNotNull('category')
        ->pluck('category');
    return view('gallery.index', compact('images', 'categories'));
})->name('gallery.index');

// Gallery API for infinite scroll
Route::get('/api/gallery', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\Gallery::orderBy('sort_order');
    
    if ($request->has('category') && $request->category !== 'all') {
        $query->where('category', $request->category);
    }
    
    $images = $query->paginate(12);
    
    return response()->json([
        'data' => $images->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->title,
                'url' => $item->display_url,
                'originalUrl' => $item->original_url,
                'alt' => $item->alt_text ?? $item->title,
                'category' => $item->category,
            ];
        }),
        'next_page' => $images->hasMorePages() ? $images->currentPage() + 1 : null,
        'has_more' => $images->hasMorePages(),
    ]);
})->name('api.gallery');

// Auth Redirect
Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

// Dynamic Sitemap
Route::get('/sitemap.xml', function () {
    $baseUrl = config('app.url', 'https://bromoijenexpeditionjava.com');
    
    $urls = collect();
    
    // Static pages
    $urls->push(['loc' => $baseUrl . '/', 'priority' => '1.0', 'changefreq' => 'daily']);
    $urls->push(['loc' => $baseUrl . '/packages', 'priority' => '0.9', 'changefreq' => 'weekly']);
    $urls->push(['loc' => $baseUrl . '/blogs', 'priority' => '0.8', 'changefreq' => 'weekly']);
    $urls->push(['loc' => $baseUrl . '/gallery', 'priority' => '0.7', 'changefreq' => 'monthly']);
    $urls->push(['loc' => $baseUrl . '/reviews', 'priority' => '0.8', 'changefreq' => 'weekly']);
    
    // Packages
    \App\Models\Package::where('status', 'published')->get()->each(function ($pkg) use ($urls, $baseUrl) {
        $urls->push([
            'loc' => $baseUrl . '/packages/' . $pkg->slug,
            'lastmod' => $pkg->updated_at?->toDateString(),
            'priority' => '0.8',
            'changefreq' => 'weekly',
        ]);
    });
    
    // Destinations
    \App\Models\Destination::all()->each(function ($dest) use ($urls, $baseUrl) {
        $urls->push([
            'loc' => $baseUrl . '/destinations/' . $dest->slug,
            'lastmod' => $dest->updated_at?->toDateString(),
            'priority' => '0.7',
            'changefreq' => 'monthly',
        ]);
    });
    
    // Blogs
    \App\Models\Blog::where('status', 'published')->get()->each(function ($blog) use ($urls, $baseUrl) {
        $urls->push([
            'loc' => $baseUrl . '/blogs/' . $blog->slug,
            'lastmod' => $blog->updated_at?->toDateString(),
            'priority' => '0.7',
            'changefreq' => 'monthly',
        ]);
    });
    
    // Category pages
    \App\Models\Category::where('type', 'package')->get()->each(function ($cat) use ($urls, $baseUrl) {
        $urls->push([
            'loc' => $baseUrl . '/packages/category/' . $cat->slug,
            'priority' => '0.6',
            'changefreq' => 'weekly',
        ]);
    });
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($urls as $url) {
        $xml .= '<url>';
        $xml .= '<loc>' . htmlspecialchars($url['loc'], ENT_XML1) . '</loc>';
        if (!empty($url['lastmod'])) $xml .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
        $xml .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
        $xml .= '<priority>' . $url['priority'] . '</priority>';
        $xml .= '</url>';
    }
    $xml .= '</urlset>';
    
    return response($xml, 200, ['Content-Type' => 'application/xml']);
});

// Admin Routes (Visual Editor & Media Bridge)
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // Visual Editor
    Route::get('/visual-editor/{page}', [\App\Http\Controllers\Admin\VisualEditorController::class, 'edit'])
        ->name('admin.visual-editor.edit');
    Route::post('/visual-editor/{page}', [\App\Http\Controllers\Admin\VisualEditorController::class, 'update'])
        ->name('admin.visual-editor.update');
    
    // Media Bridge API
    Route::get('/api/media-library', [\App\Http\Controllers\Admin\MediaLibraryController::class, 'index'])
        ->name('admin.api.media-library.index');
    Route::post('/api/media-store', [\App\Http\Controllers\Admin\MediaLibraryController::class, 'store'])
        ->name('admin.api.media-library.store');
});

// Client Review Routes (Token-based, single-use)
Route::get('/review/{token}', [\App\Http\Controllers\ClientReviewController::class, 'create'])->name('client.review.create');
Route::post('/review/{token}', [\App\Http\Controllers\ClientReviewController::class, 'store'])->name('client.review.store');
Route::get('/guest-review/success', [\App\Http\Controllers\ClientReviewController::class, 'success'])->name('client.review.success');

// Public Reviews Page
Route::get('/reviews', function () {
    $reviews = \App\Models\Testimonial::whereIn('status', ['approved', 'published'])
        ->orderByDesc('created_at')
        ->paginate(12);

    $stats = [
        'total' => \App\Models\Testimonial::whereIn('status', ['approved', 'published'])->count(),
        'average' => round(\App\Models\Testimonial::whereIn('status', ['approved', 'published'])->avg('rating'), 1),
        'distribution' => collect(range(5, 1))->mapWithKeys(function ($star) {
            return [$star => \App\Models\Testimonial::whereIn('status', ['approved', 'published'])->where('rating', $star)->count()];
        }),
    ];

    return view('reviews.index', compact('reviews', 'stats'));
})->name('reviews.index');
