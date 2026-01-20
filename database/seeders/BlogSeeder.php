<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::first();
        
        $blogs = [
            [
                'title' => '10 Tips for Hiking Mount Bromo at Sunrise',
                'slug' => '10-tips-for-hiking-mount-bromo-at-sunrise',
                'excerpt' => 'Planning to catch the legendary Bromo sunrise? Here are essential tips to make your journey unforgettable.',
                'body' => '<p>Mount Bromo is one of Indonesia\'s most iconic volcanoes, drawing thousands of visitors each year to witness its breathtaking sunrise. Here are 10 essential tips to ensure you have the best experience possible.</p><h2>1. Start Early</h2><p>The journey to the viewpoint starts around 3 AM. Yes, it\'s early, but trust us - it\'s worth it!</p><h2>2. Dress Warmly</h2><p>Temperatures can drop to near freezing at the summit. Layer up with warm clothing, including a jacket, gloves, and a beanie.</p><h2>3. Book Your Jeep in Advance</h2><p>4WD jeeps are the most popular way to reach the viewpoint. Book ahead during peak season to avoid disappointment.</p>',
                'category' => 'Travel Guide',
                'tags' => ['bromo', 'hiking', 'sunrise', 'tips'],
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subDays(5),
                'read_time' => '5 min read',
            ],
            [
                'title' => 'Ijen Blue Fire: A Complete Guide',
                'slug' => 'ijen-blue-fire-complete-guide',
                'excerpt' => 'Everything you need to know about witnessing the mystical blue flames at Kawah Ijen.',
                'body' => '<p>Kawah Ijen is famous for its ethereal blue flames - a rare natural phenomenon that occurs when sulfuric gases ignite upon contact with air. This comprehensive guide will help you plan your visit.</p><h2>What is Blue Fire?</h2><p>The blue fire is caused by the combustion of sulfuric gases that emerge from cracks in the volcano at extremely high temperatures (up to 600°C).</p><h2>Best Time to Visit</h2><p>The blue flames are only visible in complete darkness, making the pre-dawn hours (2-4 AM) the ideal time to visit.</p>',
                'category' => 'Adventure',
                'tags' => ['ijen', 'blue fire', 'volcano', 'photography'],
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subDays(10),
                'read_time' => '7 min read',
            ],
            [
                'title' => 'Best Photography Spots in East Java',
                'slug' => 'best-photography-spots-east-java',
                'excerpt' => 'Capture stunning shots at these incredible locations across East Java.',
                'body' => '<p>East Java is a photographer\'s paradise, offering diverse landscapes from volcanic peaks to pristine beaches. Here are the must-visit spots for your camera.</p><h2>1. Penanjakan Viewpoint</h2><p>The classic Bromo sunrise shot - volcanic craters framed by the golden hour light.</p><h2>2. Madakaripura Waterfall</h2><p>A dramatic 200-meter waterfall surrounded by towering cliffs.</p><h2>3. Tumpak Sewu</h2><p>Often called the "Niagara of Java", this semicircular waterfall is absolutely breathtaking.</p>',
                'category' => 'Photography',
                'tags' => ['photography', 'east java', 'landscapes'],
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(15),
                'read_time' => '6 min read',
            ],
            [
                'title' => 'What to Pack for Your Bromo-Ijen Trip',
                'slug' => 'what-to-pack-bromo-ijen-trip',
                'excerpt' => 'A complete packing list to ensure you\'re prepared for your volcanic adventure.',
                'body' => '<p>Packing for a trip to Bromo and Ijen requires careful consideration of the unique conditions you\'ll face. Here\'s our comprehensive packing list.</p><h2>Essential Clothing</h2><ul><li>Warm jacket (temperatures can drop to 5°C)</li><li>Comfortable hiking boots</li><li>Thermal underwear</li><li>Rain jacket</li></ul><h2>Camera Gear</h2><ul><li>DSLR or mirrorless camera</li><li>Wide-angle lens (14-24mm)</li><li>Tripod for long exposures</li><li>Extra batteries (cold drains them fast!)</li></ul>',
                'category' => 'Tips',
                'tags' => ['packing', 'travel tips', 'preparation'],
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(20),
                'read_time' => '4 min read',
            ],
            [
                'title' => 'Solo Travel to Bromo: Safety Tips',
                'slug' => 'solo-travel-bromo-safety-tips',
                'excerpt' => 'Traveling solo? Here\'s how to stay safe while exploring Mount Bromo.',
                'body' => '<p>Solo travel can be an incredibly rewarding experience. Mount Bromo is generally safe for solo travelers, but here are some tips to ensure your trip goes smoothly.</p><h2>Join a Tour Group</h2><p>Even as a solo traveler, joining a group tour can enhance safety and provide opportunities to meet other travelers.</p><h2>Stay in Touch</h2><p>Keep friends or family updated on your itinerary and check in regularly.</p>',
                'category' => 'Solo Travel',
                'tags' => ['solo travel', 'safety', 'tips'],
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(25),
                'read_time' => '5 min read',
            ],
            [
                'title' => 'The Culture and Traditions of the Tenggerese People',
                'slug' => 'culture-traditions-tenggerese-people',
                'excerpt' => 'Learn about the fascinating culture of the indigenous people living around Mount Bromo.',
                'body' => '<p>The Tenggerese are the indigenous people who have lived in the shadow of Mount Bromo for centuries. Their culture and traditions are deeply intertwined with the volcano they call sacred.</p><h2>Kasada Festival</h2><p>Once a year, the Tenggerese hold the Kasada ceremony, throwing offerings into the Bromo crater to honor their ancestors and seek blessings.</p><h2>Hindu-Buddhist Beliefs</h2><p>Unlike most of Java, the Tenggerese practice a unique blend of Hinduism and Buddhism.</p>',
                'category' => 'Culture',
                'tags' => ['culture', 'tenggerese', 'traditions'],
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(30),
                'read_time' => '8 min read',
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create([
                'title' => $blog['title'],
                'slug' => $blog['slug'],
                'excerpt' => $blog['excerpt'],
                'body' => $blog['body'],
                'category' => $blog['category'],
                'tags' => $blog['tags'],
                'status' => $blog['status'],
                'is_featured' => $blog['is_featured'],
                'published_at' => $blog['published_at'],
                'author_id' => $author->id,
                'read_time' => $blog['read_time'],
            ]);
        }

        $this->command->info('6 blog posts created successfully!');
    }
}
