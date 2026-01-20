<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Str;

class SystemHealthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Admin Exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@bromo.com'],
            [
                'name' => 'Admin Bromo',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Ensure Home Page Exists
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'status' => 'published',
                'content' => [
                    [
                        'type' => 'hero_video',
                        'data' => [
                            'video_source' => 'url',
                            'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-aerial-view-of-a-volcano-crater-3965-large.mp4',
                            'heading' => 'Experience the Mystical Fire',
                            'subheading' => 'Journey to the heart of East Java\'s most iconic volcanoes.',
                        ],
                    ],
                ],
            ]
        );

        // 3. Ensure Basic Settings Exist (if table exists)
        // We check for the Setting model usage by seeing if we can update or create
        if (class_exists(Setting::class)) {
            $settings = [
                'site_name' => 'Bromo Ijen Expedition',
                'site_description' => 'Premium Tours in East Java',
                'contact_email' => 'info@bromoijen.com',
                'contact_phone' => '+6281234567890',
            ];

            foreach ($settings as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }
    }
}
