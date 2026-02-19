<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class CenterProfileController extends Controller
{
    public function edit(string $id)
    {
        $user = User::where('role', 'center')->findOrfail($id);
        if (auth()->user()->id !== $user->id) {
            abort(403);
        }
        $currentTeachersCount = Teacher::where('referral_code', $user->rand_code)->count();
        $currentStudentsCount = Student::where('referral_code', $user->rand_code)->count();

        return view('website.web.center.profile.edit', [
            'user' => $user,
            'center' => $user->center,
            'provinces' => Province::where('status', 1)->get(),
            'currentTeachersCount' => $currentTeachersCount,
            'currentStudentsCount' => $currentStudentsCount,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::where('role', 'center')->findOrfail($id);
        if ($request->user()->id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:11',
            'province' => 'nullable|string|max:255|exists:provinces,name',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
        ]);

        $user->center()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'province' => $data['province'] ?? null,
                'address' => $data['address'] ?? null,
                'description' => $data['description'] ?? null,
            ]
        );

        return back()->with('status', 'profile-updated');
    }
}
