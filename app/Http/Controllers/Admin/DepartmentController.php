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
use App\Traits\FileUploadTrait;

class DepartmentController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $systems = System::all();
        $provinces = Province::all();
        $universities = University::all();
        $departments = Department::all();
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
        $data = $request->validated();

        // point (optional)
        if ($request->filled('lat') && $request->filled('lng')) {
            $data['lat'] = (float) $request->lat;
            $data['lng'] = (float) $request->lng;
        }

        $data['image'] = $this->UploadImage($request, 'image');

        // status هەیە لە rules → دڵنیابە پاشەکەوت دەبێت
        // هەروەها هەموو خانەکانی rules هەمانە دێنە ناو $data

        $dep = new Department();
        $dep->system_id = $data['system_id'];
        $dep->province_id = $data['province_id'];
        $dep->university_id = $data['university_id'];
        $dep->college_id = $data['college_id'];
        $dep->name = $data['name'];
        $dep->name_en = $data['name_en'];
        $dep->local_score = $data['local_score'];
        $dep->external_score = $data['external_score'];
        $dep->type = $data['type'];
        $dep->sex = $data['sex'];
        $dep->image = $data['image'];
        $dep->lat = $data['lat'] ?? null;
        $dep->lng = $data['lng'] ?? null;
        $dep->description = $data['description'] ?? null;
        $dep->status = $data['status'];
        $dep->save();

        return redirect()->route('admin.departments.index')->with('success', 'بەشەک بەسەرکەوتووی دروستکرا.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::findOrFail($id);
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
        $department = Department::findOrFail($id);

        $compact = compact('department', 'colleges', 'universities', 'provinces', 'systems');

        return view('website.web.admin.department.edit', $compact);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentUpdateRequest $request, string $id)
    {
        $department = Department::findOrFail($id);
        $data = $request->validated();

        if ($request->filled('lat') && $request->filled('lng')) {
            $data['lat'] = (float) $request->lat;
            $data['lng'] = (float) $request->lng;
        } else {
            // هەلتە بێ‌جێ: ئەگەر خاڵی کەوتن، ناهێنینە NaN — هەموو شت بمانێنەوە وەکو هەبوون
            unset($data['lat'], $data['lng']);
        }

        $imagePath = $this->UploadImage($request, 'image', $department->image);
        $data['image'] = !empty($imagePath) ? $imagePath : $department->image;
        //dd($data);

        $department->update($data);

        return redirect()->route('admin.departments.index')->with('success', 'بەشەک بەسەرکەوتووی نوێکرا.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);

        if (!empty($department->geojson_path)) {
            Storage::disk('public')->delete($department->geojson_path);
        }

        $this->DeleteImage($department->image);

        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'بەشەک بەسەرکەوتووی سڕاوە.');
    }

    public function getUniversities(Request $request)
    {
        $pid = (int) $request->query('province_id');
        abort_if($pid <= 0, 422, 'پاریزگا نەدۆزرایەوە!');

        $universities = University::select('id', 'name')->where('province_id', $pid)->where('status', 1)->get();

        return response()->json($universities)->header('Cache-Control', 'no-store, max-age=0'); //لە Laravel ـدا دەتوانی no-cache لە وەڵامەکان زیاد بکەیت بۆ دڵنیابوون:
    }

    public function getColleges(Request $request)
    {
        $uid = (int) $request->query('university_id');
        abort_if($uid <= 0, 422, 'زانکۆ نەدۆزرایەوە!');

        $colleges = College::select('id', 'name')->where('university_id', $uid)->where('status', 1)->get();

        return response()->json($colleges)->header('Cache-Control', 'no-store, max-age=0'); //لە Laravel ـدا دەتوانی no-cache لە وەڵامەکان زیاد بکەیت بۆ دڵنیابوون:
    }
}
