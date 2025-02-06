<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'phone_number' => '080987654321',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'produksi',
                'email' => 'produksi@gmail.com',
                'phone_number' => '081234567890',
                'role' => 'produksi',
                'password' => Hash::make('produksi123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'marketing',
                'email' => 'marketing@gmail.com',
                'phone_number' => '082345678901',
                'role' => 'marketing',
                'password' => Hash::make('marketing123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);        
    }
}
