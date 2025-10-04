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
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', Rule::in(['admin', 'student'])], // or remove to force default
        ]);

        $user = User::create([
            'name' => $request->name,
            'code' => (int) $request->code,
            'password' => Hash::make($request->password),
            'role' => $request->filled('role') ? $request->role : 'student', // default student
        ]);

        event(new Registered($user));

        Auth::login($user);

        // check role and redirect accordingly
        if ($user->role === 'admin') {
            return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
