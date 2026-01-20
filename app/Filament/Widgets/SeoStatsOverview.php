<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\SeoMetadata;
use Illuminate\Support\Facades\File;

class SeoStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Sitemap Status
        $sitemapPath = public_path('sitemap.xml');
        $exists = File::exists($sitemapPath);
        $sitemapStatus = $exists ? 'Active' : 'Missing';
        $sitemapAge = $exists ? \Carbon\Carbon::createFromTimestamp(filemtime($sitemapPath))->diffForHumans() : 'N/A';
        $sitemapColor = $exists ? 'success' : 'danger';
        $sitemapIcon = $exists ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle';

        // 2. Duplicates
        $duplicateTitles = SeoMetadata::select('meta_title')
            ->whereNotNull('meta_title')
            ->where('meta_title', '!=', '')
            ->groupBy('meta_title')
            ->havingRaw('count(*) > 1')
            ->get()
            ->count();

        $duplicateDescs = SeoMetadata::select('meta_description')
            ->whereNotNull('meta_description')
            ->where('meta_description', '!=', '')
            ->groupBy('meta_description')
            ->havingRaw('count(*) > 1')
            ->get()
            ->count();

        // 3. Score Calculation
        $seoRecords = SeoMetadata::all();
        $missingTitle = $seoRecords->where('meta_title', '')->count();
        $missingDesc = $seoRecords->where('meta_description', '')->count();

        $score = 100;
        if (!$exists) $score -= 20;
        $score -= ($duplicateTitles * 5); 
        $score -= ($duplicateDescs * 3);
        $score -= ($missingTitle * 10);
        $score -= ($missingDesc * 5);
        $score = max(0, $score);

        $scoreColor = 'success';
        if ($score < 80) $scoreColor = 'warning';
        if ($score < 50) $scoreColor = 'danger';

        return [
            Stat::make('Overall SEO Score', $score . '/100')
                ->description('Based on internal audit')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($scoreColor),

            Stat::make('Sitemap Status', $sitemapStatus)
                ->description($exists ? "Updated $sitemapAge" : 'Action Required')
                ->descriptionIcon($sitemapIcon)
                ->color($sitemapColor),

            Stat::make('Duplicate Titles', $duplicateTitles)
                ->description('Pages with same title')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color($duplicateTitles > 0 ? 'danger' : 'success'),

            Stat::make('Duplicate Descriptions', $duplicateDescs)
                ->description('Pages with same description')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color($duplicateDescs > 0 ? 'warning' : 'success'),
        ];
    }
}
