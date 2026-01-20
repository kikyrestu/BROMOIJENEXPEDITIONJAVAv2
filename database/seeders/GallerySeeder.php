<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        // Sample gallery images from Unsplash (volcano/mountain/nature themed)
        // Using direct Unsplash URLs instead of downloading (SSL issues on Windows)
        $images = [
            ['name' => 'Bromo Sunrise', 'alt' => 'Golden sunrise over Mount Bromo crater', 'url' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?w=1200'],
            ['name' => 'Ijen Blue Fire', 'alt' => 'Blue flames at Kawah Ijen crater', 'url' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=1200'],
            ['name' => 'Bromo Sea of Sand', 'alt' => 'Jeep crossing the sea of sand at Bromo', 'url' => 'https://images.unsplash.com/photo-1577717903315-1691ae25ab3f?w=1200'],
            ['name' => 'Volcano Silhouette', 'alt' => 'Mountain silhouette at dawn', 'url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1200'],
            ['name' => 'Trekking Adventure', 'alt' => 'Hikers on volcanic trail', 'url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=1200'],
            ['name' => 'Milky Way Bromo', 'alt' => 'Milky way galaxy over Mount Bromo', 'url' => 'https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?w=1200'],
            ['name' => 'Savanna Hills', 'alt' => 'Green savanna hills near Bromo', 'url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200'],
            ['name' => 'Crater Lake', 'alt' => 'Turquoise crater lake at Ijen', 'url' => 'https://images.unsplash.com/photo-1433086966358-54859d0ed716?w=1200'],
            ['name' => 'Volcanic Smoke', 'alt' => 'Smoke rising from active volcano', 'url' => 'https://images.unsplash.com/photo-1518173946687-a4c036bc4b35?w=1200'],
            ['name' => 'Morning Mist', 'alt' => 'Morning mist in the valley', 'url' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=1200'],
            ['name' => 'Starry Night', 'alt' => 'Star trails over mountain peak', 'url' => 'https://images.unsplash.com/photo-1507400492013-162706c8c05e?w=1200'],
            ['name' => 'Jeep Safari', 'alt' => 'Off-road jeep adventure', 'url' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=1200'],
        ];

        foreach ($images as $imageData) {
            // Store the URL directly as file_path (external URL)
            Media::create([
                'name' => $imageData['name'],
                'file_path' => $imageData['url'], // Store as external URL
                'type' => 'image',
                'mime_type' => 'image/jpeg',
                'size' => 0,
                'alt_text' => $imageData['alt'],
            ]);
            
            $this->command->info("Added: {$imageData['name']}");
        }

        $this->command->info('Gallery seeding completed! Added ' . count($images) . ' images.');
    }
}
