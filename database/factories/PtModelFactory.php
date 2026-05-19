<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PtModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'pic' => fake()->name(),
            'no_pic' => '08' . fake()->numerify('##########'),
            'alamat' => fake()->address(),
            'penagihan' => fake()->optional()->name(),
            'no_penagihan' => fake()->optional()->numerify('08##########'),
        ];
    }
}
