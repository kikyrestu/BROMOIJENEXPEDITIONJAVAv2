<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production to prevent mixed content errors
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        \App\Models\Media::observe(\App\Observers\MediaObserver::class);
        \App\Models\Gallery::observe(\App\Observers\GalleryObserver::class);
        \App\Models\Package::observe(\App\Observers\PackageObserver::class);
        \App\Models\Blog::observe(\App\Observers\BlogObserver::class);
        \App\Models\Destination::observe(\App\Observers\DestinationObserver::class);
        \App\Models\Banner::observe(\App\Observers\BannerObserver::class);
    }
}
