<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MbtiQuestion;
use App\Models\MbtiAnswer;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MbtiResultsExport;

class MbtiAdminController extends Controller
{
    /**
     * نیشاندانی لیستی پرسیارەکان
     */
    public function index()
    {
        $questions = MbtiQuestion::orderBy('dimension')
                                 ->orderBy('side')
                                 ->orderBy('order')
                                 ->get();

        return view('website.web.admin.mbti.questions.index', compact('questions'));
    }

    /**
     * نیشاندانی فۆڕمی دروستکردنی پرسیاری نوێ
     */
    public function create()
    {
        $dimensions = [
            'EI' => 'ڕووکراوە (E) - داخراو (I)',
            'SN' => 'هەست (S) - ژیری (N)',
            'TF' => 'بیرکردنەوە (T) - هەست (F)',
            'JP' => 'ڕەچاوکردن (J) - تێگەیشتن (P)',
        ];

        $sides = [
            'EI' => ['E', 'I'],
            'SN' => ['S', 'N'],
            'TF' => ['T', 'F'],
            'JP' => ['J', 'P'],
        ];

        return view('website.web.admin.mbti.questions.create', compact('dimensions', 'sides'));
    }

    /**
     * هەڵگرتنی پرسیاری نوێ
     */
    public function store(Request $request)
    {
        $request->validate([
            'dimension' => 'required|in:EI,SN,TF,JP',
            'side' => 'required|in:E,I,S,N,T,F,J,P',
            'question_ku' => 'required|string|max:500',
            'question_en' => 'nullable|string|max:500',
            'order' => 'required|integer|min:1|max:100',
        ]);

        MbtiQuestion::create($request->all());

        return redirect()->route('admin.mbti.questions.index')
                         ->with('success', 'پرسیارەکە بە سەرکەوتوویی زیاد کرا.');
    }

    /**
     * نیشاندانی پرسیارێکی دیاریکراو
     */
    public function show(MbtiQuestion $question)
    {
        $question->load('answers.user');

        return view('website.web.admin.mbti.questions.show', compact('question'));
    }

    /**
     * نیشاندانی فۆڕمی دەستکاری
     */
    public function edit(MbtiQuestion $question)
    {
        $dimensions = [
            'EI' => 'ڕووکراوە (E) - داخراو (I)',
            'SN' => 'هەست (S) - ژیری (N)',
            'TF' => 'بیرکردنەوە (T) - هەست (F)',
            'JP' => 'ڕەچاوکردن (J) - تێگەیشتن (P)',
        ];

        $sides = [
            'EI' => ['E', 'I'],
            'SN' => ['S', 'N'],
            'TF' => ['T', 'F'],
            'JP' => ['J', 'P'],
        ];

        return view('website.web.admin.mbti.questions.edit', compact('question', 'dimensions', 'sides'));
    }

    /**
     * نوێکردنەوەی پرسیار
     */
    public function update(Request $request, MbtiQuestion $question)
    {
        $request->validate([
            'dimension' => 'required|in:EI,SN,TF,JP',
            'side' => 'required|in:E,I,S,N,T,F,J,P',
            'question_ku' => 'required|string|max:500',
            'question_en' => 'nullable|string|max:500',
            'order' => 'required|integer|min:1|max:100',
        ]);

        $question->update($request->all());

        return redirect()->route('admin.mbti.questions.index')
                         ->with('success', 'پرسیارەکە بە سەرکەوتوویی نوێکرایەوە.');
    }

    /**
     * سڕینەوەی پرسیار
     */
    public function destroy(MbtiQuestion $question)
    {
        if ($question->answers()->count() > 0) {
            return redirect()->back()
                             ->with('error', 'ناتوانیت ئەم پرسیارە بسڕیتەوە چونکە وەڵامی هەیە. سەرەتا وەڵامەکان بسڕەوە.');
        }

        $question->delete();

        return redirect()->route('admin.mbti.questions.index')
                         ->with('success', 'پرسیارەکە بە سەرکەوتوویی سڕایەوە.');
    }

