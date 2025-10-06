<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Province::create([
            'name' => 'هەولێر',
        ]);
        Province::create([
            'name' => 'سلێمانی',
        ]);
        Province::create([
            'name' => 'دهۆک',
        ]);
        Province::create([
            'name' => 'هەلەبجە',
        ]);
    }
}
