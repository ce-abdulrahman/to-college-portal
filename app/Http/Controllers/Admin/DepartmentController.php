<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $systems = System::where('status', 1)->get();
        $provinces = Province::where('status', 1)->get();
        $universities = University::where('status', 1)->get();
        $departments = Department::with('college.university.province.system')->get();
        return view('website.web.admin.department.index', compact('departments', 'systems', 'universities', 'provinces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $colleges = College::where('status', 1)->get();
        $universities = University::where('status', 1)->get();
        $provinces = Province::where('status', 1)->get();
        $systems = System::where('status', 1)->get();
        return view('website.web.admin.department.create', compact('colleges', 'universities', 'provinces', 'systems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'system_id' => 'required|exists:systems,id',
            'province_id' => 'required|exists:provinces,id',
            'university_id' => 'required|exists:universities,id',
            'college_id' => 'required|exists:colleges,id',
            'name' => 'required|string',
            'local_score' => 'required|numeric|min:50|max:100',
            'internal_score' => 'required|numeric|min:50|max:100',
            'type' => 'required|in:زانستی,وێژەیی,زانستی و وێژەیی',
            'sex' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $dep = new Department();
        $dep->system_id = $validated['system_id'];
        $dep->province_id = $validated['province_id'];
        $dep->university_id = $validated['university_id'];
        $dep->college_id = $validated['college_id'];
        $dep->name = $validated['name'];
        $dep->local_score = $validated['local_score'];
        $dep->internal_score = $validated['internal_score'];
        $dep->type = $validated['type'];
        $dep->sex = $validated['sex'];
        $dep->description = $validated['description'];
        $dep->status = $validated['status'];
        $dep->save();

        return redirect()->route('admin.departments.index')->with('success', 'بەشەک بەسەرکەوتووی دروستکرا.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::with('college.university.province.system')->findOrFail($id);
        return view('website.web.admin.department.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $systems = System::where('status', 1)->get();
        $provinces = Province::where('status', 1)->get();
        $universities = University::where('status', 1)->get();
        $colleges = College::where('status', 1)->get();
        $department = Department::with('college.university.province.system')->findOrFail($id);

        $compact = compact('department', 'colleges', 'universities', 'provinces', 'systems');

        return view('website.web.admin.department.edit', $compact);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'name' => 'required|string|unique:departments,name,' . $department->id,
            'local_score' => 'required|numeric|min:50|max:100',
            'internal_score' => 'required|numeric|min:50|max:100',
            'type' => 'required|in:زانستی,وێژەیی,زانستی و وێژەیی',
            'sex' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.index')->with('success', 'بەشەک بەسەرکەوتووی نوێکرا.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'بەشەک بەسەرکەوتووی سڕاوە.');
    }

    // API to get universities based on province
    public function getUniversities(Request $request)
    {
        $provinceId = $request->query('province_id');
        $universities = University::where('province_id', $provinceId)->where('status', 1)->get();
        return response()->json($universities);
    }

    // API to get colleges based on university
    public function getColleges(Request $request)
    {
        $universityId = $request->query('university_id');
        $colleges = College::where('university_id', $universityId)->where('status', 1)->get();
        return response()->json($colleges);
    }

}
