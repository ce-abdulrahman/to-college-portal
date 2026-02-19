<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Department;
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

        $this->call([
            SystemSeeder::class,
            ProvinceSeeder::class,
            UniversitySeeder::class,
            CollegeSeeder::class,
            DepartmentsSeeder::class,
            MbtiQuestionsSeeder::class,
            AIQuestionsSeeder::class,
            SettingsSeeder::class,
        ]);

        User::create([
            'name' => 'ئەندازیار عبدالرحمن',
            'code' => '100',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'rand_code' => 0,
            'phone' => '07504342452',
            'status' => 1,
        ]);

        $this->updateDepartmentWeights();
    }

    private function updateDepartmentWeights()
    {
        $departments = Department::all();
        $questions = \App\Models\AIQuestion::all();

        foreach ($questions as $question) {
            $weights = [];

            // پەیوەندی نێوان پرسیار و بەشەکان
            foreach ($departments as $department) {
                $score = $this->calculateWeightScore($question, $department);
                if ($score > 0) {
                    $weights[$department->id] = $score;
                }
            }

            $question->department_weights = json_encode($weights);
            $question->save();
        }
    }

    private function calculateWeightScore($question, $department)
    {
        $questionText = strtolower($question->question_ku);
        $deptName = strtolower($department->name);
        $score = 0;

        // پەیوەندی بەپێی جۆری پرسیار
        switch ($question->category) {
            case 'interest':
                if (str_contains($questionText, 'پزیشکی') && (str_contains($deptName, 'پزیشکی') || str_contains($deptName, 'ددان') || str_contains($deptName, 'پەرستاری'))) {
                    $score = 20;
                }
                // ... بە هەمان شێوە بۆ جۆرەکانی تر
                break;

            case 'personality':
                // پەیوەندیەکانی جۆری کەسی
                break;

            case 'location':
                // پەیوەندیەکانی شوێن
                break;

            case 'priority':
                // پەیوەندیەکانی پێشەنگی
                break;
        }

        return $score;
    }
}
