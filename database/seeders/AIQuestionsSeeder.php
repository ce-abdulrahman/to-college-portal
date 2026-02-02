<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Department;

class AIQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();

        $questions = [
            // ========== پرسیارەکانی جۆری کەسی (Personality) ==========
            [
                'question_ku' => 'ئایا حەز دەکەیت کارەکانت پلان بکەیت و ڕێکبخەیت پێش دەستپێکردن؟',
                'question_en' => 'Do you prefer to plan and organize your tasks before starting?',
                'category' => 'personality',
                'options' => json_encode([
                    ['text' => 'بەڵێ، هەمیشە', 'score' => 100],
                    ['text' => 'زۆربەی کات', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەمێک', 'score' => 25],
                    ['text' => 'هەرگیز', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForPlanning($departments)),
                'order' => 1,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز دەکەیت لەگەڵ خەڵکدا کار بکەیت یان بە تەنها؟',
                'question_en' => 'Do you prefer working with people or alone?',
                'category' => 'personality',
                'options' => json_encode([
                    ['text' => 'تەنها کار کردن', 'score' => 0],
                    ['text' => 'زیاتر بەتەنها', 'score' => 25],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'زیاتر لەگەڵ کۆمەڵ', 'score' => 75],
                    ['text' => 'هەمیشە لەگەڵ کۆمەڵ', 'score' => 100]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForSocial($departments)),
                'order' => 2,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز دەکەیت لە کێشە چارەسەرکردندا بە شێوەیەکی لۆژیکی بیربکەیتەوە؟',
                'question_en' => 'Do you enjoy solving problems logically?',
                'category' => 'personality',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر', 'score' => 100],
                    ['text' => 'بەڵێ', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم', 'score' => 25],
                    ['text' => 'هیچ', 'score' => 0]
                ]),
                'weight' => 3,
                'department_weights' => json_encode($this->getDepartmentWeightsForLogical($departments)),
                'order' => 3,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا کەسێکی داهێنەر و خەیاڵی?',
                'question_en' => 'Are you a creative and imaginative person?',
                'category' => 'personality',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر', 'score' => 100],
                    ['text' => 'بەڵێ', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم', 'score' => 25],
                    ['text' => 'هیچ', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForCreative($departments)),
                'order' => 4,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز دەکەیت لە کارە ورد و ڕیزبەندکراوەکاندا؟',
                'question_en' => 'Do you enjoy detailed and organized work?',
                'category' => 'personality',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر', 'score' => 100],
                    ['text' => 'بەڵێ', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم', 'score' => 25],
                    ['text' => 'هیچ', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForDetail($departments)),
                'order' => 5,
                'status' => true,
            ],

            // ========== پرسیارەکانی حەز و ئارەزوو (Interest) ==========
            [
                'question_ku' => 'ئایا حەز بە بەشی پزیشکی دەکەی؟',
                'question_en' => 'Are you interested in the medical field?',
                'category' => 'interest',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر حەزێکی پێیە', 'score' => 100],
                    ['text' => 'بەڵێ، حەزێکی پێیە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم حەزێکی پێیە', 'score' => 25],
                    ['text' => 'هیچ حەزێکی پێی نییە', 'score' => 0]
                ]),
                'weight' => 3,
                'department_weights' => json_encode($this->getDepartmentWeightsForMedical($departments)),
                'order' => 1,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز بە بەشی ئەندازیاری و تەکنەلۆژیا دەکەی؟',
                'question_en' => 'Are you interested in engineering and technology?',
                'category' => 'interest',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر حەزێکی پێیە', 'score' => 100],
                    ['text' => 'بەڵێ، حەزێکی پێیە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم حەزێکی پێیە', 'score' => 25],
                    ['text' => 'هیچ حەزێکی پێی نییە', 'score' => 0]
                ]),
                'weight' => 3,
                'department_weights' => json_encode($this->getDepartmentWeightsForEngineering($departments)),
                'order' => 2,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز بە بەشی بازرگانی و بەڕێوەبردن دەکەی؟',
                'question_en' => 'Are you interested in business and management?',
                'category' => 'interest',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر حەزێکی پێیە', 'score' => 100],
                    ['text' => 'بەڵێ، حەزێکی پێیە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم حەزێکی پێیە', 'score' => 25],
                    ['text' => 'هیچ حەزێکی پێی نییە', 'score' => 0]
                ]),
                'weight' => 3,
                'department_weights' => json_encode($this->getDepartmentWeightsForBusiness($departments)),
                'order' => 3,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز بە بەشی وێژە و زمان دەکەی؟',
                'question_en' => 'Are you interested in literature and languages?',
                'category' => 'interest',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر حەزێکی پێیە', 'score' => 100],
                    ['text' => 'بەڵێ، حەزێکی پێیە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم حەزێکی پێیە', 'score' => 25],
                    ['text' => 'هیچ حەزێکی پێی نییە', 'score' => 0]
                ]),
                'weight' => 3,
                'department_weights' => json_encode($this->getDepartmentWeightsForLiterature($departments)),
                'order' => 4,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز بە بەشی یاسا و دادوەری دەکەی؟',
                'question_en' => 'Are you interested in law and justice?',
                'category' => 'interest',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر حەزێکی پێیە', 'score' => 100],
                    ['text' => 'بەڵێ، حەزێکی پێیە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم حەزێکی پێیە', 'score' => 25],
                    ['text' => 'هیچ حەزێکی پێی نییە', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForLaw($departments)),
                'order' => 5,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز بە بەشی هونەر و دیزاین دەکەی؟',
                'question_en' => 'Are you interested in art and design?',
                'category' => 'interest',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر حەزێکی پێیە', 'score' => 100],
                    ['text' => 'بەڵێ، حەزێکی پێیە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم حەزێکی پێیە', 'score' => 25],
                    ['text' => 'هیچ حەزێکی پێی نییە', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForArt($departments)),
                'order' => 6,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز بە بەشی زانست و توێژینەوە دەکەی؟',
                'question_en' => 'Are you interested in science and research?',
                'category' => 'interest',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر حەزێکی پێیە', 'score' => 100],
                    ['text' => 'بەڵێ، حەزێکی پێیە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم حەزێکی پێیە', 'score' => 25],
                    ['text' => 'هیچ حەزێکی پێی نییە', 'score' => 0]
                ]),
                'weight' => 3,
                'department_weights' => json_encode($this->getDepartmentWeightsForScience($departments)),
                'order' => 7,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز بە بەشی کشتوکاڵ و ژینگە دەکەی؟',
                'question_en' => 'Are you interested in agriculture and environment?',
                'category' => 'interest',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر حەزێکی پێیە', 'score' => 100],
                    ['text' => 'بەڵێ، حەزێکی پێیە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم حەزێکی پێیە', 'score' => 25],
                    ['text' => 'هیچ حەزێکی پێی نییە', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForAgriculture($departments)),
                'order' => 8,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز بە بەشی کۆمەڵایەتی و مرۆڤایەتی دەکەی؟',
                'question_en' => 'Are you interested in social sciences and humanities?',
                'category' => 'interest',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر حەزێکی پێیە', 'score' => 100],
                    ['text' => 'بەڵێ، حەزێکی پێیە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم حەزێکی پێیە', 'score' => 25],
                    ['text' => 'هیچ حەزێکی پێی نییە', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForSocialScience($departments)),
                'order' => 9,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا حەز بە بەشی مامۆستایەتی و پەروەردە دەکەی؟',
                'question_en' => 'Are you interested in teaching and education?',
                'category' => 'interest',
                'options' => json_encode([
                    ['text' => 'بەڵێ، زۆر حەزێکی پێیە', 'score' => 100],
                    ['text' => 'بەڵێ، حەزێکی پێیە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم حەزێکی پێیە', 'score' => 25],
                    ['text' => 'هیچ حەزێکی پێی نییە', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForTeaching($departments)),
                'order' => 10,
                'status' => true,
            ],

            // ========== پرسیارەکانی شوێن (Location) ==========
            [
                'question_ku' => 'ئایا حەز دەکەیت لە هەمان پارێزگای خۆت بخوێنیت؟',
                'question_en' => 'Do you prefer to study in your own province?',
                'category' => 'location',
                'options' => json_encode([
                    ['text' => 'بەڵێ، تەنها لە پارێزگای خۆم', 'score' => 100],
                    ['text' => 'بەڵێ، بەڵام دەتوانم بچم بۆ پارێزگاکانی تر', 'score' => 75],
                    ['text' => 'هیچ گرنگیەکم پێ نادەم', 'score' => 50],
                    ['text' => 'پێم باشترە لە دەرەوەی پارێزگاکەم بخوێنم', 'score' => 25],
                    ['text' => 'تەنها لە دەرەوەی پارێزگاکەم', 'score' => 0]
                ]),
                'weight' => 3,
                'department_weights' => json_encode($this->getDepartmentWeightsForLocalPreference($departments)),
                'order' => 1,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا دەتوانی بەدوور لە ماڵەوە بخوێنیت؟',
                'question_en' => 'Can you study far from home?',
                'category' => 'location',
                'options' => json_encode([
                    ['text' => 'بەڵێ، هەر شوێنێک', 'score' => 100],
                    ['text' => 'بەڵێ، بەڵام نزیکتر باشترە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'تەنها لە پارێزگای خۆم', 'score' => 25],
                    ['text' => 'تەنها لە شارەکەی خۆم', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode([]),
                'order' => 2,
                'status' => true,
            ],
            [
                'question_ku' => 'ئایا شوێنی خوێندن کاریگەری لەسەر هەڵبژاردنەکەت دەبێت؟',
                'question_en' => 'Does the study location affect your choice?',
                'category' => 'location',
                'options' => json_encode([
                    ['text' => 'زۆر کاریگەری هەیە', 'score' => 100],
                    ['text' => 'کاریگەری هەیە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم کاریگەری هەیە', 'score' => 25],
                    ['text' => 'هیچ کاریگەریەکی نییە', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode([]),
                'order' => 3,
                'status' => true,
            ],

            // ========== پرسیارەکانی پێشەنگی (Priority) ==========
            [
                'question_ku' => 'چەندێک گرینگە بۆت ناوی زانکۆ و پێگەیەکەی؟',
                'question_en' => 'How important is the university name and reputation to you?',
                'category' => 'priority',
                'options' => json_encode([
                    ['text' => 'زۆر گرینگە', 'score' => 100],
                    ['text' => 'گرینگە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم گرینگە', 'score' => 25],
                    ['text' => 'هیچ گرنگیەکم پێ نادەم', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForUniversityReputation($departments)),
                'order' => 1,
                'status' => true,
            ],
            [
                'question_ku' => 'چەندێک گرینگە بۆت ئەگەری کاری دوای تەواوکردنی بەشەکە؟',
                'question_en' => 'How important is job opportunities after graduation?',
                'category' => 'priority',
                'options' => json_encode([
                    ['text' => 'زۆر گرینگە', 'score' => 100],
                    ['text' => 'گرینگە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم گرینگە', 'score' => 25],
                    ['text' => 'هیچ گرنگیەکم پێ نادەم', 'score' => 0]
                ]),
                'weight' => 3,
                'department_weights' => json_encode($this->getDepartmentWeightsForJobOpportunities($departments)),
                'order' => 2,
                'status' => true,
            ],
            [
                'question_ku' => 'چەندێک گرینگە بۆت خوێندنەوەی درێژخایەن (ماستەر، دکتۆرا)؟',
                'question_en' => 'How important is continuing education (Master, PhD)?',
                'category' => 'priority',
                'options' => json_encode([
                    ['text' => 'زۆر گرینگە', 'score' => 100],
                    ['text' => 'گرینگە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم گرینگە', 'score' => 25],
                    ['text' => 'هیچ گرنگیەکم پێ نادەم', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForHigherEducation($departments)),
                'order' => 3,
                'status' => true,
            ],
            [
                'question_ku' => 'چەندێک گرینگە بۆت نرخ و تێچووی خوێندن؟',
                'question_en' => 'How important is the tuition cost?',
                'category' => 'priority',
                'options' => json_encode([
                    ['text' => 'زۆر گرینگە', 'score' => 100],
                    ['text' => 'گرینگە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم گرینگە', 'score' => 25],
                    ['text' => 'هیچ گرنگیەکم پێ نادەم', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForCost($departments)),
                'order' => 4,
                'status' => true,
            ],
            [
                'question_ku' => 'چەندێک گرینگە بۆت ئاسوودەیی و سەرچاوەکانی زانکۆ؟',
                'question_en' => 'How important are university facilities and comfort?',
                'category' => 'priority',
                'options' => json_encode([
                    ['text' => 'زۆر گرینگە', 'score' => 100],
                    ['text' => 'گرینگە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم گرینگە', 'score' => 25],
                    ['text' => 'هیچ گرنگیەکم پێ نادەم', 'score' => 0]
                ]),
                'weight' => 1,
                'department_weights' => json_encode($this->getDepartmentWeightsForFacilities($departments)),
                'order' => 5,
                'status' => true,
            ],
            [
                'question_ku' => 'چەندێک گرینگە بۆت سیستەمی خوێندن (ئێواران، پاڕالیل، زانکۆلاین)؟',
                'question_en' => 'How important is the study system (evening, parallel, online)?',
                'category' => 'priority',
                'options' => json_encode([
                    ['text' => 'زۆر گرینگە', 'score' => 100],
                    ['text' => 'گرینگە', 'score' => 75],
                    ['text' => 'مامناوەند', 'score' => 50],
                    ['text' => 'کەم گرینگە', 'score' => 25],
                    ['text' => 'هیچ گرنگیەکم پێ نادەم', 'score' => 0]
                ]),
                'weight' => 2,
                'department_weights' => json_encode($this->getDepartmentWeightsForStudySystem($departments)),
                'order' => 6,
                'status' => true,
            ],
        ];

        foreach ($questions as $question) {
            DB::table('ai_questions')->insert($question);
        }

        $this->command->info('✅ 24 AI Questions seeded successfully with department_weights!');
    }

    // ========== Helper Methods for department_weights ==========

    /**
     * پلانکردن و ڕێکخستن
     */
    private function getDepartmentWeightsForPlanning($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'حسابات') ||
                str_contains($name, 'بەڕێوەبردن') ||
                str_contains($name, 'پڕۆژە') ||
                str_contains($name, 'پلان')) {
                $score = 20;
            } elseif (str_contains($name, 'یاسا') ||
                     str_contains($name, 'پزیشکی') ||
                     str_contains($name, 'ئەندازیاری')) {
                $score = 15;
            } else {
                $score = 5;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * کۆمەڵایەتی
     */
    private function getDepartmentWeightsForSocial($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'مامۆستایەتی') ||
                str_contains($name, 'پەرستاری') ||
                str_contains($name, 'کۆمەڵایەتی') ||
                str_contains($name, 'ڕاگەیاندن')) {
                $score = 20;
            } elseif (str_contains($name, 'پزیشکی') ||
                     str_contains($name, 'بازرگانی') ||
                     str_contains($name, 'یاسا')) {
                $score = 15;
            } elseif (str_contains($name, 'ئەندازیاری') ||
                     str_contains($name, 'زانست') ||
                     str_contains($name, 'کشتوکاڵ')) {
                $score = 5;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * لۆژیکی
     */
    private function getDepartmentWeightsForLogical($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'ئەندازیاری') ||
                str_contains($name, 'کۆمپیوتەر') ||
                str_contains($name, 'بیرکاری') ||
                str_contains($name, 'زانست')) {
                $score = 20;
            } elseif (str_contains($name, 'پزیشکی') ||
                     str_contains($name, 'یاسا') ||
                     str_contains($name, 'حسابات')) {
                $score = 15;
            } else {
                $score = 5;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * داهێنەری
     */
    private function getDepartmentWeightsForCreative($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'هونەری') ||
                str_contains($name, 'دیزاین') ||
                str_contains($name, 'مۆسیقا') ||
                str_contains($name, 'وێژە') ||
                str_contains($name, 'ئەدەبی')) {
                $score = 20;
            } elseif (str_contains($name, 'ڕاگەیاندن') ||
                     str_contains($name, 'ئارکیتێکت') ||
                     str_contains($name, 'فیلم')) {
                $score = 15;
            } else {
                $score = 5;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * وردەکاری
     */
    private function getDepartmentWeightsForDetail($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'حسابات') ||
                str_contains($name, 'پزیشکی') ||
                str_contains($name, 'ددان') ||
                str_contains($name, 'پەرستاری') ||
                str_contains($name, 'تاقیگە')) {
                $score = 20;
            } elseif (str_contains($name, 'ئەندازیاری') ||
                     str_contains($name, 'یاسا') ||
                     str_contains($name, 'زانست')) {
                $score = 15;
            } else {
                $score = 5;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * پزیشکی
     */
    private function getDepartmentWeightsForMedical($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'پزیشکی') ||
                str_contains($name, 'ددان') ||
                str_contains($name, 'پەرستاری') ||
                str_contains($name, 'دەرمانسازی') ||
                str_contains($name, 'تەندروستی')) {
                $score = 20;
            } elseif (str_contains($name, 'ژینناسی') ||
                     str_contains($name, 'کیمیا') ||
                     str_contains($name, 'فیزیا')) {
                $score = 10;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * ئەندازیاری
     */
    private function getDepartmentWeightsForEngineering($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'ئەندازیاری') ||
                str_contains($name, 'کۆمپیوتەر') ||
                str_contains($name, 'بەرنامەسازی') ||
                str_contains($name, 'تەکنیکی') ||
                str_contains($name, 'میکانیکی')) {
                $score = 20;
            } elseif (str_contains($name, 'ئارکیتێکت') ||
                     str_contains($name, 'بیناسازی') ||
                     str_contains($name, 'ئەلکترۆنی')) {
                $score = 15;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * بازرگانی
     */
    private function getDepartmentWeightsForBusiness($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'بازرگانی') ||
                str_contains($name, 'بەڕێوەبردن') ||
                str_contains($name, 'ئابووری') ||
                str_contains($name, 'حسابات') ||
                str_contains($name, 'بازاریابی')) {
                $score = 20;
            } elseif (str_contains($name, 'یاسا') ||
                     str_contains($name, 'سیاسی')) {
                $score = 10;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * وێژە و زمان
     */
    private function getDepartmentWeightsForLiterature($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'وێژە') ||
                str_contains($name, 'زمان') ||
                str_contains($name, 'ئەدەبی') ||
                str_contains($name, 'کوردی') ||
                str_contains($name, 'عەرەبی') ||
                str_contains($name, 'ئینگلیزی')) {
                $score = 20;
            } elseif (str_contains($name, 'مێژوو') ||
                     str_contains($name, 'فەلسەفە') ||
                     str_contains($name, 'ڕوانگە')) {
                $score = 15;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * یاسا
     */
    private function getDepartmentWeightsForLaw($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'یاسا') ||
                str_contains($name, 'داد') ||
                str_contains($name, 'قانون') ||
                str_contains($name, 'حقوق')) {
                $score = 20;
            } elseif (str_contains($name, 'سیاسی') ||
                     str_contains($name, 'نێودەوڵەتی')) {
                $score = 15;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * هونەر
     */
    private function getDepartmentWeightsForArt($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'هونەری') ||
                str_contains($name, 'دیزاین') ||
                str_contains($name, 'مۆسیقا') ||
                str_contains($name, 'تەلارسازی') ||
                str_contains($name, 'نەخشەکێشان')) {
                $score = 20;
            } elseif (str_contains($name, 'ڕاگەیاندن') ||
                     str_contains($name, 'فیلم') ||
                     str_contains($name, 'تەلەڤیزیۆن')) {
                $score = 15;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * زانست
     */
    private function getDepartmentWeightsForScience($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'زانست') ||
                str_contains($name, 'فیزیا') ||
                str_contains($name, 'کیمیا') ||
                str_contains($name, 'ژینناسی') ||
                str_contains($name, 'بیرکاری') ||
                str_contains($name, 'تاقیگە')) {
                $score = 20;
            } elseif (str_contains($name, 'پزیشکی') ||
                     str_contains($name, 'ئەندازیاری') ||
                     str_contains($name, 'کشتوکاڵ')) {
                $score = 10;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * کشتوکاڵ
     */
    private function getDepartmentWeightsForAgriculture($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'کشتوکاڵ') ||
                str_contains($name, 'خواردن') ||
                str_contains($name, 'گیا') ||
                str_contains($name, 'ئاژەڵ') ||
                str_contains($name, 'باخ') ||
                str_contains($name, 'ژینگە')) {
                $score = 20;
            } elseif (str_contains($name, 'ژینناسی') ||
                     str_contains($name, 'کیمیا')) {
                $score = 10;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * کۆمەڵایەتی
     */
    private function getDepartmentWeightsForSocialScience($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'کۆمەڵایەتی') ||
                str_contains($name, 'دەروونی') ||
                str_contains($name, 'مرۆڤایەتی') ||
                str_contains($name, 'مێژوو') ||
                str_contains($name, 'فەلسەفە') ||
                str_contains($name, 'ئایین')) {
                $score = 20;
            } elseif (str_contains($name, 'مامۆستایەتی') ||
                     str_contains($name, 'ڕاگەیاندن')) {
                $score = 15;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * مامۆستایەتی
     */
    private function getDepartmentWeightsForTeaching($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 0;
            $name = strtolower($dept->name);

            if (str_contains($name, 'مامۆستایەتی') ||
                str_contains($name, 'پەروەردە') ||
                str_contains($name, 'قوتابخانە') ||
                str_contains($name, 'خوێندن')) {
                $score = 20;
            } elseif (str_contains($name, 'کۆمەڵایەتی') ||
                     str_contains($name, 'زمان')) {
                $score = 15;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * پەیوەندی بە پارێزگا
     */
    private function getDepartmentWeightsForLocalPreference($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            // بەشەکانی ناو پارێزگا نمرەی بەرزتر
            $score = $dept->province_id ? 10 : 5;
            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * ناوی زانکۆ
     */
    private function getDepartmentWeightsForUniversityReputation($departments)
    {
        $weights = [];
        $prestigiousUniversities = [1, 2, 3, 4]; // IDکانی زانکۆە دیارەکان

        foreach ($departments as $dept) {
            $score = 5;

            // ئەگەر زانکۆیەکی دیارە
            if (in_array($dept->university_id, $prestigiousUniversities)) {
                $score = 20;
            }

            // ئەگەر بەشێکی دیارە
            if (str_contains(strtolower($dept->name), 'پزیشکی') ||
                str_contains(strtolower($dept->name), 'ئەندازیاری') ||
                str_contains(strtolower($dept->name), 'یاسا')) {
                $score += 5;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * ئەگەری کار
     */
    private function getDepartmentWeightsForJobOpportunities($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 10;
            $name = strtolower($dept->name);

            if (str_contains($name, 'پزیشکی') ||
                str_contains($name, 'ئەندازیاری') ||
                str_contains($name, 'کۆمپیوتەر') ||
                str_contains($name, 'حسابات') ||
                str_contains($name, 'یاسا')) {
                $score = 20;
            } elseif (str_contains($name, 'مامۆستایەتی') ||
                     str_contains($name, 'پەرستاری') ||
                     str_contains($name, 'بازرگانی')) {
                $score = 15;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * خوێندنی بەرزتر
     */
    private function getDepartmentWeightsForHigherEducation($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 10;
            $name = strtolower($dept->name);

            if (str_contains($name, 'پزیشکی') ||
                str_contains($name, 'ئەندازیاری') ||
                str_contains($name, 'زانست') ||
                str_contains($name, 'یاسا')) {
                $score = 20;
            } elseif (str_contains($name, 'مامۆستایەتی') ||
                     str_contains($name, 'بازرگانی') ||
                     str_contains($name, 'کۆمەڵایەتی')) {
                $score = 15;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * نرخ و تێچوو
     */
    private function getDepartmentWeightsForCost($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 10;

            // بەشە گرانەکان (وەک پزیشکی) نمرەی کەمتر
            if (str_contains(strtolower($dept->name), 'پزیشکی') ||
                str_contains(strtolower($dept->name), 'ددان') ||
                str_contains(strtolower($dept->name), 'ئەندازیاری')) {
                $score = 5;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * سەرچاوە و ئاسوودەیی
     */
    private function getDepartmentWeightsForFacilities($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 10;

            // بەشەکانی کە پێویستی بە سەرچاوەی تایبەت هەیە
            if (str_contains(strtolower($dept->name), 'پزیشکی') ||
                str_contains(strtolower($dept->name), 'ئەندازیاری') ||
                str_contains(strtolower($dept->name), 'هونەری') ||
                str_contains(strtolower($dept->name), 'تاقیگە')) {
                $score = 20;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }

    /**
     * سیستەمی خوێندن
     */
    private function getDepartmentWeightsForStudySystem($departments)
    {
        $weights = [];
        foreach ($departments as $dept) {
            $score = 10;

            // ئەگەر بەشەکە لە هەموو سیستەمەکاندا بکرێت
            if ($dept->system_id == 1 || $dept->system_id == 2 || $dept->system_id == 3) {
                $score = 20;
            }

            $weights[$dept->id] = $score;
        }
        return $weights;
    }
}
