<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Destination;
use App\Models\Package;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@bromo.com'],
            [
                'name' => 'Admin Super',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Destinations
        $destinations = [
            'Bromo Volcano' => 'Experience the sunrise at Mount Bromo.',
            'Ijen Crater' => 'Witness the blue fire phenomena.',
            'Tumpak Sewu' => 'The Niagara of Indonesia.',
        ];

        foreach ($destinations as $name => $desc) {
            Destination::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $desc,
                'is_featured' => true,
            ]);
        }

        // Packages
        $bromo = Destination::where('slug', 'bromo-volcano')->first();
        if ($bromo) {
            Package::create([
                'destination_id' => $bromo->id,
                'name' => 'Bromo Midnight Tour',
                'slug' => 'bromo-midnight-tour',
                'price_start_from' => 750000,
                'duration_days' => 1,
                'duration_nights' => 0,
                'inclusions' => "- Jeep 4x4 (Hardtop)\n- Professional Driver\n- Fuel & Parking Fees\n- Mineral Water\n- Entrance Tickets",
                'exclusions' => "- Personal Expenses\n- Horse Riding\n- Meals (Breakfast/Lunch)\n- Travel Insurance",
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
            ]);
        }

        $ijen = Destination::where('slug', 'ijen-crater')->first();
        if ($ijen) {
            Package::create([
                'destination_id' => $ijen->id,
                'name' => 'Ijen Blue Fire Expedition',
                'slug' => 'ijen-blue-fire',
                'price_start_from' => 850000,
                'duration_days' => 2,
                'duration_nights' => 1,
                'inclusions' => "- Private AC Transport\n- Hotel Accommodation (1 Night)\n- Gas Mask & Headlamp\n- Local Guide\n- Entrance Fees",
                'exclusions' => "- Flight Tickets\n- Tipping\n- Personal Expenses\n- Lunch & Dinner",
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
            ]);
        }
        $this->call([
            HomePageSeeder::class,
            BlogSeeder::class,
            TestimonialSeeder::class,
        ]);
    }
}
