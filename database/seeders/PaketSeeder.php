<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pakets')->insert([
            [
                'name_paket' => 'Website',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_paket' => 'Aplikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
