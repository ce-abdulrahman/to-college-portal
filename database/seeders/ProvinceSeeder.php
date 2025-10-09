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
            'geojson' => '{
  "type": "Polygon",
  "coordinates": [[
    [43.80, 36.20],
    [44.20, 36.20],
    [44.20, 36.50],
    [43.80, 36.50],
    [43.80, 36.20]
  ]]
}',
        ]);
        Province::create([
            'name' => 'سلێمانی',
            'geojson' => '{
  "type": "Polygon",
  "coordinates": [[
    [45.20, 35.40],
    [45.80, 35.40],
    [45.80, 35.80],
    [45.20, 35.80],
    [45.20, 35.40]
  ]]
}
',
        ]);
        Province::create([
            'name' => 'دهۆک',
            'geojson' => '{
  "type": "Polygon",
  "coordinates": [[
    [42.80, 36.70],
    [43.30, 36.70],
    [43.30, 37.10],
    [42.80, 37.10],
    [42.80, 36.70]
  ]]
}
',
        ]);
        Province::create([
            'name' => 'هەلەبجە',
            'geojson' => '{
  "type": "Polygon",
  "coordinates": [[
    [45.80, 35.00],
    [46.20, 35.00],
    [46.20, 35.30],
    [45.80, 35.30],
    [45.80, 35.00]
  ]]
}
',
        ]);
    }
}
