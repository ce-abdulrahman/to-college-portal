<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherProfileController extends Controller
{
    /**
     * Update the profile.
     */
    public function edit(string $id)
    {
        $user = User::where('role', 'teacher')->findOrfail($id);
        if (auth()->user()->id !== $user->id) {
            abort(403);
        }
        $currentStudentsCount = Student::where('referral_code', $user->rand_code)->count();

        return view('website.web.teacher.profile.edit', [
            'user' => $user,
            'teacher' => $user->teacher,
            'provinces' => Province::where('status', 1)->get(),
            'currentStudentsCount' => $currentStudentsCount,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::where('role', 'teacher')->findOrfail($id);
        if ($request->user()->id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:11',
            'province' => 'nullable|string|max:255|exists:provinces,name',
        ]);

        $user->update([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
        ]);

        $user->teacher()->updateOrCreate(
            ['user_id' => $user->id],
            ['province' => $data['province'] ?? null]
        );

        return back()->with('status', 'profile-updated');
    }
}
