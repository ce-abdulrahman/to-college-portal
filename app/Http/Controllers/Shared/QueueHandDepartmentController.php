<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Department;
use App\Models\Province;
use App\Models\ResultDep;
use App\Models\Student;
use App\Models\System;
use App\Models\University;
use App\Models\User;
use App\Support\DepartmentAccessScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class QueueHandDepartmentController extends Controller
{
    private function resolveContext(bool $strict = true): array
    {
        $user = auth()->user();
        $scope = app(DepartmentAccessScope::class)->forUser($user);

        if (!$user) {
            abort(403);
        }

        if ($user->role === 'admin') {
            return [
                'route_prefix' => 'admin',
                'dashboard_route' => route('admin.dashboard'),
                'dashboard_label' => 'داشبۆرد',
                'has_access' => true,
                'request_feature_route' => null,
                'is_all_departments' => true,
                'is_restricted' => false,
                'allowed_province_ids' => [],
                'max_selections' => null,
            ];
        }

        if ($user->role === 'center') {
            if ((int) ($user?->center?->queue_hand_department ?? 0) !== 1) {
                if ($strict) {
                    abort(403, 'ئەم تایبەتمەندییە بۆ هەژماری تۆ چالاک نییە.');
                }

                return [
                    'route_prefix' => 'center',
                    'dashboard_route' => route('center.dashboard'),
                    'dashboard_label' => 'داشبۆرد',
                    'has_access' => false,
                    'request_feature_route' => route('center.features.request'),
                    'is_all_departments' => (bool) ($scope['is_all_departments'] ?? false),
                    'is_restricted' => (bool) ($scope['is_restricted'] ?? false),
                    'allowed_province_ids' => $scope['allowed_province_ids'] ?? [],
                    'max_selections' => $scope['max_selections'] ?? 20,
                ];
            }

            return [
                'route_prefix' => 'center',
                'dashboard_route' => route('center.dashboard'),
                'dashboard_label' => 'داشبۆرد',
                'has_access' => true,
                'request_feature_route' => route('center.features.request'),
                'is_all_departments' => (bool) ($scope['is_all_departments'] ?? false),
                'is_restricted' => (bool) ($scope['is_restricted'] ?? false),
                'allowed_province_ids' => $scope['allowed_province_ids'] ?? [],
                'max_selections' => $scope['max_selections'] ?? 20,
            ];
        }

        if ($user->role === 'teacher') {
            if ((int) ($user?->teacher?->queue_hand_department ?? 0) !== 1) {
                if ($strict) {
                    abort(403, 'ئەم تایبەتمەندییە بۆ هەژماری تۆ چالاک نییە.');
                }

                return [
                    'route_prefix' => 'teacher',
                    'dashboard_route' => route('teacher.dashboard'),
                    'dashboard_label' => 'داشبۆرد',
                    'has_access' => false,
                    'request_feature_route' => route('teacher.features.request'),
                    'is_all_departments' => (bool) ($scope['is_all_departments'] ?? false),
                    'is_restricted' => (bool) ($scope['is_restricted'] ?? false),
                    'allowed_province_ids' => $scope['allowed_province_ids'] ?? [],
                    'max_selections' => $scope['max_selections'] ?? 20,
                ];
            }

            return [
                'route_prefix' => 'teacher',
                'dashboard_route' => route('teacher.dashboard'),
                'dashboard_label' => 'داشبۆرد',
                'has_access' => true,
                'request_feature_route' => route('teacher.features.request'),
                'is_all_departments' => (bool) ($scope['is_all_departments'] ?? false),
                'is_restricted' => (bool) ($scope['is_restricted'] ?? false),
                'allowed_province_ids' => $scope['allowed_province_ids'] ?? [],
                'max_selections' => $scope['max_selections'] ?? 20,
            ];
        }

        abort(403);
    }

    private function studentQueryForCurrentUser(User $user)
    {
        $query = Student::query()
            ->with(['user:id,name,code,phone,role,status'])
            ->whereHas('user', fn($q) => $q->where('role', 'student'));

        if (in_array($user->role, ['center', 'teacher'], true)) {
            $query->where('referral_code', (string) $user->rand_code);
        }

        return $query;
    }

    private function resolveStudentFeatureFlags(User $user): array
    {
        if ($user->role === 'center') {
            return [
                'ai_rank' => (int) ($user?->center?->ai_rank ?? 0),
                'gis' => (int) ($user?->center?->gis ?? 0),
                'all_departments' => (int) ($user?->center?->all_departments ?? 0),
            ];
        }

        if ($user->role === 'teacher') {
            return [
                'ai_rank' => (int) ($user?->teacher?->ai_rank ?? 0),
                'gis' => (int) ($user?->teacher?->gis ?? 0),
                'all_departments' => (int) ($user?->teacher?->all_departments ?? 0),
            ];
        }

        return [
            'ai_rank' => 0,
            'gis' => 0,
            'all_departments' => 0,
        ];
    }

    private function ensureStudentLimitNotReached(User $user): void
    {
        if (!in_array($user->role, ['center', 'teacher'], true)) {
            return;
        }

        $limit = $user->role === 'center'
            ? ($user?->center?->limit_student)
            : ($user?->teacher?->limit_student);

        if (is_null($limit)) {
            return;
        }

        $currentCount = Student::query()
            ->where('referral_code', (string) $user->rand_code)
            ->count();

        if ($currentCount >= (int) $limit) {
            throw ValidationException::withMessages([
                'limit_student' => 'سنووری دروستکردنی قوتابی تەواو بووە.',
            ]);
        }
    }

    private function generateUniqueRandCode(): string
    {
        do {
            $candidate = (string) random_int(100000, 999999999);
        } while (User::query()->where('rand_code', $candidate)->exists());

        return $candidate;
    }

    public function index()
    {
        $context = $this->resolveContext(false);
        $currentUser = auth()->user();

        if (($context['has_access'] ?? true) !== true) {
            return view('website.web.admin.queue-hand-department.index', [
                'hasAccess' => false,
                'requestFeatureRoute' => $context['request_feature_route'],
                'dashboardRoute' => $context['dashboard_route'],
                'dashboardLabel' => $context['dashboard_label'],
                'maxSelection' => $context['max_selections'] ?? null,
                'systems' => collect(),
                'provinces' => collect(),
                'universities' => collect(),
                'colleges' => collect(),
                'types' => collect(),
                'sexes' => collect(),
                'students' => collect(),
                'dataRoute' => null,
                'storeStudentRoute' => null,
                'saveResultDepsRoute' => null,
                'studentSelectionRoute' => null,
            ]);
        }

        $isRestricted = (bool) ($context['is_restricted'] ?? false);
        $allowedProvinceIds = $context['allowed_province_ids'] ?? [];

        $systems = Cache::remember('departments.systems', now()->addMinutes(10), function () {
            return System::where('status', 1)->get(['id', 'name']);
        });

        if ($isRestricted) {
            if (empty($allowedProvinceIds)) {
                $provinces = collect();
                $universities = collect();
                $colleges = collect();
                $types = collect();
                $sexes = collect();
            } else {
                $provinces = Province::query()
                    ->where('status', 1)
                    ->whereIn('id', $allowedProvinceIds)
                    ->get(['id', 'name']);

                $universities = University::query()
                    ->where('status', 1)
                    ->whereIn('province_id', $allowedProvinceIds)
                    ->orderBy('name')
                    ->get(['id', 'name', 'province_id']);

                $universityIds = $universities->pluck('id')->all();
                $colleges = empty($universityIds)
                    ? collect()
                    : College::query()
                        ->where('status', 1)
                        ->whereIn('university_id', $universityIds)
                        ->orderBy('name')
                        ->get(['id', 'name', 'university_id']);

                $types = Department::query()
                    ->visibleForSelection()
                    ->whereIn('province_id', $allowedProvinceIds)
                    ->whereNotNull('type')
                    ->distinct()
                    ->orderBy('type')
                    ->pluck('type')
                    ->values();

                $sexes = Department::query()
                    ->visibleForSelection()
                    ->whereIn('province_id', $allowedProvinceIds)
                    ->whereNotNull('sex')
                    ->distinct()
                    ->orderBy('sex')
                    ->pluck('sex')
                    ->values();
            }
        } else {
            $provinces = Cache::remember('departments.provinces.system:all', now()->addMinutes(10), function () {
                return Province::where('status', 1)->get(['id', 'name']);
            });

            $universities = Cache::remember('queue-hand-department.universities.system:all.province:all.v2', now()->addMinutes(10), function () {
                return University::where('status', 1)
                    ->orderBy('name')
                    ->get(['id', 'name', 'province_id']);
            });

            $colleges = Cache::remember('queue-hand-department.colleges.system:all.university:all.v2', now()->addMinutes(10), function () {
                return College::where('status', 1)
                    ->orderBy('name')
                    ->get(['id', 'name', 'university_id']);
            });

            $types = Cache::remember('queue-hand-department.types', now()->addMinutes(10), function () {
                return Department::query()
                    ->visibleForSelection()
                    ->whereNotNull('type')
                    ->distinct()
                    ->orderBy('type')
                    ->pluck('type')
                    ->values();
            });

            $sexes = Cache::remember('queue-hand-department.sexes', now()->addMinutes(10), function () {
                return Department::query()
                    ->visibleForSelection()
                    ->whereNotNull('sex')
                    ->distinct()
                    ->orderBy('sex')
                    ->pluck('sex')
                    ->values();
            });
        }

        $students = $this->studentQueryForCurrentUser($currentUser)
            ->whereHas('user', fn($q) => $q->where('status', 1))
            ->get()
            ->sortBy(fn($student) => mb_strtolower((string) data_get($student, 'user.name')))
            ->values();

        return view('website.web.admin.queue-hand-department.index', [
            'hasAccess' => true,
            'maxSelection' => $context['max_selections'] ?? null,
            'systems' => $systems,
            'provinces' => $provinces,
            'universities' => $universities,
            'colleges' => $colleges,
            'types' => $types,
            'sexes' => $sexes,
            'students' => $students,
            'dataRoute' => route($context['route_prefix'] . '.queue-hand-departments.data'),
            'storeStudentRoute' => route($context['route_prefix'] . '.queue-hand-departments.students.store'),
            'saveResultDepsRoute' => route($context['route_prefix'] . '.queue-hand-departments.save-result-deps'),
            'studentSelectionRoute' => route($context['route_prefix'] . '.queue-hand-departments.student-selection'),
            'dashboardRoute' => $context['dashboard_route'],
            'dashboardLabel' => $context['dashboard_label'],
            'requestFeatureRoute' => $context['request_feature_route'],
        ]);
    }

    public function data(Request $request)
    {
        $context = $this->resolveContext(true);
        $isRestricted = (bool) ($context['is_restricted'] ?? false);
        $allowedProvinceIds = $context['allowed_province_ids'] ?? [];

        $perPage = (int) $request->input('per_page', 25);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 25;
        }

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
                return response()->json([
                    'data' => [],
                    'total' => 0,
                    'per_page' => $perPage,
                    'current_page' => 1,
                    'last_page' => 1,
                ]);
            }

            $query->whereIn('province_id', $allowedProvinceIds);

            if ($request->filled('province_id')) {
                $requestedProvinceId = (int) $request->input('province_id');
                if (!in_array($requestedProvinceId, $allowedProvinceIds, true)) {
                    return response()->json([
                        'data' => [],
                        'total' => 0,
                        'per_page' => $perPage,
                        'current_page' => 1,
                        'last_page' => 1,
                    ]);
                }
            }
        }

        if ($request->filled('system_id')) {
            $query->where('system_id', (int) $request->input('system_id'));
        }

        if ($request->filled('province_id')) {
            $query->where('province_id', (int) $request->input('province_id'));
        }

        if ($request->filled('university_id')) {
            $query->where('university_id', (int) $request->input('university_id'));
        }

        if ($request->filled('college_id')) {
            $query->where('college_id', (int) $request->input('college_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('sex')) {
            $query->where('sex', $request->input('sex'));
        }

        if ($request->filled('status') && in_array((string) $request->input('status'), ['0', '1'], true)) {
            $query->where('status', (int) $request->input('status'));
        }

        if ($request->filled('local_score_min')) {
            $query->where('local_score', '>=', (float) $request->input('local_score_min'));
        }

        if ($request->filled('local_score_max')) {
            $query->where('local_score', '<=', (float) $request->input('local_score_max'));
        }

        if ($request->filled('external_score_min')) {
            $query->where('external_score', '>=', (float) $request->input('external_score_min'));
        }

        if ($request->filled('external_score_max')) {
            $query->where('external_score', '<=', (float) $request->input('external_score_max'));
        }

        $search = trim((string) $request->input('search', ''));
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

                if (is_numeric($search)) {
                    $sub->orWhere('id', (int) $search);
                }

                $sub->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('university', fn($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('college', fn($c) => $c->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('province', fn($p) => $p->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('system', fn($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        $allowedSorts = [
            'id',
            'name',
            'name_en',
            'local_score',
            'external_score',
            'type',
            'sex',
            'lat',
            'lng',
            'status',
            'created_at',
            'updated_at',
        ];
        $sortBy = (string) $request->input('sort_by', 'id');
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'id';
        }
        $sortDirection = strtolower((string) $request->input('sort_direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        $departments = $query->orderBy($sortBy, $sortDirection)->paginate($perPage);

        return response()->json([
            'data' => $departments->items(),
            'total' => $departments->total(),
            'per_page' => $departments->perPage(),
            'current_page' => $departments->currentPage(),
            'last_page' => $departments->lastPage(),
        ]);
    }

    public function storeStudent(Request $request)
    {
        $this->resolveContext(true);
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        $featureFlags = $this->resolveStudentFeatureFlags($user);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'unique:users,code'],
            'phone' => ['nullable', 'string', 'max:11'],
            'password' => ['nullable', 'string', 'min:8'],
            'status' => ['nullable', 'in:0,1'],
            'mark' => ['required', 'numeric', 'min:0', 'max:100'],
            'province' => ['required', 'string', 'max:255', 'exists:provinces,name'],
            'type' => ['required', 'in:زانستی,وێژەیی'],
            'gender' => ['required', 'in:نێر,مێ'],
            'year' => ['required', 'integer', 'min:1', 'max:5'],
            'lat' => [\Illuminate\Validation\Rule::requiredIf((int) ($featureFlags['ai_rank'] ?? 0) === 1), 'nullable', 'numeric', 'between:-90,90'],
            'lng' => [\Illuminate\Validation\Rule::requiredIf((int) ($featureFlags['ai_rank'] ?? 0) === 1), 'nullable', 'numeric', 'between:-180,180'],
        ]);

        $this->ensureStudentLimitNotReached($user);

        $referralCode = in_array($user->role, ['center', 'teacher'], true)
            ? (string) $user->rand_code
            : null;

        $student = DB::transaction(function () use ($data, $featureFlags, $referralCode) {
            $newUser = User::query()->create([
                'name' => $data['name'],
                'code' => $data['code'],
                'password' => Hash::make((string) ($data['password'] ?? '12345678')),
                'role' => 'student',
                'status' => (int) ($data['status'] ?? 1),
                'phone' => $data['phone'] ?? null,
                'rand_code' => $this->generateUniqueRandCode(),
            ]);

            return Student::query()->create([
                'user_id' => $newUser->id,
                'mark' => (float) $data['mark'],
                'province' => $data['province'],
                'type' => $data['type'],
                'gender' => $data['gender'],
                'year' => (int) $data['year'],
                'referral_code' => $referralCode,
                'status' => (int) ($data['status'] ?? 1),
                'ai_rank' => (int) ($featureFlags['ai_rank'] ?? 0),
                'gis' => (int) ($featureFlags['gis'] ?? 0),
                'all_departments' => (int) ($featureFlags['all_departments'] ?? 0),
                'lat' => (int) ($featureFlags['ai_rank'] ?? 0) === 1 ? (isset($data['lat']) ? (float) $data['lat'] : null) : null,
                'lng' => (int) ($featureFlags['ai_rank'] ?? 0) === 1 ? (isset($data['lng']) ? (float) $data['lng'] : null) : null,
            ]);
        });

        $student->loadMissing('user:id,name,code');

        return response()->json([
            'message' => 'قوتابی بە سەرکەوتوویی زیادکرا.',
            'student' => [
                'id' => $student->id,
                'name' => $student->user?->name,
                'code' => $student->user?->code,
                'mark' => $student->mark,
                'province' => $student->province,
                'type' => $student->type,
                'gender' => $student->gender,
            ],
        ], 201);
    }

    public function studentSelection(Request $request)
    {
        $this->resolveContext(true);
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
        ]);

        $student = $this->studentQueryForCurrentUser($user)
            ->where('students.id', (int) $data['student_id'])
            ->first();

        if (!$student) {
            abort(403);
        }

        $selection = ResultDep::query()
            ->where('student_id', $student->id)
            ->with([
                'department:id,name,local_score,external_score,province_id,university_id,college_id,system_id',
                'department.system:id,name',
                'department.province:id,name',
                'department.university:id,name',
                'department.college:id,name',
            ])
            ->orderByRaw('CASE WHEN rank IS NULL THEN 1 ELSE 0 END')
            ->orderBy('rank')
            ->orderBy('id')
            ->get()
            ->map(function (ResultDep $result) {
                $department = $result->department;

                return [
                    'id' => $department?->id,
                    'name' => $department?->name ?? '-',
                    'system_id' => $department?->system?->id,
                    'system' => $department?->system?->name ?? '-',
                    'province' => $department?->province?->name ?? '-',
                    'university' => $department?->university?->name ?? '-',
                    'college' => $department?->college?->name ?? '-',
                    'local' => $department?->local_score,
                    'external' => $department?->external_score,
                    'rank' => $result->rank,
                ];
            })
            ->filter(fn($item) => !empty($item['id']))
            ->values();

        return response()->json([
            'student_id' => $student->id,
            'count' => $selection->count(),
            'data' => $selection,
        ]);
    }

    public function saveResultDeps(Request $request)
    {
        $context = $this->resolveContext(true);
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'selection_payload' => ['required', 'array', 'min:1'],
            'selection_payload.*.id' => ['required', 'integer'],
        ]);

        $student = $this->studentQueryForCurrentUser($user)
            ->where('students.id', (int) $data['student_id'])
            ->first();

        if (!$student) {
            abort(403);
        }

        $departmentIds = collect($data['selection_payload'])
            ->map(fn($item) => (int) ($item['id'] ?? 0))
            ->filter(fn($id) => $id > 0)
            ->values();

        if ($departmentIds->isEmpty()) {
            throw ValidationException::withMessages([
                'selection_payload' => 'هیچ بەشێک هەڵنەبژێردراوە بۆ پاشەکەوتکردن.',
            ]);
        }

        $orderedDepartmentIds = $departmentIds->unique()->values();
        $maxSelections = $context['max_selections'] ?? null;
        if (!is_null($maxSelections) && $orderedDepartmentIds->count() > (int) $maxSelections) {
            throw ValidationException::withMessages([
                'selection_payload' => 'ژمارەی بەشە هەڵبژێردراوەکان لە سنووری ڕێپێدراو زیاترە.',
            ]);
        }

        $departmentQuery = Department::query()
            ->visibleForSelection()
            ->whereIn('id', $orderedDepartmentIds->all());

        if ((bool) ($context['is_restricted'] ?? false)) {
            $allowedProvinceIds = collect($context['allowed_province_ids'] ?? [])
                ->map(fn($id) => (int) $id)
                ->filter(fn($id) => $id > 0)
                ->values()
                ->all();

            if (empty($allowedProvinceIds)) {
                throw ValidationException::withMessages([
                    'selection_payload' => 'مۆڵەتت نییە بۆ هەڵبژاردنی بەش.',
                ]);
            }

            $departmentQuery->whereIn('province_id', $allowedProvinceIds);
        }

        $allowedIds = $departmentQuery->pluck('id')->map(fn($id) => (int) $id)->all();
        $blockedIds = $orderedDepartmentIds->reject(fn($id) => in_array((int) $id, $allowedIds, true));

        if ($blockedIds->isNotEmpty()) {
            throw ValidationException::withMessages([
                'selection_payload' => 'هەندێک بەش بۆ تۆ مۆڵەت پێنەدراوە.',
            ]);
        }

        DB::transaction(function () use ($user, $student, $orderedDepartmentIds) {
            $selectedDepartmentId = ResultDep::query()
                ->where('student_id', $student->id)
                ->whereNotNull('result_rank')
                ->value('department_id');

            ResultDep::query()
                ->where('student_id', $student->id)
                ->delete();

            foreach ($orderedDepartmentIds as $index => $departmentId) {
                ResultDep::query()->create([
                    'user_id' => $user->id,
                    'student_id' => $student->id,
                    'department_id' => (int) $departmentId,
                    'rank' => $index + 1,
                    'result_rank' => (int) $selectedDepartmentId === (int) $departmentId ? $index + 1 : null,
                ]);
            }
        });

        return response()->json([
            'message' => 'لیستی ڕێزبەندی بە سەرکەوتوویی پاشەکەوت کرا بۆ قوتابی.',
            'student_id' => $student->id,
            'saved_count' => $orderedDepartmentIds->count(),
        ]);
    }
}
