<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DriverModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'no_telp' => '08' . fake()->numerify('##########'),
        ];
    }
}
