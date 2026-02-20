<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\ResultDep;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\DepartmentSelector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class StudentInCenterController extends Controller
{
    private function generateUniqueRandCode(): string
    {
        do {
            $candidate = (string) random_int(100000, 999999999);
        } while (User::query()->where('rand_code', $candidate)->exists());

        return $candidate;
    }

    private function assertCenterOwnsStudent(Student $student): void
    {
        $user = auth()->user();
        $center = $user?->center;

        if (!$user || $user->role !== 'center' || !$center) {
            abort(404);
        }

        $student->loadMissing('user');

        if (!$student->user || $student->user->role !== 'student') {
            abort(404);
        }

        if ((string) $student->referral_code !== (string) $user->rand_code) {
            abort(404);
        }
    }

    private function resolveFeatureFlags(array $data): array
    {
        $center = auth()->user()?->center;
        $features = ['ai_rank', 'gis', 'all_departments'];
        $resolved = [];

        foreach ($features as $feature) {
            $centerHasFeature = (int) ($center?->{$feature} ?? 0) === 1;
            $requestedValue = (int) ($data[$feature] ?? 0) === 1 ? 1 : 0;

            $resolved[$feature] = $centerHasFeature ? $requestedValue : 0;
        }

        return $resolved;
    }

    public function index()
    {
        $center = auth()->user();
        if (!$center) {
            abort(403);
        }

        $students = Student::with('user')->where('referral_code', $center->rand_code)->whereHas('user', fn($q) => $q->where('role', 'student'))->get();

        return view('website.web.center.student.index', compact('students'));
    }

    public function create()
    {
        $user = auth()->user();
        $center = $user?->center;
        $studentLimit = $center?->limit_student;
        $currentStudentsCount = Student::where('referral_code', $user?->rand_code)->count();
        $remainingStudentsCount = is_null($studentLimit)
            ? null
            : max((int) $studentLimit - $currentStudentsCount, 0);
        $canCreateStudent = is_null($studentLimit) || $currentStudentsCount < (int) $studentLimit;

        $provinces = Province::where('status', 1)->get();
        return view('website.web.center.student.create', compact(
            'provinces',
            'studentLimit',
            'currentStudentsCount',
            'remainingStudentsCount',
            'canCreateStudent'
        ));
    }

    public function store(Request $request, DepartmentSelector $selector)
    {
        // 1) Validation یەکخستراو
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'unique:users,code'],
            'phone' => ['nullable', 'string', 'max:11'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['student'])],
            'status' => ['required', 'in:1,0'],

            // ـــــــــــ Student-only (هەمیشە کاتێک role=student)
            'mark' => ['required_if:role,student', 'numeric'],
            'province' => ['required_if:role,student', 'string', 'max:255', Rule::exists('provinces', 'name')],
            'type' => ['required_if:role,student', 'string', Rule::in(['زانستی', 'وێژەیی'])],
            'gender' => ['required_if:role,student', 'string', Rule::in(['نێر', 'مێ'])],
            'year' => ['required_if:role,student', 'integer', 'min:1'],

            // ـــــــــــ Queue-only (تەنها کاتێک queue=yes)
            'queue' => ['nullable', 'in:yes,no'],
            'zankoline_num' => ['required_if:queue,yes', 'nullable', 'numeric', 'min:0'],
            'parallel_num' => ['required_if:queue,yes', 'nullable', 'numeric', 'min:0'],
            'evening_num' => ['required_if:queue,yes', 'nullable', 'numeric', 'min:0'],

            // ـــــــــــ Referral هاوکات هەماهەنگی ناوەکان
            'referral_student_code' => ['nullable', 'string', 'max:255'],
            'ai_rank' => ['nullable', 'in:0,1'],
            'gis' => ['nullable', 'in:0,1'],
            'all_departments' => ['nullable', 'in:0,1'],
            'lat' => ['required_if:ai_rank,1', 'nullable', 'numeric', 'between:-90,90'],
            'lng' => ['required_if:ai_rank,1', 'nullable', 'numeric', 'between:-180,180'],
        ]);

        $normalizedYear = (int) $data['year'] > 1 ? 2 : 1;

        $featureFlags = $this->resolveFeatureFlags($data);
        $center = auth()->user()?->center;
        $studentLimit = $center?->limit_student;
        $currentStudentsCount = Student::where('referral_code', auth()->user()?->rand_code)->count();

        if (!is_null($studentLimit) && $currentStudentsCount >= (int) $studentLimit) {
            return back()
                ->withErrors(['limit_student' => 'سنووری دروستکردنی قوتابی تەواو بووە.'])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($data, $selector, $featureFlags, $normalizedYear) {
                // 2) User
                $user = User::create([
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'password' => Hash::make($data['password']),
                    'role' => 'student',
                    'status' => (int) $data['status'],
                    'phone' => $data['phone'] ?? null,
                    'rand_code' => $this->generateUniqueRandCode(),
                ]);

                // 4) Student (ئەگەر student ـە)
                if ($data['role'] === 'student') {
                    Student::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'mark' => isset($data['mark']) ? (float) $data['mark'] : null,
                            'province' => $data['province'] ?? null,
                            'type' => $data['type'] ?? null,
                            'gender' => $data['gender'] ?? null,
                            'year' => $normalizedYear,
                            'referral_code' => auth()->user()->rand_code,
                            'status' => (int) ($data['status'] ?? 1),
                            'ai_rank' => $featureFlags['ai_rank'],
                            'gis' => $featureFlags['gis'],
                            'all_departments' => $featureFlags['all_departments'],
                            'lat' => (int) $featureFlags['ai_rank'] === 1 ? (isset($data['lat']) ? (float) $data['lat'] : null) : null,
                            'lng' => (int) $featureFlags['ai_rank'] === 1 ? (isset($data['lng']) ? (float) $data['lng'] : null) : null,
                        ],
                    );

                    // 5) Queue build
                    if (($data['queue'] ?? 'no') === 'yes') {
                        $selector->build($user->id, $data['province'] ?? null, $data['type'] ?? null, $data['gender'] ?? null, $normalizedYear, isset($data['mark']) ? (float) $data['mark'] : null, $data['zankoline_num'] ?? null, $data['parallel_num'] ?? null, $data['evening_num'] ?? null);
                    }
                }
            });
        } catch (InvalidArgumentException $e) {
            return back()
                ->withErrors(['choices' => $e->getMessage()])
                ->withInput();
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['error' => 'هەڵە ڕوویدا: ' . $e->getMessage()])
                ->withInput();
        }

        // 6) Redirect بەپێی role
        return redirect()->route('center.students.index')->with('success', 'قوتابی دروستکرا بە سەرکەوتوویی.');
    }

    public function show(Student $student)
    {
        $student->loadMissing('user');
        $this->assertCenterOwnsStudent($student);
        $user = $student->user;

        $result_deps = ResultDep::query()
            ->where('student_id', $student->id)
            ->with([
                'department.system:id,name',
                'department.province:id,name',
                'department.university:id,name',
                'department.college:id,name',
            ])
            ->orderByRaw('CASE WHEN rank IS NULL THEN 1 ELSE 0 END')
            ->orderBy('rank')
            ->orderBy('id')
            ->get();

        $ai_rankings = collect();
        if ((int) ($student->ai_rank ?? 0) === 1) {
            $ai_rankings = $student->aiRankings()
                ->with([
                    'department.system:id,name',
                    'department.province:id,name',
                    'department.university:id,name',
                    'department.college:id,name',
                ])
                ->orderByRaw('CASE WHEN rank IS NULL THEN 1 ELSE 0 END')
                ->orderBy('rank')
                ->orderBy('id')
                ->get();
        }

        return view('website.web.center.student.show', compact('user', 'student', 'result_deps', 'ai_rankings'));
    }

    public function edit(Student $student)
    {
        $this->assertCenterOwnsStudent($student);
        // Load related user for the form fields
        $student->load('user');

        $user = auth()->user();
        $studentLimit = $user?->center?->limit_student;
        $activeStudentsCount = Student::query()
            ->where('referral_code', (string) $user?->rand_code)
            ->where('status', 1)
            ->whereHas('user', fn($q) => $q->where('status', 1)->where('role', 'student'))
            ->count();
        $isCurrentStudentActive = (int) $student->status === 1 && (int) data_get($student, 'user.status', 0) === 1;
        $canActivateStudent = is_null($studentLimit) || $isCurrentStudentActive || $activeStudentsCount < (int) $studentLimit;

        $provinces = Province::where('status', 1)->get();

        return view('website.web.center.student.edit', compact(
            'student',
            'provinces',
            'studentLimit',
            'activeStudentsCount',
            'canActivateStudent'
        ));
    }

    /** Persist the update */
    public function update(Request $request, Student $student)
    {
        $this->assertCenterOwnsStudent($student);
        // We need user fields too
        $student->load('user');

        $data = $request->validate([
            // users table
            'name'      => ['required','string','max:50'],
            'phone'     => ['required','string','max:50'],
            'status'    => ['required', 'in:0,1'],
            // students table
            'mark'      => ['required','numeric','min:0','max:100'],
            'type'      => ['required', Rule::in(['زانستی','وێژەیی'])],
            'year'      => ['required','integer','min:1'],
            'province'  => ['required', 'string', 'max:255', Rule::exists('provinces', 'name')],
            'ai_rank' => ['nullable', 'in:0,1'],
            'gis' => ['nullable', 'in:0,1'],
            'all_departments' => ['nullable', 'in:0,1'],
            'lat' => ['required_if:ai_rank,1', 'nullable', 'numeric', 'between:-90,90'],
            'lng' => ['required_if:ai_rank,1', 'nullable', 'numeric', 'between:-180,180'],
        ]);

        $normalizedYear = (int) $data['year'] > 1 ? 2 : 1;

        $featureFlags = $this->resolveFeatureFlags($data);
        $requestedStatus = (int) ($data['status'] ?? 0);
        $isCurrentStudentActive = (int) $student->status === 1 && (int) data_get($student, 'user.status', 0) === 1;
        $studentLimit = auth()->user()?->center?->limit_student;

        if ($requestedStatus === 1 && !$isCurrentStudentActive && !is_null($studentLimit)) {
            $activeStudentsCount = Student::query()
                ->where('referral_code', (string) auth()->user()?->rand_code)
                ->where('status', 1)
                ->whereHas('user', fn($q) => $q->where('status', 1)->where('role', 'student'))
                ->count();

            if ($activeStudentsCount >= (int) $studentLimit) {
                return back()
                    ->withErrors(['status' => 'سنووری قبوڵکردنی قوتابی تەواو بووە. تکایە داواکاری زیادکردنی سنووری قوتابی بکە.'])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($student, $data, $featureFlags, $requestedStatus, $normalizedYear) {
            // Update user fields
            $student->user->update([
                'name'  => $data['name'],
                'phone' => $data['phone'],
                'status' => $requestedStatus,
            ]);

            // Update student fields
            $student->update([
                'mark'     => (float)$data['mark'],
                'type'     => $data['type'],
                'year'     => $normalizedYear,
                'province' => $data['province'],
                'status' => $requestedStatus,
                'ai_rank' => $featureFlags['ai_rank'],
                'gis' => $featureFlags['gis'],
                'all_departments' => $featureFlags['all_departments'],
                'lat' => (int) $featureFlags['ai_rank'] === 1 ? (isset($data['lat']) ? (float) $data['lat'] : null) : null,
                'lng' => (int) $featureFlags['ai_rank'] === 1 ? (isset($data['lng']) ? (float) $data['lng'] : null) : null,
            ]);
        });

        return redirect()
            ->route('center.students.index')
            ->with('success', 'زانیاری قوتابی بەسەرکەوتوویی نوێکرایەوە.');
    }

    public function activate(Student $student)
    {
        $this->assertCenterOwnsStudent($student);
        $student->loadMissing('user');

        if (!$student->user) {
            abort(404);
        }

        $requiresActivation = (int) $student->status !== 1 || (int) $student->user->status !== 1;
        $studentLimit = auth()->user()?->center?->limit_student;

        if ($requiresActivation && !is_null($studentLimit)) {
            $activeStudentsCount = Student::query()
                ->where('referral_code', (string) auth()->user()?->rand_code)
                ->where('status', 1)
                ->whereHas('user', fn($q) => $q->where('status', 1)->where('role', 'student'))
                ->count();

            if ($activeStudentsCount >= (int) $studentLimit) {
                return back()->with(
                    'error',
                    'سنووری قبوڵکردنی قوتابی تەواو بووە. تکایە داواکاری زیادکردنی سنووری قوتابی بکە.'
                );
            }
        }

        DB::transaction(function () use ($student) {
            if ((int) $student->user->status !== 1) {
                $student->user->update(['status' => 1]);
            }

            if ((int) $student->status !== 1) {
                $student->update(['status' => 1]);
            }
        });

        return back()->with('success', 'قوتابی بە سەرکەوتوویی چاڵاک کرا.');
    }

    public function destroy(Student $student)
    {
        $this->assertCenterOwnsStudent($student);
        $student->load('user');
        $student->user->delete();

        return redirect()->route('center.students.index')->with('success', 'قوتابی بە سەرکەوتوویی سڕایەوە.');
    }

}
