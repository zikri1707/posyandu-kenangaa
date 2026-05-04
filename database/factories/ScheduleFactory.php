<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        $startTime = fake()->dateTimeBetween('-1 month', '+1 month');
        $endTime = (clone $startTime)->modify('+2 hours');

        return [
            'posyandu_id' => Posyandu::factory(),
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'location' => fake()->address(),
            'status' => fake()->randomElement(['Pending', 'Completed', 'Cancelled']),
        ];
    }
}
