<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\System;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        System::create([
            'name' => 'زانکۆلاین',
        ]);
        System::create([
            'name' => 'پاراڵیل',
        ]);
        System::create([
            'name' => 'ئێواران',
        ]);
    }
}
