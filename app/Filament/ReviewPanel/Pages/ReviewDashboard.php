<?php

namespace App\Filament\ReviewPanel\Pages;

use App\Filament\ReviewPanel\Widgets\RecentReviewsTable;
use App\Filament\ReviewPanel\Widgets\ReviewStatsOverview;
use App\Filament\ReviewPanel\Widgets\StarDistributionChart;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ReviewDashboard extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = -2;
    protected static ?string $title = 'Review Dashboard';
    protected string $view = 'filament.review-panel.pages.review-dashboard';

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return '/';
    }

    public function getVisibleWidgets(): array
    {
        return [
            ReviewStatsOverview::class,
            StarDistributionChart::class,
            RecentReviewsTable::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}
