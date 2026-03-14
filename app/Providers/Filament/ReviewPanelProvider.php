<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ReviewPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('review')
            ->path('review-panel')
            ->login()
            ->brandName('Review Manager')
            ->colors([
                'primary' => Color::Emerald,
                'danger' => Color::Rose,
                'warning' => Color::Amber,
                'success' => Color::Green,
            ])
            ->darkMode(true, true)
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/ReviewPanel/Resources'), for: 'App\Filament\ReviewPanel\Resources')
            ->discoverPages(in: app_path('Filament/ReviewPanel/Pages'), for: 'App\Filament\ReviewPanel\Pages')
            ->discoverWidgets(in: app_path('Filament/ReviewPanel/Widgets'), for: 'App\Filament\ReviewPanel\Widgets')
            ->pages([
                \App\Filament\ReviewPanel\Pages\ReviewDashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
