<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'content' => 'Welcome to Bromo Ijen Expedition',
            ]
        );

        $this->command->info('Home page created successfully!');
    }
}
