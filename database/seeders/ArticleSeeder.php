<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        DB::table('articles')->insert([
            [
                'user_id' => 1,
                'title' => 'Pentingnya Imunisasi Dasar Lengkap',
                'content' => 'Artikel tentang pentingnya imunisasi dasar lengkap untuk bayi dan balita...',
                'thumbnail' => 'imunisasi.jpg',
                'slug' => 'pentingnya-imunisasi-dasar-lengkap',
                'status' => 'published',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'title' => 'Gizi Seimbang untuk Ibu Hamil',
                'content' => 'Artikel tentang gizi seimbang yang dibutuhkan oleh ibu hamil...',
                'thumbnail' => 'gizi-ibu-hamil.jpg',
                'slug' => 'gizi-seimbang-untuk-ibu-hamil',
                'status' => 'published',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'title' => 'Pencegahan Stunting pada Balita',
                'content' => 'Artikel tentang cara mencegah stunting pada balita...',
                'thumbnail' => 'stunting.jpg',
                'slug' => 'pencegahan-stunting-pada-balita',
                'status' => 'draft',
                'published_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
