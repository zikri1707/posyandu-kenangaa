<?php

namespace Database\Factories;

use App\Models\Pedukuhan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedukahanFactory extends Factory
{
    protected $model = Pedukuhan::class;

    public function definition(): array
    {
        return [
            'name' => 'Pedukuhan '.fake()->city(),
            'postal_code' => fake()->postcode(),
            'geo_location' => fake()->latitude().','.fake()->longitude(),
        ];
    }
}
