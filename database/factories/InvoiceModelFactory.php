<?php

namespace Database\Factories;

use App\Models\PtModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nomor_invoice' => fake()->unique()->numerify('###') . '/INV/7084/' . fake()->randomElement(['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII']) . '/' . now()->year,
            'tanggal_invoice' => fake()->date(),
            'pt_id' => PtModel::factory(),
            'nominal_invoice' => fake()->numberBetween(1000000, 50000000),
            'nominal_cair' => null,
            'status' => 'pending',
            'tanggal_cair' => null,
            'signature_id' => null,
            'generated_by' => User::factory(),
            'verification_token' => fake()->unique()->uuid(),
        ];
    }
}
