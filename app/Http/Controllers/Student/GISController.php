<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\ResultDep;
use App\Models\University;
use App\Models\Province;
use App\Support\DepartmentAccessScope;
use App\Support\DepartmentSexScope;
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

        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];
        $studentProvinceId = (int) ($student->province_id ?? 0);

        // پارێزگاکان
        $provinces = Province::whereHas('departments', function($query) use ($student) {
            $query->visibleForSelection()
                ->where(function($q) use ($student) {
                    $q->where('type', $student->type)
                      ->orWhere('type', 'زانستی و وێژەیی');
                });
            DepartmentSexScope::applyForStudent($query, $student->gender);
        })->when(!empty($scope['is_restricted']), function ($q) use ($allowedProvinceIds) {
            if (empty($allowedProvinceIds)) {
                $q->whereRaw('1 = 0');
            } else {
                $q->whereIn('id', $allowedProvinceIds);
            }
        })->get()->filter(function ($province) use ($scope, $student, $studentProvinceId) {
            $deptQuery = Department::query()
                ->visibleForSelection()
                ->where('province_id', $province->id)
                ->where(function($q) use ($student) {
                    $q->where('type', $student->type)
                        ->orWhere('type', 'زانستی و وێژەیی');
                });
            DepartmentSexScope::applyForStudent($deptQuery, $student->gender);

            if (!empty($scope['is_restricted'])) {
                $deptQuery->where('local_score', '<=', (float) $student->mark);
            } elseif ($studentProvinceId > 0) {
                if ((int) $province->id === $studentProvinceId) {
                    $deptQuery->where('local_score', '<=', (float) $student->mark);
                } else {
                    $deptQuery->where('external_score', '<=', (float) $student->mark);
                }
            } else {
                $deptQuery->where('external_score', '<=', (float) $student->mark);
            }

            return $deptQuery->exists();
        })->values();

        // سنووری هەڵبژاردن
        $maxSelections = (int) ($scope['max_selections'] ?? 10);
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

        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];
        $studentProvinceId = (int) ($student->province_id ?? 0);
        $requestedProvinceId = (int) $provinceId;

        if (!empty($scope['is_restricted'])) {
            if (empty($allowedProvinceIds) || !in_array($requestedProvinceId, $allowedProvinceIds, true)) {
                return response()->json(['error' => 'تەنها پارێزگای خۆت دەتوانیت ببینیت.'], 403);
            }
        }

        // بەشە هەڵبژێردراوەکان
        $selectedIds = ResultDep::where('student_id', $student->id)
            ->pluck('department_id')
            ->toArray();

        $requiredScoreColumn = $studentProvinceId > 0 && $requestedProvinceId === $studentProvinceId
            ? 'local_score'
            : 'external_score';

        // بەشەکانی پارێزگا
        $departmentsQuery = Department::query()
            ->visibleForSelection()
            ->where('province_id', $provinceId)
            ->where(function($query) use ($student) {
                $query->where('type', $student->type)
                      ->orWhere('type', 'زانستی و وێژەیی');
            });
        DepartmentSexScope::applyForStudent($departmentsQuery, $student->gender);

        $departments = $departmentsQuery
            ->where($requiredScoreColumn, '<=', (float) $student->mark)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->with(['university', 'college', 'system'])
            ->get()
            ->map(function ($department) use ($selectedIds, $student, $requiredScoreColumn) {
                $isSelected = in_array($department->id, $selectedIds);
                $requiredScore = $requiredScoreColumn === 'local_score'
                    ? (float) $department->local_score
                    : (float) $department->external_score;
                $isEligible = (float) $student->mark >= $requiredScore;
                
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
        $universities = University::whereHas('departments', function($query) use ($provinceId, $student, $requiredScoreColumn) {
            $query->visibleForSelection()
                ->where('province_id', $provinceId)
                ->where(function($q) use ($student) {
                    $q->where('type', $student->type)
                      ->orWhere('type', 'زانستی و وێژەیی');
                })
                ->where($requiredScoreColumn, '<=', (float) $student->mark);
            DepartmentSexScope::applyForStudent($query, $student->gender);
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
        $searchTerm = trim((string) $request->input('query', ''));

        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->gis != 1) {
            return response()->json(['error' => 'مۆڵەت نییە'], 403);
        }

        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];
        $studentProvinceId = (int) ($student->province_id ?? 0);

        $departmentsQuery = Department::query()
            ->visibleForSelection()
            ->where(function($query) use ($student) {
                $query->where('type', $student->type)
                      ->orWhere('type', 'زانستی و وێژەیی');
            });
        DepartmentSexScope::applyForStudent($departmentsQuery, $student->gender);

        $departments = $departmentsQuery
            ->when(!empty($scope['is_restricted']), function ($query) use ($allowedProvinceIds, $student) {
                if (empty($allowedProvinceIds)) {
                    $query->whereRaw('1 = 0');
                } else {
                    $query->whereIn('province_id', $allowedProvinceIds)
                        ->where('local_score', '<=', (float) $student->mark);
                }
            }, function ($query) use ($student, $studentProvinceId) {
                if ($studentProvinceId > 0) {
                    $query->where(function ($q) use ($student, $studentProvinceId) {
                        $q->where(function ($x) use ($student, $studentProvinceId) {
                            $x->where('province_id', $studentProvinceId)
                                ->where('local_score', '<=', (float) $student->mark);
                        })->orWhere(function ($x) use ($student, $studentProvinceId) {
                            $x->where('province_id', '!=', $studentProvinceId)
                                ->where('external_score', '<=', (float) $student->mark);
                        });
                    });
                } else {
                    $query->where('external_score', '<=', (float) $student->mark);
                }
            })
            ->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhereHas('university', function($query) use ($searchTerm) {
                        $query->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('province', function($query) use ($searchTerm) {
                        $query->where('name', 'like', "%{$searchTerm}%");
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

        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];

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
        $department = Department::query()
            ->visibleForSelection()
            ->with(['university', 'system'])
            ->find($request->department_id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە بەردەست نییە.'
            ], 400);
        }

        if (!empty($scope['is_restricted'])) {
            if (empty($allowedProvinceIds) || !in_array((int) $department->province_id, $allowedProvinceIds, true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ئەم بەشە لە ناو پارێزگای ڕێگەپێدراو نییە.'
                ], 400);
            }
        }
        
        if (!in_array($department->type, [$student->type, 'زانستی و وێژەیی'])) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە گونجاو نییە بۆ تیپەکەت.'
            ], 400);
        }

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

        if ($requiredScore > (float) $student->mark) {
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
            'result_rank' => null,
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
        $maxSelections = (int) ((app(DepartmentAccessScope::class)->forStudent($student))['max_selections'] ?? 10);

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
