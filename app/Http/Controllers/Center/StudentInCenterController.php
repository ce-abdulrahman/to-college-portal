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

class StudentInCenterController extends Controller
{
    public function index()
    {
        $center = auth()->user();
        if (!$center) {
            abort(403);
        }

        $users = User::where('role', 'student')->get();

        $students = Student::with('user')->where('referral_code', $center->rand_code)->whereHas('user', fn($q) => $q->where('role', 'student'))->get();

        return view('website.web.center.student.index', compact('students', 'users'));
    }

    public function create()
    {
        $provinces = Province::where('status', 1)->get();
        return view('website.web.center.student.create', compact('provinces'));
    }

    public function store(Request $request, DepartmentSelector $selector)
    {
        // 1) Validation یەکخستراو
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:users,code'],
            'phone' => ['nullable', 'string', 'max:11'],
            'password' => ['required', 'string', 'min:8'],
            'rand_code' => ['required', 'integer', 'unique:users,rand_code'],
            'role' => ['required', Rule::in(['student'])],
            'status' => ['required', 'in:1,0'],

            // ـــــــــــ Student-only (هەمیشە کاتێک role=student)
            'mark' => ['required_if:role,student', 'numeric'],
            'province' => ['required_if:role,student', 'string', 'max:255'],
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
        ]);

        try {
            DB::transaction(function () use ($data, $selector) {
                // 2) User
                $user = User::create([
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'password' => Hash::make($data['password']),
                    'role' => $data['role'],
                    'status' => (int) $data['status'],
                    'phone' => $data['phone'] ?? null,
                    'rand_code' => (int) ($data['rand_code'] ?? 0),
                ]);

                // 4) Student (ئەگەر student ـە)
                if ($data['role'] === 'student') {
                    $center = auth()->user()->center;
                    
                    Student::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'mark' => isset($data['mark']) ? (float) $data['mark'] : null,
                            'province' => $data['province'] ?? null,
                            'type' => $data['type'] ?? null,
                            'gender' => $data['gender'] ?? null,
                            'year' => isset($data['year']) ? (int) $data['year'] : null,
                            'referral_code' => auth()->user()->rand_code,
                            'status' => (int) ($data['status'] ?? 1),
                            'ai_rank' => $center->ai_rank ?? 0,
                            'gis' => $center->gis ?? 0,
                            'all_departments' => $center->all_departments ?? 0,
                        ],
                    );

                    // 5) Queue build
                    if (($data['queue'] ?? 'no') === 'yes') {
                        $selector->build($user->id, $data['province'] ?? null, $data['type'] ?? null, $data['gender'] ?? null, isset($data['year']) ? (int) $data['year'] : null, isset($data['mark']) ? (float) $data['mark'] : null, $data['zankoline_num'] ?? null, $data['parallel_num'] ?? null, $data['evening_num'] ?? null);
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

    public function show(string $id)
    {
        // student_id لە ڕوتەکەدات
        $user = User::where('id', $id)->findOrFail($id); // بە ئاسانی دەستی بە یوزەر دەگات
        $student = Student::with('user')->where('user_id', $user->id)->firstOrFail();

        $result_deps = ResultDep::with('student')->where('student_id', $student->id)->get();

        $NameDep = Department::whereIn('id', $result_deps->pluck('department_id'))->get();

        return view('website.web.center.student.show', compact('user', 'student', 'result_deps', 'NameDep'));
    }

    public function edit(Student $student)
    {
        // Load related user for the form fields
        $student->load('user');

        $provinces = Province::where('status', 1)->get();

        return view('website.web.center.student.edit', compact('student', 'provinces'));
    }

    /** Persist the update */
    public function update(Request $request, Student $student)
    {
        // We need user fields too
        $student->load('user');

        $data = $request->validate([
            // users table
            'name'      => ['required','string','max:50'],
            'phone'     => ['required','string','max:50'],
            // students table
            'mark'      => ['required','numeric','min:0','max:100'],
            'type'      => ['required', Rule::in(['زانستی','وێژەیی'])],
            'year'      => ['required','integer','min:1','max:5'],
            'province'  => ['required','string','max:255'],
        ]);

        DB::transaction(function () use ($student, $data) {
            // Update user fields
            $student->user->update([
                'name'  => $data['name'],
                'phone' => $data['phone'],
            ]);

            // Update student fields
            $student->update([
                'mark'     => (float)$data['mark'],
                'type'     => $data['type'],
                'year'     => (int)$data['year'],
                'province' => $data['province'],
            ]);
        });

        return redirect()
            ->route('center.students.index')
            ->with('success', 'زانیاری قوتابی بەسەرکەوتوویی نوێکرایەوە.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('center.students.index')->with('success', 'زانکۆ بە سەرکەوتوویی سڕایەوە.');
    }

}
