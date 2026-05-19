<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArmadaModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_armada' => fake()->randomElement(['Tronton', 'Pick Up', 'Truk Box', 'Container', 'CDE', 'Fuso', 'Engkel']),
            'plat_nomor' => 'B ' . fake()->numberBetween(1000, 9999) . ' ' . fake()->randomLetter() . fake()->randomLetter() . fake()->randomLetter(),
            'foto_armada' => null,
        ];
    }
}