    /**
     * نیشاندانی هەموو ئەنجامەکان
     */
    public function results()
    {
        $students = Student::whereNotNull('mbti_type')
                    ->with(['mbtiAnswers' => function($query) {
                        $query->with('question');
                    }])
                    ->orderBy('mbti_type')
                    ->get();

        $mbtiTypes = [
            'ISTJ', 'ISFJ', 'INFJ', 'INTJ',
            'ISTP', 'ISFP', 'INFP', 'INTP',
            'ESTP', 'ESFP', 'ENFP', 'ENTP',
            'ESTJ', 'ESFJ', 'ENFJ', 'ENTJ'
        ];

        $statistics = $this->getStatistics();

        return view('website.web.admin.mbti.results.index', compact('students', 'mbtiTypes', 'statistics'));
    }

    /**
     * نیشاندانی ئەنجامی خوێندکارێکی دیاریکراو
     */
    public function showUserResult(string $id)
    {
        $student = Student::findOrFail($id);
        if (!$student->mbti_type) {
            return redirect()->route('admin.mbti.results.index')
                             ->with('error', 'ئەم خوێندکارە تاقیکردنەوەی MBTI نەکردووە.');
        }

        $answers = $student->mbtiAnswers()->with('question')->get();

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

        $strengths = $this->getMbtiTypeStrengths($student->mbti_type);
        $weaknesses = $this->getMbtiTypeWeaknesses($student->mbti_type);
        $careers = $this->getMbtiTypeCareers($student->mbti_type);
        $workStyles = $this->getMbtiTypeWorkStyles($student->mbti_type);

        return view('website.web.admin.mbti.results.show', compact('student', 'answers', 'scores', 'strengths', 'weaknesses', 'careers', 'workStyles'));
    }

    /**
     * وەرگرتنی لایەنی بەهێزییەکانی جۆری MBTI
     */
    private function getMbtiTypeStrengths($type)
    {
        $strengths = [
            'ISTJ' => ['ڕێکخراو', 'بەرپرسیار', 'پڕاکتیکی', 'وردبین', 'بەڵێندەر'],
            'ISFJ' => ['پارێزەر', 'پشتگیری', 'وردبین', 'خۆشەویست', 'بەرپرسیار'],
            'INFJ' => ['ڕاوێژکار', 'داهێنەر', 'هاوسۆز', 'پێشبینیکەر', 'بەرزان'],
            'INTJ' => ['ستراتیژیست', 'سەربەخۆ', 'بیرکار', 'پێشبینیکەر', 'بەرپرسیار'],
            'ISTP' => ['چارەسەرکەر', 'ئارام', 'پڕاکتیکی', 'ئەکسیۆن-ئۆرینتد', 'سەربەخۆ'],
            'ISFP' => ['هونەرمەند', 'داهێنەر', 'ئارام', 'هاوسۆز', 'بەخێوکەر'],
            'INFP' => ['ئایدیالیست', 'داهێنەر', 'بەرزان', 'هاوسۆز', 'خۆشەویست'],
            'INTP' => ['بیرکار', 'نەریتی', 'کنجکاو', 'سەربەخۆ', 'پاکیزە'],
            'ESTP' => ['بەهرەمەند', 'ئەنەرجەتیک', 'پڕاکتیکی', 'سەرگەرم', 'گۆڕانکار'],
            'ESFP' => ['ڕابەر', 'سۆزی', 'ئەنەرجەتیک', 'بەخێوکەر', 'دڵخۆشکەر'],
            'ENFP' => ['پیشاندەر', 'داهێنەر', 'ئەنەرجەتیک', 'هاوسۆز', 'خۆشەویست'],
            'ENTP' => ['داهێنەر', 'بیرکار', 'کنجکاو', 'ئەنەرجەتیک', 'گۆڕانکار'],
            'ESTJ' => ['بەڕێوەبەر', 'ڕێکخراو', 'پڕاکتیکی', 'بەرپرسیار', 'کاربەدەست'],
            'ESFJ' => ['بەخێوکەر', 'کۆمەڵایەتی', 'پشتگیری', 'بەرپرسیار', 'خۆشەویست'],
            'ENFJ' => ['مامۆستا', 'هاوسۆز', 'پەرەپێدەر', 'بەرپرسیار', 'بەخێوکەر'],
            'ENTJ' => ['فەرماندە', 'ستراتیژیست', 'بەڕێوەبەر', 'بەرپرسیار', 'کاریگەر'],
        ];

        return $strengths[$type] ?? ['زانیاری بەردەست نییە'];
    }

