<?php

namespace Database\Factories;

use App\Models\ArmadaModel;
use App\Models\DriverModel;
use App\Models\PtModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengirimanModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pt_id' => PtModel::factory(),
            'armada_id' => ArmadaModel::factory(),
            'driver_id' => DriverModel::factory(),
            'tanggal_ambil' => fake()->date(),
            'rute_from' => fake()->city(),
            'rute_to' => fake()->city(),
            'harga_pabrik' => fake()->numberBetween(100000, 5000000),
            'harga_armada' => fake()->numberBetween(50000, 3000000),
            'keterangan' => fake()->optional()->sentence(),
        ];
    }
}
