<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\ResultDep;
use App\Models\University;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GISController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'student']);
    }

    /**
     * نمایشی نەخشەی بەشەکان
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }

        // پشکنینی مۆڵەتی GIS
        if ($student->gis != 1) {
            return redirect()->route('student.departments.selection')
                ->with('info', 'تۆ مۆڵەتی بەکارهێنانی نەخشەت نییە. تکایە داواکاری بکە.');
        }

        // بەشە هەڵبژێردراوەکان
        $selectedDepartments = ResultDep::where('student_id', $student->id)
            ->with(['department' => function($query) {
                $query->with(['university', 'province', 'college', 'system']);
            }])
            ->get();

        // پارێزگاکان
        $provinces = Province::whereHas('departments', function($query) use ($student) {
            $query->where('status', 1)
                ->where(function($q) use ($student) {
                    $q->where('type', $student->type)
                      ->orWhere('type', 'زانستی و وێژەیی');
                })
                ->where(function($q) use ($student) {
                    $q->where('sex', $student->gender)
                      ->orWhere('sex', 'هەردووکیان');
                })
                ->where('local_score', '<=', $student->mark);
        })->get();

        // سنووری هەڵبژاردن
        $maxSelections = $student->all_departments == 1 ? 50 : 20;
        $currentCount = $selectedDepartments->count();

        return view('website.web.student.gis.index', compact(
            'student',
            'provinces',
            'selectedDepartments',
            'maxSelections',
            'currentCount'
        ));
    }

    /**
     * وەرگرتنی داتای بەشەکان بۆ پارێزگا
     */
    public function getProvinceData($provinceId)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->gis != 1) {
            return response()->json(['error' => 'مۆڵەت نییە'], 403);
        }

        // بەشە هەڵبژێردراوەکان
        $selectedIds = ResultDep::where('student_id', $student->id)
            ->pluck('department_id')
            ->toArray();

        // بەشەکانی پارێزگا
        $departments = Department::where('province_id', $provinceId)
            ->where('status', 1)
            ->where(function($query) use ($student) {
                $query->where('type', $student->type)
                      ->orWhere('type', 'زانستی و وێژەیی');
            })
            ->where(function($query) use ($student) {
                $query->where('sex', $student->gender)
                      ->orWhere('sex', 'هەردووکیان');
            })
            ->where('local_score', '<=', $student->mark)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->with(['university', 'college', 'system'])
            ->get()
            ->map(function ($department) use ($selectedIds, $student) {
                $isSelected = in_array($department->id, $selectedIds);
                $isEligible = $student->mark >= $department->local_score;
                
                return [
                    'id' => $department->id,
                    'name' => $department->name,
                    'type' => $department->type,
                    'local_score' => $department->local_score,
                    'lat' => floatval($department->lat),
                    'lng' => floatval($department->lng),
                    'university' => $department->university->name ?? 'نەناسراو',
                    'college' => $department->college->name ?? 'نەناسراو',
                    'system' => $department->system->name ?? 'نەناسراو',
                    'is_selected' => $isSelected,
                    'is_eligible' => $isEligible,
                    'marker_color' => $isSelected ? 'green' : ($isEligible ? 'blue' : 'red'),
                ];
            });

        // زانکۆکان
        $universities = University::whereHas('departments', function($query) use ($provinceId, $student) {
            $query->where('province_id', $provinceId)
                ->where('status', 1)
                ->where(function($q) use ($student) {
                    $q->where('type', $student->type)
                      ->orWhere('type', 'زانستی و وێژەیی');
                })
                ->where('local_score', '<=', $student->mark);
        })
        ->whereNotNull('lat')
        ->whereNotNull('lng')
        ->get(['id', 'name', 'lat', 'lng']);

        return response()->json([
            'departments' => $departments,
            'universities' => $universities,
            'province' => Province::find($provinceId)
        ]);
    }

    /**
     * گەڕان بۆ بەش
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->gis != 1) {
            return response()->json(['error' => 'مۆڵەت نییە'], 403);
        }

        $departments = Department::where('status', 1)
            ->where(function($query) use ($student) {
                $query->where('type', $student->type)
                      ->orWhere('type', 'زانستی و وێژەیی');
            })
            ->where('local_score', '<=', $student->mark)
            ->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->query}%")
                    ->orWhereHas('university', function($query) use ($request) {
                        $query->where('name', 'like', "%{$request->query}%");
                    })
                    ->orWhereHas('province', function($query) use ($request) {
                        $query->where('name', 'like', "%{$request->query}%");
                    });
            })
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->with(['university', 'province'])
            ->limit(10)
            ->get(['id', 'name', 'lat', 'lng', 'local_score']);

        return response()->json([
            'success' => true,
            'results' => $departments
        ]);
    }

    /**
     * زیادکردنی بەش لە نەخشە
     */
    public function addDepartment(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->gis != 1) {
            return response()->json([
                'success' => false,
                'message' => 'مۆڵەتی بەکارهێنانی نەخشەت نییە.'
            ], 403);
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

        // پشکنین ئەگەر پێشتر هەڵی نەبژاردووە
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
        $department = Department::with(['university', 'system'])->findOrFail($request->department_id);
        
        if (!in_array($department->type, [$student->type, 'زانستی و وێژەیی'])) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە گونجاو نییە بۆ تیپەکەت.'
            ], 400);
        }

        if (!in_array($department->sex, [$student->gender, 'هەردووکیان'])) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە گونجاو نییە بۆ جێندەرەکەت.'
            ], 400);
        }

        if ($department->local_score > $student->mark) {
            return response()->json([
                'success' => false,
                'message' => 'نمرەکەت پێویست نییە بۆ ئەم بەشە.'
            ], 400);
        }

        // دروستکردنی تۆمارەکە
        $resultDep = ResultDep::create([
            'user_id' => $user->id,
            'student_id' => $student->id,
            'department_id' => $request->department_id,
            'status' => 'pending',
            'system_id' => $department->system_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'بەشەکە بە سەرکەوتوویی زیاد کرا.',
            'data' => [
                'department' => [
                    'id' => $department->id,
                    'name' => $department->name,
                    'local_score' => $department->local_score,
                    'university' => $department->university->name ?? 'نەناسراو',
                ],
                'remaining' => $maxSelections - ($currentCount + 1),
                'total_selected' => $currentCount + 1
            ]
        ]);
    }

    /**
     * سڕینەوەی بەش
     */
    public function removeDepartment($id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->gis != 1) {
            return response()->json([
                'success' => false,
                'message' => 'مۆڵەتی بەکارهێنانی نەخشەت نییە.'
            ], 403);
        }

        $resultDep = ResultDep::where('student_id', $student->id)
            ->where('department_id', $id)
            ->first();

        if (!$resultDep) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە نەهەڵتبژاردووە.'
            ], 404);
        }

        $departmentName = $resultDep->department->name;
        $resultDep->delete();

        $currentCount = ResultDep::where('student_id', $student->id)->count();
        $maxSelections = $student->all_departments == 1 ? 50 : 20;

        return response()->json([
            'success' => true,
            'message' => 'بەشەکە (' . $departmentName . ') سڕدرایەوە.',
            'data' => [
                'remaining' => $maxSelections - $currentCount,
                'total_selected' => $currentCount
            ]
        ]);
    }
}