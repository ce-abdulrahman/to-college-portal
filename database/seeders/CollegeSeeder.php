<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\College;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        College::create([
            'university_id' => 1,
            'name' => 'کۆلێژی ئەندازیاری',
        ]);
        College::create([
            'university_id' => 1,
            'name' => 'کۆلێژی زانست',
        ]);
    }
}
