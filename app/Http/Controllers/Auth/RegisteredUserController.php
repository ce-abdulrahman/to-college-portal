<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $provinces = Province::where('status', 1)->orderBy('name')->get(['name']);
        return view('auth.register', compact('provinces'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'integer', 'unique:users,code'],
            'phone' => ['nullable', 'string', 'max:11'],
            'password' => ['required', Rules\Password::defaults()],
            'mark' => ['required', 'numeric', 'min:0', 'max:100'],
            'province' => ['required', 'string', 'max:255', Rule::exists('provinces', 'name')->where('status', 1)],
            'type' => ['required', Rule::in(['زانستی', 'وێژەیی'])],
            'gender' => ['required', Rule::in(['نێر', 'مێ'])],
            'year' => ['required', 'integer', 'min:1', 'max:12'],
            'referral_code' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'code' => (int) $data['code'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'rand_code' => $this->generateRandCode(),
                'role' => 'student',
                'status' => 0,
            ]);

            Student::create([
                'user_id' => $user->id,
                'mark' => $data['mark'],
                'province' => $data['province'],
                'type' => $data['type'],
                'gender' => $data['gender'],
                'year' => $data['year'],
                'referral_code' => $data['referral_code'] ?? null,
                'status' => 0,
                'ai_rank' => 0,
                'gis' => 0,
                'all_departments' => 0,
            ]);
        });

        return view('auth.register-waiting');
    }

    private function generateRandCode(): int
    {
        do {
            $code = random_int(100000, 999999);
        } while (User::where('rand_code', $code)->exists());

        return $code;
    }
}
