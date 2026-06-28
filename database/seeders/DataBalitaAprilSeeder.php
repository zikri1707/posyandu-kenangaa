<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Posyandu;

class DataBalitaAprilSeeder extends Seeder
{
    public function run()
    {
        // Data hasil ekstraksi dari gambar (Tabel Balita 1 April 2026)
        $data = [
            // [Nama Balita, Nama Ortu, RT/RW, L/P, Tanggal Lahir, BB, TB, Indikator, Ket]
            ['M. Said', 'Ragil', '03/11', 'L', '2021-04-20', 16, 98, 'N', ''],
            ['Vallera Darren', 'Usman', '02/11', 'L', '2021-04-23', 15.5, 108, 'T', ''],
            ['Salwa D. 2', 'Heri S', '01/11', 'P', '2021-06-29', 32.7, 111, 'T', ''],
            ['Nita Noel', 'Marphan', '01/11', 'P', '2021-02-21', 19, 93, 'N', ''],
            ['Sabrina', 'Murdiansyah', '01/11', 'P', '2021-09-29', 16.8, 97, 'T', ''],
            ['Isabina A.L', 'Astu K.2', '03/11', 'P', '2021-06-20', 15.2, 97, 'N', ''],
            ['Ganesh M.D', 'Adi Rasa', '01/11', 'L', '2022-01-06', 12.8, 95, 'T', ''],
            ['Ryuga A.H', 'Rahmat T', '02/11', 'L', '2022-02-03', 16, 102, 'N', ''],
            ['Anindya Shea', 'M. Asysyam', '02/11', 'P', '2022-03-27', 15.7, 100, 'T', ''],
            ['Mikael', 'Winson', '01/11', 'L', '2022-04-03', 14.5, 99, 'T', ''],
            ['Arka R', 'Suherman', '03/11', 'L', '2022-05-21', 13.3, 92, 'N', ''],
            ['Khaizanu', 'Heniyawan', '03/11', 'L', '2022-06-21', 17.8, 98, 'N', ''],
            ['Fatimah Yumi', 'A. Akbar', '01/11', 'L', '2022-05-20', null, null, null, 'Pindah'],
            ['Barrea Ghazala', 'Ali Baba', '01/11', 'P', '2022-05-06', 16, 105, 'N', ''],
            ['Arfan Sidai', 'Jadi', '01/11', 'P', '2022-05-20', 11, 100, 'N', ''],
            ['Nabila Warna', 'Adri M.S', '01/11', 'P', '2022-04-03', 11.9, 90, 'T', ''],
            ['Assela Fida', 'Ujang', '04/11', 'P', '2022-04-07', 13.8, 95, 'T', ''],
            ['A. Zafran U.R', 'Riban R.R', '01/11', 'L', '2022-06-06', 16.7, 101, 'T', ''],
            ['Ratu Raline VCB', 'Bachtiar', '02/11', 'P', '2022-02-01', 14.3, 95, 'T', ''],
            ['Michayla A.F', 'Datvr', '03/11', 'P', '2022-06-26', 12.1, 91, 'T', ''],
            ['Safia', 'Wisnu', '03/10', 'L', '2023-01-05', 18, 97, 'N', ''],
            ['Moriel N.P.A', 'Miel K', '03/11', 'L', '2023-03-25', 11.3, 93, 'T', ''],
            ['Albira', 'Anestya', '01/11', 'P', '2023-03-02', 13, 93, 'N', ''],
            ['Shaynala', 'Siahmi', '03/11', 'P', '2023-04-10', 13.3, 92, 'N', ''],
            ['M. Azzumar', 'M. Kamaludin', '02/11', 'L', '2023-04-16', 11.1, 84, 'T', ''],
            ['Adi Tama', 'Dodi F', '03/11', 'L', '2023-04-22', 13.5, 92, 'T', ''],
            ['Calvin Zane', 'Ananda', '04/11', 'L', '2023-05-22', 13.1, 92, 'N', ''],
            ['Jasmin', 'Oji', '04/11', 'P', '2023-06-04', 12.8, 78, 'T', ''],
            ['Gladis A', 'Adi Rasa', '01/11', 'P', '2023-06-21', 10.6, 86, 'T', ''],
            ['M. Ichsan A', 'Imam', '02/11', 'L', '2023-06-26', 12.7, 96, 'T', ''],
            ['Askara A', 'Arindra', '07/11', 'P', '2023-04-20', null, null, null, 'Pindah'],
            ['Azzam Rafasya', 'Irwansyah', '01/11', 'L', '2023-09-11', 11, 84, 'N', ''],
            ['M. Zidan A', 'Rapdi H', '03/11', 'L', '2023-09-10', 14.3, 87, 'T', ''],
            ['Faiza Takzia', 'Hardian', '01/11', 'P', '2023-10-15', 12.3, 89, 'T', ''],
            ['Annisa Jafran', 'Riangga', '01/11', 'P', '2023-11-09', 11.6, 85, 'T', ''],
            ['Abraham', 'Riswan', '02/11', 'L', '2024-05-20', 12.5, 89, 'T', ''],
            ['Ashraf Faisan', 'Alan F', '02/11', 'L', '2024-05-20', 11.1, 82, 'N', ''],
            ['Cerenah U.T', 'Theresa', '04/11', 'P', '2024-06-20', 12.5, 74, 'T', ''],
            ['Kirei  Hafza H', 'Rahmat T', '02/11', 'P', '2024-03-27', 14, 92, 'N', ''],
            ['Mikail', 'Oki', '02/11', 'P', '2024-04-22', 11.5, 85, 'T', ''],
        ];

        $posyandu = Posyandu::first();
        $posyanduId = $posyandu ? $posyandu->id : 1;
        $visitDate = '2026-04-01'; // 1 April 2026

        foreach ($data as $item) {
            $statusMutasi = ($item[8] === 'Pindah') ? 'pindah' : 'aktif';

            // Insert atau update Patient
            $patient = Patient::updateOrCreate(
                ['full_name' => $item[0]],
                [
                    'category' => 'balita',
                    'gender' => $item[3] === 'L' ? 'L' : 'P',
                    'birth_date' => $item[4],
                    'mother_name' => $item[1],
                    'dusun_rt_rw' => $item[2],
                    'weight_at_birth' => 3.0,
                    'height_at_birth' => 50.0,
                    'status_mutasi' => $statusMutasi,
                    'posyandu_id' => $posyanduId,
                    'id_number' => \Faker\Factory::create('id_ID')->unique()->numerify('################'),
                    'address' => 'RT/RW ' . $item[2]
                ]
            );

            // Buat record medis jika tidak pindah
            if ($statusMutasi !== 'pindah' && $item[5] !== null) {
                MedicalRecord::updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                        'visit_date' => $visitDate,
                    ],
                    [
                        'weight' => $item[5],
                        'height' => $item[6],
                        'weight_status' => $item[7],
                        'nutrition_status' => null,
                        'complaint' => '-',
                        'health_note' => '-',
                        'diagnosis' => '-',
                        'user_id' => 1
                    ]
                );
            }
        }
    }
}
