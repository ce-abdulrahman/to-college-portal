<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;

use App\Http\Requests\Admin\DepartmentStoreRequest;
use App\Http\Requests\Admin\DepartmentUpdateRequest;
use App\Http\Resources\UniversityResource;
use App\Http\Resources\CollegeResource;

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
    public function store(DepartmentStoreRequest $request)
    {
        $d = new Department();
        $d->system_id = $request->validated('system_id');
        $d->province_id = $request->validated('province_id');
        $d->university_id = $request->validated('university_id');
        $d->college_id = $request->validated('college_id');
        $d->name = $request->validated('name');
        $d->local_score = $request->validated('local_score');
        $d->internal_score = $request->validated('internal_score');
        $d->type = $request->validated('type');
        $d->sex = $request->validated('sex');
        $d->description = $request->validated('description');
        $d->save();

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
    public function update(DepartmentUpdateRequest $request, string $id)
    {
        $department = Department::findOrFail($id);

        $department->update($request->validated());

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

    public function getUniversities(Request $request)
    {
        $pid = (int) $request->query('province_id');
        abort_if($pid <= 0, 422, 'پاریزگا نەدۆزرایەوە!');

        $universities = University::select('id', 'name')->where('province_id', $pid)->where('status', 1)->get();

        return response()->json($universities)
        ->header('Cache-Control', 'no-store, max-age=0'); //لە Laravel ـدا دەتوانی no-cache لە وەڵامەکان زیاد بکەیت بۆ دڵنیابوون:
    }

    public function getColleges(Request $request)
    {
        $uid = (int) $request->query('university_id');
        abort_if($uid <= 0, 422, 'زانکۆ نەدۆزرایەوە!');

        $colleges = College::select('id', 'name')->where('university_id', $uid)->where('status', 1)->get();

        return response()->json($colleges)
        ->header('Cache-Control', 'no-store, max-age=0'); //لە Laravel ـدا دەتوانی no-cache لە وەڵامەکان زیاد بکەیت بۆ دڵنیابوون:
    }
}
