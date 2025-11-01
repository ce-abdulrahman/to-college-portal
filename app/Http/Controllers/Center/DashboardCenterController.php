<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;
use Illuminate\Http\Request;

class DashboardCenterController extends Controller
{
    public function index()
    {
        return view('website.web.center.index');
    }

    public function departments()
    {
        $systems = System::where('status', 1)->get();
        $provinces = Province::where('status', 1)->get();
        $universities = University::where('status', 1)->get();
        $colleges = College::where('status', 1)->get();
        $departments = Department::where('status', 1)->get();

        return view('website.web.center.departments.index', compact('departments', 'systems', 'provinces', 'universities', 'colleges'));
    }

    public function show(string $id)
    {
        $department = Department::findOrFail($id);
        return view('website.web.center.departments.show', compact('department'));
    }

}
