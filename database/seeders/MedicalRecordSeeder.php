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
        $kaderKenanga1 = User::where('username', 'kader_kenanga1')->firstOrFail();
        $kaderKenanga2 = User::where('username', 'kader_kenanga2')->firstOrFail();

        // Resolve patients by NIK
        $amri = Patient::where('id_number', '3275011704550020')->firstOrFail();
        $meita = Patient::where('id_number', '3275015905630012')->firstOrFail();

        $records = [
            // ── LANSIA checkups ──────────────────────────────────────────────
            [
                'patient_id' => $amri->id,
                'user_id' => $kaderKenanga1->id,
                'visit_date' => now()->subMonths(1)->startOfMonth(),
                'weight' => 65.5,
                'height' => 165.0,
                'systolic_bp' => 135,
                'diastolic_bp' => 85,
                'blood_sugar' => 125,
                'cholesterol' => 180,
                'uric_acid' => 5.8,
                'complaint' => 'Pegal di punggung',
                'diagnosis' => 'Hipertensi Ringan',
            ],
            [
                'patient_id' => $meita->id,
                'user_id' => $kaderKenanga1->id,
                'visit_date' => now()->subMonths(1)->startOfMonth(),
                'weight' => 58.0,
                'height' => 155.0,
                'systolic_bp' => 120,
                'diastolic_bp' => 80,
                'blood_sugar' => 95,
                'cholesterol' => 190,
                'uric_acid' => 4.2,
                'complaint' => 'Tidak ada keluhan',
                'diagnosis' => 'Sehat',
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
