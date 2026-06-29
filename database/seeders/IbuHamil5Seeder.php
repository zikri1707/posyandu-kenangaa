<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\MedicalRecord;
use Carbon\Carbon;
use Faker\Factory as Faker;

class IbuHamil5Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $posyanduIds = \App\Models\Posyandu::pluck('id')->toArray();
        if (empty($posyanduIds)) {
            $posyanduIds = [1];
        }

        // --- 5 Ibu Hamil ---
        for ($i = 0; $i < 5; $i++) {
            $hpht = Carbon::now()->subMonths(rand(1, 8))->subDays(rand(1, 20));
            $age = rand(18, 40);
            
            $patient = Patient::create([
                'id_number' => $faker->unique()->numerify('################'),
                'full_name' => $faker->name('female'),
                'birth_date' => Carbon::now()->subYears($age)->subDays(rand(1, 365)),
                'gender' => 'P',
                'category' => 'ibu_hamil',
                'posyandu_id' => $faker->randomElement($posyanduIds),
                'status_mutasi' => 'aktif',
                'hpht' => $hpht->format('Y-m-d'),
                'address' => $faker->address,
            ]);

            // Add 1-6 medical records for this year
            $visitCount = rand(1, 6);
            for ($v = 0; $v < $visitCount; $v++) {
                MedicalRecord::create([
                    'patient_id' => $patient->id,
                    'user_id' => \App\Models\User::first()->id ?? 1,
                    'visit_date' => Carbon::now()->subMonths($v)->subDays(rand(1, 5)),
                    'weight' => rand(50, 80) + (rand(0, 9) / 10),
                    'height' => rand(145, 170),
                    'systolic_bp' => rand(100, 140),
                    'diastolic_bp' => rand(70, 90),
                    'hemoglobin' => rand(9, 13) + (rand(0, 9) / 10),
                    'gestational_age' => rand(4, 38),
                    'nakes_gives_fe_mms' => rand(0, 1),
                    'complaint' => '-',
                    'health_note' => '-',
                    'diagnosis' => '-',
                ]);
            }
        }
    }
}
