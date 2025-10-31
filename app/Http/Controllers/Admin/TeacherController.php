<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class TeacherController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'teacher')->get();
        return view('website.web.admin.user.teacher.index', compact('users'));
    }
}
