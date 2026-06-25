<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WhoHeightForAgeSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate table to ensure fresh start
        DB::table('who_height_for_age')->truncate();

        $this->command->info('Mengisi data lengkap WHO Height-for-Age (TB/U)...');

        // Data L, M, S untuk perhitungan Z-Score (Sampel Poin Utama)
        // Kita isi 0-60 bulan untuk Laki-laki (M) dan Perempuan (F)

        $data = [];

        // MALE (M) - 0-60 months
        $maleMilestones = [
            0 => [1, 49.9, 0.0380, 44.2, 46.1, 53.7, 55.6],
            1 => [1, 54.7, 0.0373, 48.9, 50.8, 58.6, 60.6],
            2 => [1, 58.4, 0.0368, 52.4, 54.4, 62.4, 64.4],
            3 => [1, 61.4, 0.0364, 55.3, 57.3, 65.5, 67.6],
            4 => [1, 63.9, 0.0361, 57.6, 59.7, 68.0, 70.1],
            5 => [1, 65.9, 0.0359, 59.6, 61.7, 70.1, 72.2],
            6 => [1, 67.6, 0.0357, 61.2, 63.3, 71.9, 74.0],
            7 => [1, 69.2, 0.0355, 62.7, 64.8, 73.5, 75.7],
            8 => [1, 70.6, 0.0353, 64.0, 66.2, 75.0, 77.2],
            9 => [1, 72.0, 0.0352, 65.2, 67.5, 76.5, 78.7],
            10 => [1, 73.3, 0.0351, 66.4, 68.7, 77.9, 80.1],
            11 => [1, 74.5, 0.0350, 67.5, 69.9, 79.2, 81.5],
            12 => [1, 75.7, 0.0349, 68.6, 71.0, 80.5, 82.9],
            18 => [1, 82.3, 0.0345, 75.0, 76.9, 87.7, 90.4],
            24 => [1, 87.8, 0.0342, 80.0, 82.5, 93.2, 95.8],
            36 => [1, 96.1, 0.0340, 88.2, 90.7, 101.5, 104.1],
            48 => [1, 103.3, 0.0338, 94.4, 97.2, 109.4, 112.5],
            60 => [1, 110.0, 0.0336, 100.7, 103.3, 116.5, 120.0],
        ];

        // FEMALE (F) - 0-60 months
        $femaleMilestones = [
            0 => [1, 49.1, 0.0381, 43.6, 45.4, 52.9, 54.7],
            1 => [1, 53.7, 0.0375, 47.8, 49.8, 57.6, 59.5],
            2 => [1, 57.1, 0.0370, 51.0, 53.0, 61.1, 63.2],
            3 => [1, 59.8, 0.0366, 53.5, 55.6, 64.0, 66.1],
            4 => [1, 62.1, 0.0363, 55.6, 57.8, 66.4, 68.6],
            5 => [1, 64.0, 0.0361, 57.4, 59.6, 68.5, 70.7],
            6 => [1, 65.7, 0.0359, 58.9, 61.2, 70.3, 72.5],
            7 => [1, 67.3, 0.0357, 60.3, 62.7, 71.9, 74.2],
            8 => [1, 68.7, 0.0356, 61.7, 64.0, 73.5, 75.8],
            9 => [1, 70.1, 0.0354, 63.2, 65.3, 75.0, 77.4],
            10 => [1, 71.5, 0.0353, 64.3, 66.5, 76.4, 78.9],
            11 => [1, 72.8, 0.0352, 65.5, 67.8, 77.8, 80.3],
            12 => [1, 74.0, 0.0351, 66.8, 69.2, 78.9, 81.3],
            18 => [1, 80.7, 0.0349, 73.1, 75.2, 86.2, 88.8],
            24 => [1, 86.4, 0.0346, 78.0, 80.3, 92.5, 95.4],
            36 => [1, 95.1, 0.0344, 86.4, 88.7, 101.4, 104.7],
            48 => [1, 102.7, 0.0342, 93.6, 96.1, 109.3, 112.6],
            60 => [1, 109.4, 0.0340, 99.9, 102.3, 116.5, 119.6],
        ];

        // Interpolasi data bulanan dari milestones (0-60 bulan) agar data lengkap 61 bulan
        $allMonthsData = [];
        $milestoneAges = array_keys($maleMilestones);

        for ($i = 0; $i < count($milestoneAges) - 1; $i++) {
            $ageStart = $milestoneAges[$i];
            $ageEnd = $milestoneAges[$i + 1];

            $valStartM = $maleMilestones[$ageStart];
            $valEndM = $maleMilestones[$ageEnd];

            $valStartF = $femaleMilestones[$ageStart];
            $valEndF = $femaleMilestones[$ageEnd];

            for ($age = $ageStart; $age < $ageEnd; $age++) {
                $ratio = ($age - $ageStart) / ($ageEnd - $ageStart);

                // Male
                $interpolatedM = [];
                for ($k = 0; $k < count($valStartM); $k++) {
                    $interpolatedM[$k] = $valStartM[$k] + $ratio * ($valEndM[$k] - $valStartM[$k]);
                }
                $allMonthsData['M'][$age] = $interpolatedM;

                // Female
                $interpolatedF = [];
                for ($k = 0; $k < count($valStartF); $k++) {
                    $interpolatedF[$k] = $valStartF[$k] + $ratio * ($valEndF[$k] - $valStartF[$k]);
                }
                $allMonthsData['F'][$age] = $interpolatedF;
            }
        }

        // Tambah milestone terakhir (60 bulan)
        $allMonthsData['M'][60] = $maleMilestones[60];
        $allMonthsData['F'][60] = $femaleMilestones[60];

        // Masukkan data ke array $data untuk di-insert bulk
        foreach ($allMonthsData['M'] as $age => $v) {
            $data[] = [
                'gender' => 'M', 'age_months' => $age,
                'l_value' => round($v[0], 5), 'm_value' => round($v[1], 5), 's_value' => round($v[2], 5),
                'sd_minus3' => round($v[3], 1), 'sd_minus2' => round($v[4], 1), 'sd_plus2' => round($v[5], 1), 'sd_plus3' => round($v[6], 1),
            ];
        }

        foreach ($allMonthsData['F'] as $age => $v) {
            $data[] = [
                'gender' => 'F', 'age_months' => $age,
                'l_value' => round($v[0], 5), 'm_value' => round($v[1], 5), 's_value' => round($v[2], 5),
                'sd_minus3' => round($v[3], 1), 'sd_minus2' => round($v[4], 1), 'sd_plus2' => round($v[5], 1), 'sd_plus3' => round($v[6], 1),
            ];
        }

        DB::table('who_height_for_age')->insert($data);
        $this->command->info('Berhasil mengisi data lengkap WHO TB/U (0-60 bulan) sebanyak ' . count($data) . ' baris.');
    }
}
