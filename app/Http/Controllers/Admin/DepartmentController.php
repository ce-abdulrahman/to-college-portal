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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DepartmentsExport;
use App\Exports\DepartmentsTemplateExport;
use App\Imports\DepartmentsImport;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // بۆ AJAX بوونەکە
        if ($request->ajax()) {
            // چ فلتەرەکانی dropdown بە AJAX
            if ($request->get_filters) {
                return response()->json([
                    'systems' => Cache::remember('departments.systems', now()->addMinutes(10), function () {
                        return System::where('status', 1)->get(['id', 'name']);
                    }),
                    'provinces' => Cache::remember('departments.provinces.system:all', now()->addMinutes(10), function () {
                        return Province::where('status', 1)->get(['id', 'name']);
                    }),
                    'universities' => Cache::remember('departments.universities.system:all.province:all', now()->addMinutes(10), function () {
                        return University::where('status', 1)->get(['id', 'name']);
                    }),
                    'colleges' => Cache::remember('departments.colleges.system:all.university:all', now()->addMinutes(10), function () {
                        return College::where('status', 1)->get(['id', 'name']);
                    }),
                ]);
            }

            // جستجۆ و فلتەرکردن
            $query = Department::with(['university', 'college', 'system', 'province']);

            // جستجۆی ناوی بەش
            if ($request->filled('search')) {
                $search = trim((string) $request->search);
                $driver = DB::getDriverName();
                $boolean = collect(preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY))
                    ->map(function ($term) {
                        $term = preg_replace('/[^\p{L}\p{N}_]+/u', '', $term);
                        return $term ? '+' . $term . '*' : null;
                    })
                    ->filter()
                    ->implode(' ');

                $query->where(function($q) use ($search, $driver, $boolean) {
                    if (in_array($driver, ['mysql', 'mariadb'], true) && $boolean !== '') {
                        $q->whereRaw('MATCH(name, name_en) AGAINST (? IN BOOLEAN MODE)', [$boolean]);
                        $q->orWhere('name', 'like', "%{$search}%")
                          ->orWhere('name_en', 'like', "%{$search}%");
                    } else {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('name_en', 'like', "%{$search}%");
                    }

                    $q->orWhereHas('university', fn($u) => $u->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('college', fn($c) => $c->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('province', fn($p) => $p->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('system', fn($s) => $s->where('name', 'like', "%{$search}%"));
                });
            }

            // فلتەرکردن بەپێی سیستەم
            if ($request->filled('system_id')) {
                $query->where('system_id', $request->system_id);
            }

            // فلتەرکردن بەپێی پارێزگا
            if ($request->filled('province_id')) {
                $query->where('province_id', $request->province_id);
            }

            // فلتەرکردن بەپێی زانکۆ
            if ($request->filled('university_id')) {
                $query->where('university_id', $request->university_id);
            }

            // فلتەرکردن بەپێی پۆل
            if ($request->filled('college_id')) {
                $query->where('college_id', $request->college_id);
            }

            $departments = $query->paginate(10);

            return response()->json([
                'data' => $departments->items(),
                'total' => $departments->total(),
                'per_page' => $departments->perPage(),
                'current_page' => $departments->currentPage(),
                'last_page' => $departments->lastPage(),
            ]);
        }

        return view('website.web.admin.department.index');
    }

    public function compareDescriptions()
    {
        $departments = Department::query()
            ->with([
                'system:id,name',
                'province:id,name',
                'university:id,name',
                'college:id,name',
            ])
            ->where('status', 1)
            ->orderBy('name')
            ->get([
                'id',
                'system_id',
                'province_id',
                'university_id',
                'college_id',
                'name',
                'description',
            ]);

        $departmentsForCompare = $departments
            ->map(function ($department) {
                return [
                    'id' => $department->id,
                    'name' => $department->name,
                    'description' => (string) ($department->description ?? ''),
                    'system' => $department->system->name ?? '-',
                    'province' => $department->province->name ?? '-',
                    'university' => $department->university->name ?? '-',
                    'college' => $department->college->name ?? '-',
                ];
            })
            ->values();

        return view('website.web.admin.department.compare-descriptions', [
            'departments' => $departments,
            'departmentsForCompare' => $departmentsForCompare,
            'dashboardRoute' => route('admin.dashboard'),
            'departmentsIndexRoute' => route('admin.departments.index'),
            'canCreateDepartment' => true,
            'createDepartmentRoute' => route('admin.departments.create'),
        ]);
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
        $dep->local_score = (float)$data['local_score'];
        $dep->external_score = (float)$data['external_score'];
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

        $department->system_id = $data['system_id'];
        $department->province_id = $data['province_id'];
        $department->university_id = $data['university_id'];
        $department->college_id = $data['college_id'];
        $department->name = $data['name'];
        $department->name_en = $data['name_en'];
        $department->local_score = (float)$data['local_score'];
        $department->external_score = (float)$data['external_score'];
        $department->type = $data['type'];
        $department->sex = $data['sex'];
        $department->image = $data['image'];
        $department->lat = $data['lat'] ?? null;
        $department->lng = $data['lng'] ?? null;
        $department->description = $data['description'] ?? null;
        $department->status = $data['status'];
        $department->save();

        return redirect()->route('admin.departments.index')->with('success', 'بەشەک بەسەرکەوتووی نوێکرا.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);

        if (!empty($department->geojson_path)) {
            // command storage:link
            Storage::disk('public')->delete($department->geojson_path);
        }

        $this->DeleteImage($department->image);

        $department->delete();

        // بۆ AJAX بوونەکە
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'بەشەک سڕیبدرایتەوە']);
        }

        return redirect()->route('admin.departments.index')->with('success', 'بەشەک سڕیبدرایتەوە.');
    }


    public function getUniversities(Request $request)
    {
        $pid = (int) $request->query('province_id');
        abort_if($pid <= 0, 422, 'پاریزگا نەدۆزرایەوە!');

        $universities = Cache::remember(
            'departments.universities.province:' . $pid,
            now()->addMinutes(10),
            function () use ($pid) {
                return University::select('id', 'name')
                    ->where('province_id', $pid)
                    ->where('status', 1)
                    ->get();
            }
        );

        return response()->json($universities)->header('Cache-Control', 'no-store, max-age=0'); //لە Laravel ـدا دەتوانی no-cache لە وەڵامەکان زیاد بکەیت بۆ دڵنیابوون:
    }

    public function getColleges(Request $request)
    {
        $uid = (int) $request->query('university_id');
        abort_if($uid <= 0, 422, 'زانکۆ نەدۆزرایەوە!');

        $colleges = Cache::remember(
            'departments.colleges.university:' . $uid,
            now()->addMinutes(10),
            function () use ($uid) {
                return College::select('id', 'name')
                    ->where('university_id', $uid)
                    ->where('status', 1)
                    ->get();
            }
        );

        return response()->json($colleges)->header('Cache-Control', 'no-store, max-age=0'); //لە Laravel ـدا دەتوانی no-cache لە وەڵامەکان زیاد بکەیت بۆ دڵنیابوون:
    }

    public function export()
    {
        return Excel::download(new DepartmentsExport(), 'departments_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * دانانی فایلێکی نموونە بۆ Import
     */
    public function downloadTemplate()
    {
        return Excel::download(new DepartmentsTemplateExport(), 'departments_template.xlsx');
    }

    /**
     * Import بەشەکان لە Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new DepartmentsImport($request->update_existing), $request->file('file'));

            return redirect()->route('admin.departments.index')
                ->with('success', 'بەشەکان بە سەرکەوتوویی Import کراون!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'هەڵەیەک ڕوویدا: ' . $e->getMessage());
        }
    }
}
