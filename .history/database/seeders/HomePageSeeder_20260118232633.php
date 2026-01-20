<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content = [
            // 1. Hero Section (Gotur Style)
            [
                'type' => 'hero_video',
                'data' => [
                    'heading' => 'Experience the <span class="text-brand-accent">Mystical Fire</span>',
                    'subheading' => 'Journey to the heart of East Java\'s most iconic volcanoes.',
                    'video_source' => 'url',
                    'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-aerial-view-of-a-volcano-crater-3965-large.mp4',
                ],
            ],
            // 2. Exclusive Destinations
            [
                'type' => 'exclusive_destinations', // Verify this matches the logic in home.blade.php
                'data' => [], // Component fetches its own data
            ],
            // 3. Package Slider
            [
                 'type' => 'package_slider',
                 'data' => [], // Component fetches its own data
            ],
        ];

        // Soft delete existing home to ensure clean slate or update
        $page = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'content' => $content,
            ]
        );

        // SEO Metadata
        if(!$page->seo) {
             $page->seo()->create([
                'meta_title' => 'Bromo Ijen Expedition - Premium Tours',
                'meta_description' => 'Book your private Bromo and Ijen Crater tour. Experience the blue fire and sunrise with premium service.',
            ]);
        }
    }
}