    /**
     * وەرگرتنی لەتەکانی جۆری MBTI
     */
    private function getMbtiTypeWeaknesses($type)
    {
        $weaknesses = [
            'ISTJ' => ['زۆر ڕەسمی', 'نەگونجاو لەگەڵ گۆڕانکاری', 'کەمەلایەنی داهێنان', 'قورسی دەرکردنی بڕیار'],
            'ISFJ' => ['زۆر هەستیار', 'قورسی نەبوونەوە', 'کەمەلایەنی سەربەخۆیی', 'نەگونجاو بۆ ئەرکی قورس'],
            'INFJ' => ['زۆر تەنیاخواز', 'قورسی دەرکردنی بڕیار', 'نەگونجاو لەگەڵ ڕق', 'زۆر کەمالخواز'],
            'INTJ' => ['زۆر ڕەسمی', 'کەمەلایەنی هەستیار', 'قورسی پەیوەندی کەسی', 'بێزارکەر لەگەڵ بێ ئەندازەیی'],
            'ISTP' => ['بێزارکەر لەگەڵ ڕەوتین', 'کەمەلایەنی پلاندانان', 'زۆر ڕێک', 'قورسی دەربڕینی هەست'],
            'ISFP' => ['زۆر هەستیار', 'بێ پلان', 'قورسی دەرکردنی بڕیار', 'نەگونجاو بۆ فشاری زۆر'],
            'INFP' => ['زۆر کەمالخواز', 'قورسی ڕووکەشتن', 'زۆر هەستیار', 'نەگونجاو لەگەڵ ڕق'],
            'INTP' => ['زۆر تەنیاخواز', 'قورسی پەیوەندی کەسی', 'بێزارکەر لەگەڵ ڕەوتین', 'زۆر ڕێک'],
            'ESTP' => ['بێزارکەر لەگەڵ ڕەوتین', 'کەمەلایەنی پلاندانان', 'سەرگەرمی زۆر', 'قورسی تەمەرکیز'],
            'ESFP' => ['کەمەلایەنی پلاندانان', 'نەگونجاو بۆ کارێکی تەنیا', 'سەرگەرمی زۆر', 'قورسی تەمەرکیز'],
            'ENFP' => ['بێ پلان', 'قورسی تەمەرکیز', 'زۆر سەرگەرم', 'نەگونجاو لەگەڵ ڕەوتین'],
            'ENTP' => ['بێزارکەر لەگەڵ ڕەوتین', 'قورسی تەمەرکیز', 'زۆر دەمەقاڵێکار', 'نەگونجاو بۆ کارێکی تەنیا'],
            'ESTJ' => ['زۆر ڕەسمی', 'نەگونجاو لەگەڵ گۆڕانکاری', 'کەمەلایەنی هەستیار', 'بێزارکەر لەگەڵ بێ ئەندازەیی'],
            'ESFJ' => ['زۆر هەستیار', 'قورسی نەبوونەوە', 'نەگونجاو بۆ ڕق', 'زۆر کۆنترۆڵکار'],
            'ENFJ' => ['زۆر هەستیار', 'قورسی نەبوونەوە', 'کەمەلایەنی سەربەخۆیی', 'نەگونجاو بۆ ڕق'],
            'ENTJ' => ['زۆر ڕەسمی', 'کەمەلایەنی هەستیار', 'بێزارکەر لەگەڵ بێ ئەندازەیی', 'قورسی پەیوەندی کەسی'],
        ];

        return $weaknesses[$type] ?? ['زانیاری بەردەست نییە'];
    }

