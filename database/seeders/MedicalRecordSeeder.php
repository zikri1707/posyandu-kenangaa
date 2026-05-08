<?php

namespace Database\Seeders;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        // Resolve kader users by username so we never rely on hardcoded IDs
        // Resolve kader users by username
        $kaderKenanga1 = User::where('username', 'kenanga1')->firstOrFail();
        $kaderKenanga2 = User::where('username', 'kader_kenanga2')->firstOrFail();

        // Resolve patients by NIK
        $ahmad = Patient::where('id_number', '1234567890123456')->firstOrFail();
        $siti = Patient::where('id_number', '1234567890123457')->firstOrFail();
        $budi = Patient::where('id_number', '1234567890123458')->firstOrFail();
        $dewi = Patient::where('id_number', '1234567890123459')->firstOrFail();

        $records = [
            // ── KENANGA 1 ────────────────────────────────────────────────────
            [
                'patient_id' => $ahmad->id,
                'user_id' => $kaderKenanga1->id,
                'visit_date' => now()->subMonths(1)->startOfMonth(),
                'weight' => 11.8,
                'height' => 85.5,
                'head_circumference' => 47.2,
                'immunization' => null,
                'vitamin_a' => false,
                'pill_fe' => false,
                'complaint' => 'Tidak ada keluhan',
                'diagnosis' => 'Sehat',
                'nutrition_status' => 'baik',
                'z_score' => -0.40,
                'nutrition_trend' => 'naik',
            ],
            [
                'patient_id' => $siti->id,
                'user_id' => $kaderKenanga1->id,
                'visit_date' => now()->subMonths(1)->startOfMonth(),
                'weight' => 9.2,
                'height' => 76.0,
                'head_circumference' => 45.0,
                'immunization' => 'Campak',
                'vitamin_a' => true,
                'pill_fe' => false,
                'complaint' => 'Batuk ringan',
                'diagnosis' => 'ISPA ringan',
                'nutrition_status' => 'baik',
                'z_score' => -0.80,
                'nutrition_trend' => 'tetap',
            ],
            // ── KENANGA 2 ────────────────────────────────────────────────────
            [
                'patient_id' => $budi->id,
                'user_id' => $kaderKenanga2->id,
                'visit_date' => now()->subMonths(1)->startOfMonth(),
                'weight' => 13.0,
                'height' => 92.0,
                'head_circumference' => 49.0,
                'immunization' => null,
                'vitamin_a' => true,
                'pill_fe' => false,
                'complaint' => 'Tidak ada keluhan',
                'diagnosis' => 'Sehat',
                'nutrition_status' => 'baik',
                'z_score' => -0.20,
                'nutrition_trend' => 'naik',
            ],
            [
                'patient_id' => $dewi->id,
                'user_id' => $kaderKenanga2->id,
                'visit_date' => now()->subMonths(1)->startOfMonth(),
                'weight' => 10.5,
                'height' => 82.0,
                'head_circumference' => 46.5,
                'immunization' => null,
                'vitamin_a' => true,
                'pill_fe' => false,
                'complaint' => 'Tidak ada keluhan',
                'diagnosis' => 'Sehat',
                'nutrition_status' => 'baik',
                'z_score' => -0.60,
                'nutrition_trend' => 'naik',
            ],
        ];

        foreach ($records as $data) {
            // Idempotent: one record per patient per visit_date
            MedicalRecord::updateOrCreate(
                [
                    'patient_id' => $data['patient_id'],
                    'visit_date' => $data['visit_date'],
                ],
                $data
            );
        }
    }
}
