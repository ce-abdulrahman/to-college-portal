<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\RequestMoreDepartments;
use App\Models\ResultDep;
use App\Models\System;
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

        $maxSelections = $student->all_departments === 1 ? 50 : 20;
        $systems = Cache::remember('departments.systems', now()->addMinutes(10), function () {
            return System::where('status', 1)->get();
        });
        $provinces = Cache::remember('departments.provinces.system:all', now()->addMinutes(10), function () {
            return \App\Models\Province::where('status', 1)->get();
        });

        $selectedDepartments = ResultDep::where('student_id', $student->id)
            ->with(['department.university', 'department.system', 'department.province', 'department.college'])
            ->orderBy('rank', 'asc')
            ->get();

        return view('website.web.student.departments.selection', compact(
            'student',
            'maxSelections',
            'systems',
            'provinces',
            'selectedDepartments'
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

        // سنووری هەڵبژاردن
        $maxSelections = $student->all_departments == 1 ? 50 : 20;
        $currentCount = ResultDep::where('student_id', $student->id)->count();

        if ($currentCount >= $maxSelections) {
            return response()->json([
                'success' => false,
                'message' => 'تۆ گەیشتویتە بە سنووری هەڵبژاردنەکان (' . $maxSelections . ' بەش).'
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
        $department = Department::with('university')->findOrFail($request->department_id);

        // پشکنینی تیپ
        if (!in_array($department->type, [$student->type, 'زانستی و وێژەیی'])) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە گونجاو نییە بۆ تیپەکەت.'
            ], 400);
        }

        // پشکنینی جێندەر
        if (!in_array($department->sex, [$student->gender, 'هەردووکیان'])) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە گونجاو نییە بۆ جێندەرەکەت.'
            ], 400);
        }

        // پشکنینی نمرە
        if ($department->local_score > $student->mark) {
            return response()->json([
                'success' => false,
                'message' => 'نمرەکەت پێویست نییە بۆ ئەم بەشە. پێویستە ' . $department->local_score . ' نمرە.'
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
        $maxSelections = $student->all_departments == 1 ? 50 : 20;
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
            'max' => $student->all_departments == 1 ? 50 : 20
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

        $query = Department::where('status', 1)
            ->where(function($q) use ($student) {
                $q->where('type', $student->type)
                  ->orWhere('type', 'زانستی و وێژەیی');
            })
            ->where(function($q) use ($student) {
                $q->where('sex', $student->gender)
                  ->orWhere('sex', 'هەردووکیان');
            })
            ->where('local_score', '<=', $student->mark);

        // فلتەرەکان
        if ($request->system_id) {
            $query->where('system_id', $request->system_id);
        }
        if ($request->province_id) {
            $query->where('province_id', $request->province_id);
        }
        if ($request->university_id) {
            $query->where('university_id', $request->university_id);
        }
        if ($request->college_id) {
            $query->where('college_id', $request->college_id);
        }
        if ($request->search_val) {
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
            ->skip($request->start ?? 0)
            ->take($request->length ?? 10)
            ->get();

        $selectedIds = ResultDep::where('student_id', $student->id)->pluck('department_id')->toArray();

        $data = $departments->map(function($dept) use ($selectedIds) {
            $isSelected = in_array($dept->id, $selectedIds);
            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'province' => $dept->province->name,
                'university' => $dept->university->name,
                'college' => $dept->college->name,
                'local_score' => $dept->local_score,
                'system_name' => $dept->system->name,
                'system_id' => $dept->system->id,
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

        DB::beginTransaction();
        try {
            $selectedDepartmentId = ResultDep::where('student_id', $student->id)
                ->whereNotNull('result_rank')
                ->value('department_id');

            // سڕینەوەی کۆنەکان
            ResultDep::where('student_id', $student->id)->delete();

            foreach ($request->department_ids as $index => $departmentId) {
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
        $universities = Cache::remember(
            'departments.universities.province:' . (int) $province_id,
            now()->addMinutes(10),
            function () use ($province_id) {
                return \App\Models\University::where('province_id', $province_id)
                    ->where('status', 1)->get();
            }
        );
        return response()->json($universities);
    }

    public function getColleges($university_id)
    {
        $colleges = Cache::remember(
            'departments.colleges.university:' . (int) $university_id,
            now()->addMinutes(10),
            function () use ($university_id) {
                return \App\Models\College::where('university_id', $university_id)
                    ->where('status', 1)->get();
            }
        );
        return response()->json($colleges);
    }
}
