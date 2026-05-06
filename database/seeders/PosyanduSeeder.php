<?php

namespace Database\Seeders;

use App\Models\Pedukuhan;
use App\Models\Posyandu;
use Illuminate\Database\Seeder;

class PosyanduSeeder extends Seeder
{
    public function run(): void
    {
        $dukuhA = Pedukuhan::where('name', 'Dukuh A')->firstOrFail();
        $dukuhB = Pedukuhan::where('name', 'Dukuh B')->firstOrFail();
        $dukuhC = Pedukuhan::where('name', 'Dukuh C')->firstOrFail();
        $arenJaya = Pedukuhan::where('name', 'Aren Jaya')->firstOrFail();

        $posyandus = [
            [
                'pedukuhan_id' => $dukuhA->id,
                'name' => 'Posyandu Melati',
                'address' => 'Jl. Melati No. 1, Dukuh A',
                'unique_code' => 'PSY001',
                'logo_photo' => null,
            ],
            [
                'pedukuhan_id' => $dukuhB->id,
                'name' => 'Posyandu Mawar',
                'address' => 'Jl. Mawar No. 2, Dukuh B',
                'unique_code' => 'PSY002',
                'logo_photo' => null,
            ],
            [
                'pedukuhan_id' => $dukuhC->id,
                'name' => 'Posyandu Anggrek',
                'address' => 'Jl. Anggrek No. 3, Dukuh C',
                'unique_code' => 'PSY003',
                'logo_photo' => null,
            ],
            [
                'pedukuhan_id' => $arenJaya->id,
                'name' => 'KENANGA 1',
                'address' => 'Aren Jaya, RW 11, Bekasi Timur',
                'unique_code' => 'KENANGA1',
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
