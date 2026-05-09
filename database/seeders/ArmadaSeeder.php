<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArmadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_armada' => 'Truk Colt Diesel', 'plat_nomor' => 'B 1234 ABC'],
            ['nama_armada' => 'Truk Fuso', 'plat_nomor' => 'B 2234 DEF'],
            ['nama_armada' => 'Pickup L300', 'plat_nomor' => 'B 3234 GHI'],
            ['nama_armada' => 'Wingbox', 'plat_nomor' => 'B 4234 JKL'],
            ['nama_armada' => 'Trailer', 'plat_nomor' => 'B 5234 MNO'],
            ['nama_armada' => 'Box Engkel', 'plat_nomor' => 'B 6234 PQR'],
            ['nama_armada' => 'Pickup Grandmax', 'plat_nomor' => 'B 7234 STU'],
            ['nama_armada' => 'Tronton', 'plat_nomor' => 'B 8234 VWX'],
            ['nama_armada' => 'CDE Box', 'plat_nomor' => 'B 9234 YZA'],
            ['nama_armada' => 'CDD Bak', 'plat_nomor' => 'B 1034 BCD'],
        ];

        foreach ($data as $item) {
            DB::table('armada_table')->insert([
                ...$item,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
