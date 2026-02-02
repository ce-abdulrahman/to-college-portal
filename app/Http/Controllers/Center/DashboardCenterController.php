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
        $user = auth()->user();
        $center = $user->center;
        
        // Check if center has GIS feature enabled
        $hasGIS = $center && $center->gis == 1;
        
        // Show GIS map dashboard if enabled, otherwise show simple dashboard
        if ($hasGIS) {
            return view('website.web.center.dashboard-gis');
        } else {
            return view('website.web.center.dashboard-simple');
        }
    }

    public function departments()
    {
        return view('website.web.center.departments.index');
    }

    public function show(string $id)
    {
        $department = Department::findOrFail($id);
        return view('website.web.center.departments.show', compact('department'));
    }

}
