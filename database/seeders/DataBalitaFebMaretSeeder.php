<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Posyandu;
use App\Services\NutritionCalculatorService;
use Carbon\Carbon;

class DataBalitaFebMaretSeeder extends Seeder
{
    public function run()
    {
        $calculator = app(NutritionCalculatorService::class);
        $posyandu = Posyandu::first();
        $posyanduId = $posyandu ? $posyandu->id : 1;

        // --- SHEET 1: Februari 2026 (Pelaksanaan: 5 Februari 2026) ---
        $febDate = '2026-02-05';
        $febData = [
            // [Nama, RT/RW, Ortu, L/P, Tgl Lahir, Umur, BB, TB, Status]
            ['Alfian Zaftan M', '02/11', 'M Asysyam', 'L', '2021-06-02', 60, 21.5, 112, 'TE'],
            ['Vallera Carmen R', '02/11', 'Ragid', 'P', '2021-09-23', 56, 15.8, 98, 'T'],
            ['Salwa D.Z', '02/11', 'Heri', 'P', '2021-06-29', 55, 14.5, 96, 'T'],
            ['Nata Noel', '01/11', 'Marjohan', 'L', '2021-06-21', 53, 12.3, 93, '-'],
            ['Sabhika', '01/11', 'Nardiansyah', 'P', '2021-01-20', 50, 15.7, 98, '-'],
            ['Tsabina A.L', '01/11', 'Astu', 'P', null, 48, 19.6, 102, 'N'],
            ['Ganester', '01/11', 'Adi Rasa', 'L', '2022-06-03', 48, 16.5, 99, 'R'],
            ['Ryuga A.H', '02/11', 'Rahmat', 'L', '2022-05-21', 48, 14.5, 97, 'TE'],
            ['Anindya Zhea', '02/11', 'Asysyam', 'P', '2022-01-20', 49, null, null, 'TE'],
            ['Mikael', '01/11', 'Winson', 'L', '2022-04-03', 46, 14, 96, '7'],
            ['Arka', '02/11', 'Suherman', 'L', '2022-06-21', 45, 12.7, 90, '-'],
            ['Khaizanu', '03/12', 'Heriyawan', 'L', null, 45, 16.6, 96, 'Te'],
            ['Fatimah', '01/11', 'A. Akbar', 'P', '2022-05-20', 45, 13.5, 90, 'TE'],
            ['Arfan Sidqi', '03/11', 'Jadi', 'L', '2022-06-29', 45, 15.3, 100, 'Tt'],
            ['Nabila Warna', '01/11', 'Adri M.S', 'P', '2022-07-03', 43, 11.5, 93, 'T'],
            ['Adeela Fida', '04/11', 'Ujang', 'P', '2022-07-07', 43, 13.2, 89, 'Te'],
            ['Mahira N.P.E.A', '02/11', 'Duta A', 'P', '2022-07-13', 43, 16.3, 100, 'TE'],
            ['A. Zafran U.R', '04/11', 'Ryan R.R', 'L', '2022-08-06', 42, 16.3, 99, 'TE'],
            ['Raty Raline VCB', '02/11', 'Bachtiar', 'P', '2022-12-07', 38, 14.1, 94, '-'],
            ['Mikhayla A.F', '03/11', 'Catur', 'P', '2022-02-26', 37, 11.9, 91, 'T'],
            ['Safia', '02/11', 'Wisnu', 'P', '2023-05-01', 36, 7.5, 97, '-'],
            ['Moriel N.P.A', '02/11', 'Nuel K', 'P', '2023-03-25', 35, 10.9, 89, 'TE'],
            ['Albiru', '01/11', 'Ariestya', 'L', '2023-03-25', 35, 12.7, 88, 'To'],
            ['Shaynala', '03/11', 'Syahmi', 'P', '2023-04-10', 34, 12.9, 92, 'TE'],
            ['M. Azzumar', '02/11', 'M. Kamaludin', 'L', '2023-04-16', 34, 11.8, 84, 'TE'],
            ['Adi Tama', '03/11', 'Dodi', 'L', '2023-04-12', 34, 13.7, 93, 'TE'],
            ['Calvin Zane', '04/11', 'Ananda', 'L', '2023-06-23', 33, 19.2, 92, '-'],
            ['Jasmin', '04/11', 'Oji', 'P', '2023-06-04', 32, 12.6, null, 'Th'],
            ['Gladis A', '01/11', 'Adi Rasa', 'P', '2023-06-02', 32, 8.9, 10, 'R'],
            ['M. Ichsan A', '02/11', 'Imam', 'L', '2023-06-26', 32, 13.2, 94, 'V'],
            ['Askara A', '02/11', 'Arindra', 'P', '2023-07-20', 31, 12.1, 87, 'M'],
            ['Azam Rafasya', '01/11', 'Irwansyah', 'L', '2023-08-11', 29, 10.8, 76, 'TE'],
            ['M. Zidan Rafdi', '03/11', 'Rafdi', 'L', '2023-05-10', 30, 13.5, 92, 'TH'],
            ['Faiza Takzia', '01/11', 'Hardian', 'P', '2023-10-15', 28, 12.7, 87, 'Tt'],
            ['Mikail', '02/11', 'Oki', 'L', '2024-04-22', 22, 10.2, 85, 'V'],
            ['Argawira A.A', '02/11', 'Aldifa', 'L', '2024-05-17', 21, 11.8, 82, 'TE'],
            ['M. Albifarzan', '01/11', 'M. Yuda', 'L', '2024-08-06', 19, 9.7, 79, 'T'],
            ['Abhipraya A', '02/11', 'Arief S.S', 'L', '2024-08-20', 18, 10, 79, 'TE'],
            ['M. Maulia R', '03/11', 'Era K', 'L', '2024-09-05', 17, 9.1, 72, 'Th'],
            ['Elisa Shanum', '01/11', 'Al Putra', 'P', '2024-09-23', 17, 9.3, 74, 'M'],
            ['Alena Shalihah', '04/11', 'Ghema P', 'P', '2024-06-03', 16, 8.7, 77, 'Te'],
            ['Haura R.R', '04/11', 'Ridwan', 'P', '2024-10-04', 16, 9.3, 76, 'TE'],
            ['Barra Al Fatih', '01/11', 'Wiwit A', 'L', '2024-10-01', 15, 14.3, 81, 'Tt'],
            ['Soca M.N', '01/11', 'Van B.P', 'P', '2025-03-05', 11, 9.2, 72, 'TE'],
            ['Mistiael V.P.A', '02/11', 'Muel K', 'P', '2025-03-24', 11, 7, 72, '-'],
            ['M. Zaid U', '03/11', 'Harry L', 'L', '2025-06-20', 8, 9.7, 73, '-'],
            ['Raisa Amarilia S', '02/11', 'Bachtiar', 'P', '2025-06-12', 8, 7.1, 65, '-'],
            ['Lanang V.A', '04/11', 'Doko', 'L', '2025-06-28', 8, 8.5, 68, 'TH'],
            ['Grace Veliora', '02/11', 'Bachtiar', 'P', '2025-07-26', 7, 6.1, 65, 'N'],
            ['M. Zacky', '02/11', 'M. Asysyam', 'L', '2025-08-01', 6, 7.8, 70, '2'],
            ['Albertina A', '04/11', 'Ismu', 'P', '2025-08-08', 6, 6.8, 68, 'N'],
            ['Hasan Ayyub A', '03/11', 'M. Endang', 'L', '2025-09-01', 5, 6.2, 60, 'Tt'],
            ['M. Ibrahim R', '01/11', 'Hardian', 'L', '2025-09-27', 5, 7.3, 64, 'N'],
            ['Arcelia A.A', '02/11', 'Aldifa', 'P', '2025-10-07', 4, 6.6, 64, 'N'],
        ];

        // --- SHEET 2: Maret 2026 (Pelaksanaan: 2 Maret 2026) ---
        $marDate = '2026-03-02';
        $marData = [
            ['Alfian Zaftan M', '02/11', 'M Said', 'L', '2021-06-02', 61, null, null, '-'],
            ['Vallera Carmen R', '03/11', 'Usman', 'P', '2021-09-23', 59, 15.8, 98, 'T'],
            ['Salwa D.Z', '01/11', 'Heri S', 'P', '2021-06-29', 57, 15.3, 108, '-'],
            ['Naha Mael', '01/11', 'Marjahan', 'L', '2021-07-21', 54, null, null, 'TE'],
            ['Sabhika', '01/11', 'Nardiansyah', 'P', '2021-01-20', 50, 16, 100, 'M'],
            ['Tsabina A.L', '03/11', 'Astu', 'P', '2022-03-29', 49, 16.2, 96, 'M'],
            ['Ganesti', '02/11', 'Adi Rasa', 'L', '2022-06-03', 48, 14.7, 96, 'M'],
            ['Ryuga A.H', '02/11', 'Rahmat', 'L', '2022-05-21', 47, 12.6, 95, 'TE'],
            ['Anindya Zhea', '02/11', 'Asysyam', 'P', '2022-01-20', 46, 10.2, null, 'TE'],
            ['Mikael', '01/11', 'Winson', 'L', '2022-04-03', 46, 14.2, 96, 'Tt'],
            ['Arka', '02/11', 'Suherman', 'L', '2022-06-21', 46, 13, 90, '-'],
            ['Khaizanu', '03/11', 'Heriyawan', 'L', '2022-05-24', 46, 16.2, 96, '-'],
            ['Fatimah Yomi', '01/11', 'A. Akteon', 'P', '2022-05-22', 46, 15.8, 105, 'TE'],
            ['Barrea Chazada', '01/11', 'Ali Baba', 'P', '2025-09-06', 46, null, null, '-'],
            ['Arfan Sidqi', '03/11', 'Jodi', 'L', '2022-06-29', 46, 15.6, 100, '-'],
            ['Mabila Warna', '01/11', 'Adri M.S', 'P', '2022-07-03', 44, 11.8, 90, 'Th'],
            ['Adeela Fida', '04/11', 'Ujang', 'P', '2022-07-07', 44, 13.5, 94, 'TH'],
            ['Mahira N.P.E.A', '02/11', 'Duta A', 'P', '2022-07-13', 44, 16.5, 100, '-'],
            ['A. Zafran U.R', '09/11', 'Ryan R.R', 'L', '2022-08-16', 43, null, null, '-'],
            ['Raty Raline VCB', '02/11', 'Bachtiar', 'P', '2022-12-07', 39, 14.3, 95, 'R'],
            ['Mikhayla A.F', '03/11', 'Catur', 'P', '2022-02-26', 38, 12.4, 91, 'M'],
            ['Safia', '02/11', 'Wisnu', 'P', '2023-05-01', 37, 17.7, 97, 'TE'],
            ['Moriel N.P.A', '02/11', 'Nuel K', 'P', '2023-03-25', 36, 10.7, 91, '-'],
            ['Albiru', '01/11', 'Ariestya', 'L', '2023-03-25', 36, 12.9, 83, '-'],
            ['Shaynala', '03/11', 'Syahmi', 'P', '2023-04-10', 35, 12.9, 92, 'TR'],
            ['M. Azzumar', '02/11', 'M. Kamaludin', 'L', '2023-04-16', 35, 11, 84, 'TE'],
            ['Adi Tama', '03/11', 'Dodi', 'L', '2023-04-22', 35, 13.8, 92, 'P'],
            ['Calvin Zane', '04/11', 'Ananda', 'L', '2023-05-22', 34, 12.8, 92, 'T'],
            ['Mikail', '02/11', 'Oki', 'L', '2024-04-22', 23, 11.3, 85, 'Tt'],
            ['Argawira A.A', '02/11', 'Aldifa', 'L', '2024-05-17', 22, 11.9, 84, 'TR'],
            ['M. Albifarzan', '01/11', 'M. Yuda', 'L', '2024-08-06', 19, 9.9, 75, 'TE'],
            ['Abhipraya A', '02/11', 'Arief S.S', 'L', '2024-08-20', 19, 10.2, 75, 'Tt'],
            ['M. Maulia R', '03/11', 'Era K', 'L', '2024-09-05', 18, 9.1, 72, 'TE'],
            ['Elisa Shanum', '01/11', 'Al Putra', 'P', '2024-09-23', 18, 9.3, 74, 'TE'],
            ['Alena Shalihah', '04/11', 'Ghema P', 'P', '2024-10-03', 17, 14, 77, 'T'],
            ['Haura R.R', '04/11', 'Ridwan', 'P', '2024-10-04', 17, 8.8, 73, 'Tt'],
            ['Barra Al Fatih', '01/11', 'Wiwit A', 'L', '2024-10-01', 17, 9.3, 81, 'H'],
            ['Soca M.N', '01/11', 'Van B.P', 'P', '2025-03-05', 12, 14.5, 72, 'N'],
            ['Mistiael V.P.A', '02/11', 'Muel K', 'P', '2025-03-24', 12, 9.4, 74, '-'],
            ['M. Zaid U', '03/11', 'Harry L', 'L', '2025-06-20', 9, 9.6, 73, 'T'],
            ['Raisa Amarilia', '02/11', 'Aditya R', 'P', '2025-06-12', 9, 7.5, 66, 'N'],
            ['Lanang V.A', '04/11', 'Joko', 'L', '2025-06-28', 9, null, 70, 'Tt'],
            ['Grace Veliora', '02/11', 'Bachtiar', 'P', '2025-07-26', 8, 6.5, 65, '-'],
            ['M. Zacky', '02/11', 'M. Asysyam', 'L', '2025-08-01', 7, 8.4, 68, 'Tt'],
        ];

        // Seed data Februari
        $this->seedSheetData($febDate, $febData, $posyanduId, $calculator);

        // Seed data Maret
        $this->seedSheetData($marDate, $marData, $posyanduId, $calculator);
    }

