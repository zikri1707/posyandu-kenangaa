<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Posyandu;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $kenanga1 = Posyandu::where('unique_code', 'KENANGA1')->firstOrFail();
        $kenanga2 = Posyandu::where('unique_code', 'KENANGA2')->firstOrFail();

        $patients = [
            // ── KENANGA 1 ────────────────────────────────────────────────────
            [
                'posyandu_id' => $kenanga1->id,
                'category' => 'balita',
                'parent_name' => 'Bapak Fauzi',
                'id_number' => '1234567890123456',
                'full_name' => 'Ahmad Fauzi',
                'birth_date' => '2021-05-10',
                'gender' => 'L',
                'address' => 'Aren Jaya, RT 01',
                'phone_number' => '081234567890',
                'profile_photo' => null,
            ],
            [
                'posyandu_id' => $kenanga1->id,
                'category' => 'balita',
                'parent_name' => 'Ibu Aminah',
                'id_number' => '1234567890123457',
                'full_name' => 'Siti Aminah',
                'birth_date' => '2022-08-15',
                'gender' => 'P',
                'address' => 'Aren Jaya, RT 02',
                'phone_number' => '081298765432',
                'profile_photo' => null,
            ],
            // ── KENANGA 2 ────────────────────────────────────────────────────
            [
                'posyandu_id' => $kenanga2->id,
                'category' => 'balita',
                'parent_name' => 'Bapak Santoso',
                'id_number' => '1234567890123458',
                'full_name' => 'Budi Santoso',
                'birth_date' => '2020-12-01',
                'gender' => 'L',
                'address' => 'Aren Jaya, RT 03',
                'phone_number' => '081212345678',
                'profile_photo' => null,
            ],
            [
                'posyandu_id' => $kenanga2->id,
                'category' => 'balita',
                'parent_name' => 'Bapak Lestari',
                'id_number' => '1234567890123459',
                'full_name' => 'Dewi Lestari',
                'birth_date' => '2021-03-22',
                'gender' => 'P',
                'address' => 'Aren Jaya, RT 04',
                'phone_number' => '081223344556',
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
