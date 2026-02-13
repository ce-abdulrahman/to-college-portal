<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
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
        return view('website.web.teacher.profile.edit', [
            'user' => $user,
            'teacher' => $user->teacher,
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
        ]);

        $user->update($data);

        return back()->with('status', 'profile-updated');
    }
}
