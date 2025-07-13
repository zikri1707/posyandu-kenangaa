<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PedukuhanSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PosyanduSeeder;
use Database\Seeders\ScheduleSeeder;
use Database\Seeders\GallerySeeder;
use Database\Seeders\ArticleSeeder;
use Database\Seeders\MedicalRecordSeeder;
use Database\Seeders\PatientSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PedukuhanSeeder::class,
            PosyanduSeeder::class,
            UserSeeder::class,
            ScheduleSeeder::class,
            GallerySeeder::class,
            PatientSeeder::class,
            ArticleSeeder::class,
            MedicalRecordSeeder::class,
        ]);
    }
}
