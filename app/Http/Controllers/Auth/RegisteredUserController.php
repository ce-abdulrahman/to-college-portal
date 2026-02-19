<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Province;
use Illuminate\Http\JsonResponse;
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
    public function create(Request $request): View
    {
        $rawReferral = session()->getOldInput('referral_code')
            ?? $request->query('ref')
            ?? $request->query('referral_code');

        $prefilledReferralCode = is_numeric($rawReferral) ? (string) ((int) $rawReferral) : null;
        $referrer = $this->resolveReferrerUser($prefilledReferralCode);

        $provinces = Province::where('status', 1)->orderBy('name')->get(['name']);

        return view('auth.register', compact('provinces', 'prefilledReferralCode', 'referrer'));
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
            'code' => ['required', 'string', 'unique:users,code'],
            'phone' => ['nullable', 'string', 'max:11'],
            'password' => ['required', Rules\Password::defaults()],
            'mark' => ['required', 'numeric', 'min:0', 'max:100'],
            'province' => ['required', 'string', 'max:255', Rule::exists('provinces', 'name')->where('status', 1)],
            'type' => ['required', Rule::in(['زانستی', 'وێژەیی'])],
            'gender' => ['required', Rule::in(['نێر', 'مێ'])],
            'year' => ['required', 'integer', 'min:1', 'max:12'],
            'referral_code' => [
                'nullable',
                'integer',
            ],
        ]);

        $submittedReferralCode = isset($data['referral_code'])
            ? (string) ((int) $data['referral_code'])
            : null;

        // ئەگەر referral_code بۆ center/teacher نەبێت، خۆکارانە هەژمار دەکرێت بۆ admin.
        $referrer = $this->resolveCenterOrTeacherReferrer($submittedReferralCode)
            ?? $this->resolvePrimaryAdminReferrer();

        if (!$referrer) {
            return back()
                ->withErrors(['referral_code' => 'هیچ ئەدمینێک نەدۆزرایەوە بۆ تۆمارکردن.'])
                ->withInput();
        }

        DB::transaction(function () use ($data, $referrer) {
            $user = User::create([
                'name' => $data['name'],
                'code' => $data['code'],
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
                'referral_code' => (string) $referrer->rand_code,
                'status' => 0,
                'ai_rank' => 0,
                'gis' => 0,
                'all_departments' => 0,
            ]);
        });

        return view('auth.register-waiting', [
            'referrer' => $referrer,
            'referrerRoleLabel' => $referrer ? $this->mapRoleLabel($referrer->role) : null,
        ]);
    }

    public function referrerInfo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['nullable', 'string'],
        ]);

        $code = isset($data['code']) ? trim((string) $data['code']) : null;
        $referrer = $this->resolveReferrerUser($code);

        if (!$referrer) {
            return response()->json([
                'found' => false,
            ]);
        }

        return response()->json([
            'found' => true,
            'name' => $referrer->name,
            'phone' => $referrer->phone,
            'role' => $referrer->role,
            'role_label' => $this->mapRoleLabel($referrer->role),
            'rand_code' => (string) $referrer->rand_code,
        ]);
    }

    private function generateRandCode(): int
    {
        do {
            $code = random_int(100000, 999999);
        } while (User::where('rand_code', $code)->exists());

        return $code;
    }

    private function resolveReferrerUser(?string $code): ?User
    {
        if (!$code || !is_numeric($code)) {
            return null;
        }

        return User::query()
            ->select('id', 'name', 'phone', 'role', 'rand_code')
            ->where('rand_code', (int) $code)
            ->whereIn('role', ['admin', 'center', 'teacher'])
            ->first();
    }

    private function resolveCenterOrTeacherReferrer(?string $code): ?User
    {
        if (!$code || !is_numeric($code)) {
            return null;
        }

        return User::query()
            ->select('id', 'name', 'phone', 'role', 'rand_code')
            ->where('rand_code', (int) $code)
            ->whereIn('role', ['center', 'teacher'])
            ->first();
    }

    private function resolvePrimaryAdminReferrer(): ?User
    {
        return User::query()
            ->select('id', 'name', 'phone', 'role', 'rand_code')
            ->where('role', 'admin')
            ->orderBy('id')
            ->first();
    }

    private function mapRoleLabel(string $role): string
    {
        return match ($role) {
            'admin' => 'ئەدمین',
            'center' => 'سەنتەر',
            'teacher' => 'مامۆستا',
            default => $role,
        };
    }
}
