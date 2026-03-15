<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SeoDashboard extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';
    protected static string | \UnitEnum | null $navigationGroup = 'Content';
    protected static ?string $title = 'SEO Dashboard';
    
    protected string $view = 'filament.pages.seo-dashboard';

    public $stats = [];
    public $issues = [];

    public function mount()
    {
        $this->analyze();
    }

    public function analyze()
    {
        // 1. Sitemap Status
        $sitemapPath = public_path('sitemap.xml');
        $sitemapStatus = file_exists($sitemapPath) ? 'Active' : 'Missing';
        $sitemapAge = file_exists($sitemapPath) ? \Carbon\Carbon::createFromTimestamp(filemtime($sitemapPath))->diffForHumans() : 'N/A';

        // 2. Content Analysis
        $seoRecords = \App\Models\SeoMetadata::all();
        $totalSeo = $seoRecords->count();
        
        $missingTitle = $seoRecords->where('meta_title', '')->count(); // empty string
        $missingDesc = $seoRecords->where('meta_description', '')->count();
        
        // Duplicates
        $duplicateTitles = \App\Models\SeoMetadata::select('meta_title')
            ->whereNotNull('meta_title')
            ->where('meta_title', '!=', '')
            ->groupBy('meta_title')
            ->havingRaw('count(*) > 1')
            ->get()
            ->count();

        $duplicateDescs = \App\Models\SeoMetadata::select('meta_description')
            ->whereNotNull('meta_description')
            ->where('meta_description', '!=', '')
            ->groupBy('meta_description')
            ->havingRaw('count(*) > 1')
            ->get()
            ->count();

        // Calculate Score (Simple Algorithm)
        // Deduct points for issues
        $score = 100;
        if ($sitemapStatus === 'Missing') $score -= 20;
        $score -= ($duplicateTitles * 5); 
        $score -= ($duplicateDescs * 3);
        $score -= ($missingTitle * 10);
        $score -= ($missingDesc * 5);
        $score = max(0, $score);

        // $this->stats = [ ... ]; // Moved to Widget

        // Recommendations
        $this->issues = [];
        if ($sitemapStatus === 'Missing') $this->issues[] = ['type' => 'critical', 'message' => 'Sitemap.xml is missing. Generate it now.'];
        if ($duplicateTitles > 0) $this->issues[] = ['type' => 'warning', 'message' => "$duplicateTitles pages have duplicate Meta Titles."];
        if ($duplicateDescs > 0) $this->issues[] = ['type' => 'warning', 'message' => "$duplicateDescs pages have duplicate Meta Descriptions."];
        if ($missingTitle > 0) $this->issues[] = ['type' => 'critical', 'message' => "$missingTitle pages are missing Meta Titles."];
    }

    public function regenerateSitemap()
    {
        $urls = []; // Each entry: ['loc' => url, 'priority' => float, 'changefreq' => string]
        $baseUrl = rtrim(config('app.url'), '/');
        $today = date('Y-m-d');

        // Homepage
        $urls[] = ['loc' => $baseUrl . '/', 'priority' => '1.0', 'changefreq' => 'daily'];

        // Static index pages
        $urls[] = ['loc' => $baseUrl . '/packages', 'priority' => '0.9', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => $baseUrl . '/destinations', 'priority' => '0.9', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => $baseUrl . '/blogs', 'priority' => '0.8', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => $baseUrl . '/gallery', 'priority' => '0.7', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => $baseUrl . '/reviews', 'priority' => '0.6', 'changefreq' => 'monthly'];

        // CMS Pages (exclude homepage — already added above)
        $pages = \App\Models\Page::all();
        foreach ($pages as $page) {
            if ($page->slug === 'home') continue;
            $urls[] = ['loc' => $baseUrl . '/' . $page->slug, 'priority' => '0.7', 'changefreq' => 'monthly'];
        }

        // Destinations
        $destinations = \App\Models\Destination::all();
        foreach ($destinations as $dest) {
            $urls[] = ['loc' => $baseUrl . '/destinations/' . $dest->slug, 'priority' => '0.8', 'changefreq' => 'weekly'];
        }

        // Package categories
        $packageCategories = \App\Models\Category::where('type', 'package')->get();
        foreach ($packageCategories as $cat) {
            $urls[] = ['loc' => $baseUrl . '/packages/category/' . $cat->slug, 'priority' => '0.8', 'changefreq' => 'weekly'];
        }

        // Individual packages
        $packages = \App\Models\Package::where('status', 'published')->get();
        foreach ($packages as $pkg) {
            $urls[] = ['loc' => $baseUrl . '/packages/' . $pkg->slug, 'priority' => '0.8', 'changefreq' => 'weekly'];
        }

        // Published blogs
        $blogs = \App\Models\Blog::where('status', 'published')->get();
        foreach ($blogs as $blog) {
            $urls[] = ['loc' => $baseUrl . '/blogs/' . $blog->slug, 'priority' => '0.7', 'changefreq' => 'monthly'];
        }

        // Generate XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $entry) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($entry['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $today . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . $entry['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $entry['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        $xml .= '</urlset>' . "\n";

        file_put_contents(public_path('sitemap.xml'), $xml);

        \Filament\Notifications\Notification::make()
            ->title('Sitemap Generated — ' . count($urls) . ' URLs')
            ->success()
            ->send();

        $this->analyze(); // Refresh stats
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\SeoStatsOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('regenerate_sitemap')
                ->label('Regenerate Sitemap')
                ->icon('heroicon-o-arrow-path')
                ->action('regenerateSitemap')
                ->color('primary'),
        ];
    }
}
