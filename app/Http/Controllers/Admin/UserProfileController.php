<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Province;
use App\Models\Student;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'admin')->get();
        $provinces = Province::all();
        return view('website.web.admin.user.index', compact('users', 'provinces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::all();
        return view('website.web.admin.user.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,student',
        ]);

        $user = User::create([
            'name' => $request->name,
            'code' => $request->code,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'mark' => $request->mark,
                'province' => $request->province,
                'type' => $request->type,
                'gender' => $request->gender,
                'year' => $request->year,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'بەکارهێنەر دروستکرا بەسەرکەوتوویی.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrfail($id);
        return view('website.web.admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrfail($id);

        // checked password old then two input for password new and confirm password
        if ($request->old_password) {
            $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255|unique:users,code,' . $user->id,
                'old_password' => 'required|string|min:8',
                'password' => 'required|string|min:8|confirmed',
            ]);
            if (!\Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'The provided password does not match your current password.']);
            }
            $user->update([
                'name' => $request->name,
                'code' => $request->code,
                'password' => bcrypt($request->password),
            ]);
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255|unique:users,code,' . $user->id,
            ]);
            $user->update([
                'name' => $request->name,
                'code' => $request->code,
            ]);
        }
        return redirect()->route('admin.users.index')->with('success', 'ئەدمینی نوێکردنەوە بەسەرکەوتوویی تەواو بوو.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrfail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'بەکارهێنەر سڕایەوە بەسەرکەوتوویی.');
    }
}
