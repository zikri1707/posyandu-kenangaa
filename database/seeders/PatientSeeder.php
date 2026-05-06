<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Posyandu;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $melati = Posyandu::where('unique_code', 'PSY001')->firstOrFail();
        $mawar = Posyandu::where('unique_code', 'PSY002')->firstOrFail();
        $anggrek = Posyandu::where('unique_code', 'PSY003')->firstOrFail();

        $patients = [
            // ── Posyandu Melati ──────────────────────────────────────────────
            [
                'posyandu_id' => $melati->id,
                'category' => 'balita',
                'parent_name' => 'Bapak Fauzi',
                'id_number' => '1234567890123456',
                'full_name' => 'Ahmad Fauzi',
                'birth_date' => '2021-05-10',
                'gender' => 'L',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'phone_number' => '081234567890',
                'profile_photo' => null,
            ],
            [
                'posyandu_id' => $melati->id,
                'category' => 'balita',
                'parent_name' => 'Ibu Aminah',
                'id_number' => '1234567890123457',
                'full_name' => 'Siti Aminah',
                'birth_date' => '2022-08-15',
                'gender' => 'P',
                'address' => 'Jl. Sudirman No. 20, Bandung',
                'phone_number' => '081298765432',
                'profile_photo' => null,
            ],
            [
                'posyandu_id' => $melati->id,
                'category' => 'balita',
                'parent_name' => 'Ibu Rahayu',
                'id_number' => '1234567890123461',
                'full_name' => 'Rizky Rahayu',
                'birth_date' => '2023-02-20',
                'gender' => 'L',
                'address' => 'Jl. Merdeka No. 5, Jakarta',
                'phone_number' => '081234000001',
                'profile_photo' => null,
            ],
            // ── Posyandu Mawar ───────────────────────────────────────────────
            [
                'posyandu_id' => $mawar->id,
                'category' => 'balita',
                'parent_name' => 'Bapak Santoso',
                'id_number' => '1234567890123458',
                'full_name' => 'Budi Santoso',
                'birth_date' => '2020-12-01',
                'gender' => 'L',
                'address' => 'Jl. Diponegoro No. 5, Surabaya',
                'phone_number' => '081212345678',
                'profile_photo' => null,
            ],
            [
                'posyandu_id' => $mawar->id,
                'category' => 'balita',
                'parent_name' => 'Bapak Lestari',
                'id_number' => '1234567890123459',
                'full_name' => 'Dewi Lestari',
                'birth_date' => '2021-03-22',
                'gender' => 'P',
                'address' => 'Jl. Gatot Subroto No. 15, Yogyakarta',
                'phone_number' => '081223344556',
                'profile_photo' => null,
            ],
            [
                'posyandu_id' => $mawar->id,
                'category' => 'balita',
                'parent_name' => 'Ibu Wulandari',
                'id_number' => '1234567890123462',
                'full_name' => 'Nadia Wulandari',
                'birth_date' => '2022-06-10',
                'gender' => 'P',
                'address' => 'Jl. Mawar No. 8, Surabaya',
                'phone_number' => '081234000002',
                'profile_photo' => null,
            ],
            // ── Posyandu Anggrek ─────────────────────────────────────────────
            [
                'posyandu_id' => $anggrek->id,
                'category' => 'balita',
                'parent_name' => 'Bapak Prasetyo',
                'id_number' => '1234567890123460',
                'full_name' => 'Eko Prasetyo',
                'birth_date' => '2022-11-30',
                'gender' => 'L',
                'address' => 'Jl. Ahmad Yani No. 8, Semarang',
                'phone_number' => '081234443322',
                'profile_photo' => null,
            ],
            [
                'posyandu_id' => $anggrek->id,
                'category' => 'balita',
                'parent_name' => 'Ibu Kartini',
                'id_number' => '1234567890123463',
                'full_name' => 'Putri Kartini',
                'birth_date' => '2023-04-05',
                'gender' => 'P',
                'address' => 'Jl. Anggrek No. 3, Semarang',
                'phone_number' => '081234000003',
                'profile_photo' => null,
            ],
        ];

        foreach ($patients as $data) {
            Patient::updateOrCreate(
                ['id_number' => $data['id_number']],
                $data
            );
        }
    }
}
