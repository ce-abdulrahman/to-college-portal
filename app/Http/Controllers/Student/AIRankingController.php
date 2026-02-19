<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AIQuestion;
use App\Models\AIAnswer;
use App\Models\AIRanking;
use App\Models\AIRankingPreference;
use App\Models\Department;
use App\Models\Province;
use App\Models\System;
use App\Services\AIRankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIRankingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'student']);
    }

    /**
     * نمایشی پرسیارە فیلتەرەکان پێشتر لە پرسیارەکانی AI
     */
    public function preferencesForm()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'توشێت مۆڵەتی ئەم کاره نیە.');
        }

        $aiRestricted = $student->ai_rank == 0;
        $preference = null;
        $provinces = collect();
        $systems = collect();

        if (!$aiRestricted) {
            $allowedSystemIds = $this->getAllowedSystemIdsForStudent($student);
            $systems = $this->getAllowedSystemsForStudent($student);
            $preference = AIRankingPreference::where('student_id', $student->id)->first();

            if (!$preference) {
                $preference = AIRankingPreference::create([
                    'student_id' => $student->id,
                    'consider_personality' => true,
                    'include_specific_questions' => true,
                    'prefer_nearby_departments' => true,
                    'use_mark_bonus' => true,
                    'mark_bonus_enabled' => true,
                    'preferred_systems' => $allowedSystemIds,
                    'gender_filter' => [$student->gender],
                    'field_type_filter' => [$student->type],
                    'province_filter' => $student->province_id,
                ]);
            } else {
                $currentPreferred = collect($preference->preferred_systems ?? [])
                    ->map(fn($id) => (int) $id)
                    ->values();
                $normalizedPreferred = $currentPreferred
                    ->intersect($allowedSystemIds)
                    ->unique()
                    ->values();

                if ($normalizedPreferred->isEmpty()) {
                    $normalizedPreferred = collect($allowedSystemIds);
                }

                if ($normalizedPreferred->toArray() !== $currentPreferred->toArray()) {
                    $preference->update([
                        'preferred_systems' => $normalizedPreferred->all(),
                    ]);
                    $preference->refresh();
                }
            }

            // وەرگرتنی پارێزگاکان
            if ((int) ($student->all_departments ?? 0) === 1) {
                $provinces = Province::all();
            } else {
                $studentProvinceId = (int) ($student->province_id ?? 0);
                $provinces = Province::query()
                    ->when($studentProvinceId > 0, fn($q) => $q->where('id', $studentProvinceId))
                    ->get();
            }
        }

        return view('website.web.student.ai.preferences', compact(
            'student',
            'preference',
            'provinces',
            'systems',
            'aiRestricted'
        ));
    }

    /**
     * پاشەکشانی فیلتەرەکان
     */
    public function savePreferences(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->ai_rank == 0) {
            return response()->json([
                'success' => false,
                'message' => 'مۆڵەتی ئەم کاره نیە.'
            ], 403);
        }

        $allowedSystemIds = $this->getAllowedSystemIdsForStudent($student);
        if (empty($allowedSystemIds)) {
            return response()->json([
                'success' => false,
                'message' => 'هیچ سیستەمێکی خوێندن بەردەست نییە.'
            ], 422);
        }

        $validated = $request->validate([
            'consider_personality' => 'boolean',
            'include_specific_questions' => 'boolean',
            'prefer_nearby_departments' => 'boolean',
            'use_mark_bonus' => 'boolean',
            'mark_bonus_enabled' => 'boolean',
            'preferred_systems' => 'array',
            'preferred_systems.*' => 'integer|exists:systems,id',
            'province_filter' => 'nullable|integer|exists:provinces,id',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
        ]);

        $payload = array_merge([
            'consider_personality' => 0,
            'include_specific_questions' => 1,
            'prefer_nearby_departments' => 0,
            'use_mark_bonus' => 1,
            'mark_bonus_enabled' => 1,
        ], $validated);

        $selectedSystemIds = collect($payload['preferred_systems'] ?? [])
            ->map(fn($id) => (int) $id)
            ->intersect($allowedSystemIds)
            ->unique()
            ->values()
            ->all();
        if (empty($selectedSystemIds)) {
            $selectedSystemIds = $allowedSystemIds;
        }

        $payload['preferred_systems'] = $selectedSystemIds;
        $payload['gender_filter'] = [$student->gender];
        $payload['field_type_filter'] = [$student->type];

        if ((int) ($student->all_departments ?? 0) !== 1) {
            $payload['province_filter'] = (int) ($student->province_id ?? 0) ?: null;
            $payload['prefer_nearby_departments'] = true;
        }

        if (empty($payload['prefer_nearby_departments'])) {
            $payload['province_filter'] = null;
        }

        if (!empty($payload['prefer_nearby_departments'])) {
            $lat = $validated['lat'] ?? $student->lat;
            $lng = $validated['lng'] ?? $student->lng;

            if ($lat === null || $lng === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'تکایە مۆڵەتی شوێن بدە بۆ بەکارهێنانی فیلتەری نزیکی شوێن.'
                ], 422);
            }

            $student->update([
                'lat' => (float) $lat,
                'lng' => (float) $lng,
            ]);
        }

        unset($payload['lat'], $payload['lng']);

        AIRankingPreference::updateOrCreate(
            ['student_id' => $student->id],
            $payload
        );

        $student->refresh();

        if (!empty($payload['consider_personality']) && empty($student->mbti_type)) {
            return response()->json([
                'success' => true,
                'message' => 'پێویستە سەرەتا تاقیکردنەوەی MBTI تەواو بکەیت.',
                'redirect' => route('student.mbti.index')
            ]);
        }

        if (empty($payload['use_mark_bonus'])) {
            (new AIRankingService($student))->calculateRankings();

            return response()->json([
                'success' => true,
                'message' => 'تایبەتمەندیەکانت پاشەکش کرا! پشکنینی AI پێویست نییە.',
                'redirect' => route('student.ai-ranking.results')
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تایبەتمەندیەکانت پاشەکش کرا!',
            'redirect' => route('student.ai-ranking.questionnaire')
        ]);
    }

    /**
     * نمایشی پرسیارەکانی AI
     */
    public function questionnaire()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'توشێت پێویست نیە.');
        }

        // ئەگەر ai_rank == 0 ئەوا نیشان بدە warning
        if ($student->ai_rank == 0) {
            return view('website.web.student.ai.restricted', compact('student'));
        }

        // ئەگەر پێش ئەو پرسیاریە فیلتەرەکان هاتنی، بڕۆ بۆ preferences
        $preference = AIRankingPreference::where('student_id', $student->id)->first();
        if (!$preference) {
            return redirect()->route('student.ai-ranking.preferences');
        }

        if ((bool) $preference->consider_personality && empty($student->mbti_type)) {
            return redirect()->route('student.mbti.index')
                ->with('warning', 'پێویستە سەرەتا تاقیکردنەوەی MBTI تەواو بکەیت.');
        }

        if (!(bool) $preference->use_mark_bonus) {
            (new AIRankingService($student))->calculateRankings();
            return redirect()->route('student.ai-ranking.results');
        }

        // پشکنین ئەگەر پێشتر وەڵامی دابێت
        $hasAnswers = AIAnswer::where('student_id', $student->id)->exists();

        if ($hasAnswers) {
            return redirect()->route('student.ai-ranking.results');
        }

        $questions = AIQuestion::where('status', true)
            ->orderBy('category')
            ->orderBy('order')
            ->get()
            ->groupBy('category');

        return view('website.web.student.ai.questionnaire', compact('student', 'questions'));
    }

    /**
     * وەرگرتنی وەڵامەکان
     */
    public function submitQuestionnaire(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->ai_rank == 0) {
            return response()->json([
                'success' => false,
                'message' => 'مۆڵەتی بەکارهێنانی سیستەمی AIت نییە.'
            ], 403);
        }

        $preference = AIRankingPreference::where('student_id', $student->id)->first();
        if (!$preference) {
            return response()->json([
                'success' => false,
                'message' => 'تکایە سەرەتا تایبەتمەندیەکان دیاری بکە.',
                'redirect' => route('student.ai-ranking.preferences'),
            ], 422);
        }

        if ((bool) $preference->consider_personality && empty($student->mbti_type)) {
            return response()->json([
                'success' => false,
                'message' => 'پێویستە سەرەتا تاقیکردنەوەی MBTI تەواو بکەیت.',
                'redirect' => route('student.mbti.index'),
            ], 422);
        }

        if (!(bool) $preference->use_mark_bonus) {
            (new AIRankingService($student))->calculateRankings();

            return response()->json([
                'success' => true,
                'message' => 'پشکنینی AI پێویست نەبوو، ڕیزبەندی دروست کرا.',
                'redirect' => route('student.ai-ranking.results')
            ]);
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string|max:500',
        ]);

        // سڕینەوەی وەڵامە کۆنەکان
        AIAnswer::where('student_id', $student->id)->delete();

        // تۆمارکردنی وەڵامە نوێەکان
        foreach ($request->answers as $questionId => $answer) {
            AIAnswer::create([
                'student_id' => $student->id,
                'question_id' => $questionId,
                'answer' => $answer,
                'score' => $this->calculateAnswerScore($questionId, $answer),
            ]);
        }

        // هەژمارکردنی ڕیزبەندیەکان
        $rankingService = new AIRankingService($student);
        $rankingService->calculateRankings();

        return response()->json([
            'success' => true,
            'message' => 'پرسیارەکان بە سەرکەوتوویی تەواو بوون!',
            'redirect' => route('student.ai-ranking.results')
        ]);
    }

    /**
     * نمایشی ئەنجامەکان
     */
    public function results()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'توشێت پێویست نیە.');
        }

        if ($student->ai_rank == 0) {
            return view('website.web.student.ai.restricted', compact('student'));
        }

        $preference = AIRankingPreference::where('student_id', $student->id)->first();
        if (!$preference) {
            return redirect()->route('student.ai-ranking.preferences');
        }

        if ((bool) $preference->consider_personality && empty($student->mbti_type)) {
            return redirect()->route('student.mbti.index')
                ->with('warning', 'پێویستە سەرەتا تاقیکردنەوەی MBTI تەواو بکەیت.');
        }

        // پشکنین ئەگەر پێشتر وەڵامی دابێت
        $hasAnswers = AIAnswer::where('student_id', $student->id)->exists();

        if ((bool) $preference->use_mark_bonus && !$hasAnswers) {
            return redirect()->route('student.ai-ranking.questionnaire');
        }

        $hasRankings = AIRanking::where('student_id', $student->id)->exists();
        if (!$hasRankings) {
            (new AIRankingService($student))->calculateRankings();
        }

        // وەرگرتنی ڕیزبەندیەکان
        $maxRankedDepartments = (int) ($student->all_departments ?? 0) === 1 ? 50 : 10;

        $rankings = AIRanking::where('student_id', $student->id)
            ->whereHas('department', fn($q) => $q->visibleForSelection())
            ->with(['department' => function($query) {
                $query->visibleForSelection()
                    ->with(['university', 'province', 'college', 'system']);
            }])
            ->orderBy('rank')
            ->limit($maxRankedDepartments)
            ->get();

        // ئامارەکان
        $stats = $this->getRankingStats($rankings);

        $studentProvinceId = $student->province_id;

        return view('website.web.student.ai.results', compact('student', 'rankings', 'stats', 'studentProvinceId'));
    }

    /**
     * دووبارەکردنەوەی تاقیکردنەوە
     */
    public function retake()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->ai_rank == 0) {
            return redirect()->back()->with('error', 'مۆڵەتی بەکارهێنانی سیستەمی AIت نییە.');
        }

        // سڕینەوەی هەموو زانیاریەکانی پێشوو
        AIAnswer::where('student_id', $student->id)->delete();
        AIRanking::where('student_id', $student->id)->delete();

        $preference = AIRankingPreference::where('student_id', $student->id)->first();
        if ($preference && !(bool) $preference->use_mark_bonus) {
            (new AIRankingService($student))->calculateRankings();
            return redirect()->route('student.ai-ranking.results');
        }

        return redirect()->route('student.ai-ranking.questionnaire');
    }

    /**
     * زیادکردنی بەش بۆ لیستی هەڵبژێردراوەکان
     */
    public function addToSelection(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'rank' => 'required|integer',
        ]);

        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->ai_rank == 0) {
            return response()->json([
                'success' => false,
                'message' => 'مۆڵەتی بەکارهێنانی سیستەمی AIت نییە.'
            ], 403);
        }

        // دەتوانێت لە DepartmentSelectionController سوود بگرێت
        // یان ڕاستەوخۆ تۆمار بکات

        return response()->json([
            'success' => true,
            'message' => 'بەشەکە زیاد کرا بە لیستی هەڵبژێردراوەکان.'
        ]);
    }

    /**
     * نمرەی وەڵام
     */
    private function calculateAnswerScore($questionId, $answer)
    {
        $question = AIQuestion::find($questionId);

        if (!$question || !$question->options) {
            return 50;
        }

        // Handle both JSON string and already-cast array
        $options = is_array($question->options)
            ? $question->options
            : json_decode($question->options, true);

        if (!is_array($options)) {
            return 50;
        }

        $answerKey = strtolower(trim($answer));

        foreach ($options as $option) {
            if (strtolower(trim($option['text'])) == $answerKey) {
                return $option['score'] ?? 50;
            }
        }

        return 50;
    }

    /**
     * ئامارەکانی ڕیزبەندی
     */
    private function getRankingStats($rankings)
    {
        $total = $rankings->count();

        if ($total === 0) {
            return [
                'total' => 0,
                'average_score' => 0,
                'top_category' => null,
                'categories' => [],
                'match_level' => 'کەم',
            ];
        }

        $averageScore = $rankings->avg('score');

        // کۆکردنەوەی فاکتەرەکان
        $categories = [
            'نمرە' => 0,
            'کەسایەتی' => 0,
            'حەز' => 0,
            'شوێن' => 0,
            'دیمۆگرافی' => 0,
        ];

        foreach ($rankings as $ranking) {
            // Handle both JSON string and already-cast array
            $factors = is_array($ranking->match_factors)
                ? $ranking->match_factors
                : json_decode($ranking->match_factors, true) ?? [];

            if (isset($factors['academic_match'])) $categories['نمرە'] += $factors['academic_match'];
            if (isset($factors['personality_match'])) $categories['کەسایەتی'] += $factors['personality_match'];
            if (isset($factors['interest_match'])) $categories['حەز'] += $factors['interest_match'];
            if (isset($factors['location_match'])) $categories['شوێن'] += $factors['location_match'];
            if (isset($factors['demographic_match'])) $categories['دیمۆگرافی'] += $factors['demographic_match'];
        }

        // کۆکردنەوەی ناوەندی
        foreach ($categories as &$category) {
            $category = round($category / $total, 1);
        }

        // دیاریکردنی باشترین کاتێگۆری
        arsort($categories);
        $topCategory = key($categories);

        // ئاستی گونجاویی
        $matchLevel = 'کەم';
        if ($averageScore >= 80) $matchLevel = 'زۆر بەرز';
        elseif ($averageScore >= 70) $matchLevel = 'بەرز';
        elseif ($averageScore >= 60) $matchLevel = 'مامناوەند';
        elseif ($averageScore >= 50) $matchLevel = 'کەم';

        return [
            'total' => $total,
            'average_score' => round($averageScore, 1),
            'top_category' => $topCategory,
            'categories' => $categories,
            'match_level' => $matchLevel,
        ];
    }

    /**
     * پشکنینی بارودۆخی AI بۆ قوتابی
     */
    public function checkAIStatus(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        if (!$student) {
            return response()->json([
                'status' => 'missing',
                'message' => 'زانیاریەکانی قوتابی تۆمار نەکراوە.',
                'ai_rank' => 0,
            ], 404);
        }

        return response()->json([
            'status' => $student->ai_rank == 1 ? 'active' : 'restricted',
            'message' => $student->ai_rank == 1 ?
                'سیستەمی AI چالاکە' :
                'سیستەمی AI بند کراوە',
            'ai_rank' => $student->ai_rank
        ]);
    }

    /**
     * داگرتنی ئەنجامەکان بە فۆرماتی Excel
     */
    public function exportExcel()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->ai_rank == 0) {
            return redirect()->back()->with('error', 'مۆڵەت نیە');
        }

        $rankings = AIRanking::where('student_id', $student->id)
            ->whereHas('department', fn($q) => $q->visibleForSelection())
            ->with(['department' => fn($q) => $q->visibleForSelection()])
            ->orderBy('rank')
            ->get();

        // ئیتریشە Excel export logic هێریکان
        return response()->json([
            'message' => 'Excel export دەسپێ دەکات'
        ]);
    }

    /**
     * داگرتنی ئەنجامەکان بە فۆرماتی PDF
     */
    public function exportPDF()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->ai_rank == 0) {
            return redirect()->back()->with('error', 'مۆڵەت نیە');
        }

        $maxRankedDepartments = (int) ($student->all_departments ?? 0) === 1 ? 50 : 10;
        $rankings = AIRanking::where('student_id', $student->id)
            ->whereHas('department', fn($q) => $q->visibleForSelection())
            ->with([
                'department' => fn($q) => $q->visibleForSelection(),
                'department.university',
                'department.province',
            ])
            ->orderBy('rank')
            ->limit($maxRankedDepartments)
            ->get();

        // ئیتریشە PDF export logic هێریکان
        return response()->json([
            'message' => 'PDF export دەسپێ دەکات'
        ]);
    }

    /**
     * نیشاندانی وردەکاری بەش
     */
    public function departmentDetails($id)
    {
        $student = Auth::user()->student;
        if (!$student || (int) ($student->ai_rank ?? 0) !== 1) {
            return response()->json(['error' => 'مۆڵەت نییە'], 403);
        }

        $department = Department::query()
            ->visibleForSelection()
            ->with(['university', 'province', 'college', 'system'])
            ->find($id);

        if (!$department) {
            return response()->json(['error' => 'بەشەکە نەدۆزرایەوە'], 404);
        }

        $ranking = AIRanking::where('student_id', $student->id)
            ->where('department_id', $id)
            ->first();

        $matchFactors = null;
        if ($ranking) {
            $matchFactors = is_array($ranking->match_factors)
                ? $ranking->match_factors
                : json_decode($ranking->match_factors, true);
        }

        return response()->json([
            'department' => $department,
            'ranking' => $ranking,
            'match_factors' => $matchFactors
        ]);
    }

    /**
     * دووبارە ڕیزکردن بە فاکتەری دیاریکراو
     */
    public function reorderByFactor(Request $request)
    {
        $request->validate([
            'factor' => 'required|in:academic,personality,interest,location,demographic'
        ]);

        $user = Auth::user();
        $student = $user->student;
        if (!$student || (int) ($student->ai_rank ?? 0) !== 1) {
            return redirect()->route('student.ai-ranking.questionnaire')
                ->with('error', 'مۆڵەتی بەکارهێنانی سیستەمی AIت نییە.');
        }

        $rankings = AIRanking::where('student_id', $student->id)
            ->whereHas('department', fn($q) => $q->visibleForSelection())
            ->with(['department' => fn($q) => $q->visibleForSelection()])
            ->orderBy('rank')
            ->get();

        // ڕیزکردنی دووبارە بەپێی فاکتەری دیاریکراو
        $sortedRankings = $rankings->sortByDesc(function($ranking) use ($request) {
            $factors = is_array($ranking->match_factors)
                ? $ranking->match_factors
                : json_decode($ranking->match_factors, true);
            $factorKey = $request->factor . '_match';
            return $factors[$factorKey] ?? 0;
        });

        return view('website.web.student.ai.results', [
            'student' => $student,
            'rankings' => $sortedRankings->values(),
            'sorted_by' => $request->factor,
            'stats' => $this->getRankingStats($sortedRankings)
        ]);
    }

    /**
     * پێوانەکردن و بەراوردکردنی ڕیزبەندیەکان
     */
    public function compareRankings()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->ai_rank == 0) {
            return redirect()->route('student.ai-ranking.questionnaire');
        }

        $topDepartments = AIRanking::where('student_id', $student->id)
            ->whereHas('department', fn($q) => $q->visibleForSelection())
            ->with(['department' => fn($q) => $q->visibleForSelection()])
            ->orderBy('rank')
            ->limit(5)
            ->get();

        return view('website.web.student.ai.compare', [
            'student' => $student,
            'topDepartments' => $topDepartments
        ]);
    }

    private function getAllowedSystemNamesForStudent($student): array
    {
        return (int) ($student->year ?? 1) > 1
            ? ['پاراڵیل', 'ئێواران']
            : ['زانکۆلاین', 'پاراڵیل', 'ئێواران'];
    }

    private function getAllowedSystemsForStudent($student)
    {
        return System::query()
            ->whereIn('name', $this->getAllowedSystemNamesForStudent($student))
            ->orderBy('id')
            ->get();
    }

    private function getAllowedSystemIdsForStudent($student): array
    {
        return $this->getAllowedSystemsForStudent($student)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }
}
