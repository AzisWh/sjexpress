<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// import model db

class UserSeeds extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('admin@gmail.com'),
            ],
            [
                'name' => 'Super',
                'email' => 'super@gmail.com',
                'role' => 'superadmin',
                'password' => Hash::make('super@gmail.com'),
            ],
        ]);

    }
}