    /**
     * وەرگرتنی کارە گونجاوەکان
     */
    private function getMbtiTypeCareers($type)
    {
        $careers = [
            'ISTJ' => ['بەڕێوەبەری', 'حسابات', 'پزیشکی', 'پۆلیس', 'بارەگا ڕێکخەر'],
            'ISFJ' => ['مامۆستا', 'نەرس', 'سێکرتێر', 'کەسانی چاودێری', 'کتێبدار'],
            'INFJ' => ['پزیشکی دەروونی', 'ڕاوێژکار', 'مامۆستا', 'نووسەر', 'ئایینپەروەر'],
            'INTJ' => ['بیرکار', 'پڕۆگرامساز', 'پزیشک', 'ئەندازیار', 'بەڕێوەبەر'],
            'ISTP' => ['ئەندازیار', 'تەکنیسین', 'پۆلیس', 'فڕۆکەوان', 'میکانیک'],
            'ISFP' => ['هونەرمەند', 'مامۆستا', 'پارێزەری ژینگە', 'پزیشکی ئاژەڵ', 'دیزاینەر'],
            'INFP' => ['نووسەر', 'مامۆستا', 'ڕاوێژکار', 'پزیشکی دەروونی', 'هونەرمەند'],
            'INTP' => ['بیرکار', 'پڕۆگرامساز', 'تاقیکار', 'گەردوونناس', 'فەیلەسوف'],
            'ESTP' => ['بازرگان', 'پۆلیس', 'خۆشدوو', 'یاریزان', 'ڕێکخەری ڕووداو'],
            'ESFP' => ['هونەرمەند', 'مامۆستا', 'ڕێکخەری ڕووداو', 'خۆشدوو', 'کارمەندی مارکێتینگ'],
            'ENFP' => ['مامۆستا', 'ڕاوێژکار', 'ژورنالیست', 'کارمەندی مارکێتینگ', 'سیاسەتمەدار'],
            'ENTP' => ['بازرگان', 'پڕۆگرامساز', 'پەرەپێدەر', 'ئەندازیار', 'پزیشک'],
            'ESTJ' => ['بەڕێوەبەر', 'حسابات', 'پۆلیس', 'سەرباز', 'بارەگا ڕێکخەر'],
            'ESFJ' => ['مامۆستا', 'نەرس', 'سێکرتێر', 'کارمەندانی کۆمەڵایەتی', 'ڕێکخەری ڕووداو'],
            'ENFJ' => ['مامۆستا', 'ڕاوێژکار', 'کارمەندانی کۆمەڵایەتی', 'پەرەپێدەری کەسایەتی', 'سیاسەتمەدار'],
            'ENTJ' => ['بەڕێوەبەر', 'ئەندازیار', 'پزیشک', 'پڕۆگرامساز', 'سەرباز'],
        ];

        return $careers[$type] ?? ['زانیاری بەردەست نییە'];
    }

