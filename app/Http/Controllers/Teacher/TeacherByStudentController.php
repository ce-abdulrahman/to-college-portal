<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Province;
use App\Models\ResultDep;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\DepartmentSelector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use InvalidArgumentException;



class TeacherByStudentController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();
        if (! $teacher) {
            abort(403);
        }

        $users = User::where('role', 'student')->get();

        $students = Student::with('user')
            ->where('referral_code', $teacher->rand_code)
            ->whereHas('user', fn($q) => $q->where('role', 'student'))
            ->get();

        return view('website.web.teacher.student.index', compact('students', 'users'));
    }

    public function create()
    {
        $provinces = Province::where('status', 1)->get();
        return view('website.web.teacher.student.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, DepartmentSelector $selector)
    {
        // 1) ڤالیدەیشنی بنەڕەتی
        $base = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:users,code'],
            'phone' => ['nullable', 'string', 'max:11'],
            'password' => ['required', 'string', 'min:8'],
            'rand_code' => ['required', 'integer', 'unique:users,rand_code'],
            'role' => ['required', Rule::in(['student'])],
            // هەرکە لە فۆرمەکەت هەیە، ڕاگرتووە؛ دەتوانیت دابنێیت:
            'mark' => ['required', 'numeric'],
            'province' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(['زانستی', 'وێژەیی'])],
            'gender' => ['required', 'string', Rule::in(['نێر', 'مێ'])],
            'year' => ['required', 'integer', 'min:1'],
            'referral_code' => ['nullable', 'string', 'max:255'],

            'status' => ['required', 'in:1,0'],
        ]);

        $student = [];
        if ($request->queue === 'yes') {
            // 2) ڤالیدەیشنی تایبەتی قوتابی
            $student = $request->validate([
                // تەنیا کاتێک queue = yes → پێویست، هەروەها بە numeric/min:0
                'queue' => ['nullable', 'in:yes,no'],
                'zankoline_num' => ['required_if:queue,yes', 'nullable', 'numeric', 'min:0'],
                'parallel_num' => ['required_if:queue,yes', 'nullable', 'numeric', 'min:0'],
                'evening_num' => ['required_if:queue,yes', 'nullable', 'numeric', 'min:0'],
            ]);
        }

        // هەردوو set ـەکە پێکبخە
        $data = array_merge($base, $student);

        try {
            DB::transaction(function () use ($data, $selector) {
                // 3) دروستکردنی بەکارهێنەر
                $user = User::create([
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'password' => Hash::make($data['password']),
                    'role' => $data['role'],
                    // ئەگەر status هەیە لە فۆرم:
                    'status' => (int) $data['status'],
                    'phone' => $data['phone'] ?? null,
                    'rand_code' => (int) $data['rand_code'] ?? 0,
                ]);

                // 4) تەنیا بۆ قوتابی
                if ($data['role'] === 'student') {
                    Student::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'mark' => (float) $data['mark'],
                            'province' => $data['province'],
                            'type' => $data['type'],
                            'gender' => $data['gender'],
                            'year' => (int) $data['year'],
                            'referral_code' => $data['referral_code'] ?? null,
                            'status' => 1,
                        ],
                    );

                    // 5) ئەگەر queue=yes → ڕێزبەندی
                    if (($data['queue'] ?? 'no') === 'yes') {
                        $selector->build($user->id, $data['province'], $data['type'], $data['gender'], (int) $data['year'], (float) $data['mark'], $data['zankoline_num'] ?? null, $data['parallel_num'] ?? null, $data['evening_num'] ?? null);
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

        return redirect()->route('teacher.students.index')->with('success', 'بەکارهێنەر دروستکرا بەسەرکەوتوویی.');
    }

    public function show(string $id)
    {
        // student_id لە ڕوتەکەدات
        $user = User::where('id', $id)->findOrFail($id); // بە ئاسانی دەستی بە یوزەر دەگات
        $student = Student::with('user')->where('user_id', $user->id)->firstOrFail();

        $result_deps = ResultDep::with('student')->where('student_id', $student->id)->get();

        $NameDep = Department::whereIn('id', $result_deps->pluck('department_id'))->get();

        return view('website.web.teacher.student.show', compact('user', 'student', 'result_deps', 'NameDep'));
    }
    

}
