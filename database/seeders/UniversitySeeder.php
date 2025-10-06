<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\University;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        University::create([
            'province_id' => 1,
            'name' => 'زانکۆی سەڵاحەددین',
        ]);
        University::create([
            'province_id' => 1,
            'name' => 'زانکۆی هەولێری پزیشکی',
        ]);
        University::create([
            'province_id' => 1,
            'name' => 'زانکۆی کۆیە',
        ]);
        University::create([
            'province_id' => 1,
            'name' => 'زانکۆی سۆران',
        ]);
        University::create([
            'province_id' => 1,
            'name' => 'زانکۆی پۆلەتەکنیکی هەولێر',
        ]);
    }
}
