<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;

class CenterController extends Controller
{
    public function index()
    {
        $users = User::with('center')->where('role', 'center')->get();
        return view('website.web.admin.user.center.index', compact('users'));
    }

    public function show(string $id)
    {
        $user = User::with('center')->findOrFail($id);
        $teachers = Teacher::with('user')->where('referral_code', $user->rand_code)->get();

        return view('website.web.admin.user.center.show', compact(
            'teachers',
            'user'
        ));

    }
}
