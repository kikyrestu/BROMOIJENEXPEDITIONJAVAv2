<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$page = \App\Models\Page::where('slug', 'home')->first();
$content = $page->content;

// Current order: hero_video, about_us, exclusive_destinations, gallery, package_slider, testimonials_marquee, blog_news
// Move gallery (index 3) to after testimonials (index 5)

$gallery = $content[3]; // gallery
unset($content[3]);
$content = array_values($content); // reindex

// Now: hero_video(0), about_us(1), exclusive_destinations(2), package_slider(3), testimonials_marquee(4), blog_news(5)
// Insert gallery at index 5 (after testimonials which is at 4)
array_splice($content, 5, 0, [$gallery]);

$page->content = $content;
$page->save();

echo "New order: " . json_encode(array_column($content, 'type'), JSON_PRETTY_PRINT) . "\n";