    /**
     * وەرگرتنی شێوازی کارکردن
     */
    private function getMbtiTypeWorkStyles($type)
    {
        $workStyles = [
            'ISTJ' => [
                'شێوازی کارکردن' => ['ڕێکوپێک', 'پڕۆسەیی', 'وردبین', 'بەڵێندەر'],
                'کار لەگەڵ کەسەکان' => ['پشتگیری', 'بەرپرسیار', 'دیسیپلیندار', 'ڕاستگۆ'],
                'بەرەوپێشبردنی کار' => ['پلانبەند', 'ردەوامی', 'وردبین', 'پێشبینیکەر'],
                'بەرەنگاربوون' => ['بەرپرسیار', 'پڕاکتیکی', 'ڕاستگۆ', 'بەڵێндەر']
            ],
            'ISFJ' => [
                'شێوازی کارکردن' => ['پشتگیری', 'وردبین', 'تەکبەخۆ', 'حەساس'],
                'کار لەگەڵ کەسەکان' => ['چاودێریکەر', 'هاوسۆز', 'پشتگیری', 'خۆشەویست'],
                'بەرەوپێشبردنی کار' => ['ڕێکخراو', 'زیندوو', 'وردبین', 'سەرنج لەسەر وردەکاری'],
                'بەرەنگاربوون' => ['بەرپرسیار', 'ئارام', 'پشتگیری', 'خۆگونجێنەر']
            ],
            'INFJ' => [
                'شێوازی کارکردن' => ['داهێنەر', 'بەرزان', 'ڕێکخراو', 'قووڵ'],
                'کار لەگەڵ کەسەکان' => ['هاوسۆز', 'ئیلهامبەخش', 'پشتگیری', 'ڕاستگۆ'],
                'بەرەوپێشبردنی کار' => ['پلانبەندی ماوەی درێژ', 'داهێنەر', 'سەرنج لەسەر بنەما', 'سەربەخۆ'],
                'بەرەنگاربوون' => ['هاوسۆز', 'بەرزان', 'ڕاستگۆ', 'خۆگونجێنەر']
            ],
            'INTJ' => [
                'شێوازی کارکردن' => ['ستراتیژی', 'بیرکاری', 'سەربەخۆ', 'داهێنەر'],
                'کار لەگەڵ کەسەکان' => ['ڕاستەوخۆ', 'ڕاستگۆ', 'ڕێزدار', 'نا حەساس'],
                'بەرەوپێشبردنی کار' => ['پلانبەند', 'بیرکاری', 'سەربەخۆ', 'سەرنج لەسەر ئامانج'],
                'بەرەنگاربوون' => ['بیرکاری', 'داهێنەر', 'ڕاستگۆ', 'بەهێز']
            ],
            'ISTP' => [
                'شێوازی کارکردن' => ['پڕاکتیکی', 'خۆگونجێنەر', 'حلکەری کێشە', 'ژێر فشاری کار'],
                'کار لەگەڵ کەسەکان' => ['سەربەخۆ', 'ئارام', 'ڕاستگۆ', 'نا حەساس'],
                'بەرەوپێشبردنی کار' => ['خۆگونجێنەر', 'سەرنج لەسەر سات', 'حلکەری کێشە', 'پڕاکتیکی'],
                'بەرەنگاربوون' => ['ئارام', 'پڕاکتیکی', 'خۆگونجێنەر', 'حلکەری کێشە']
            ],
            'ISFP' => [
                'شێوازی کارکردن' => ['داهێنەر', 'حەساس', 'ئارام', 'خۆشەویست'],
                'کار لەگەڵ کەسەکان' => ['هاوسۆز', 'بەخشندە', 'پشتگیری', 'چاودێریکەر'],
                'بەرەوپێشبردنی کار' => ['هەستی', 'ئارام', 'داهێنەر', 'خۆگونجێنەر'],
                'بەرەنگاربوون' => ['هاوسۆز', 'هەستی', 'پشتگیری', 'ئارام']
            ],
            'INFP' => [
                'شێوازی کارکردن' => ['داهێنەر', 'بەرزان', 'خۆگونجێنەر', 'قووڵ'],
                'کار لەگەڵ کەسەکان' => ['هاوسۆز', 'ڕاستگۆ', 'پشتگیری', 'خۆشەویست'],
                'بەرەوپێشبردنی کار' => ['داهێنەر', 'خۆگونجێنەر', 'سەرنج لەسەر بنەما', 'سەربەخۆ'],
                'بەرەنگاربوون' => ['هاوسۆز', 'بەرزان', 'ڕاستگۆ', 'خۆگونجێنەر']
            ],
            'INTP' => [
                'شێوازی کارکردن' => ['شیکاری', 'داهێنەر', 'سەربەخۆ', 'بیرکاری'],
                'کار لەگەڵ کەسەکان' => ['سەربەخۆ', 'ڕاستگۆ', 'بێلایەن', 'نا حەساس'],
                'بەرەوپێشبردنی کار' => ['شیکاری', 'داهێنەر', 'سەربەخۆ', 'سەرنج لەسەر بیرۆکە'],
                'بەرەنگاربوون' => ['بیرکاری', 'داهێنەر', 'ڕاستگۆ', 'ئارام']
            ],
            'ESTP' => [
                'شێوازی کارکردن' => ['پڕاکتیکی', 'سەرگەرم', 'خێرا', 'خۆگونجێنەر'],
                'کار لەگەڵ کەسەکان' => ['کۆمەڵایەتی', 'قایلکەر', 'پێکەنیناوی', 'ڕاستەوخۆ'],
                'بەرەوپێشبردنی کار' => ['سەرنج لەسەر سات', 'خێرا', 'پڕاکتیکی', 'خۆگونجێنەر'],
                'بەرەنگاربوون' => ['سەرگەرم', 'پڕاکتیکی', 'خۆگونجێنەر', 'حلکەری کێشە']
            ],
            'ESFP' => [
                'شێوازی کارکردن' => ['کۆمەڵایەتی', 'خۆگونجێنەر', 'پڕاکتیکی', 'خۆشەویست'],
                'کار لەگەڵ کەسەکان' => ['خۆشەویست', 'هاوسۆز', 'پێکەنیناوی', 'پشتگیری'],
                'بەرەوپێشبردنی کار' => ['خۆگونجێنەر', 'سەرنج لەسەر سات', 'پڕاکتیکی', 'کۆمەڵایەتی'],
                'بەرەنگاربوون' => ['خۆگونجێنەر', 'کۆمەڵایەتی', 'هاوسۆز', 'پڕاکتیکی']
            ],
            'ENFP' => [
                'شێوازی کارکردن' => ['داهێنەر', 'خۆگونجێنەر', 'ئیلهامبەخش', 'کۆمەڵایەتی'],
                'کار لەگەڵ کەسەکان' => ['پەرۆش', 'هاوسۆز', 'ئیلهامبەخش', 'پشتگیری'],
                'بەرەوپێشبردنی کار' => ['داهێنەر', 'خۆگونجێنەر', 'ئیلهامبەخش', 'کارکردن لەسەر چەند پرۆژە'],
                'بەرەنگاربوون' => ['داهێنەر', 'خۆگونجێنەر', 'هاوسۆز', 'ئیلهامبەخش']
            ],
            'ENTP' => [
                'شێوازی کارکردن' => ['داهێنەر', 'ستراتیژی', 'کنجکاو', 'بیرکاری'],
                'کار لەگەڵ کەسەکان' => ['قایلکەر', 'کۆمەڵایەتی', 'ڕوون', 'گفتوگۆچی'],
                'بەرەوپێشبردنی کار' => ['داهێنەر', 'ستراتیژی', 'خۆگونجێنەر', 'کارکردن لەسەر بیرۆکەی نوێ'],
                'بەرەنگاربوون' => ['داهێنەر', 'خۆگونجێنەر', 'بیرکاری', 'حەزی لە ململانێی بیرکاری']
            ],
            'ESTJ' => [
                'شێوازی کارکردن' => ['ڕێکخراو', 'پڕاکتیکی', 'بەهێز', 'باش'],
                'کار لەگەڵ کەسەکان' => ['ڕاستەوخۆ', 'ڕێکخراو', 'ڕاستگۆ', 'سەرکردە'],
                'بەرەوپێشبردنی کار' => ['پلانبەند', 'ڕێکخراو', 'پڕاکتیکی', 'سەرنج لەسەر ئامانج'],
                'بەرەنگاربوون' => ['بەهێز', 'پڕاکتیکی', 'ڕێکخراو', 'بەرپرسیار']
            ],
            'ESFJ' => [
                'شێوازی کارکردن' => ['ڕێکخراو', 'پشتگیری', 'پڕاکتیکی', 'کۆمەڵایەتی'],
                'کار لەگەڵ کەسەکان' => ['خۆشەویست', 'پشتگیری', 'هاوسۆز', 'ڕێکخراو'],
                'بەرەوپێشبردنی کار' => ['ڕێکخراو', 'پشتگیری', 'پڕاکتیکی', 'سەرنج لەسەر خەڵک'],
                'بەرەنگاربوون' => ['پشتگیری', 'بەرپرسیار', 'هاوسۆز', 'ڕێکخراو']
            ],
            'ENFJ' => [
                'شێوازی کارکردن' => ['ئیلهامبەخش', 'ڕێکخراو', 'پشتگیری', 'کۆمەڵایەتی'],
                'کار لەگەڵ کەسەکان' => ['هاوسۆز', 'ئیلهامبەخش', 'پشتگیری', 'سەرکردە'],
                'بەرەوپێشبردنی کار' => ['ڕێکخراو', 'ئیلهامبەخش', 'سەرنج لەسەر خەڵک', 'پلانبەند'],
                'بەرەنگاربوون' => ['هاوسۆز', 'ئیلهامبەخش', 'پشتگیری', 'بەرپرسیار']
            ],
            'ENTJ' => [
                'شێوازی کارکردن' => ['ستراتیژی', 'بەهێز', 'ڕێکخراو', 'سەرکردە'],
                'کار لەگەڵ کەسەکان' => ['ڕاستەوخۆ', 'بەهێز', 'ئیلهامبەخش', 'سەرکردە'],
                'بەرەوپێشبردنی کار' => ['پلانبەند', 'ستراتیژی', 'بەهێز', 'سەرنج لەسەر ئامانج'],
                'بەرەنگاربوون' => ['بەهێز', 'ستراتیژی', 'سەرکردە', 'بەرپرسیار']
            ],
        ];

        return $workStyles[$type] ?? [
            'شێوازی کارکردن' => ['زانیاری بەردەست نییە'],
            'کار لەگەڵ کەسەکان' => ['زانیاری بەردەست نییە'],
            'بەرەوپێشبردنی کار' => ['زانیاری بەردەست نییە'],
            'بەرەنگاربوون' => ['زانیاری بەردەست نییە']
        ];
    }

