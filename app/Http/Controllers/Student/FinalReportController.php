<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ResultDep;
use App\Models\AIRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinalReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'student']);
    }

    /**
     * Display the final report containing chosen departments and AI rankings.
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

        // AI recommended departments from ai_rankings
        $aiRankings = AIRanking::where('student_id', $student->id)
            ->with(['department.university', 'department.system', 'department.province', 'department.college'])
            ->orderBy('rank', 'asc')
            ->get();

        return view('website.web.student.final-report.index', compact('student', 'chosenDepartments', 'aiRankings'));
    }
}
