<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosyanduSeeder extends Seeder
{
    public function run()
    {
        DB::table('posyandus')->insert([
            [
                'pedukuhan_id' => 1,
                'name' => 'Posyandu Melati',
                'address' => 'Jl. Melati No. 1, Dukuh A',
                'unique_code' => 'PSY001',
                'logo_photo' => 'posyandu-melati.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pedukuhan_id' => 2,
                'name' => 'Posyandu Mawar',
                'address' => 'Jl. Mawar No. 2, Dukuh B',
                'unique_code' => 'PSY002',
                'logo_photo' => 'posyandu-mawar.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pedukuhan_id' => 3,
                'name' => 'Posyandu Anggrek',
                'address' => 'Jl. Anggrek No. 3, Dukuh C',
                'unique_code' => 'PSY003',
                'logo_photo' => 'posyandu-anggrek.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
