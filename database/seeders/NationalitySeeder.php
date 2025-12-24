<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NationalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = file_get_contents(database_path('seeders/SeedData/countries.json'));
        $countries = [];

        foreach (json_decode($file, true) as $item) {
            $countries[] = [
                'name'       => $item['country'],
                // 'country_code'  => $item['iso2'],
                'phone_code' => $item['phone_code'],
                // 'currency'      => $item['currency'],
                // 'iso2'          => $item['iso2'],
                // 'iso3'          => $item['iso3'],
                // 'currency_name' => $item['currency_name'],
                // 'region'        => $item['region'],
                'timezone'   => $item['timezone'],
            ];
        }

        DB::table('nationalities')->insert($countries);
    }
}
