<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;
use App\Models\student;

class CenterController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'center')->get();
        return view('website.web.admin.user.center.index', compact('users'));
    }

    public function show(string $id)
    {
        $user = User::with('teacher')->findOrFail($id);
        $teachers = Teacher::where('referral_code', $user->rand_code)->get();
        $teachersUser = Teacher::where('user_id', 11)->get();

        //$student = Student::where('referral_code', $teachers->rand_code)->get();
        //dd($student->user->id);
        return view('website.web.admin.user.center.show', compact('teachers', 'user'));

    }
}
