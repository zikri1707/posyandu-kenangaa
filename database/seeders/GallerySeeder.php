<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GallerySeeder extends Seeder
{
    public function run()
    {
        DB::table('galleries')->insert([
            [
                'posyandu_id' => 1,
                'user_id' => 2,
                'title' => 'Kegiatan Posyandu Januari',
                'description' => 'Foto-foto kegiatan posyandu bulan Januari',
                'photo' => 'kegiatan-januari.jpg',
                'type' => 'activity',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posyandu_id' => 1,
                'user_id' => 2,
                'title' => 'Imunisasi Campak',
                'description' => 'Foto kegiatan imunisasi campak',
                'photo' => 'imunisasi-campak.jpg',
                'type' => 'immunization',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posyandu_id' => 2,
                'user_id' => 2,
                'title' => 'Penyuluhan Gizi',
                'description' => 'Foto kegiatan penyuluhan gizi',
                'photo' => 'penyuluhan-gizi.jpg',
                'type' => 'education',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
