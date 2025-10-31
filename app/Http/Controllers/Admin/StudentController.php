<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Department;
use App\Models\ResultDep;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'student')->get();
        return view('website.web.admin.user.student.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // student_id لە ڕوتەکەدات
        $student = Student::with('user')->findOrFail($id);
        $user = $student->user; // بە ئاسانی دەستی بە یوزەر دەگات

        $result_deps = ResultDep::with('system')
            ->where(function ($q) use ($user, $student) {
                $q->where('user_id', $user->id)->orWhere('student_id', $student->id);
            })
            ->get();

        return view('website.web.admin.user.student.show', compact('user', 'student', 'result_deps'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('website.web.admin.user.student.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password_old' => 'nullable|string|max:255',
            'password_new' => 'nullable|string|max:255',
            'password_confirmation' => 'nullable|string|max:255|same:password_new',
            'role' => 'required|in:admin,student',
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->role = $request->role;

        if ($request->password_old && $request->password_new && $request->password_confirmation) {
            if (password_verify($request->password_old, $user->password)) {
                $user->password = bcrypt($request->password_new);
            } else {
                return redirect()
                    ->back()
                    ->withErrors(['password_old' => 'وشەی پێشوو هەڵەیە'])
                    ->withInput();
            }
        }

        $user->save();

        return redirect()->route('admin.students.index')->with('success', 'بەسەرکەوتوویی نوێکراوی قوتابی');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
