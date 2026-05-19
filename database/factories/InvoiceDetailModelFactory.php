<?php

namespace Database\Factories;

use App\Models\InvoiceModel;
use App\Models\PengirimanModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceDetailModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_id' => InvoiceModel::factory(),
            'pengiriman_id' => PengirimanModel::factory(),
        ];
    }
}
