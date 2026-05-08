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
