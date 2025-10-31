<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class CenterController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'center')->get();
        return view('website.web.admin.user.center.index', compact('users'));
    }
}
