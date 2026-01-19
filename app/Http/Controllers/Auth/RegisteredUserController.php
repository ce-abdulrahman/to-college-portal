<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'integer', 'unique:users,code'],
            'phone' => ['nullable', 'string', 'max:11'],
            'password' => ['required', Rules\Password::defaults()],
            'rand_code' => ['required', 'integer', 'unique:users,rand_code'],
            'role' => ['nullable', Rule::in(['admin', 'center', 'teacher', 'student'])], // or remove to force default
        ]);

        $user = User::create([
            'name' => $request->name,
            'code' => (int) $request->code,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'rand_code' => (int) $request->rand_code,
            'role' => $request->filled('role') ? $request->role : 'student', // default student
        ]);

        //ئەگەر نەتەوێت بەکاربهێنیت
        // ئەگەر پەیجەکەت verification بە email پێویست نییە، دەتوانیت بە ئاسانی لە کۆدەکەت بسڕیتەوە:
        //event(new Registered($user));

        //Auth::login($user);

        // check role and redirect accordingly
        if (Auth::user()->role === 'super_admin') {
            return redirect(RouteServiceProvider::SUPER_ADMIN_DASHBOARD);
        }

        if (Auth::user()->role === 'admin') {
            return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
        }

        if (Auth::user()->role === 'teacher') {
            return redirect(RouteServiceProvider::TEACHER_DASHBOARD);
        }

        if (Auth::user()->role === 'student') {
            return redirect(RouteServiceProvider::STUDENT_DASHBOARD);
        }

        return redirect()->route('login')->with('success', 'قوتابی نوێ بە سەرکەوتووی دروست کرا.');
    }
}
