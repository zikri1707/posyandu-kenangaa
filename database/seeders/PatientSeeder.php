<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientSeeder extends Seeder
{
    public function run()
    {
        DB::table('patients')->insert([
            [
                'posyandu_id' => 1,
                'full_name' => 'Ahmad Fauzi',
                'id_number' => '1234567890123456',
                'birth_date' => '2018-05-10',
                'gender' => 'M',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'phone_number' => '081234567890',
                'profile_photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posyandu_id' => 1,
                'full_name' => 'Siti Aminah',
                'id_number' => '1234567890123457',
                'birth_date' => '2019-08-15',
                'gender' => 'F',
                'address' => 'Jl. Sudirman No. 20, Bandung',
                'phone_number' => '081298765432',
                'profile_photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posyandu_id' => 2,
                'full_name' => 'Budi Santoso',
                'id_number' => '1234567890123458',
                'birth_date' => '2017-12-01',
                'gender' => 'M',
                'address' => 'Jl. Diponegoro No. 5, Surabaya',
                'phone_number' => '081212345678',
                'profile_photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posyandu_id' => 2,
                'full_name' => 'Dewi Lestari',
                'id_number' => '1234567890123459',
                'birth_date' => '2018-03-22',
                'gender' => 'F',
                'address' => 'Jl. Gatot Subroto No. 15, Yogyakarta',
                'phone_number' => '081223344556',
                'profile_photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posyandu_id' => 3,
                'full_name' => 'Eko Prasetyo',
                'id_number' => '1234567890123460',
                'birth_date' => '2019-11-30',
                'gender' => 'M',
                'address' => 'Jl. Ahmad Yani No. 8, Semarang',
                'phone_number' => '081234443322',
                'profile_photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
