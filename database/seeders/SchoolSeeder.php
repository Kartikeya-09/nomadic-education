<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $tribes = ['Gujjar', 'Bakarwal'];
        $locations = [
            'Pahalgam Camp',
            'Sonamarg Valley',
            'Kargil Route',
            'Lidder Valley',
            'Gulmarg Camp',
        ];

        for ($i = 1; $i <= 3; $i++) {
            School::create([
                'name' => 'Nomadic Primary Camp ' . $i,
                'school_code' => 'NPC-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'community_tribe' => fake()->randomElement($tribes),
                'current_camp_location' => fake()->randomElement($locations),
                'address' => fake()->sentence(6),
                'contact_phone' => fake()->numerify('96########'),
                'status' => true,
            ]);
        }
    }
}
