<?php

namespace Database\Seeders;

use App\Models\Pedukuhan;
use Illuminate\Database\Seeder;

class PedukuhanSeeder extends Seeder
{
    public function run(): void
    {
        $pedukuhans = [
            [
                'name' => 'Dukuh A',
                'postal_code' => '55281',
                'geo_location' => json_encode(['lat' => -7.123456, 'lng' => 110.123456]),
            ],
            [
                'name' => 'Dukuh B',
                'postal_code' => '55282',
                'geo_location' => json_encode(['lat' => -7.223456, 'lng' => 110.223456]),
            ],
            [
                'name' => 'Dukuh C',
                'postal_code' => '55283',
                'geo_location' => json_encode(['lat' => -7.323456, 'lng' => 110.323456]),
            ],
            [
                'name' => 'Aren Jaya',
                'postal_code' => '17111',
                'geo_location' => json_encode(['lat' => -6.234567, 'lng' => 107.012345]),
            ],
        ];

        foreach ($pedukuhans as $data) {
            Pedukuhan::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
