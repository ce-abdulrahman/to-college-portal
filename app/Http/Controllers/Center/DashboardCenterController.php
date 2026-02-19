<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;
use App\Models\Student;
use App\Models\Teacher;
use App\Support\DepartmentAccessScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardCenterController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $center = $user?->center;

        // Check if center has GIS feature enabled
        $hasGIS = $center && $center->gis == 1;

        // Show GIS map dashboard if enabled, otherwise show simple dashboard
        if ($hasGIS) {
            $isAllDepartments = (int) ($center?->all_departments ?? 0) === 1;
            $ownProvinceName = trim((string) ($center?->province ?? ''));

            if ($isAllDepartments) {
                $mapScope = [
                    'is_restricted' => false,
                    'is_all_departments' => true,
                    'allowed_province_names' => [],
                    'primary_province_name' => null,
                ];
            } else {
                // Fallback for older accounts with no explicit province set.
                if ($ownProvinceName === '') {
                    $scope = app(DepartmentAccessScope::class)->forUser($user);
                    $allowedProvinceIds = collect($scope['allowed_province_ids'] ?? [])
                        ->map(fn($id) => (int) $id)
                        ->filter(fn($id) => $id > 0)
                        ->values();
                    $primaryProvinceId = (int) ($scope['primary_province_id'] ?? 0);

                    $lookupIds = $allowedProvinceIds
                        ->push($primaryProvinceId)
                        ->filter(fn($id) => $id > 0)
                        ->unique()
                        ->values()
                        ->all();
                    $provinceLookup = empty($lookupIds)
                        ? collect()
                        : Province::query()->whereIn('id', $lookupIds)->pluck('name', 'id');

                    $allowedProvinceNames = $allowedProvinceIds
                        ->map(fn($id) => $provinceLookup->get($id))
                        ->filter()
                        ->values()
                        ->all();

                    $ownProvinceName = $primaryProvinceId > 0
                        ? $provinceLookup->get($primaryProvinceId)
                        : ($allowedProvinceNames[0] ?? null);
                }

                $mapScope = [
                    'is_restricted' => true,
                    'is_all_departments' => false,
                    'allowed_province_names' => $ownProvinceName ? [$ownProvinceName] : [],
                    'primary_province_name' => $ownProvinceName ?: null,
                ];
            }

            return view('website.web.center.dashboard-gis', compact('center', 'mapScope'));
        }

        $teachersCount = Teacher::query()
            ->where('referral_code', $user?->rand_code)
            ->count();

        $studentsCount = Student::query()
            ->where('referral_code', $user?->rand_code)
            ->count();

        $scope = app(DepartmentAccessScope::class)->forUser($user);
        $departmentsQuery = Department::query()->visibleForSelection();
        if (!empty($scope['is_restricted'])) {
            $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];
            if (empty($allowedProvinceIds)) {
                $departmentsQuery->whereRaw('1=0');
            } else {
                $departmentsQuery->whereIn('province_id', $allowedProvinceIds);
            }
        }
        $departmentsCount = $departmentsQuery->count();

        $teacherLimit = $center?->limit_teacher;
        $studentLimit = $center?->limit_student;

        $teacherRemaining = is_null($teacherLimit) ? null : max((int) $teacherLimit - $teachersCount, 0);
        $studentRemaining = is_null($studentLimit) ? null : max((int) $studentLimit - $studentsCount, 0);

        $teacherUsagePercent = is_null($teacherLimit) || (int) $teacherLimit === 0
            ? null
            : min(100, (int) round(($teachersCount / (int) $teacherLimit) * 100));
        $studentUsagePercent = is_null($studentLimit) || (int) $studentLimit === 0
            ? null
            : min(100, (int) round(($studentsCount / (int) $studentLimit) * 100));

        $features = [
            [
                'key' => 'ai_rank',
                'label' => 'ڕیزبەندی کرد بە زیرەکی دەستکرد',
                'icon' => 'fa-robot',
                'active' => (int) ($center?->ai_rank ?? 0) === 1,
            ],
            [
                'key' => 'gis',
                'label' => 'سەیرکردن بە نەخشە',
                'icon' => 'fa-map-location-dot',
                'active' => (int) ($center?->gis ?? 0) === 1,
            ],
            [
                'key' => 'all_departments',
                'label' => 'ڕێزبەندی ٥٠ بەش + بینینی پارێزگاکانی تر',
                'icon' => 'fa-layer-group',
                'active' => (int) ($center?->all_departments ?? 0) === 1,
            ],
            [
                'key' => 'queue_hand_department',
                'label' => 'ڕیزبەندی دەستی بەشەکان',
                'icon' => 'fa-list-ol',
                'active' => (int) ($center?->queue_hand_department ?? 0) === 1,
            ],
        ];

        $activeFeaturesCount = collect($features)->where('active', true)->count();

        $dashboard = [
            'teachers_count' => $teachersCount,
            'students_count' => $studentsCount,
            'departments_count' => $departmentsCount,
            'teacher_limit' => $teacherLimit,
            'student_limit' => $studentLimit,
            'teacher_remaining' => $teacherRemaining,
            'student_remaining' => $studentRemaining,
            'teacher_usage_percent' => $teacherUsagePercent,
            'student_usage_percent' => $studentUsagePercent,
            'features' => $features,
            'active_features_count' => $activeFeaturesCount,
            'features_count' => count($features),
        ];

        return view('website.web.center.dashboard-simple', compact('dashboard', 'center'));
    }

    public function departments(Request $request)
    {
        $limit = (int) $request->input('limit', 25);
        if (!in_array($limit, [10, 25, 50, 100], true)) {
            $limit = 25;
        }

        $systemId = $request->input('system_id');
        $provinceId = $request->input('province_id');
        $universityId = $request->input('university_id');
        $collegeId = $request->input('college_id');
        $search = trim((string) $request->input('search', ''));
        $scope = app(DepartmentAccessScope::class)->forUser(auth()->user());
        $isRestricted = (bool) ($scope['is_restricted'] ?? false);
        $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];

        $query = Department::query()
            ->visibleForSelection()
            ->with(['system', 'province', 'university', 'college']);

        if ($isRestricted) {
            if (empty($allowedProvinceIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('province_id', $allowedProvinceIds);
            }
        }

        if ($systemId) {
            $query->where('system_id', $systemId);
        }

        if ($provinceId) {
            if ($isRestricted && !in_array((int) $provinceId, $allowedProvinceIds, true)) {
                $query->whereRaw('1 = 0');
            }
            $query->where('province_id', $provinceId);
        }

        if ($universityId) {
            $query->where('university_id', $universityId);
        }

        if ($collegeId) {
            $query->where('college_id', $collegeId);
        }

        if ($search !== '') {
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

        $departments = $query->latest()->paginate($limit)->withQueryString();

        $systems = Cache::remember('departments.systems', now()->addMinutes(10), function () {
            return System::where('status', 1)->get();
        });

        if ($isRestricted) {
            if (empty($allowedProvinceIds)) {
                $provinces = collect();
                $universities = collect();
                $colleges = collect();
            } else {
                $provinces = Province::query()
                    ->where('status', 1)
                    ->whereIn('id', $allowedProvinceIds)
                    ->when($systemId, function ($q) use ($systemId) {
                        $q->whereIn('id', function ($sub) use ($systemId) {
                            $sub->select('province_id')
                                ->from('departments')
                                ->where('system_id', $systemId)
                                ->where('status', 1)
                                ->where('local_score', '>=', Department::MIN_VISIBLE_LOCAL_SCORE);
                        });
                    })->get();

                $universities = University::query()
                    ->where('status', 1)
                    ->whereIn('province_id', $allowedProvinceIds)
                    ->when($provinceId, fn($q) => $q->where('province_id', $provinceId))
                    ->when($systemId, function ($q) use ($systemId) {
                        $q->whereIn('id', function ($sub) use ($systemId) {
                            $sub->select('university_id')
                                ->from('departments')
                                ->where('system_id', $systemId)
                                ->where('status', 1)
                                ->where('local_score', '>=', Department::MIN_VISIBLE_LOCAL_SCORE);
                        });
                    })->get();

                $allowedUniversityIds = $universities->pluck('id')->all();
                $colleges = College::query()
                    ->where('status', 1)
                    ->when(!empty($allowedUniversityIds), fn($q) => $q->whereIn('university_id', $allowedUniversityIds))
                    ->when($universityId, fn($q) => $q->where('university_id', $universityId))
                    ->when($systemId, function ($q) use ($systemId) {
                        $q->whereIn('id', function ($sub) use ($systemId) {
                            $sub->select('college_id')
                                ->from('departments')
                                ->where('system_id', $systemId)
                                ->where('status', 1)
                                ->where('local_score', '>=', Department::MIN_VISIBLE_LOCAL_SCORE);
                        });
                    })->get();
            }
        } else {
            $provinces = Cache::remember('departments.provinces.system:' . ($systemId ?: 'all'), now()->addMinutes(10), function () use ($systemId) {
                return Province::where('status', 1)
                    ->when($systemId, function ($q) use ($systemId) {
                        $q->whereIn('id', function ($sub) use ($systemId) {
                            $sub->select('province_id')
                                ->from('departments')
                                ->where('system_id', $systemId)
                                ->where('status', 1)
                                ->where('local_score', '>=', Department::MIN_VISIBLE_LOCAL_SCORE);
                        });
                    })->get();
            });

            $universities = Cache::remember(
                'departments.universities.system:' . ($systemId ?: 'all') . '.province:' . ($provinceId ?: 'all'),
                now()->addMinutes(10),
                function () use ($systemId, $provinceId) {
                    return University::where('status', 1)
                        ->when($provinceId, fn($q) => $q->where('province_id', $provinceId))
                        ->when($systemId, function ($q) use ($systemId) {
                            $q->whereIn('id', function ($sub) use ($systemId) {
                                $sub->select('university_id')
                                    ->from('departments')
                                    ->where('system_id', $systemId)
                                    ->where('status', 1)
                                    ->where('local_score', '>=', Department::MIN_VISIBLE_LOCAL_SCORE);
                            });
                        })->get();
                }
            );

            $colleges = Cache::remember(
                'departments.colleges.system:' . ($systemId ?: 'all') . '.university:' . ($universityId ?: 'all'),
                now()->addMinutes(10),
                function () use ($systemId, $universityId) {
                    return College::where('status', 1)
                        ->when($universityId, fn($q) => $q->where('university_id', $universityId))
                        ->when($systemId, function ($q) use ($systemId) {
                            $q->whereIn('id', function ($sub) use ($systemId) {
                                $sub->select('college_id')
                                    ->from('departments')
                                    ->where('system_id', $systemId)
                                    ->where('status', 1)
                                    ->where('local_score', '>=', Department::MIN_VISIBLE_LOCAL_SCORE);
                            });
                        })->get();
                }
            );
        }

        return view('website.web.center.departments.index', compact(
            'departments',
            'systems',
            'provinces',
            'universities',
            'colleges'
        ));
    }

    public function show(string $id)
    {
        $department = Department::query()
            ->visibleForSelection()
            ->findOrFail($id);

        $scope = app(DepartmentAccessScope::class)->forUser(auth()->user());
        if (!empty($scope['is_restricted'])) {
            $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];
            if (empty($allowedProvinceIds) || !in_array((int) $department->province_id, $allowedProvinceIds, true)) {
                abort(403, 'تەنها دەتوانیت بەشەکانی پارێزگای ڕێگەپێدراو ببینیت.');
            }
        }

        return view('website.web.center.departments.show', compact('department'));
    }

    public function compareDescriptions()
    {
        $scope = app(DepartmentAccessScope::class)->forUser(auth()->user());
        $isRestricted = (bool) ($scope['is_restricted'] ?? false);
        $allowedProvinceIds = $scope['allowed_province_ids'] ?? [];

        $query = Department::query()
            ->visibleForSelection()
            ->with([
                'system:id,name',
                'province:id,name',
                'university:id,name',
                'college:id,name',
            ]);

        if ($isRestricted) {
            if (empty($allowedProvinceIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('province_id', $allowedProvinceIds);
            }
        }

        $departments = $query
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
            'dashboardRoute' => route('center.dashboard'),
            'departmentsIndexRoute' => route('center.departments.index'),
            'canCreateDepartment' => false,
            'createDepartmentRoute' => null,
        ]);
    }

}
