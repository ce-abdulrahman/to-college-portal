<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CenterProfileController extends Controller
{
    public function edit(string $id)
    {
        $user = User::where('role', 'center')->findOrfail($id);
        return view('website.web.center.profile.edit', compact('user'));
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
                return back()->withErrors(['old_password' => 'وشەی نهێنی دابینکراو لەگەڵ وشەی نهێنی ئێستاتدا ناگونجێت.']);
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
                'phone' => 'nullable',
            ]);
            $user->update([
                'name' => $request->name,
                'code' => $request->code,
                'phone' => $request->phone,
            ]);
        }
        return redirect()->route('center.students.index')->with('success', 'ئەدمینی نوێکردنەوە بەسەرکەوتوویی تەواو بوو.');
    }
}
