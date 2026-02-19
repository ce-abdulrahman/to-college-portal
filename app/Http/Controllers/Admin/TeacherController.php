<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;

class TeacherController extends Controller
{
    public function index()
    {
        $users = User::with('teacher')->where('role', 'teacher')->get();
        return view('website.web.admin.user.teacher.index', compact('users'));
    }

    public function show(string $id)
    {
        $user = User::with('student')->findOrFail($id);

        $students = Student::with('user')->where('referral_code', $user->rand_code)->get();

        return view('website.web.admin.user.teacher.show', compact(
            'user',
            'students'
        ));

    }
}
