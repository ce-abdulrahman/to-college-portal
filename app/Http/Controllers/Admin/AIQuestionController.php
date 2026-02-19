<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AIQuestion;
use App\Models\AIAnswer;
use App\Models\Student;
use Illuminate\Http\Request;

class AIQuestionController extends Controller
{
    /**
     * نیشاندانی لیستی پرسیارەکانی AI
     */
    public function index()
    {
        $questions = AIQuestion::orderBy('category')
            ->orderBy('order')
            ->get();

        $categories = [
            'personality' => 'کەسایەتی',
            'interest' => 'حەز و ئارەزوو',
            'location' => 'شوێن',
            'priority' => 'پێشەنگی',
        ];

        return view('website.web.admin.ai.questions.index', compact('questions', 'categories'));
    }

    /**
     * نیشاندانی فۆڕمی دروستکردنی پرسیاری نوێ
     */
    public function create()
    {
        $categories = [
            'personality' => 'کەسایەتی',
            'interest' => 'حەز و ئارەزوو',
            'location' => 'شوێن',
            'priority' => 'پێشەنگی',
        ];

        return view('website.web.admin.ai.questions.create', compact('categories'));
    }

    /**
     * هەڵگرتنی پرسیاری نوێ
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|in:personality,interest,location,priority',
            'question_ku' => 'required|string|max:1000',
            'question_en' => 'nullable|string|max:1000',
            'options' => 'nullable|json',
            'weight' => 'required|numeric|between:0,10',
            'order' => 'required|integer|min:1',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->all();

        // ئاپشندەی options JSON بەگۆڕاندن
        if ($request->has('options') && !empty($request->options)) {
            $data['options'] = json_encode($request->options);
        }

        $data['status'] = $request->has('status');

        AIQuestion::create($data);

        return redirect()->route('admin.ai.questions.index')
            ->with('success', 'پرسیارەکە بە سەرکەوتوویی زیاد کرا.');
    }

    /**
     * نیشاندانی پرسیارێکی دیاریکراو
     */
    public function show(AIQuestion $question)
    {
        return view('website.web.admin.ai.questions.show', compact('question'));
    }

    /**
     * نیشاندانی فۆڕمی دەستکاری
     */
    public function edit(AIQuestion $question)
    {
        $categories = [
            'personality' => 'کەسایەتی',
            'interest' => 'حەز و ئارەزوو',
            'location' => 'شوێن',
            'priority' => 'پێشەنگی',
        ];

        return view('website.web.admin.ai.questions.edit', compact('question', 'categories'));
    }

    /**
     * نوێکردنەوەی پرسیار
     */
    public function update(Request $request, AIQuestion $question)
    {
        $request->validate([
            'category' => 'required|in:personality,interest,location,priority',
            'question_ku' => 'required|string|max:1000',
            'question_en' => 'nullable|string|max:1000',
            'options' => 'nullable|json',
            'weight' => 'required|numeric|between:0,10',
            'order' => 'required|integer|min:1',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->all();

        // ئاپشندەی options JSON بەگۆڕاندن
        if ($request->has('options') && !empty($request->options)) {
            $data['options'] = json_encode($request->options);
        }

        $data['status'] = $request->has('status');

        $question->update($data);

        return redirect()->route('admin.ai.questions.index')
            ->with('success', 'پرسیارەکە بە سەرکەوتوویی نوێکرایەوە.');
    }

    /**
     * سڕینەوەی پرسیار
     */
    public function destroy(AIQuestion $question)
    {
        if ($question->answers()->count() > 0) {
            return redirect()->back()
                ->with('error', 'ناتوانیت ئەم پرسیارە بسڕیتەوە چونکە وەڵامی هەیە.');
        }

        $question->delete();

        return redirect()->route('admin.ai.questions.index')
            ->with('success', 'پرسیارەکە بە سەرکەوتوویی سڕایەوە.');
    }

    /**
     * نیشاندانی وەڵامەکانی قوتابیان
     */
    public function results()
    {
        $students = Student::where('ai_rank', 1)
            ->with(['aiAnswers' => function($query) {
                $query->with('question');
            }])
            ->get();

        return view('website.web.admin.ai.answers.index', compact('students'));
    }

    /**
     * نیشاندانی وەڵامەکانی قوتابیی دیاریکراو
     */
    public function showStudentAnswers($studentId)
    {
        $student = Student::findOrFail($studentId);

        $answers = AIAnswer::where('student_id', $studentId)
            ->with('question')
            ->orderBy('question_id')
            ->get()
            ->groupBy('question.category');

        $categories = [
            'personality' => 'کەسایەتی',
            'interest' => 'حەز و ئارەزوو',
            'location' => 'شوێن',
            'priority' => 'پێشەنگی',
        ];

        return view('website.web.admin.ai.answers.show', compact('student', 'answers', 'categories'));
    }

    /**
     * سڕینەوەی وەڵامەکانی قوتابیی دیاریکراو
     */
    public function deleteStudentAnswers($studentId)
    {
        $student = Student::findOrFail($studentId);

        AIAnswer::where('student_id', $studentId)->delete();

        return redirect()->route('admin.ai.results')
            ->with('success', 'وەڵامەکانی ' . $student->user->name . ' بە سەرکەوتوویی سڕایەوە.');
    }

    /**
     * آپشندەی نوێکردنەوەی model relations
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
}
