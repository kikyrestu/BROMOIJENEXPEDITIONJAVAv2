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
                'data' => [
                    'badge' => 'About BromoIjen',
                    'title' => 'Experience The <span class="text-brand-primary">New Adventure</span> With Us',
                    'description' => 'We organize premium trips to Mount Bromo, Ijen Crater, and other exotic destinations in East Java. Our goal is to provide safe, comfortable, and memorable experiences for every traveler.',
                    'main_image' => 'https://placehold.co/600x800?text=Adventure',
                    'secondary_image' => 'https://placehold.co/500x500?text=Joy',
                    'features' => [
                        [
                            'title' => 'Trusted Travel Guide',
                            'description' => 'Professional English speaking guides.',
                        ],
                        [
                            'title' => 'Instant Booking',
                            'description' => 'Easy and secure online booking.',
                        ]
                    ]
                ],
            ],
            // 3. Exclusive Destinations
            [
                'type' => 'exclusive_destinations',
                'data' => [],
            ],
            // 4. Gallery
            [
                'type' => 'gallery',
                'data' => [
                     'badge' => 'Our Memories',
                     'title' => 'Capture The <span class="text-brand-primary font-hand italic">Moments</span>',
                     'description' => 'Explore the beauty of East Java through our lens. From the sunrise of Bromo to the blue fire of Ijen.',
                     'images' => [
                        ['image' => 'https://placehold.co/800x800?text=Bromo+Sunrise', 'size' => 'large', 'caption' => 'Golden Sunrise'],
                        ['image' => 'https://placehold.co/400x400?text=Jeep+Ride', 'size' => 'small', 'caption' => 'Jeep Adventure'],
                        ['image' => 'https://placehold.co/400x400?text=Ijen+Crater', 'size' => 'small', 'caption' => 'Blue Fire'],
                        ['image' => 'https://placehold.co/400x800?text=Savana', 'size' => 'tall', 'caption' => 'Savana Hills'],
                        ['image' => 'https://placehold.co/400x400?text=People', 'size' => 'small', 'caption' => 'Happy Travelers'],
                        ['image' => 'https://placehold.co/800x400?text=Milky+Way', 'size' => 'wide', 'caption' => 'Milky Way']
                     ]
                ],
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
