<?php

use App\Models\Page;

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$page = Page::where('slug', 'home')->first();

if (!$page) {
    echo "Page 'home' not found.\n";
    exit;
}

echo "Page Title: " . $page->title . "\n";
echo "Content Type: " . gettype($page->content) . "\n";

$content = $page->content;
if (is_string($content)) {
    $content = json_decode($content, true);
    echo "Content was string, decoded.\n";
}

$hero = collect($content)->firstWhere('type', 'hero_video');

if ($hero) {
    echo "Hero Block Found!\n";
    print_r($hero);
} else {
    echo "Hero Block NOT Found.\n";
    echo "Available Blocks:\n";
    foreach ($content as $block) {
        echo "- " . ($block['type'] ?? 'Unknown') . "\n";
    }
}
