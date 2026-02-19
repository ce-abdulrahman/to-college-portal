<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function inactive(Request $request): View
    {
        $inactiveAccount = $request->session()->get('inactiveAccount');

        if (!$inactiveAccount) {
            return view('auth.login');
        }

        return view('auth.inactive-account', compact('inactiveAccount'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (
            in_array($user->role, ['center', 'teacher', 'student'], true)
            && (int) $user->status === 0
        ) {
            $referrer = $this->resolveReferrer($user);

            $inactiveAccount = [
                'accountRoleLabel' => $this->roleLabel($user->role),
                'referrer' => $referrer,
            ];

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('account.inactive')
                ->with('inactiveAccount', $inactiveAccount);
        }

        if ($user->role === 'center') {
            return redirect(RouteServiceProvider::CENTER_DASHBOARD);
        }

        if ($user->role === 'admin') {
            return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
        }

        if ($user->role === 'teacher') {
            return redirect(RouteServiceProvider::TEACHER_DASHBOARD);
        }

        if ($user->role === 'student') {
            return redirect(RouteServiceProvider::STUDENT_DASHBOARD);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function roleLabel(string $role): string
    {
        return match ($role) {
            'admin' => 'ئەدمین',
            'center' => 'سەنتەر',
            'teacher' => 'مامۆستا',
            'student' => 'قوتابی',
            default => $role,
        };
    }

    private function resolveReferrer(User $user): ?array
    {
        $user->loadMissing(['center', 'teacher', 'student']);

        $referralCode = match ($user->role) {
            'center' => data_get($user, 'center.referral_code'),
            'teacher' => data_get($user, 'teacher.referral_code'),
            'student' => data_get($user, 'student.referral_code'),
            default => null,
        };

        if (!$referralCode || !is_numeric($referralCode)) {
            return null;
        }

        $referrer = User::query()
            ->select('name', 'phone', 'role', 'rand_code')
            ->where('rand_code', (int) $referralCode)
            ->first();

        if (!$referrer) {
            return null;
        }

        return [
            'name' => $referrer->name,
            'phone' => $referrer->phone,
            'role' => $referrer->role,
            'roleLabel' => $this->roleLabel($referrer->role),
            'randCode' => $referrer->rand_code,
        ];
    }
}
