<?php

namespace Database\Factories;

use App\Models\Posyandu;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosyanduFactory extends Factory
{
    protected $model = Posyandu::class;

    public function definition(): array
    {
        return [
            'pedukuhan_id' => \App\Models\Pedukuhan::factory(),
            'name' => 'Posyandu '.fake()->city(),
            'address' => fake()->address(),
            'unique_code' => 'POS'.fake()->unique()->numberBetween(1000, 9999),
            'logo_photo' => null,
        ];
    }
}
