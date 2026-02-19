<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Province;
use App\Models\RequestMoreDepartments;
use App\Models\ResultDep;
use App\Models\System;
use App\Support\DepartmentAccessScope;
use App\Support\DepartmentSexScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DepartmentSelectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'student']);
    }

    /**
     * نمایشی پەیجی هەڵبژاردنی بەش
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }

        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $maxSelections = (int) ($scope['max_selections'] ?? 10);
        $allowedSystemIds = $this->allowedSystemIdsForStudent($student);
        $systems = Cache::remember(
            'departments.systems.student-year:' . ((int) ($student->year ?? 1) > 1 ? 'gt1' : 'y1'),
            now()->addMinutes(10),
            function () use ($allowedSystemIds) {
                return System::where('status', 1)
                    ->whereIn('id', $allowedSystemIds)
                    ->orderBy('id')
                    ->get();
            }
        );
        if (!empty($scope['is_restricted'])) {
            $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];
            $provinces = Province::query()
                ->where('status', 1)
                ->when(!empty($allowedProvinceIds), fn($q) => $q->whereIn('id', $allowedProvinceIds))
                ->get();
        } else {
            $provinces = Cache::remember('departments.provinces.system:all', now()->addMinutes(10), function () {
                return Province::where('status', 1)->get();
            });
        }

        $selectedDepartments = ResultDep::where('student_id', $student->id)
            ->whereHas('department', fn($q) => $q->visibleForSelection()->whereIn('system_id', $allowedSystemIds))
            ->with(['department.university', 'department.system', 'department.province', 'department.college'])
            ->orderBy('rank', 'asc')
            ->get();

        return view('website.web.student.departments.selection', compact(
            'student',
            'maxSelections',
            'systems',
            'provinces',
            'selectedDepartments',
            'scope'
        ));
    }

    /**
     * زیادکردنی بەشێک
     */
    public function addDepartment(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'زانیاریەکانی قوتابی تۆمار نەکراوە.'
            ], 400);
        }

        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $allowedSystemIds = $this->allowedSystemIdsForStudent($student);

        // سنووری هەڵبژاردن
        $maxSelections = (int) ($scope['max_selections'] ?? 10);
        $currentCount = ResultDep::where('student_id', $student->id)->count();

        if ($currentCount >= $maxSelections) {
            return response()->json([
                'success' => false,
                'message' => 'تۆ گەیشتویتە بە سنووری هەڵبژاردنەکان (' . $maxSelections . ' بەش). دەتوانیت داواکاری زیادکردنی بەش بنێریت.',
                'can_request_more' => true,
                'request_url' => route('student.departments.request-more'),
            ], 400);
        }

        // دڵنیایی لەوەی پێشتر هەڵی نەبژاردووە
        $existing = ResultDep::where('student_id', $student->id)
            ->where('department_id', $request->department_id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە پێشتر هەڵتبژاردووە.'
            ], 400);
        }

        // دڵنیایی لە گونجاوبوونی بەش
        $department = Department::query()
            ->visibleForSelection()
            ->with('university')
            ->find($request->department_id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە بەردەست نییە.'
            ], 400);
        }

        if (!in_array((int) $department->system_id, $allowedSystemIds, true)) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم سیستەمەی خوێندن گونجاو نییە بۆ ساڵی تۆ.'
            ], 400);
        }

        if (!empty($scope['is_restricted'])) {
            $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];
            if (empty($allowedProvinceIds) || !in_array((int) $department->province_id, $allowedProvinceIds, true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ئەم بەشە لە ناو پارێزگای ڕێگەپێدراو نییە.'
                ], 400);
            }
        }

        // پشکنینی تیپ
        if (!in_array($department->type, [$student->type, 'زانستی و وێژەیی'])) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە گونجاو نییە بۆ تیپەکەت.'
            ], 400);
        }

        // پشکنینی جێندەر
        if (!DepartmentSexScope::isAllowedForStudent($department->sex, $student->gender)) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە گونجاو نییە بۆ جێندەرەکەت.'
            ], 400);
        }

        $studentProvinceId = (int) ($student->province_id ?? 0);
        $requiredScore = (int) $department->province_id === $studentProvinceId
            ? (float) $department->local_score
            : (float) $department->external_score;

        // پشکنینی نمرە
        if ($requiredScore > (float) $student->mark) {
            return response()->json([
                'success' => false,
                'message' => 'نمرەکەت پێویست نییە بۆ ئەم بەشە. پێویستە ' . $requiredScore . ' نمرە.'
            ], 400);
        }

        // دروستکردنی تۆمارەکە
        DB::beginTransaction();
        try {
            $resultDep = ResultDep::create([
                'user_id' => $user->id,
                'student_id' => $student->id,
                'department_id' => $request->department_id,
                'result_rank' => null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'بەشەکە بە سەرکەوتوویی زیاد کرا.',
                'data' => [
                    'id' => $resultDep->id,
                    'department_name' => $department->name, // name_ku یە
                    'local_mark' => $department->local_score,
                    'university_name' => $department->university->name ?? 'نەناسراو',
                    'created_at' => $resultDep->created_at->format('Y/m/d H:i'),
                    'remaining' => $maxSelections - ($currentCount + 1)
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'هەڵەیەک ڕوویدا: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * سڕینەوەی بەشێک
     */
    public function removeDepartment(Request $request, $id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'زانیاریەکانی قوتابی تۆمار نەکراوە.'
            ], 400);
        }

        $resultDep = ResultDep::where('student_id', $student->id)
            ->where('id', $id)
            ->first();

        if (!$resultDep) {
            return response()->json([
                'success' => false,
                'message' => 'تۆمارەکە نەدۆزرایەوە.'
            ], 404);
        }

        $departmentName = $resultDep->department->name;
        $resultDep->delete();

        // ئەندازەی ماوە
        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $maxSelections = (int) ($scope['max_selections'] ?? 10);
        $currentCount = ResultDep::where('student_id', $student->id)->count();

        return response()->json([
            'success' => true,
            'message' => 'بەشەکە (' . $departmentName . ') سڕدرایەوە.',
            'data' => [
                'remaining' => $maxSelections - $currentCount
            ]
        ]);
    }

    /**
     * پێداچوونەوەی لیستی بەشە هەڵبژێردراوەکان
     */
    public function getSelectedDepartments()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'زانیاریەکانی قوتابی تۆمار نەکراوە.'
            ], 400);
        }

        $selectedDepartments = ResultDep::where('student_id', $student->id)
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'department_name' => $item->department->name,
                    'local_mark' => $item->department->local_score,
                    'created_at' => $item->created_at->format('Y/m/d H:i'),
                    'result_rank' => $item->result_rank,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $selectedDepartments,
            'count' => $selectedDepartments->count(),
            'max' => (int) ((app(DepartmentAccessScope::class)->forStudent($student))['max_selections'] ?? 10)
        ]);
    }

    public function showRequestForm()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.departments.selection')
                ->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }

        // پشکنین ئەگەر پێشتر داواکاری هەبێت
        $existingRequest = RequestMoreDepartments::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        return view('website.web.student.departments.request-more', compact('student', 'existingRequest'));
    }

    public function submitRequest(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
            'request_types' => 'required|array|min:1',
            'request_types.*' => 'in:all_departments,ai_rank,gis',
            'receipt_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->back()->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }

        // پشکنین ئەگەر پێشتر داواکاری هەبێت
        $existingRequest = RequestMoreDepartments::where('student_id', $student->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'تۆ پێشتر داواکاریت ناردووە و چاوەڕوانی پەسەندکردنێ.');
        }

        $receiptPath = null;
        if ($request->hasFile('receipt_image')) {
            $file = $request->file('receipt_image');
            $uploadDir = public_path('uploads/request');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = 'request_' . $user->id . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $receiptPath = 'uploads/request/' . $fileName;
        }

        // دروستکردنی داواکاری نوێ
        RequestMoreDepartments::create([
            'student_id' => $student->id,
            'user_id' => $user->id,
            'request_all_departments' => in_array('all_departments', $request->request_types),
            'request_ai_rank' => in_array('ai_rank', $request->request_types),
            'request_gis' => in_array('gis', $request->request_types),
            'reason' => $request->reason,
            'receipt_image' => $receiptPath,
            'status' => 'pending',
        ]);

        return redirect()->route('student.departments.selection')
            ->with('success', 'داواکاریەکەت بە سەرکەوتوویی نێردرا! چاوەڕوانی وەڵامی بەڕێوەبەر بە.');
    }

    /**
     * سڕینەوەی داواکاری
     */
    public function cancelRequest($id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->back()->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }

        $request = RequestMoreDepartments::where('student_id', $student->id)
            ->where('id', $id)
            ->where('status', 'pending')
            ->first();

        if (!$request) {
            return redirect()->back()->with('error', 'داواکاریەکە نەدۆزرایەوە یان ناتوانرێت سڕێنرێتەوە.');
        }

        $request->delete();

        return redirect()->route('student.departments.selection')
            ->with('success', 'داواکاریەکەت سڕدرایەوە.');
    }

    /**
     * بینینی مێژووی داواکاریەکان
     */
    public function requestHistory()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.departments.selection')
                ->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }

        $requests = RequestMoreDepartments::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('website.web.student.departments.request-history', compact('student', 'requests'));
    }
    /**
     * API بۆ لیستی بەشە بەردەستەکان (DataTables)
     */
    public function availableApi(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student) {
            return response()->json([
                'draw' => (int) $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }

        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];
        $studentProvinceId = (int) ($student->province_id ?? 0);
        $allowedSystemIds = $this->allowedSystemIdsForStudent($student);

        $query = Department::query()
            ->visibleForSelection()
            ->whereIn('system_id', $allowedSystemIds)
            ->where(function($q) use ($student) {
                $q->where('type', $student->type)
                  ->orWhere('type', 'زانستی و وێژەیی');
            });
        DepartmentSexScope::applyForStudent($query, $student->gender);

        if (!empty($scope['is_restricted'])) {
            if (empty($allowedProvinceIds)) {
                return response()->json([
                    'draw' => (int) $request->draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                ]);
            }

            $query->whereIn('province_id', $allowedProvinceIds)
                ->where('local_score', '<=', (float) $student->mark);
        } elseif ($studentProvinceId > 0) {
            $query->where(function ($q) use ($studentProvinceId, $student) {
                $q->where(function ($x) use ($studentProvinceId, $student) {
                    $x->where('province_id', $studentProvinceId)
                        ->where('local_score', '<=', (float) $student->mark);
                })->orWhere(function ($x) use ($studentProvinceId, $student) {
                    $x->where('province_id', '!=', $studentProvinceId)
                        ->where('external_score', '<=', (float) $student->mark);
                });
            });
        } else {
            $query->where('external_score', '<=', (float) $student->mark);
        }

        // فلتەرەکان
        if ($request->filled('system_id')) {
            $requestedSystemId = (int) $request->system_id;
            if (!in_array($requestedSystemId, $allowedSystemIds, true)) {
                return response()->json([
                    'draw' => (int) $request->draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                ]);
            }
            $query->where('system_id', $requestedSystemId);
        }

        if ($request->filled('province_id')) {
            $requestedProvinceId = (int) $request->province_id;

            if (!empty($scope['is_restricted']) && !in_array($requestedProvinceId, $allowedProvinceIds, true)) {
                return response()->json([
                    'draw' => (int) $request->draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                ]);
            }

            $query->where('province_id', $requestedProvinceId);
        }

        if ($request->filled('university_id')) {
            $query->where('university_id', (int) $request->university_id);
        }

        if ($request->filled('college_id')) {
            $query->where('college_id', (int) $request->college_id);
        }

        if ($request->filled('search_val')) {
            $search = trim((string) $request->search_val);
            $driver = DB::getDriverName();
            $boolean = collect(preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY))
                ->map(function ($term) {
                    $term = preg_replace('/[^\p{L}\p{N}_]+/u', '', $term);
                    return $term ? '+' . $term . '*' : null;
                })
                ->filter()
                ->implode(' ');

            $query->where(function ($sub) use ($search, $driver, $boolean) {
                if (in_array($driver, ['mysql', 'mariadb'], true) && $boolean !== '') {
                    $sub->whereRaw('MATCH(name, name_en) AGAINST (? IN BOOLEAN MODE)', [$boolean]);
                    $sub->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%");
                } else {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%");
                }

                $sub->orWhereHas('university', fn($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('college', fn($c) => $c->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('province', fn($p) => $p->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('system', fn($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        $totalCount = $query->count();

        $departments = $query->with(['university', 'system', 'province', 'college'])
            ->orderBy('local_score', 'desc')
            ->skip((int) ($request->start ?? 0))
            ->take((int) ($request->length ?? 10))
            ->get();

        $selectedIds = ResultDep::where('student_id', $student->id)->pluck('department_id')->toArray();

        $data = $departments->map(function($dept) use ($selectedIds) {
            $isSelected = in_array($dept->id, $selectedIds);
            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'province' => $dept->province->name ?? '-',
                'university' => $dept->university->name ?? '-',
                'college' => $dept->college->name ?? '-',
                'local_score' => $dept->local_score,
                'system_name' => $dept->system->name ?? '-',
                'system_id' => $dept->system->id ?? null,
                'is_selected' => $isSelected
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalCount,
            'recordsFiltered' => $totalCount,
            'data' => $data
        ]);
    }

    /**
     * پاشەکەوتکردنی ڕێزبەندی
     */
    public function saveRanking(Request $request)
    {
        $request->validate([
            'department_ids' => 'required|array',
            'department_ids.*' => 'exists:departments,id'
        ]);

        $user = Auth::user();
        $student = $user->student;
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'زانیاریەکانی قوتابی تۆمار نەکراوە.'], 400);
        }

        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $maxSelections = (int) ($scope['max_selections'] ?? 10);
        $allowedSystemIds = $this->allowedSystemIdsForStudent($student);
        $submittedIds = collect($request->department_ids)
            ->map(fn($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        if ($submittedIds->count() > $maxSelections) {
            return response()->json([
                'success' => false,
                'message' => 'ناتوانیت زیاتر لە ' . $maxSelections . ' بەش ڕێزبەندی بکەیت. دەتوانیت داواکاری زیادکردنی بەش بنێریت.',
                'can_request_more' => true,
                'request_url' => route('student.departments.request-more'),
            ], 422);
        }

        $studentProvinceId = (int) ($student->province_id ?? 0);
        $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];

        $eligibleQuery = Department::query()
            ->visibleForSelection()
            ->whereIn('id', $submittedIds)
            ->whereIn('system_id', $allowedSystemIds)
            ->where(function($q) use ($student) {
                $q->where('type', $student->type)
                    ->orWhere('type', 'زانستی و وێژەیی');
            });
        DepartmentSexScope::applyForStudent($eligibleQuery, $student->gender);

        if (!empty($scope['is_restricted'])) {
            if (empty($allowedProvinceIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'پارێزگای ڕێگەپێدراو دیاری نەکراوە بۆ ئەم هەژمارە.'
                ], 422);
            }

            $eligibleQuery->whereIn('province_id', $allowedProvinceIds)
                ->where('local_score', '<=', (float) $student->mark);
        } elseif ($studentProvinceId > 0) {
            $eligibleQuery->where(function ($q) use ($studentProvinceId, $student) {
                $q->where(function ($x) use ($studentProvinceId, $student) {
                    $x->where('province_id', $studentProvinceId)
                        ->where('local_score', '<=', (float) $student->mark);
                })->orWhere(function ($x) use ($studentProvinceId, $student) {
                    $x->where('province_id', '!=', $studentProvinceId)
                        ->where('external_score', '<=', (float) $student->mark);
                });
            });
        } else {
            $eligibleQuery->where('external_score', '<=', (float) $student->mark);
        }

        $eligibleCount = (clone $eligibleQuery)->count();
        if ($eligibleCount !== $submittedIds->count()) {
            return response()->json([
                'success' => false,
                'message' => 'بەشێک لە لیستەکە گونجاو نییە بۆ یاساکانی هەڵبژاردن.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $selectedDepartmentId = ResultDep::where('student_id', $student->id)
                ->whereNotNull('result_rank')
                ->value('department_id');

            // سڕینەوەی کۆنەکان
            ResultDep::where('student_id', $student->id)->delete();

            foreach ($submittedIds as $index => $departmentId) {
                ResultDep::create([
                    'user_id' => $user->id,
                    'student_id' => $student->id,
                    'department_id' => $departmentId,
                    'rank' => $index + 1,
                    'result_rank' => $selectedDepartmentId == $departmentId ? $index + 1 : null,
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'ڕێزبەندییەکە بە سەرکەوتوویی پاشەکەوت کرا.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'هەڵەیەک ڕوویدا: ' . $e->getMessage()], 500);
        }
    }

    /**
     * هەڵبژاردنی بەشی کۆتایی (تەنیا یەک رێکۆرد)
     */
    public function selectFinal(Request $request)
    {
        $request->validate([
            'result_dep_id' => 'required|exists:result_deps,id',
        ]);

        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'زانیاریەکانی قوتابی تۆمار نەکراوە.'
            ], 400);
        }

        $resultDep = ResultDep::where('id', $request->result_dep_id)
            ->where('student_id', $student->id)
            ->first();

        if (!$resultDep) {
            return response()->json([
                'success' => false,
                'message' => 'تۆمارەکە نەدۆزرایەوە.'
            ], 404);
        }

        if (is_null($resultDep->rank)) {
            return response()->json([
                'success' => false,
                'message' => 'تکایە سەرەتا ڕێزبەندی پاشەکەوت بکە.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            ResultDep::where('student_id', $student->id)->update(['result_rank' => null]);
            $resultDep->update(['result_rank' => $resultDep->rank]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'بەشی کۆتایی بە سەرکەوتوویی هەڵبژێردرا.',
                'data' => [
                    'result_dep_id' => $resultDep->id,
                    'result_rank' => $resultDep->result_rank,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'هەڵەیەک ڕوویدا: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUniversities($province_id)
    {
        $student = Auth::user()->student;
        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $requestedProvinceId = (int) $province_id;
        $allowedSystemIds = $this->allowedSystemIdsForStudent($student);

        if (!empty($scope['is_restricted'])) {
            $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];
            if (empty($allowedProvinceIds) || !in_array($requestedProvinceId, $allowedProvinceIds, true)) {
                return response()->json([]);
            }
        }

        $universities = Cache::remember(
            'departments.universities.province:' . $requestedProvinceId . '.systems:' . implode(',', $allowedSystemIds),
            now()->addMinutes(10),
            function () use ($requestedProvinceId, $allowedSystemIds) {
                return \App\Models\University::where('province_id', $requestedProvinceId)
                    ->where('status', 1)
                    ->whereHas('departments', fn($q) => $q->visibleForSelection()->whereIn('system_id', $allowedSystemIds))
                    ->get();
            }
        );
        return response()->json($universities);
    }

    public function getColleges($university_id)
    {
        $student = Auth::user()->student;
        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $requestedUniversityId = (int) $university_id;
        $allowedSystemIds = $this->allowedSystemIdsForStudent($student);

        if (!empty($scope['is_restricted'])) {
            $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];
            if (empty($allowedProvinceIds)) {
                return response()->json([]);
            }

            $universityIsAllowed = \App\Models\University::query()
                ->where('id', $requestedUniversityId)
                ->whereIn('province_id', $allowedProvinceIds)
                ->exists();

            if (!$universityIsAllowed) {
                return response()->json([]);
            }
        }

        $colleges = Cache::remember(
            'departments.colleges.university:' . $requestedUniversityId . '.systems:' . implode(',', $allowedSystemIds),
            now()->addMinutes(10),
            function () use ($requestedUniversityId, $allowedSystemIds) {
                return \App\Models\College::where('university_id', $requestedUniversityId)
                    ->where('status', 1)
                    ->whereHas('departments', fn($q) => $q->visibleForSelection()->whereIn('system_id', $allowedSystemIds))
                    ->get();
            }
        );
        return response()->json($colleges);
    }

    private function allowedSystemIdsForStudent($student): array
    {
        if ((int) ($student->year ?? 1) === 1) {
            return System::where('status', 1)->pluck('id')->map(fn($id) => (int) $id)->values()->all();
        }

        return System::where('status', 1)
            ->whereIn('id', [2, 3])
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }
}
