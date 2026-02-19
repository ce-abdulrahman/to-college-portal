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

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }
        
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
                'weaknesses' => ['زۆر ڕەسمی', 'نەگونجاو لەگەڵ گۆڕانکاری', 'کەم هەستیار'],
                'careers' => ['بەڕێوەبەر', 'حسابات', 'پزیشک'],
            ],
            'ISFJ' => [
                'title_ku' => 'پارێزەر',
                'strengths' => ['میهرەبان', 'پشتگیری‌کنەوە', 'وردبین', 'بەرپرسیار'],
                'weaknesses' => ['خۆی لە دوا دەخات', 'ترس لە گۆڕانکاری', 'زۆر پابەند'],
                'careers' => ['نەخۆشدار', 'مامۆستا', 'کارمەندی خزمەتگوزاری'],
            ],
            'INFJ' => [
                'title_ku' => 'ڕاوێژکار',
                'strengths' => ['داهێنەر', 'هاوسۆز', 'بینەر', 'پڕ لە مانا'],
                'weaknesses' => ['هەستیار', 'پەرفیکشنیست', 'پێویستی زۆر بە تەنهایی'],
                'careers' => ['ڕاوێژکار', 'روانناس', 'نووسەر'],
            ],
            'INTJ' => [
                'title_ku' => 'ستراتیژیست',
                'strengths' => ['بیرکردنەوەی قووڵ', 'ڕێکخراو', 'ئامانجدار', 'دۆزینەوەی چارەسەر'],
                'weaknesses' => ['زۆر ڕەسمی', 'کەم هەستیار', 'سەخت لە کۆمەڵایەتی'],
                'careers' => ['ئینجینێر', 'توێژەر', 'کارگێڕی پلان'],
            ],
            'ISTP' => [
                'title_ku' => 'چارەسەرکەر',
                'strengths' => ['پڕاکتیکی', 'ئازادفکر', 'ئاژاوه‌ناپذیر', 'چارەسەرکەر'],
                'weaknesses' => ['کەم پابەند', 'هەستیار نییە', 'زۆر بەخۆی دەبەستێت'],
                'careers' => ['تەکنیکەر', 'ئینجینێر', 'سەرجەمکار'],
            ],
            'ISFP' => [
                'title_ku' => 'هونەرمەند',
                'strengths' => ['هونەرمەند', 'میهرەبان', 'هەستیار', 'ئازادخواز'],
                'weaknesses' => ['کەم پلانکردن', 'هەستیار بە ڕەخنە', 'دەستی لەکار دەکێشێت'],
                'careers' => ['هونەرمەند', 'دیزاینەر', 'کاری دەستی'],
            ],
            'INFP' => [
                'title_ku' => 'ئایدیالیست',
                'strengths' => ['هاوسۆز', 'داهێنەر', 'باوەڕدار', 'خەیاڵپەرست'],
                'weaknesses' => ['هەستیار', 'پڕاکتیکی کەم', 'زۆر بەخۆی دەکێشێت'],
                'careers' => ['نووسەر', 'روانناس', 'کارمەندی کۆمەڵایەتی'],
            ],
            'INTP' => [
                'title_ku' => 'بیرکار',
                'strengths' => ['بیرکردنەوەی تیژ', 'توێژینەوەکار', 'چارەسەرکەر', 'ئازادفکر'],
                'weaknesses' => ['دواکەوتن', 'کەم کۆمەڵایەتی', 'ڕێکخستنی کەم'],
                'careers' => ['پڕۆگرامەر', 'زانستیار', 'توێژەر'],
            ],
            'ESTP' => [
                'title_ku' => 'بەهرەمەند',
                'strengths' => ['چالاک', 'ڕیسک‌خواز', 'کردەوەگر', 'گفتوگۆکار'],
                'weaknesses' => ['بەپەلە بڕیار دەدات', 'ڕێکخستنی کەم', 'هەستیار نییە'],
                'careers' => ['بازرگانی', 'فرۆشکار', 'ئاسایش/پۆلیس'],
            ],
            'ESFP' => [
                'title_ku' => 'پێشانگەڕ',
                'strengths' => ['کۆمەڵایەتی', 'خۆشەویست', 'داهێنەر', 'خۆشگوزەران'],
                'weaknesses' => ['ڕێکخستنی کەم', 'کەم خۆڕێکخستن', 'کەم بیر لە داهاتوو'],
                'careers' => ['هونەرمەند', 'پەیامگەڕ', 'کار لەگەڵ خەڵک'],
            ],
            'ENFP' => [
                'title_ku' => 'پیشاندەر',
                'strengths' => ['داهێنەر', 'چالاک', 'هاوسۆز', 'ئامادەی گۆڕانکاری'],
                'weaknesses' => ['ڕێکخستنی کەم', 'زۆر هەستیار', 'دواکەوتن'],
                'careers' => ['مارکێتەری', 'نووسەر', 'مامۆستا'],
            ],
            'ENTP' => [
                'title_ku' => 'مجادلەکار',
                'strengths' => ['بیرکردنەوەی تیژ', 'داهێنەر', 'مناظەرەکار', 'چارەسەرکەر'],
                'weaknesses' => ['ڕێکخستنی کەم', 'کەم تەحمل', 'کەم بەردەوام'],
                'careers' => ['پڕۆگرامەر', 'کارگێڕی پڕۆژە', 'بازرگان'],
            ],
            'ESTJ' => [
                'title_ku' => 'بەڕێوەبەر',
                'strengths' => ['ڕێکخراو', 'بەرپرسیار', 'دەستپێشخەر', 'واقعبین'],
                'weaknesses' => ['زۆر ڕەسمی', 'کەم هەستیار', 'سەخت لە گۆڕانکاری'],
                'careers' => ['بەڕێوەبەر', 'سەرپەرشتیار', 'کارگێڕ'],
            ],
            'ESFJ' => [
                'title_ku' => 'بەخێوکەر',
                'strengths' => ['هاوسۆز', 'یاری‌دەران', 'ڕێکخراو', 'کۆمەڵایەتی'],
                'weaknesses' => ['زۆر پێویستی بە پەسەند', 'هەستیار بە ڕەخنە', 'کەم گۆڕان'],
                'careers' => ['مامۆستا', 'نەخۆشدار', 'خزمەتگوزاری کۆمەڵایەتی'],
            ],
            'ENFJ' => [
                'title_ku' => 'مامۆستا',
                'strengths' => ['ڕابەر', 'هاوسۆز', 'هاندەر', 'کۆمەڵایەتی'],
                'weaknesses' => ['پێویستی بە پەسەند', 'زۆر بەرپرسیاری', 'هەستیار'],
                'careers' => ['مامۆستا', 'ڕاوێژکار', 'سەرپرشتیار'],
            ],
            'ENTJ' => [
                'title_ku' => 'فەرماندە',
                'strengths' => ['ڕابەرایەتی', 'ستراتیژیست', 'ئامانجدار', 'بەرپرسیار'],
                'weaknesses' => ['سەخت لە هەست', 'ڕەسمی', 'زۆر داواکاری'],
                'careers' => ['بەڕێوەبەر', 'کارگێڕی بازرگانی', 'سەرپەرشتیار'],
            ],
        ];
        
        return $info[$type] ?? [
            'title_ku' => 'نەناسراو',
            'strengths' => [],
            'weaknesses' => [],
            'careers' => [],
        ];
    }
}
