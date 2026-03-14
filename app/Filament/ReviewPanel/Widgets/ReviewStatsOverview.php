<?php

namespace App\Filament\ReviewPanel\Widgets;

use App\Models\ReviewToken;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReviewStatsOverview extends StatsOverviewWidget
{

    protected function getStats(): array
    {
        $totalReviews = Testimonial::count();
        $avgRating = Testimonial::avg('rating');
        $pendingReviews = Testimonial::where('status', 'pending')->count();
        $activeLinks = ReviewToken::active()->count();

        return [
            Stat::make('Total Reviews', $totalReviews)
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('primary'),
            Stat::make('Average Rating', $avgRating ? number_format($avgRating, 1) . ' ★' : 'N/A')
                ->icon('heroicon-o-star')
                ->color($avgRating >= 4 ? 'success' : ($avgRating >= 3 ? 'warning' : 'danger')),
            Stat::make('Pending Moderation', $pendingReviews)
                ->icon('heroicon-o-clock')
                ->color($pendingReviews > 0 ? 'warning' : 'success'),
            Stat::make('Active Links', $activeLinks)
                ->icon('heroicon-o-link')
                ->color('primary'),
        ];
    }
}
