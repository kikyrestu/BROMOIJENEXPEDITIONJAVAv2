<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;

// Public Routes
Route::get('/test-debug', function() { return 'Application is Working'; });
Route::get('/', HomeController::class)->name('home');
// Route::get('/', function() {
//    return 'ROOT ROUTE ALIVE. If you see this, web.php is fine, and HomeController is the problem.';
// })->name('home');

Route::get('/packages', function () {
    $packages = \App\Models\Package::with('destination')->where('status', 'published')->get();
    return view('packages.index', compact('packages'));
})->name('packages.index');

Route::get('/packages/{package:slug}', function (\App\Models\Package $package) {
    return view('packages.show', compact('package'));
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
    $destination->load('packages');
    $blogs = \App\Models\Blog::where('status', 'published')
        ->where(function($q) use ($destination) {
            $q->where('title', 'like', '%' . $destination->name . '%')
              ->orWhere('body', 'like', '%' . $destination->name . '%');
        })
        ->take(6)
        ->get();
    return view('destinations.show', compact('destination', 'blogs'));
})->name('destinations.show');

// Auth Redirect
Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

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
