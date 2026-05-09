<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'PT Karya Sentosa',
                'pic' => 'Dewi Lestari',
                'no_pic' => '081234567803',
                'alamat' => 'Semarang',
                'penagihan' => 'Rian Pratama',
                'no_penagihan' => '081234567813',
            ],
            [
                'name' => 'PT Nusantara Logistik',
                'pic' => 'Agus Salim',
                'no_pic' => '081234567804',
                'alamat' => 'Surabaya',
                'penagihan' => 'Lina Amelia',
                'no_penagihan' => '081234567814',
            ],
            [
                'name' => 'PT Bintang Timur',
                'pic' => 'Yoga Prasetyo',
                'no_pic' => '081234567805',
                'alamat' => 'Yogyakarta',
                'penagihan' => 'Siska Dewanti',
                'no_penagihan' => '081234567815',
            ],
            [
                'name' => 'PT Cahaya Baru',
                'pic' => 'Maya Sari',
                'no_pic' => '081234567806',
                'alamat' => 'Bekasi',
                'penagihan' => 'Riko Saputra',
                'no_penagihan' => '081234567816',
            ],
            [
                'name' => 'PT Anugerah Mandiri',
                'pic' => 'Fajar Nugroho',
                'no_pic' => '081234567807',
                'alamat' => 'Depok',
                'penagihan' => 'Nina Karlina',
                'no_penagihan' => '081234567817',
            ],
            [
                'name' => 'PT Global Transport',
                'pic' => 'Rahmat Hidayat',
                'no_pic' => '081234567808',
                'alamat' => 'Bogor',
                'penagihan' => 'Indah Permata',
                'no_penagihan' => '081234567818',
            ],
            [
                'name' => 'PT Sumber Makmur',
                'pic' => 'Hendra Wijaya',
                'no_pic' => '081234567809',
                'alamat' => 'Malang',
                'penagihan' => 'Doni Kurniawan',
                'no_penagihan' => '081234567819',
            ],
            [
                'name' => 'PT Prima Cargo',
                'pic' => 'Ayu Wulandari',
                'no_pic' => '081234567810',
                'alamat' => 'Medan',
                'penagihan' => 'Bambang Setiawan',
                'no_penagihan' => '081234567820',
            ],
        ];

        foreach ($data as $item) {
            DB::table('pt_table')->insert([
                ...$item,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
