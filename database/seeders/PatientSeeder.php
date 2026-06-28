<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Posyandu;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $kenanga1 = Posyandu::where('unique_code', 'PSY003')->firstOrFail();
        $kenanga2 = Posyandu::where('unique_code', 'PSY002')->firstOrFail();

        $patients = [
            [
                'posyandu_id' => $kenanga1->id,
                'category' => 'lansia',
                'id_number' => '3275011704550020',
                'full_name' => 'H. Amri',
                'birth_date' => '1955-04-17',
                'gender' => 'L',
                'address' => 'Aren Jaya, RT 01',
                'status_mutasi' => 'aktif',
            ],
            [
                'posyandu_id' => $kenanga1->id,
                'category' => 'lansia',
                'id_number' => '3275015905630012',
                'full_name' => 'Meita Indriati',
                'birth_date' => '1963-05-19',
                'gender' => 'P',
                'address' => 'Aren Jaya, RT 02',
                'status_mutasi' => 'aktif',
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
