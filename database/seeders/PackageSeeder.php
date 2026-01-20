<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Destination;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Destinations Exist
        $bromo = Destination::firstOrCreate(
            ['slug' => 'bromo-volcano'],
            [
                'name' => 'Bromo Volcano',
                'description' => 'Experience the sunrise at Mount Bromo.',
                'is_featured' => true
            ]
        );

        $ijen = Destination::firstOrCreate(
            ['slug' => 'ijen-crater'],
            [
                'name' => 'Ijen Crater',
                'description' => 'Witness the blue fire phenomena.',
                'is_featured' => true
            ]
        );
        
        // 2. Seed Packages
        if ($bromo) {
            Package::updateOrCreate(
                ['slug' => 'bromo-midnight-tour'],
                [
                    'destination_id' => $bromo->id,
                    'name' => 'Bromo Midnight Tour',
                    'price_start_from' => 750000,
                    'duration_days' => 1,
                    'duration_nights' => 0,
                    'location' => 'East Java, Indonesia',
                    'category' => 'Adventure',
                    'rating' => 4.9,
                    'review_count' => 128,
                    'max_participants' => 6,
                    'short_description' => 'Witness the magical golden sunrise at Mount Bromo without staying overnight.',
                    'long_description' => '<p>Join our most popular tour, the <strong>Bromo Midnight Adventure</strong>. Departing from Malang or Surabaya at midnight, you will be whisked away in a comfortable car to the transfer point, where you’ll switch to a classic 4x4 Jeep.</p><p>Experience the thrill of off-road driving in the dark as we climb to <strong>Penanjakan Viewpoint</strong> (2,770 masl). Wait for the sun to rise and illuminate the majestic Bromo crater, Batok mountain, and Semeru volcano in the background.</p>',
                    'destinations_list' => ['Penanjakan Viewpoint', 'Bromo Crater', 'Sea of Sand', 'Teletubbies Hill'],
                    'highlights' => ['Golden Sunrise at Penanjakan', 'Jeep 4x4 Offroad Experience', 'Hiking to Crater Rim', 'Whispering Sands Photo Spot'],
                    'inclusions' => "- Jeep 4x4 (Hardtop)\n- Professional Driver\n- Fuel & Parking Fees\n- Mineral Water\n- Entrance Tickets",
                    'exclusions' => "- Personal Expenses\n- Horse Riding\n- Meals (Breakfast/Lunch)\n- Travel Insurance",
                    'faqs' => [
                        ['question' => 'What should I wear?', 'answer' => 'Warm jacket, gloves, and beanie are mandatory as temperature drops to 5-10°C.'],
                        ['question' => 'Is it suitable for children?', 'answer' => 'Yes, but be prepared for cold weather and some walking. Minimum age 5 recommended.'],
                    ],
                    'itinerary' => [
                        [
                            'day' => 1,
                            'title' => 'Midnight Adventure',
                            'activities' => [
                                '00:00 - Pick up from Malang/Surabaya',
                                '03:00 - Arrive at Tosari/Cemorolawang Hub',
                                '03:30 - Jeep ride to Penanjakan Viewpoint',
                                '05:00 - Enjoy the Golden Sunrise',
                                '06:30 - Hike to Bromo Crater',
                                '08:30 - Visit Whispering Sands & Teletubbies Hill',
                                '10:00 - Back to Hub & Transfer to City',
                                '13:00 - Drop off service'
                            ]
                        ]
                    ],
                    'wa_template_message' => 'Hello, I am interested in Bromo Midnight Tour. Is it available for next weekend?',
                    'status' => 'published', 
                ]
            );
        }

        if ($ijen) {
            Package::updateOrCreate(
                ['slug' => 'ijen-blue-fire'],
                [
                    'destination_id' => $ijen->id,
                    'name' => 'Ijen Blue Fire Expedition',
                    'price_start_from' => 850000,
                    'duration_days' => 2,
                    'duration_nights' => 1,
                    'location' => 'Banyuwangi, East Java',
                    'category' => 'Adventure',
                    'rating' => 4.8,
                    'review_count' => 95,
                    'max_participants' => 10,
                    'short_description' => 'A two-day journey to witness the rare Blue Fire phenomena at Ijen Crater.',
                    'long_description' => '<p>The <strong>Ijen Blue Fire Expedition</strong> is a once-in-a-lifetime experience. Kawah Ijen is famous for its turquoise acidic lake and the mesmerizing <strong>electric blue flames</strong> that are visible only at night.</p><p>We will start the trek at midnight to reach the crater rim. The hike is challenging but rewarding. You will also see traditional sulfur miners carrying heavy loads up and down the crater.</p>',
                    'destinations_list' => ['Paltuding Basecamp', 'Ijen Crater Rim', 'Blue Fire Spot', 'Acid Lake View'],
                    'highlights' => ['World Famous Blue Fire', 'Sunrise over Acid Lake', 'Paltuding Trekking', 'Sulfur Miners Culture'],
                    'inclusions' => "- Private AC Transport\n- Hotel Accommodation (1 Night)\n- Gas Mask & Headlamp\n- Local Guide\n- Entrance Fees",
                    'exclusions' => "- Flight Tickets\n- Tipping\n- Personal Expenses\n- Lunch & Dinner",
                    'faqs' => [
                        ['question' => 'How hard is the trekking?', 'answer' => 'It is a moderate to steep hike for about 3km. Takes 1.5 - 2 hours.'],
                        ['question' => 'Is a gas mask provided?', 'answer' => 'Yes, we provide professional gas masks for safety.'],
                    ],
                    'itinerary' => [
                        [
                            'day' => 1,
                            'title' => 'Transfer to Banyuwangi',
                            'activities' => [
                                '09:00 - Pick up from Airport/Station',
                                '12:00 - Local lunch en route',
                                '15:00 - Check in Hotel in Banyuwangi',
                                '19:00 - Free time / Rest'
                            ]
                        ],
                        [
                            'day' => 2,
                            'title' => 'Blue Fire Trekking',
                            'activities' => [
                                '00:30 - Wake up & preparation',
                                '01:30 - Start trekking from Paltuding',
                                '03:30 - Witness Blue Fire at Crater',
                                '05:30 - Enjoy Sunrise over the Acid Lake',
                                '07:30 - Trek back to Paltuding',
                                '09:00 - Back to Hotel, Breakfast & Check out',
                                '12:00 - Drop off service'
                            ]
                        ]
                    ],
                    'wa_template_message' => 'Hi, I want to book Ijen Blue Fire Expedition. What are the available dates?',
                    'status' => 'published',
                ]
            );
        }
    }
}
