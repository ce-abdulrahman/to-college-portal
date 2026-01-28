<?php
// app/Http\Controllers\Student\MbtiController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MbtiQuestion;
use App\Models\MbtiAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MbtiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'student']);
    }
    
    /**
     * نمایشی تاقیکردنەوە
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;
        
        // ئەگەر پێشتر تاقیکردنەوەی کردبێت
        if ($student->hasCompletedMbtiTest()) {
            return redirect()->route('student.mbti.result');
        }
        
        $questions = MbtiQuestion::getGroupedQuestions();
        
        return view('website.web.student.mbti.test', compact('questions', 'student'));
    }
    
    /**
     * وەرگرتنی وەڵامەکان
     */
    public function store(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer|min:1|max:10',
        ]);
        
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->back()->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }
        
        // سڕینەوەی وەڵامە کۆنەکان (ئەگەر هەبوون)
        MbtiAnswer::where('student_id', $student->id)->delete();
        
        // هەڵگرتنی وەڵامەکان
        foreach ($request->answers as $questionId => $score) {
            MbtiAnswer::create([
                'user_id' => $user->id,
                'student_id' => $student->id,
                'question_id' => $questionId,
                'score' => $score,
            ]);
        }
        
        // هەژمارکردنی ئەنجام
        $result = $student->calculateMbtiResult();
        
        // تۆمارکردنی ئەنجام
        $student->update(['mbti_type' => $result]);
        
        return redirect()->route('student.mbti.result')
                         ->with('success', 'تاقیکردنەوەکەت بە سەرکەوتوویی تەواو بوو!');
    }
    
    /**
     * نمایشی ئەنجام
     */
    public function result()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('student.dashboard')
                             ->with('error', 'تۆ قوتابی نیت یان زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }
        
        if (!$student->hasCompletedMbtiTest()) {
            return redirect()->route('student.mbti.index');
        }
        
        $answers = $student->mbtiAnswers()->with('question')->get();
        
        // هەژمارکردنی نمرەکان
        $scores = [
            'E' => 0, 'I' => 0,
            'S' => 0, 'N' => 0,
            'T' => 0, 'F' => 0,
            'J' => 0, 'P' => 0,
        ];
        
        foreach ($answers as $answer) {
            $side = $answer->question->side;
            $scores[$side] += $answer->score;
        }
        
        // زانیاریەکانی جۆری MBTI
        $mbtiInfo = $this->getMbtiTypeInfo($student->mbti_type);
        
        return view('website.web.student.mbti.result', compact('student', 'answers', 'scores', 'mbtiInfo'));
    }
    
    /**
     * دووبارەکردنەوەی تاقیکردنەوە
     */
    public function retake()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->back()->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }
        
        // سڕینەوەی هەموو زانیاریەکانی پێشوو
        MbtiAnswer::where('student_id', $student->id)->delete();
        $student->update(['mbti_type' => null]);
        
        return redirect()->route('student.mbti.index');
    }
    
    /**
     * وەرگرتنی زانیاری جۆرەکە
     */
    private function getMbtiTypeInfo($type)
    {
        $info = [
            'ISTJ' => [
                'title_ku' => 'پشکنینکار',
                'strengths' => ['ڕێکخراو', 'بەرپرسیار', 'پڕاکتیکی', 'وردبین'],
                'weaknesses' => ['زۆر ڕەسمی', 'نەگونجاو لەگەڵ گۆڕانکاری'],
                'careers' => ['بەڕێوەبەری', 'حسابات', 'پزیشکی'],
            ],
            // ... بۆ هەموو جۆرەکان
        ];
        
        return $info[$type] ?? [
            'title_ku' => 'نەناسراو',
            'strengths' => [],
            'weaknesses' => [],
            'careers' => [],
        ];
    }
}