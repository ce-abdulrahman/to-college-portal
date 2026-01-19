<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            SystemSeeder::class,
            ProvinceSeeder::class,
            UniversitySeeder::class,
            CollegeSeeder::class,
            MbtiQuestionsSeeder::class,
        ]);


        User::create([
            'name' => 'Admin User',
            'code' => '100',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'rand_code' => Random::generate(6),
        ]);
        
    }
}
