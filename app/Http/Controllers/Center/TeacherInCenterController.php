<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherInCenterController extends Controller
{
    private function generateUniqueRandCode(): string
    {
        do {
            $candidate = (string) random_int(100000, 999999999);
        } while (User::query()->where('rand_code', $candidate)->exists());

        return $candidate;
    }

    private function assertCenterOwnsTeacher(Teacher $teacher): void
    {
        $user = auth()->user();
        $center = $user?->center;

        if (!$user || $user->role !== 'center' || !$center) {
            abort(404);
        }

        $teacher->loadMissing('user');

        if (!$teacher->user || $teacher->user->role !== 'teacher') {
            abort(404);
        }

        if ((string) $teacher->referral_code !== (string) $user->rand_code) {
            abort(404);
        }
    }

    private function resolveFeatureFlags(array $data): array
    {
        $center = auth()->user()?->center;
        $features = ['ai_rank', 'gis', 'all_departments', 'queue_hand_department'];
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

        $teachers = Teacher::with('user')->where('referral_code', $center->rand_code)->whereHas('user', fn($q) => $q->where('role', 'teacher'))->get();

        return view('website.web.center.teacher.index', compact('teachers'));
    }

    public function create()
    {
        $user = auth()->user();
        $center = $user?->center;
        $teacherLimit = $center?->limit_teacher;
        $currentTeachersCount = Teacher::where('referral_code', $user?->rand_code)->count();
        $remainingTeachersCount = is_null($teacherLimit)
            ? null
            : max((int) $teacherLimit - $currentTeachersCount, 0);
        $canCreateTeacher = is_null($teacherLimit) || $currentTeachersCount < (int) $teacherLimit;

        $provinces = Province::where('status', 1)->get();
        return view('website.web.center.teacher.create', compact(
            'provinces',
            'teacherLimit',
            'currentTeachersCount',
            'remainingTeachersCount',
            'canCreateTeacher'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1) ڤالیدەیشنی بنەڕەتی
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'unique:users,code'],
            'phone' => ['nullable', 'string', 'max:11'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['teacher'])],
            // 'referral_teacher_code' => ['required', 'string', 'max:255'], // Removed as it should be auto-assigned
            'status' => ['required', 'in:1,0'],
            'province' => ['required', 'string', Rule::exists('provinces', 'name')],
            'ai_rank' => ['nullable', 'in:0,1'],
            'gis' => ['nullable', 'in:0,1'],
            'all_departments' => ['nullable', 'in:0,1'],
            'queue_hand_department' => ['nullable', 'in:0,1'],
            'limit_student' => ['nullable', 'integer', 'min:0'],
        ]);

        $center = auth()->user()?->center;
        $teacherLimit = $center?->limit_teacher;
        $currentTeachersCount = Teacher::where('referral_code', auth()->user()?->rand_code)->count();

        if (!is_null($teacherLimit) && $currentTeachersCount >= (int) $teacherLimit) {
            return back()
                ->withErrors(['limit_teacher' => 'سنووری دروستکردنی مامۆستا تەواو بووە.'])
                ->withInput();
        }

        $teacherLimitStudent = array_key_exists('limit_student', $data) && $data['limit_student'] !== null
            ? (int) $data['limit_student']
            : ($center && !is_null($center->limit_student) ? (int) $center->limit_student : null);

        if ($center && !is_null($center->limit_student) && !is_null($teacherLimitStudent) && $teacherLimitStudent > (int) $center->limit_student) {
            return back()
                ->withErrors(['limit_student' => 'سنووری قوتابی بۆ مامۆستا نابێت گەورەتر بێت لە سنووری قوتابی سەنتەر.'])
                ->withInput();
        }

        $user = User::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'password' => Hash::make($data['password']),
            'role' => 'teacher',
            'status' => (int) $data['status'],
            'phone' => $data['phone'] ?? null,
            'rand_code' => $this->generateUniqueRandCode(),
        ]);

        // Features can only be enabled if the center itself has them enabled.
        $featureFlags = $this->resolveFeatureFlags($data);

        Teacher::create([
            'user_id' => $user->id,
            'referral_code' => auth()->user()->rand_code,
            'province' => $data['province'] ?? ($center?->province ?? null),
            'ai_rank' => $featureFlags['ai_rank'],
            'gis' => $featureFlags['gis'],
            'all_departments' => $featureFlags['all_departments'],
            'queue_hand_department' => $featureFlags['queue_hand_department'],
            'limit_student' => $teacherLimitStudent,
        ]);

        return redirect()->route('center.teachers.index')->with('success', 'بەکارهێنەر دروستکرا بەسەرکەوتوویی.');
    }

    public function show(Teacher $teacher)
    {
        $this->assertCenterOwnsTeacher($teacher);
        // مامۆستا + یوزەری پەیوەست کراو
        $teacher->load('user');
        $userTeacher = $teacher->user;

        // rand_code لە users ـە ( بە پێی سکیمای تۆ )
        $ref = data_get($teacher, 'user.rand_code');
        // ئەگەر rand_code نییە، ئەوا student نییە
        if (!$ref) {
            $students = collect();
            $studentsCount = 0;
            return view('website.web.center.teacher.show', compact('teacher', 'userTeacher', 'students', 'studentsCount'));
        }

        // هەموو قوتابییە پەیوەندیدارەکان (relation: student.referral_code == teacher.user.rand_code)
        // تەنها قوتابییەکانی user-ەکەیان هێشتا هەیە (role=student).
        $students = Student::query()
            ->with([
                'user',
                'resultDeps' => function ($query) {
                    $query->with([
                        'department.system:id,name',
                        'department.province:id,name',
                        'department.university:id,name',
                        'department.college:id,name',
                    ])
                        ->orderByRaw('CASE WHEN rank IS NULL THEN 1 ELSE 0 END')
                        ->orderBy('rank')
                        ->orderBy('id');
                },
            ])
            ->where('referral_code', (string) $ref)
            ->whereHas('user', fn($q) => $q->where('role', 'student'))
            ->latest('id')
            ->get();

        // ژمارەی قوتابییەکان
        $studentsCount = $students->count();

        return view('website.web.center.teacher.show', compact('userTeacher', 'students', 'studentsCount', 'teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $this->assertCenterOwnsTeacher($teacher);
        $provinces = Province::where('status', 1)->get();
        return view('website.web.center.teacher.edit', compact('teacher', 'provinces'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $this->assertCenterOwnsTeacher($teacher);
        $teacher->load('user');
        $user = User::findOrFail($teacher->user_id); // ✅ چاککرا

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'code' => ['required', 'string', Rule::unique('users', 'code')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:50'],
            'role' => ['required', Rule::in(['teacher'])],
            'status' => ['required', 'in:1,0'],
            'province' => ['required', 'string', Rule::exists('provinces', 'name')],
            'ai_rank' => ['nullable', 'in:0,1'],
            'gis' => ['nullable', 'in:0,1'],
            'all_departments' => ['nullable', 'in:0,1'],
            'queue_hand_department' => ['nullable', 'in:0,1'],
            'limit_student' => ['nullable', 'integer', 'min:0'],
        ]);

        $center = auth()->user()?->center;
        $limitStudent = array_key_exists('limit_student', $data) && $data['limit_student'] !== null
            ? (int) $data['limit_student']
            : ($center && !is_null($center->limit_student) ? (int) $center->limit_student : null);

        if ($center && !is_null($center->limit_student) && !is_null($limitStudent) && $limitStudent > (int) $center->limit_student) {
            return back()
                ->withErrors(['limit_student' => 'سنووری قوتابی بۆ مامۆستا نابێت گەورەتر بێت لە سنووری قوتابی سەنتەر.'])
                ->withInput();
        }

        $currentTeacherStudentsCount = Student::where('referral_code', $teacher->user->rand_code)->count();
        if (!is_null($limitStudent) && $limitStudent < $currentTeacherStudentsCount) {
            return back()
                ->withErrors(['limit_student' => 'ناتوانیت سنوورەکە کەمتر بکەیتەوە لە ژمارەی قوتابییە هەنووکەییەکان.'])
                ->withInput();
        }

        // Update user info
        $user->name = $data['name'];
        $user->code = $data['code'];
        $user->phone = $data['phone'];
        // rand_code only admins can change.
        $user->role = 'teacher';
        $user->status = $data['status'];
        $user->save();

        $featureFlags = $this->resolveFeatureFlags($data);
        $teacher->update([
            'province' => $data['province'],
            'ai_rank' => $featureFlags['ai_rank'],
            'gis' => $featureFlags['gis'],
            'all_departments' => $featureFlags['all_departments'],
            'queue_hand_department' => $featureFlags['queue_hand_department'],
            'limit_student' => $limitStudent,
        ]);

        return redirect()
            ->route('center.teachers.index')
            ->with('success', 'بەسەرکەوتوویی نوێکراوی مامۆستا');
    }

    public function activate(Teacher $teacher)
    {
        $this->assertCenterOwnsTeacher($teacher);
        $teacher->loadMissing('user');

        if (!$teacher->user) {
            abort(404);
        }

        if ((int) $teacher->user->status !== 1) {
            $teacher->user->update(['status' => 1]);
        }

        return back()->with('success', 'مامۆستا بە سەرکەوتوویی چاڵاک کرا.');
    }

    public function destroy(Teacher $teacher)
    {
        $this->assertCenterOwnsTeacher($teacher);
        $teacher->load('user');
        $teacher->user->delete();

        return redirect()->route('center.teachers.index')->with('success', 'مامۆستا بە سەرکەوتوویی سڕایەوە.');
    }

}
