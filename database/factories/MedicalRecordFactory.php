<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalRecordFactory extends Factory
{
    protected $model = MedicalRecord::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'user_id' => User::factory(),
            'visit_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'weight' => fake()->randomFloat(2, 3.0, 100.0),
            'height' => fake()->randomFloat(2, 50.0, 180.0),
            'measurement_method' => fake()->randomElement(['recumbent', 'standing']),
            'head_circumference' => fake()->randomFloat(2, 30.0, 60.0),
            'immunization' => fake()->randomElement(['BCG', 'DPT', 'Polio', 'Campak', 'Hepatitis B', '']),
            'vitamin_a' => fake()->boolean(),
            'pill_fe' => fake()->boolean(),
            'complaint' => fake()->sentence(),
            'diagnosis' => fake()->sentence(),
            'nutrition_status' => fake()->randomElement(['Gizi Baik', 'Gizi Kurang', 'Gizi Lebih', 'Gizi Buruk']),
            'z_score' => fake()->randomFloat(2, -4.0, 3.0),
            'nutrition_trend' => fake()->randomElement(['naik', 'turun', 'tetap']),
        ];
    }

    public function withNutritionStatus(): static
    {
        return $this->state(fn (array $attributes) => [
            'nutrition_status' => fake()->randomElement(['Normal', 'Gizi Kurang', 'Gizi Lebih', 'Gizi Buruk/Stunting']),
            'z_score' => fake()->randomFloat(2, -4.0, 3.0),
            'nutrition_trend' => fake()->randomElement(['naik', 'turun', 'tetap']),
        ]);
    }
}
