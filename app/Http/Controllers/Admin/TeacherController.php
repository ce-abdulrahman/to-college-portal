<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;

class TeacherController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'teacher')->get();
        return view('website.web.admin.user.teacher.index', compact('users'));
    }

    public function show(string $id)
    {
        $user = User::with('student')->findOrFail($id);

        $students = Student::where('referral_code', $user->rand_code)->get();

        //dd($students);
        return view('website.web.admin.user.teacher.show', compact( 'user', 'students'));

    }
}
