<?php

namespace Database\Seeders;

use App\Models\Pedukuhan;
use App\Models\Posyandu;
use Illuminate\Database\Seeder;

class PosyanduSeeder extends Seeder
{
    public function run(): void
    {
        $arenJaya = Pedukuhan::where('name', 'Aren Jaya')->firstOrFail();

        $posyandus = [
            [
                'pedukuhan_id' => $arenJaya->id,
                'name' => 'KENANGA 1',
                'address' => 'Aren Jaya, RW 11, Bekasi Timur',
                'unique_code' => 'PSY003',
                'logo_photo' => null,
            ],
            [
                'pedukuhan_id' => $arenJaya->id,
                'name' => 'KENANGA 2',
                'address' => 'Aren Jaya, RW 12, Bekasi Timur',
                'unique_code' => 'PSY002',
                'logo_photo' => null,
            ],
        ];

        foreach ($posyandus as $data) {
            Posyandu::updateOrCreate(
                ['unique_code' => $data['unique_code']],
                $data
            );
        }
    }
}
