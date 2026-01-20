<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$page = \App\Models\Page::where('slug', 'home')->first();
$content = $page->content;

echo "Current order:\n";
foreach($content as $i => $b) {
    echo "  $i: " . $b['type'] . "\n";
}

// Find gallery index
$galleryIndex = null;
foreach($content as $i => $b) {
    if ($b['type'] === 'gallery') {
        $galleryIndex = $i;
        break;
    }
}

if ($galleryIndex !== null) {
    $gallery = $content[$galleryIndex];
    unset($content[$galleryIndex]);
    $content = array_values($content);
    $content[] = $gallery; // Add to end
    
    $page->content = $content;
    $page->save();
    
    echo "\nNew order:\n";
    foreach($content as $i => $b) {
        echo "  $i: " . $b['type'] . "\n";
    }
}
