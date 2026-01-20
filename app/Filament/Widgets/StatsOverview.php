<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total WhatsApp Inquiries', \App\Models\InquiryLog::count())
                ->description('All time clicks')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('success'),
            Stat::make('Inquiries Today', \App\Models\InquiryLog::whereDate('created_at', today())->count())
                ->description('Clicks recorded today')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),
        ];
    }
}
