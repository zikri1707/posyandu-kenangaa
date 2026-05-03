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
use Database\Seeders\WhoWeightForAgeSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            WhoWeightForAgeSeeder::class,  // Reference data - seed first
            WhoHeightForAgeSeeder::class,  // Reference data TB/U
            PedukuhanSeeder::class,
            PosyanduSeeder::class,
            UserSeeder::class,
            ScheduleSeeder::class,
            GallerySeeder::class,
            PatientSeeder::class,
            ArticleSeeder::class,
            MedicalRecordSeeder::class,
            BalitaKenanga1Seeder::class,  // Import data balita Kenanga 1
        ]);
    }
}
