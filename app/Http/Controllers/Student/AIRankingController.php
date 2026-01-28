<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AIQuestion;
use App\Models\AIAnswer;
use App\Models\AIRanking;
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
     * نمایشی پرسیارەکانی AI
     */
    public function questionnaire()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student || $student->ai_rank != 1) {
            return redirect()->route('student.dashboard')
                ->with('error', 'تۆ مۆڵەتی بەکارهێنانی سیستەمی AIت نییە.');
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
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string|max:500',
        ]);
        
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student || $student->ai_rank != 1) {
            return response()->json([
                'success' => false,
                'message' => 'مۆڵەتی بەکارهێنانی سیستەمی AIت نییە.'
            ], 403);
        }
        
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
        
        if (!$student || $student->ai_rank != 1) {
            return redirect()->route('student.dashboard')
                ->with('error', 'تۆ مۆڵەتی بەکارهێنانی سیستەمی AIت نییە.');
        }
        
        // پشکنین ئەگەر پێشتر وەڵامی دابێت
        $hasAnswers = AIAnswer::where('student_id', $student->id)->exists();
        
        if (!$hasAnswers) {
            return redirect()->route('student.ai-ranking.questionnaire');
        }
        
        // وەرگرتنی ڕیزبەندیەکان
        $rankings = AIRanking::where('student_id', $student->id)
            ->with(['department' => function($query) {
                $query->with(['university', 'province', 'college', 'system']);
            }])
            ->orderBy('rank')
            ->limit(50) // تەنها ٥٠ بەشی سەرەکی
            ->get();
        
        // ئامارەکان
        $stats = $this->getRankingStats($rankings);
        
        return view('website.web.student.ai.results', compact('student', 'rankings', 'stats'));
    }

    /**
     * دووبارەکردنەوەی تاقیکردنەوە
     */
    public function retake()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student || $student->ai_rank != 1) {
            return redirect()->back()->with('error', 'مۆڵەتی بەکارهێنانی سیستەمی AIت نییە.');
        }
        
        // سڕینەوەی هەموو زانیاریەکانی پێشوو
        AIAnswer::where('student_id', $student->id)->delete();
        AIRanking::where('student_id', $student->id)->delete();
        
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
        
        if (!$student || $student->ai_rank != 1) {
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
        
        $options = json_decode($question->options, true);
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
            $factors = json_decode($ranking->match_factors, true) ?? [];
            
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
}