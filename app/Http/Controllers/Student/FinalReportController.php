<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AIRanking;
use App\Models\ResultDep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinalReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'student']);
    }

    /**
     * Display the final report containing chosen departments.
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }

        // Chosen departments from result_deps
        $chosenDepartments = ResultDep::where('student_id', $student->id)
            ->with(['department.university', 'department.system', 'department.province', 'department.college'])
            ->orderBy('rank', 'asc')
            ->get();

        $aiRankings = collect();
        if ((int) ($student->ai_rank ?? 0) === 1) {
            $aiRankings = AIRanking::query()
                ->where('student_id', $student->id)
                ->with(['department.university', 'department.system', 'department.province', 'department.college'])
                ->orderBy('rank', 'asc')
                ->take(50)
                ->get();
        }

        return view('website.web.student.final-report.index', compact('student', 'chosenDepartments', 'aiRankings'));
    }
}
