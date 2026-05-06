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
        $kaderMelati = User::where('username', 'kader_melati')->firstOrFail();
        $kaderMawar = User::where('username', 'kader_mawar')->firstOrFail();
        $kaderAnggrek = User::where('username', 'kader_anggrek')->firstOrFail();

        // Resolve patients by NIK
        $ahmad = Patient::where('id_number', '1234567890123456')->firstOrFail();
        $siti = Patient::where('id_number', '1234567890123457')->firstOrFail();
        $rizky = Patient::where('id_number', '1234567890123461')->firstOrFail();
        $budi = Patient::where('id_number', '1234567890123458')->firstOrFail();
        $dewi = Patient::where('id_number', '1234567890123459')->firstOrFail();
        $nadia = Patient::where('id_number', '1234567890123462')->firstOrFail();
        $eko = Patient::where('id_number', '1234567890123460')->firstOrFail();
        $putri = Patient::where('id_number', '1234567890123463')->firstOrFail();

        $records = [
            // ── Posyandu Melati ──────────────────────────────────────────────
            [
                'patient_id' => $ahmad->id,
                'user_id' => $kaderMelati->id,
                'visit_date' => now()->subMonths(2)->startOfMonth(),
                'weight' => 11.5,
                'height' => 85.0,
                'head_circumference' => 47.0,
                'immunization' => null,
                'vitamin_a' => true,
                'pill_fe' => false,
                'complaint' => 'Tidak ada keluhan',
                'diagnosis' => 'Sehat',
                'nutrition_status' => 'baik',
                'z_score' => -0.50,
                'nutrition_trend' => 'naik',
            ],
            [
                'patient_id' => $ahmad->id,
                'user_id' => $kaderMelati->id,
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
                'user_id' => $kaderMelati->id,
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
            [
                'patient_id' => $rizky->id,
                'user_id' => $kaderMelati->id,
                'visit_date' => now()->subMonths(1)->startOfMonth(),
                'weight' => 7.8,
                'height' => 68.0,
                'head_circumference' => 43.5,
                'immunization' => 'DPT 1, Hib 1, Polio 2',
                'vitamin_a' => false,
                'pill_fe' => false,
                'complaint' => 'Rewel',
                'diagnosis' => 'Normal',
                'nutrition_status' => 'baik',
                'z_score' => -0.30,
                'nutrition_trend' => 'naik',
            ],
            // ── Posyandu Mawar ───────────────────────────────────────────────
            [
                'patient_id' => $budi->id,
                'user_id' => $kaderMawar->id,
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
                'user_id' => $kaderMawar->id,
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
            [
                'patient_id' => $nadia->id,
                'user_id' => $kaderMawar->id,
                'visit_date' => now()->subMonths(1)->startOfMonth(),
                'weight' => 8.5,
                'height' => 72.0,
                'head_circumference' => 44.0,
                'immunization' => 'BCG, Polio 1',
                'vitamin_a' => false,
                'pill_fe' => false,
                'complaint' => 'Tidak ada keluhan',
                'diagnosis' => 'Sehat',
                'nutrition_status' => 'baik',
                'z_score' => -0.10,
                'nutrition_trend' => 'naik',
            ],
            // ── Posyandu Anggrek ─────────────────────────────────────────────
            [
                'patient_id' => $eko->id,
                'user_id' => $kaderAnggrek->id,
                'visit_date' => now()->subMonths(1)->startOfMonth(),
                'weight' => 10.0,
                'height' => 80.0,
                'head_circumference' => 46.0,
                'immunization' => null,
                'vitamin_a' => true,
                'pill_fe' => false,
                'complaint' => 'Tidak ada keluhan',
                'diagnosis' => 'Sehat',
                'nutrition_status' => 'baik',
                'z_score' => -0.70,
                'nutrition_trend' => 'tetap',
            ],
            [
                'patient_id' => $putri->id,
                'user_id' => $kaderAnggrek->id,
                'visit_date' => now()->subMonths(1)->startOfMonth(),
                'weight' => 7.2,
                'height' => 65.0,
                'head_circumference' => 42.5,
                'immunization' => 'Hepatitis B 1',
                'vitamin_a' => false,
                'pill_fe' => false,
                'complaint' => 'Tidak ada keluhan',
                'diagnosis' => 'Sehat',
                'nutrition_status' => 'baik',
                'z_score' => -0.50,
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
