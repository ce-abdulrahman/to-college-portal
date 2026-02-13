<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ResultDep;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('user')
            ->withCount('resultDeps')
            ->whereHas('user', fn($q) => $q->where('role', 'student'))
            ->get();

        return view('website.web.admin.user.student.index', compact('students'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // student_id لە ڕوتەکەدات
        $student = Student::with('user')->findOrFail($id);
        $user = $student->user; // بە ئاسانی دەستی بە یوزەر دەگات

        $result_deps = ResultDep::with(['department.system', 'department.university', 'department.college', 'department.province'])
            ->where(function ($q) use ($user, $student) {
                $q->where('user_id', $user->id)->orWhere('student_id', $student->id);
            })
            ->get();

        return view('website.web.admin.user.student.show', compact('user', 'student', 'result_deps'));
    }


}
