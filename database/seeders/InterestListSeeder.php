<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InterestListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('interest_lists')->insert([
            [
                'name' => '💃 Art & Culture',
            ],
            [
                'name' => '🏝️ Travel & Adventure',
            ],
            [
                'name' => '🎺 Music & Concerts',
            ],
            [
                'name' => '🍔 Food & Dining',
            ],
            [
                'name' => '🪁 Outdoor Activities',
            ],
            [
                'name' => '⛹️‍♂️ Sports & Athletics',
            ],
            [
                'name' => '🧘‍♂️ Fitness & Wellness',
            ],
            [
                'name' => '🫂 Volunteer & Community Service',
            ],
            [
                'name' => '🎮 Gaming & Technology',
            ],
            [
                'name' => '🎀 Home & DIY Project',
            ]
        ]);
    }
}