    /**
     * سڕینەوەی ئەنجامی خوێندکارێک
     */
    public function deleteUserResult(Student $student)
    {
        $student->mbtiAnswers()->delete();
        $student->update(['mbti_type' => null]);

        return redirect()->route('admin.mbti.results')
                         ->with('success', 'ئەنجامەکانی خوێندکار بە سەرکەوتوویی سڕایەوە.');
    }

    /**
     * فیلتەرکردنی ئەنجامەکان بەپێی جۆری MBTI
     */
    public function filterResults(Request $request)
    {
        $type = $request->get('type');

        $query = Student::whereNotNull('mbti_type');

        if ($type && $type != 'all') {
            $query->where('mbti_type', $type);
        }

        $students = $query->orderBy('mbti_type')->paginate(20);

        $mbtiTypes = [
            'ISTJ', 'ISFJ', 'INFJ', 'INTJ',
            'ISTP', 'ISFP', 'INFP', 'INTP',
            'ESTP', 'ESFP', 'ENFP', 'ENTP',
            'ESTJ', 'ESFJ', 'ENFJ', 'ENTJ'
        ];

        $statistics = $this->getStatistics();

        return view('website.web.admin.mbti.results.index', compact('students', 'mbtiTypes', 'statistics', 'type'));
    }

