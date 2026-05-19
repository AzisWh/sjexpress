<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SignatureModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'signature' => 'data:image/png;base64,' . base64_encode(str_repeat('x', 100)),
        ];
    }
}
