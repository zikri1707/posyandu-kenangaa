<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedukuhanSeeder extends Seeder
{
    public function run()
    {
        DB::table('pedukuhans')->insert([
            [
                'name' => 'Dukuh A',
                'postal_code' => '55281',
                'geo_location' => json_encode(['lat' => -7.123456, 'lng' => 110.123456]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dukuh B',
                'postal_code' => '55282',
                'geo_location' => json_encode(['lat' => -7.223456, 'lng' => 110.223456]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dukuh C',
                'postal_code' => '55283',
                'geo_location' => json_encode(['lat' => -7.323456, 'lng' => 110.323456]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}