    private function seedSheetData(string $visitDate, array $dataset, int $posyanduId, NutritionCalculatorService $calculator)
    {
        $carbonVisitDate = Carbon::parse($visitDate);

        foreach ($dataset as $item) {
            $name = $item[0];
            $rtRw = $item[1];
            $ortu = $item[2];
            $gender = $item[3] === 'L' ? 'L' : 'P';
            $birthDate = $item[4];
            $ageMonths = $item[5];
            $weight = $item[6];
            $height = $item[7];
            $weightStatus = $item[8];

            // Cek apakah pasien sudah ada di database (case-insensitive)
            $patient = Patient::where('full_name', 'like', $name)->first();

            // Jika belum ada, buat baru
            if (!$patient) {
                // Tentukan tanggal lahir jika tidak ada datanya
                if (!$birthDate) {
                    $birthDate = $carbonVisitDate->copy()->subMonths($ageMonths)->format('Y-m-d');
                }

                $patient = Patient::create([
                    'full_name' => $name,
                    'category' => 'balita',
                    'gender' => $gender,
                    'birth_date' => $birthDate,
                    'mother_name' => $ortu,
                    'dusun_rt_rw' => $rtRw,
                    'weight_at_birth' => 3.0,
                    'height_at_birth' => 50.0,
                    'status_mutasi' => 'aktif',
                    'posyandu_id' => $posyanduId,
                    'id_number' => \Faker\Factory::create('id_ID')->unique()->numerify('################'),
                    'address' => 'RT/RW ' . $rtRw
                ]);
            } else {
                // Update data opsional jika ada
                if ($birthDate) {
                    $patient->update(['birth_date' => $birthDate]);
                }
                if ($ortu && !$patient->mother_name) {
                    $patient->update(['mother_name' => $ortu]);
                }
            }

            // Hitung umur aktual dalam bulan untuk kalkulasi gizi
            $actualBirthDate = Carbon::parse($patient->birth_date);
            $actualAgeMonths = max(0, (int) $actualBirthDate->diffInMonths($carbonVisitDate));

            // Jika data berat badan valid, hitung status gizinya
            $nutritionData = [
                'nutrition_status' => 'Gizi Baik', // default sesuai instruksi user
                'z_score' => null,
                'stunting_status' => 'Normal',
                'z_score_hfa' => null,
                'wasting_status' => 'Gizi Baik',
                'z_score_wfh' => null,
                'z_score_bfa' => null,
            ];

            if ($weight > 0) {
                $calcHeight = $height > 0 ? $height : 0;
                $result = $calculator->calculateAll($weight, $calcHeight, $actualAgeMonths, $patient->gender);
                
                $nutritionData = [
                    'nutrition_status' => $result->nutrition_status,
                    'z_score' => $result->z_score,
                    'stunting_status' => $result->stunting_status,
                    'z_score_hfa' => $result->z_score_hfa,
                    'wasting_status' => $result->wasting_status,
                    'z_score_wfh' => $result->z_score_wfh,
                    'z_score_bfa' => $result->z_score_bfa,
                ];
            }

            // Jika berat badan kosong/tidak diukur (null), record medis tidak perlu dibuat atau buat kosong?
            // Biasanya di posyandu kalau tidak hadir/tidak diukur tidak dicatat rekam medisnya.
            // Tapi jika ada status penimbangan, kita buat rekam medis kosong. Namun jika tidak ada berat badan,
            // grafik tidak akan memplot. Lebih aman jika kita rekam hanya jika berat badan > 0.
            if ($weight > 0) {
                MedicalRecord::updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                        'visit_date' => $visitDate,
                    ],
                    array_merge([
                        'weight' => $weight,
                        'height' => (float)($height ?? 0),
                        'weight_status' => $weightStatus,
                        'complaint' => '-',
                        'health_note' => '-',
                        'diagnosis' => '-',
                        'user_id' => 1
                    ], $nutritionData)
                );
            }
        }
    }
}
