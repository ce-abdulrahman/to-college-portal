<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Department;
use App\Models\AIRanking;
use App\Models\AIAnswer;
use App\Models\AIRankingPreference;
use App\Models\System;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AIRankingService
{
    private $student;
    private $preference;
    private $studentProvinceId;
    private $weights = [
        'academic' => 0.30,      // 30% نمرە (گرینگترین)
        'interest' => 0.25,      // 25% حەز
        'personality' => 0.20,   // 20% کەسایەتی
        'location' => 0.15,      // 15% شوێن
        'demographic' => 0.10,   // 10% دیمۆگرافی
    ];

    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->preference = AIRankingPreference::where('student_id', $student->id)->first();
        // وەرگرتنی پاریزگای قوتابی لە DB
        // ئەگەر 'province' فیلدی قوتابی ئەنجام بێ، ئێمە بە province_id لێ دەگێڕین
        $province = \App\Models\Province::where('name', $student->province)->first();
        $this->studentProvinceId = $province ? $province->id : null;
    }

    /**
     * هەژمارکردنی ڕیزبەندی بۆ هەموو بەشە گونجاوەکان
     */
    public function calculateRankings()
    {
        try {
            DB::beginTransaction();

            // وەرگرتنی هەموو بەشە گونجاوەکان
            $eligibleDepartments = $this->getEligibleDepartments();

            if ($eligibleDepartments->isEmpty()) {
                return false;
            }

            $rankings = [];

            foreach ($eligibleDepartments as $department) {
                $score = $this->calculateDepartmentScore($department);
                $matchFactors = $this->getMatchFactors($department);
                $rankings[] = [
                    'student_id' => $this->student->id,
                    'department_id' => $department->id,
                    'score' => $score,
                    'match_factors' => $matchFactors,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // ڕیزکردن بەپێی نمرە
            usort($rankings, function($a, $b) {
                return $b['score'] <=> $a['score'];
            });

            // زیادکردنی ڕیز
            foreach ($rankings as $index => &$ranking) {
                $ranking['rank'] = $index + 1;
                $ranking['reason'] = $this->generateReason($ranking['match_factors'] ?? []);
                $ranking['match_factors'] = json_encode(
                    $ranking['match_factors'] ?? [],
                    JSON_UNESCAPED_UNICODE
                ) ?: '[]';
            }

            // سڕینەوەی ڕیزبەندیە کۆنەکان
            AIRanking::where('student_id', $this->student->id)->delete();

            // تۆمارکردنی ڕیزبەندیە نوێەکان
            AIRanking::insert($rankings);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AI Ranking Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * وەرگرتنی هەموو بەشە گونجاوەکان - بە پاسەخ بە فیلتەرەکان
     */
    private function getEligibleDepartments()
    {
        $query = Department::where('status', 1)
            ->with(['university', 'province', 'college', 'system']);

        // ١. فیلتەری جۆر (زانستی / وێژەیی) - لە زانیاری قوتابی
        $query->where(function($q) {
            $q->where('type', $this->student->type)
              ->orWhere('type', 'زانستی و وێژەیی');
        });

        // ٢. فیلتەری ڕەگەز - لە زانیاری قوتابی
        $query->where(function($q) {
            $q->where('sex', $this->student->gender)
              ->orWhere('sex', 'هەردووکیان');
        });

        // ٣. فیلتەری نمرە بەپێی پاریزگا و تایبەتمەندی
        $query->where(function($q) {
            $preferNearby = $this->preference && $this->preference->prefer_nearby_departments ? true : false;

            if ($preferNearby && $this->preference && $this->preference->province_filter) {
                // ئەگەر پاریزگای دیاریکراو هەبێت، تەنها ئەو پاریزگاکە
                $provinceId = $this->preference->province_filter;
                $q->where('province_id', $provinceId)
                  ->where('local_score', '<=', $this->getAdjustedMark('local'));
            } elseif ($this->studentProvinceId) {
                // نۆرمال: ناو پاریزگا یا دەرەوەی پاریزگا
                $q->where('province_id', $this->studentProvinceId)
                  ->where('local_score', '<=', $this->getAdjustedMark('local'))
                  ->orWhere('province_id', '!=', $this->studentProvinceId)
                  ->where('external_score', '<=', $this->getAdjustedMark('external'));
            } else {
                // ئەگەر پاریزگای قوتابی ناسراو نەبوو، تەنها بەپێی دەرەوە نمرە هەڵبژێرە
                $q->where('external_score', '<=', $this->getAdjustedMark('external'));
            }
        });

        // ٤. فیلتەری سیستەم (ئەگەر ساڵ = 1)
        if ($this->student->year == 1) {
            if ($this->preference && $this->preference->preferred_systems && !empty($this->preference->preferred_systems)) {
                $systemIds = $this->preference->preferred_systems;
            } else {
                $systemIds = System::whereIn('name', [1, 2, 3])->pluck('id')->toArray();
            }
            $query->whereIn('system_id', $systemIds);
        }

        return $query->get();
    }

    /**
     * هەژمارکردنی نمرەی گشتی بۆ بەشێک - بە پاسەخ بە فیلتەرەکان
     */
    private function calculateDepartmentScore(Department $department)
    {
        $scores = [];

        // ١. نمرەی خوێندن (٣٠٪)
        $scores['academic'] = $this->calculateAcademicScore($department);

        // ٢. جۆری کەسی (٢٠٪) - ئەگەر فعاڵ بوو
        $scores['personality'] = ($this->preference && $this->preference->consider_personality)
            ? $this->calculatePersonalityScore($department)
            : 50;

        // ٣. حەز و ئارەزوو (٢٥٪)
        $scores['interest'] = $this->calculateInterestScore($department);

        // ٤. شوێن (١٥٪) - ئەگەر فعاڵ بوو
        $scores['location'] = ($this->preference && $this->preference->prefer_nearby_departments)
            ? $this->calculateLocationScore($department)
            : 50;

        // ٥. دیمۆگرافی (١٠٪)
        $scores['demographic'] = $this->calculateDemographicScore($department);

        // هەژمارکردنی کۆی گشتی
        $totalScore = 0;
        foreach ($scores as $category => $score) {
            $totalScore += $score * $this->weights[$category];
        }

        return min(100, max(0, $totalScore));
    }

    /**
     * نمرەی خوێندن - بە بۆنەسی نمرە قوتابی
     */
    private function calculateAcademicScore(Department $department)
    {
        $baseScore = 70;

        // دیاریکردنی نمرەی پێویست
        $requiredScore = $department->province_id == $this->studentProvinceId
            ? $department->local_score
            : $department->external_score;

        $markDifference = $this->student->mark - $requiredScore;

        // سیستەمی نمرەدان بە شێوەی ئەکادیمی
        if ($markDifference >= 15) {
            $baseScore += 25; // زۆر بەهێز
        } elseif ($markDifference >= 10) {
            $baseScore += 20;
        } elseif ($markDifference >= 5) {
            $baseScore += 15;
        } elseif ($markDifference >= 2) {
            $baseScore += 10;
        } elseif ($markDifference >= 0) {
            $baseScore += 5; // تەنها بەسەرچوو
        } elseif ($markDifference >= -2) {
            $baseScore -= 5; // کەم کەمی
        } elseif ($markDifference >= -5) {
            $baseScore -= 15; // کەمترە
        } else {
            $baseScore -= 30; // زۆر کەمترە
        }

        // زیادکردنی بۆنەس بەپێی ئاستی قوتابی
        $bonus = $this->getMarkBonus($this->student->mark);
        $baseScore += $bonus;

        return min(100, max(20, $baseScore));
    }

    private function getMarkBonus($mark)
    {
        // بۆنەسی نمرەی ئالی قوتابی
        // بەپێی قسەی قوتابی:
        // 90+ = 2 نمرە
        // 80+ = 3 نمرە
        // 70+ = 3.5 نمرە
        // 60+ = 4 نمرە

        if (!$this->preference || !$this->preference->use_mark_bonus) {
            return 0;
        }

        if ($mark >= 90) return 2;
        if ($mark >= 80) return 3;
        if ($mark >= 70) return 3.5;
        if ($mark >= 60) return 4;

        return 0;
    }

    /**
     * زیادکردنی نمرە بەپێی ئاستی قوتابی (پێشین میتۆد)
     */
    private function getMarkAdjustment()
    {
        $mark = $this->student->mark;

        if ($mark >= 90) return 2;
        if ($mark >= 80) return 3;
        if ($mark >= 70) return 3.5;
        if ($mark >= 60) return 4;

        return 0;
    }

    /**
     * نمرەی جۆری کەسی (MBTI)
     */
    private function calculatePersonalityScore(Department $department)
    {
        if (!$this->student->mbti_type) {
            return 50; // نمرەی ناوەندی ئەگەر MBTI نییە
        }

        // پەیوەندی نێوان MBTI و بەشەکان
        $mbtiDepartmentMapping = $this->getMbtiDepartmentMapping();

        $mbtiType = $this->student->mbti_type;
        $departmentName = strtolower($department->name);

        $score = 50;

        // پشکنینی پەیوەندی
        if (isset($mbtiDepartmentMapping[$mbtiType])) {
            foreach ($mbtiDepartmentMapping[$mbtiType] as $keyword => $points) {
                if (str_contains($departmentName, $keyword)) {
                    $score += $points;
                }
            }
        }

        return min(100, max(0, $score));
    }

    /**
     * پەیوەندی نێوان MBTI و بەشەکان
     */
    private function getMbtiDepartmentMapping()
    {
        return [
            'ISTJ' => ['پزیشکی' => 20, 'ئەندازیاری' => 15, 'حسابات' => 25],
            'ISFJ' => ['پەرستاری' => 25, 'مامۆستا' => 20, 'کۆمەڵایەتی' => 15],
            'INFJ' => ['دەروونی' => 30, 'فەلسەفە' => 20, 'هونەری' => 15],
            'INTJ' => ['زانست' => 25, 'بەرنامەسازی' => 30, 'بیرکاری' => 25],
            'ISTP' => ['ئەندازیاری' => 20, 'تەکنیکی' => 25, 'میکانیکی' => 20],
            'ISFP' => ['هونەری' => 30, 'دیزاین' => 25, 'مۆسیقا' => 20],
            'INFP' => ['وێژە' => 25, 'دەروونی' => 20, 'مێژوو' => 15],
            'INTP' => ['زانست' => 30, 'بیرکاری' => 25, 'فیزیا' => 20],
            'ESTP' => ['بازرگانی' => 25, 'بەڕێوەبردن' => 20, 'یاسا' => 15],
            'ESFP' => ['هونەری' => 25, 'کۆمەڵایەتی' => 30, 'میدیایی' => 20],
            'ENFP' => ['ڕاگەیاندن' => 30, 'وێژە' => 25, 'دەروونی' => 20],
            'ENTP' => ['بازرگانی' => 25, 'یاسا' => 20, 'سیاسی' => 15],
            'ESTJ' => ['بەڕێوەبردن' => 30, 'یاسا' => 25, 'حسابات' => 20],
            'ESFJ' => ['مامۆستا' => 30, 'پەرستاری' => 25, 'کۆمەڵایەتی' => 20],
            'ENFJ' => ['مامۆستا' => 25, 'دەروونی' => 30, 'ڕاگەیاندن' => 20],
            'ENTJ' => ['بەڕێوەبردن' => 30, 'یاسا' => 25, 'بازرگانی' => 20],
        ];
    }

    /**
     * نمرەی حەز و ئارەزوو
     */
    private function calculateInterestScore(Department $department)
    {
        $answers = AIAnswer::where('student_id', $this->student->id)
            ->whereHas('question', function($q) {
                $q->where('category', 'interest');
            })
            ->with('question')
            ->get();

        if ($answers->isEmpty()) {
            return 50;
        }

        $score = 50;

        foreach ($answers as $answer) {
            $question = $answer->question;
            // Handle both JSON string and already-cast array
            $departmentWeights = is_array($question->department_weights)
                ? $question->department_weights
                : json_decode($question->department_weights, true) ?? [];

            if (isset($departmentWeights[$department->id])) {
                $answerValue = strtolower($answer->answer);

                if (in_array($answerValue, ['بەڵێ', 'yes', 'true', '1'])) {
                    $score += $departmentWeights[$department->id];
                } elseif (in_array($answerValue, ['نەخێر', 'no', 'false', '0'])) {
                    $score -= $departmentWeights[$department->id];
                }
            }
        }

        return min(100, max(0, $score));
    }

    /**
     * نمرەی شوێن
     */
    private function calculateLocationScore(Department $department)
    {
        $score = 50;

        // ئەگەر قوتابی حەزی لە ناو پارێزگای خۆیەتی
        $prefersLocal = AIAnswer::where('student_id', $this->student->id)
            ->whereHas('question', function($q) {
                $q->where('category', 'location')
                  ->where('question_ku', 'like', '%پارێزگا%');
            })
            ->where('answer', 'like', '%بەڵێ%')
            ->exists();

        // دووری لە نێوان قوتابی و بەش
        $distance = $this->calculateDistance(
            $this->student->lat ?? 0,
            $this->student->lng ?? 0,
            $department->lat ?? 0,
            $department->lng ?? 0
        );

        if ($prefersLocal) {
            // ئەگەر هەردووکیان لە هەمان پارێزگان
            if ($this->studentProvinceId && $department->province_id == $this->studentProvinceId) {
                $score += 30;
            } else {
                $score -= 20;
            }
        } else {
            // پێوانەی دووری
            if ($distance < 50) { // کیلۆمەتر
                $score += 20;
            } elseif ($distance < 100) {
                $score += 10;
            } elseif ($distance < 200) {
                $score += 5;
            }
        }

        return min(100, max(0, $score));
    }

    /**
     * هەژمارکردنی دووری
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
            return 1000; // دووریەکی گەورە ئەگەر coordinates نەدۆزرایەوە
        }

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $kilometers = $miles * 1.609344;

        return $kilometers;
    }

    /**
     * نمرەی دیمۆگرافی
     */
    private function calculateDemographicScore(Department $department)
    {
        $score = 70; // بنەڕەت چونکە پێشتر فیلتەر کراون

        // ساڵی قوتابی
        if ($this->student->year == 1 && in_array($department->system_id, [1, 2, 3])) {
            $score += 15;
        }

        // ڕەگەز
        if ($department->sex == 'هەردووکیان' || $department->sex == $this->student->gender) {
            $score += 10;
        }

        // جۆری بەش
        if ($department->type == $this->student->type || $department->type == 'زانستی و وێژەیی') {
            $score += 5;
        }

        return min(100, max(0, $score));
    }

    /**
     * وەرگرتنی فاکتەرەکانی گونجاویی
     */
    private function getMatchFactors(Department $department)
    {
        return [
            'academic_match' => $this->calculateAcademicScore($department),
            'personality_match' => $this->calculatePersonalityScore($department),
            'interest_match' => $this->calculateInterestScore($department),
            'location_match' => $this->calculateLocationScore($department),
            'demographic_match' => $this->calculateDemographicScore($department),
            'mark_difference' => $this->student->mark - ($department->province_id == $this->studentProvinceId
                ? $department->local_score
                : $department->external_score),
            'is_same_province' => $department->province_id == $this->studentProvinceId,
            'distance_km' => $this->calculateDistance(
                $this->student->lat ?? 0,
                $this->student->lng ?? 0,
                $department->lat ?? 0,
                $department->lng ?? 0
            ),
        ];
    }

    /**
     * دروستکردنی هۆکار بۆ ڕیزبەندیەکە
     */
    private function generateReason($factors)
    {
        $reasons = [];

        if (($factors['academic_match'] ?? 0) >= 80) {
            $reasons[] = 'نمرەکەت زۆر گونجاوە بۆ ئەم بەشە';
        }

        if (($factors['personality_match'] ?? 0) >= 75) {
            $reasons[] = 'جۆری کەسیەکەت گونجاوە بۆ ئەم بەشە';
        }

        if (($factors['interest_match'] ?? 0) >= 70) {
            $reasons[] = 'حەز و ئارەزووەکانت گونجاون بۆ ئەم بەشە';
        }

        if (($factors['location_match'] ?? 0) >= 80) {
            $reasons[] = 'شوێنەکەی گونجاوە بۆ تۆ';
        }

        if (!empty($factors['is_same_province'])) {
            $reasons[] = 'لە هەمان پارێزگای تۆدایە';
        } elseif (($factors['distance_km'] ?? 999999) < 100) {
            $reasons[] = 'زۆر نزیکە لە تۆ';
        }

        if (empty($reasons)) {
            return 'بەشێکی گونجاوە بەپێی سیستەمی AI';
        }

        return implode('، ', $reasons);
    }

    /**
     * نمرەی گونجاوی بەپێی پاریزگا
     */
    private function getAdjustedMark($type = 'local')
    {
        $baseMark = $this->student->mark;
        $adjustment = $this->getMarkAdjustment();

        if ($type == 'local') {
            return $baseMark + $adjustment;
        } else {
            return $baseMark + ($adjustment * 0.8); // کەمتر زیاد دەکرێت بۆ دەرەوەی پارێزگا
        }
    }
}