    public function getResultsData(Request $request)
    {
        $query = Student::with(['user', 'mbtiAnswers']);

        // Apply type filter if provided
        if ($request->has('type') && !empty($request->type)) {
            $query->where('mbti_type', $request->type);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        return DataTables::eloquent($query)
            ->addColumn('user_name', function($student) {
                return $student->user->name ?? 'N/A';
            })
            ->addColumn('user_code', function($student) {
                return $student->user->code ?? 'N/A';
            })
            ->addColumn('mbti_type_badge', function($student) {
                if ($student->mbti_type) {
                    return '<span class="badge bg-primary">' . $student->mbti_type . '</span>';
                }
                return '<span class="badge bg-secondary">نەکراوە</span>';
            })
            ->addColumn('answers_count', function($student) {
                return '<span class="badge bg-info">' . $student->mbtiAnswers->count() . '</span>';
            })
            ->addColumn('last_test_date', function($student) {
                if ($student->mbtiAnswers->count() > 0) {
                    return $student->mbtiAnswers->first()->created_at->format('Y/m/d H:i');
                }
                return '<small class="text-muted">-</small>';
            })
            ->addColumn('actions', function($student) {
                $showUrl = route('admin.mbti.results.show', $student->id);
                $deleteUrl = route('admin.mbti.results.delete', $student->id);

                return '
                    <div class="btn-group btn-group-sm">
                        <a href="' . $showUrl . '" class="btn btn-info" title="بینین">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-danger delete-result"
                                data-id="' . $student->id . '" data-url="' . $deleteUrl . '" title="سڕینەوە">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->filterColumn('user_name', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('code', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['mbti_type_badge', 'answers_count', 'last_test_date', 'actions'])
            ->make(true);
    }

    /**
     * ئاماری گشتی
     */
    public function statistics()
    {
        $statistics = $this->getStatistics(true);

        return view('website.web.admin.mbti.statistics', compact('statistics'));
    }

    /**
     * هەژمارکردنی ئامارەکان
     */
    private function getStatistics($detailed = false)
    {
        $stats = [
            'total_students' => Student::count(),
            'tested_students' => Student::whereNotNull('mbti_type')->count(),
            'untested_students' => Student::whereNull('mbti_type')->count(),
            'total_answers' => MbtiAnswer::count(),
        ];

        if ($detailed) {
            $typeDistribution = Student::whereNotNull('mbti_type')
                                   ->select('mbti_type', \DB::raw('count(*) as count'))
                                   ->groupBy('mbti_type')
                                   ->orderBy('count', 'desc')
                                   ->get()
                                   ->pluck('count', 'mbti_type');

            $stats['type_distribution'] = $typeDistribution;

            $averageScores = MbtiAnswer::join('mbti_questions', 'mbti_answers.question_id', '=', 'mbti_questions.id')
                                      ->select(
                                          'mbti_questions.dimension',
                                          'mbti_questions.side',
                                          \DB::raw('AVG(mbti_answers.score) as average_score')
                                      )
                                      ->groupBy('mbti_questions.dimension', 'mbti_questions.side')
                                      ->get();

            $dimensionAverages = [];
            foreach ($averageScores as $score) {
                $dimensionAverages[$score->dimension][$score->side] = round($score->average_score, 2);
            }

            $stats['dimension_averages'] = $dimensionAverages;

            $mostCommonType = $typeDistribution->sortDesc()->keys()->first();
            $leastCommonType = $typeDistribution->sort()->keys()->first();

            $stats['most_common_type'] = $mostCommonType;
            $stats['least_common_type'] = $leastCommonType;
        }

        return $stats;
    }

    /**
     * Export کردنی ئەنجامەکان بە فۆرماتی Excel
     */
    public function exportResults()
    {
        return Excel::download(new MbtiResultsExport, 'ئەنجامەکانی-MBTI-' . date('Y-m-d') . '.xlsx');
    }
}
