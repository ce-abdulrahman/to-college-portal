<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('website.web.teacher.index');
    }

    public function departments()
    {
        $systems = System::where('status', 1)->get();
        $provinces = Province::where('status', 1)->get();
        $universities = University::where('status', 1)->get();
        $colleges = College::where('status', 1)->get();
        $departments = Department::where('status', 1)->get();

        return view('website.web.teacher.departments.index', compact('departments', 'systems', 'provinces', 'universities', 'colleges'));
    }
}
