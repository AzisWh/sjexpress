<?php

namespace Database\Factories;

use App\Models\PengirimanModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class FotoPengirimanModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pengiriman_id' => PengirimanModel::factory(),
            'file_path' => 'SuratJalan/' . fake()->uuid() . '.jpg',
        ];
    }
}
