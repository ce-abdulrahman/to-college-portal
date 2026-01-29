<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardStudentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;
        
        // Check if student has GIS feature enabled
        $hasGIS = $student && $student->gis == 1;
        
        // Show GIS map dashboard if enabled, otherwise show simple dashboard
        if ($hasGIS) {
            return view('website.web.student.dashboard-gis');
        } else {
            return view('website.web.student.dashboard-simple');
        }
    }
}
