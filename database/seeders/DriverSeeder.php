<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Joko Susilo', 'no_telp' => '081300000001'],
            ['name' => 'Bambang Riyadi', 'no_telp' => '081300000002'],
            ['name' => 'Rian Saputra', 'no_telp' => '081300000003'],
            ['name' => 'Dedi Kurniawan', 'no_telp' => '081300000004'],
            ['name' => 'Ahmad Fauzi', 'no_telp' => '081300000005'],
            ['name' => 'Rizky Maulana', 'no_telp' => '081300000006'],
            ['name' => 'Eko Prasetyo', 'no_telp' => '081300000007'],
            ['name' => 'Tono Wijaya', 'no_telp' => '081300000008'],
            ['name' => 'Dian Permana', 'no_telp' => '081300000009'],
            ['name' => 'Fahri Ramadhan', 'no_telp' => '081300000010'],
        ];

        foreach ($data as $item) {
            DB::table('driver_table')->insert([
                ...$item,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
