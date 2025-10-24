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
            'name_en' => 'Salahaddin University',
            'lng' => 44.016482,
            'lat' => 36.164002,
            'geojson' => '{
  "type": "Polygon",
  "coordinates": [[
    [44.015761, 36.164786],
    [44.016705, 36.164830],
    [44.017011, 36.163292],
    [44.016088, 36.163166]
  ]]
}',
        ]);

        University::create([
            'province_id' => 1,
            'name' => 'زانکۆی هەولێری پزیشکی',
            'name_en' => 'Erbil Medical University',
            'lng' => 44.016152,
            'lat' => 36.159944,
            'geojson' =>'',
        ]);

        University::create([
            'province_id' => 1,
            'name' => 'زانکۆی کۆیە',
            'name_en' => 'Koya University',
            'lng' => 44.654853,
            'lat' => 36.097275,
            'geojson' => '{
                "type": "Polygon",
                "coordinates": [[
                    [44.661296, 36.093464],
                    [44.659612, 36.095839],
                    [44.656693, 36.097625],
                    [44.652273, 36.100806],
                    [44.647542, 36.102792],
                    [44.646072, 36.100373],
                    [44.645506, 36.098713],
                    [44.645806, 36.098445],
                    [44.646750, 36.098978],
                    [44.646858, 36.098878],
                    [44.648767, 36.099923],
                    [44.649502, 36.099091],
                    [44.647625, 36.097946],
                    [44.649272, 36.096104],
                    [44.649100, 36.096000],
                    [44.649588, 36.095510],
                    [44.653056, 36.097400],
                    [44.658281, 36.092189]
                ]]
            }',
        ]);
        University::create([
            'province_id' => 1,
            'name' => 'زانکۆی سۆران',
            'name_en' => 'Soran University',
            'lng' => 44.016152,
            'lat' => 36.159944,
            'geojson' =>'',
        ]);
        University::create([
            'province_id' => 1,
            'name' => 'زانکۆی پۆلەتەکنیکی هەولێر',
            'name_en' => 'Erbil Technical University',
            'lng' => 44.016152,
            'lat' => 36.159944,
            'geojson' => '{
                            "type": "Polygon",
                            "coordinates": [
                                [
                                    [44.016522, 36.159771],
                                    [44.016142, 36.161443],
                                    [44.014929, 36.161378],
                                    [44.014956, 36.161313],
                                    [44.015541, 36.161062],
                                    [44.015391, 36.16065],
                                    [44.015616, 36.159654]
                                ]
                            ]
                        }'

        ]);
    }
}
