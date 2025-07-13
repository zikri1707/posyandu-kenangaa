<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicalRecordSeeder extends Seeder
{
    public function run()
    {
        // Assuming you have patients seeded or will seed them
        DB::table('medical_records')->insert([
            [
                'patient_id' => 1,
                'user_id' => 2,
                'visit_date' => now()->subDays(30),
                'weight' => 6.5,
                'height' => 65,
                'head_circumference' => 42,
                'immunization' => 'BCG, Polio 1, Hepatitis B 1',
                'complaint' => 'Bayi rewel saat malam hari',
                'diagnosis' => 'Normal, kemungkinan kolik',
                'nutrition_status' => 'baik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'patient_id' => 2,
                'user_id' => 3,
                'visit_date' => now()->subDays(15),
                'weight' => 7.2,
                'height' => 68,
                'head_circumference' => 43,
                'immunization' => 'DPT 1, Hib 1, Polio 2',
                'complaint' => 'Batuk ringan',
                'diagnosis' => 'ISPA ringan',
                'nutrition_status' => 'baik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'patient_id' => 3,
                'user_id' => 2,
                'visit_date' => now()->subDays(7),
                'weight' => 8.1,
                'height' => 70,
                'head_circumference' => 44,
                'immunization' => 'Campak',
                'complaint' => 'Tidak ada',
                'diagnosis' => 'Sehat',
                'nutrition_status' => 'sangat baik',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
