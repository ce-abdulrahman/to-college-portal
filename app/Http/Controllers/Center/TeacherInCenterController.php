<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Models\Department;
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

class TeacherInCenterController extends Controller
{
    public function index()
    {
        $center = auth()->user();
        if (!$center) {
            abort(403);
        }

        $users = User::where('role', 'teacher')->get();

        $teachers = Teacher::with('user')->where('referral_code', $center->rand_code)->whereHas('user', fn($q) => $q->where('role', 'teacher'))->get();

        return view('website.web.center.teacher.index', compact('teachers', 'users'));
    }

    public function create()
    {
        $provinces = Province::where('status', 1)->get();
        return view('website.web.center.teacher.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, DepartmentSelector $selector)
    {
        // 1) ڤالیدەیشنی بنەڕەتی
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:users,code'],
            'phone' => ['nullable', 'string', 'max:11'],
            'password' => ['required', 'string', 'min:8'],
            'rand_code' => ['required', 'integer', 'unique:users,rand_code'],
            'role' => ['required', Rule::in(['teacher', 'student'])],
            // 'referral_teacher_code' => ['required', 'string', 'max:255'], // Removed as it should be auto-assigned
            'status' => ['required', 'in:1,0'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'status' => (int) $data['status'],
            'phone' => $data['phone'] ?? null,
            'rand_code' => (int) $data['rand_code'] ?? 0,
        ]);

        // Inherit features from the Center
        $center = auth()->user()->center;
        
        Teacher::create([
            'user_id' => $user->id,
            'referral_code' => auth()->user()->rand_code,
            'ai_rank' => $center->ai_rank ?? 0,
            'gis' => $center->gis ?? 0,
            'all_departments' => $center->all_departments ?? 0,
        ]);

        return redirect()->route('center.teachers.index')->with('success', 'بەکارهێنەر دروستکرا بەسەرکەوتوویی.');
    }

    public function show(Teacher $teacher)
    {
        // مامۆستا + یوزەری پەیوەست کراو
        $teacher->load('user');
        $userTeacher = $teacher->user;

        // rand_code لە users ـە ( بە پێی سکیمای تۆ )
        $ref = data_get($teacher, 'user.rand_code');
        // ئەگەر rand_code نییە، ئەوا student نییە
        if (!$ref) {
            $students = collect();
            $studentsCount = 0;
            return view('website.web.center.teacher.show', compact('teacher', 'students', 'studentsCount'));
        }

        // هەموو قوتابییە پەیوەندیدارەکان (relation: student.referral_code == teacher.user.rand_code)
        $students = Student::with('user')->where('referral_code', $ref)->latest('id')->get();

        //dd($students);
        // ژمارەی قوتابییەکان
        $studentsCount = $students->count();
        // یان بەبێ هێنانی هەموو داتا:
        $studentsCount = Student::where('referral_code', $ref)->count();

        return view('website.web.center.teacher.show', compact('userTeacher', 'students', 'studentsCount'));
    }

    public function edit(string $id)
    {
        $teacher = Teacher::findOrFail($id);

        return view('website.web.center.teacher.edit', compact('teacher'));
    }

    public function update(Request $request, string $id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        $user = User::findOrFail($teacher->user_id); // ✅ چاککرا

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'code' => ['required', 'integer'],
            'phone' => ['required', 'string', 'max:50'],
            'rand_code' => ['required', 'integer'],
            'role' => ['required', Rule::in(['teacher'])],
            'status' => ['required', 'in:1,0'],
        ]);

        // Update user info
        $user->name = $data['name'];
        $user->code = $data['code'];
        $user->phone = $data['phone'];
        $user->rand_code = $data['rand_code'];
        $user->role = $data['role'];
        $user->status = $data['status'];
        $user->save();

        return redirect()
            ->route('center.teachers.index')
            ->with('success', 'بەسەرکەوتوویی نوێکراوی مامۆستا');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('center.students.index')->with('success', 'زانکۆ بە سەرکەوتوویی سڕایەوە.');
    }

}
