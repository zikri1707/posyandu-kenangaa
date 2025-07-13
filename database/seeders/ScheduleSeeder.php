<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        DB::table('schedules')->insert([
            [
                'posyandu_id' => 1,
                'user_id' => 2,
                'title' => 'Posyandu Bulanan',
                'description' => 'Kegiatan posyandu rutin bulanan untuk pemeriksaan bayi dan balita',
                'start_time' => now()->addDays(5),
                'end_time' => now()->addDays(5)->addHours(3),
                'location' => 'Posyandu Melati',
                'status' => 'upcoming',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posyandu_id' => 2,
                'user_id' => 2,
                'title' => 'Imunisasi Campak',
                'description' => 'Program imunisasi campak untuk bayi usia 9 bulan',
                'start_time' => now()->addDays(7),
                'end_time' => now()->addDays(7)->addHours(2),
                'location' => 'Posyandu Mawar',
                'status' => 'upcoming',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posyandu_id' => 3,
                'user_id' => 3,
                'title' => 'Penyuluhan Gizi',
                'description' => 'Penyuluhan tentang gizi seimbang untuk ibu hamil dan balita',
                'start_time' => now()->addDays(10),
                'end_time' => now()->addDays(10)->addHours(2),
                'location' => 'Posyandu Anggrek',
                'status' => 'upcoming',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
