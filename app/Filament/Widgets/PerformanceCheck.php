<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PerformanceCheck extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 1. Database Latency Check
        $startTime = microtime(true);
        try {
            DB::select('SELECT 1');
            $dbLatency = round((microtime(true) - $startTime) * 1000, 2);
        } catch (\Exception $e) {
            $dbLatency = -1;
        }

        // 2. Optimization Check
        $isProd = app()->environment('production');
        $isVite = file_exists(public_path('build/manifest.json'));
        $isCached = app()->configurationIsCached();
        
        $healthScore = 0;
        if ($isProd) $healthScore++;
        if ($isVite) $healthScore++;
        if ($isCached) $healthScore++;
        
        $healthStatus = match ($healthScore) {
            3 => 'Excellent',
            2 => 'Good',
            default => 'Needs Optimization',
        };
        
        $healthColor = match ($healthScore) {
            3 => 'success',
            2 => 'warning',
            default => 'danger',
        };

        return [
            Stat::make('Server Latency', $dbLatency . 'ms')
                ->description('Database connection time')
                ->descriptionIcon('heroicon-m-server')
                ->color($dbLatency < 50 ? 'success' : ($dbLatency < 200 ? 'warning' : 'danger'))
                ->chart([$dbLatency, $dbLatency + rand(-5, 5), $dbLatency + rand(-5, 5), $dbLatency]),

            Stat::make('System Health', $healthStatus)
                ->description($isProd ? 'Production Mode • Optimized' : 'Development Mode')
                ->descriptionIcon($isProd ? 'heroicon-m-shield-check' : 'heroicon-m-Cpu-chip')
                ->color($healthColor),

            Stat::make('Server Info', 'PHP ' . PHP_VERSION)
                ->description('Laravel ' . app()->version() . ' • ' . (request()->server('SERVER_ADDR') ?? 'IP Hidden'))
                ->descriptionIcon('heroicon-m-computer-desktop')
                ->color('gray')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'onclick' => "window.open('https://pagespeed.web.dev/analysis?url=" . urlencode(config('app.url')) . "', '_blank')",
                    'title' => 'Click to check PageSpeed',
                ]),
        ];
    }
}
