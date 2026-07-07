<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            // DataLansiaSeeder::class,     // Import data lansia (dimatikan agar lansia hanya 2)
            // IbuHamil5Seeder::class,      // Jika ingin ibu hamil juga tidak pakai data dummy, bisa di-uncomment
        ]);
    }
}
