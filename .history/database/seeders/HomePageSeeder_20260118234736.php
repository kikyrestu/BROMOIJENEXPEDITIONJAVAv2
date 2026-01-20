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
            // 1. Hero Section
            [
                'type' => 'hero_video',
                'data' => [
                    'heading' => 'Experience the <span class="text-brand-accent">Mystical Fire</span>',
                    'subheading' => 'Journey to the heart of East Java\'s most iconic volcanoes.',
                    'video_source' => 'url',
                    'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-aerial-view-of-a-volcano-crater-3965-large.mp4',
                    'show_button' => true,
                    'button_text' => 'Start Adventure',
                    'button_url' => '#packages',
                ],
            ],
            // 2. About Us
            [
                'type' => 'about_us',
                'data' => [],
            ],
            // 3. Exclusive Destinations
            [
                'type' => 'exclusive_destinations',
                'data' => [],
            ],
            // 4. Gallery
            [
                'type' => 'gallery',
                'data' => [],
            ],
            // 5. Package Slider
            [
                'type' => 'package_slider',
                'data' => [],
            ],
            // 6. Testimonials Marquee
            [
                'type' => 'testimonials_marquee',
                'data' => [
                    'testimonials' => [
                        [
                            'name' => 'Sarah Jenner',
                            'role' => 'Melbourne, Australia',
                            'content' => 'The Midnight Bromo Tour was absolutely breathtaking! The jeep ride under the stars and the sunrise view were magical. Perfectly organized!',
                            'rating' => 5,
                        ],
                        [
                             'name' => 'Michael Chen',
                             'role' => 'Singapore',
                             'content' => 'Ijen Blue Fire was a challenging hike but totally worth it. The guide ensured our safety throughout the trek. A premium experience.',
                             'rating' => 5,
                        ]
                    ]
                ],
            ],
            // 7. Blog & News
            [
                'type' => 'blog_news',
                'data' => [
                    'auto_fetch' => true,
                ],
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
