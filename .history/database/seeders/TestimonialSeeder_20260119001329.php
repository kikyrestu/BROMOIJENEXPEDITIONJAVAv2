<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Sarah Jenner',
                'role' => 'Melbourne, Australia',
                'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Jenner&background=random',
                'content' => 'The Midnight Bromo Tour was absolutely breathtaking! The jeep ride under the stars and the sunrise view were magical. Perfectly organized!',
                'rating' => 5,
                'status' => 'published',
            ],
            [
                'name' => 'Michael Chen',
                'role' => 'Singapore',
                'avatar' => 'https://ui-avatars.com/api/?name=Michael+Chen&background=random',
                'content' => 'Ijen Blue Fire was a challenging hike but totally worth it. The guide ensured our safety throughout the trek. A premium experience.',
                'rating' => 5,
                'status' => 'published',
            ],
            [
                'name' => 'Emma Watson',
                'role' => 'London, UK',
                'avatar' => 'https://ui-avatars.com/api/?name=Emma+Watson&background=random',
                'content' => 'Booking was seamless via WhatsApp. The driver was punctual and the car was very comfortable. Highly recommended for solo travelers.',
                'rating' => 5,
                'status' => 'published',
            ],
            [
                'name' => 'David Kim',
                'role' => 'Seoul, South Korea',
                'avatar' => 'https://ui-avatars.com/api/?name=David+Kim&background=random',
                'content' => 'The scenery at massive Bromo crater is unlike anything I\'ve ever seen. BromoIjen Expedition handled everything professionally.',
                'rating' => 5,
                'status' => 'published',
            ],
            [
                'name' => 'Jessica Brown',
                'role' => 'Toronto, Canada',
                'avatar' => 'https://ui-avatars.com/api/?name=Jessica+Brown&background=random',
                'content' => 'An unforgettable adventure! The Tumpak Sewu waterfall trip was the highlight of our Java journey. Great local guides.',
                'rating' => 5,
                'status' => 'published',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }

        $this->command->info('Testimonials seeded successfully!');
    }
}
