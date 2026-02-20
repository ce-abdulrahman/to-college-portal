<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Teacher;
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
            'role' => ['required', Rule::in(['center', 'teacher', 'student'])],

            'center_province' => [
                Rule::requiredIf(fn() => $request->input('role') === 'center'),
                'nullable',
                'string',
                'max:255',
                Rule::exists('provinces', 'name')->where('status', 1),
            ],
            'center_address' => ['nullable', 'string', 'max:3000'],
            'center_description' => ['nullable', 'string', 'max:5000'],

            'teacher_province' => [
                Rule::requiredIf(fn() => $request->input('role') === 'teacher'),
                'nullable',
                'string',
                'max:255',
                Rule::exists('provinces', 'name')->where('status', 1),
            ],

            'student_mark' => [Rule::requiredIf(fn() => $request->input('role') === 'student'), 'nullable', 'numeric', 'min:0', 'max:100'],
            'student_province' => [
                Rule::requiredIf(fn() => $request->input('role') === 'student'),
                'nullable',
                'string',
                'max:255',
                Rule::exists('provinces', 'name')->where('status', 1),
            ],
            'student_type' => [Rule::requiredIf(fn() => $request->input('role') === 'student'), 'nullable', Rule::in(['زانستی', 'وێژەیی'])],
            'student_gender' => [Rule::requiredIf(fn() => $request->input('role') === 'student'), 'nullable', Rule::in(['نێر', 'مێ'])],
            'student_year' => [Rule::requiredIf(fn() => $request->input('role') === 'student'), 'nullable', 'integer', 'min:1'],
            'referral_code' => [
                'nullable',
                'integer',
            ],
        ]);

        $submittedReferralCode = isset($data['referral_code'])
            ? (string) ((int) $data['referral_code'])
            : null;

        $referrer = $this->resolveReferrerForRole($data['role'], $submittedReferralCode);

        $normalizedStudentYear = isset($data['student_year'])
            ? ((int) $data['student_year'] > 1 ? 2 : 1)
            : null;

        if (!$referrer) {
            return back()
                ->withErrors(['referral_code' => 'هیچ ئەدمینێک نەدۆزرایەوە بۆ تۆمارکردن.'])
                ->withInput();
        }

        DB::transaction(function () use ($data, $referrer, $normalizedStudentYear) {
            $user = User::create([
                'name' => $data['name'],
                'code' => $data['code'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'rand_code' => $this->generateRandCode(),
                'role' => $data['role'],
                'status' => 0,
            ]);

            if ($data['role'] === 'center') {
                Center::create([
                    'user_id' => $user->id,
                    'address' => $data['center_address'] ?? null,
                    'province' => $data['center_province'],
                    'description' => $data['center_description'] ?? null,
                    'ai_rank' => 0,
                    'gis' => 0,
                    'all_departments' => 0,
                    'queue_hand_department' => 0,
                    'limit_teacher' => 0,
                    'limit_student' => 0,
                    'referral_code' => (string) $referrer->rand_code,
                ]);
            } elseif ($data['role'] === 'teacher') {
                Teacher::create([
                    'user_id' => $user->id,
                    'referral_code' => (string) $referrer->rand_code,
                    'province' => $data['teacher_province'],
                    'ai_rank' => 0,
                    'gis' => 0,
                    'all_departments' => 0,
                    'queue_hand_department' => 0,
                    'limit_student' => 0,
                ]);
            } else {
                Student::create([
                    'user_id' => $user->id,
                    'mark' => $data['student_mark'],
                    'province' => $data['student_province'],
                    'type' => $data['student_type'],
                    'gender' => $data['student_gender'],
                    'year' => $normalizedStudentYear,
                    'referral_code' => (string) $referrer->rand_code,
                    'status' => 0,
                    'ai_rank' => 0,
                    'gis' => 0,
                    'all_departments' => 0,
                ]);
            }
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
            'role' => ['nullable', Rule::in(['center', 'teacher', 'student'])],
        ]);

        $code = isset($data['code']) ? trim((string) $data['code']) : null;
        $role = $data['role'] ?? 'student';
        $referrer = $this->resolveReferrerForRoleLookup($role, $code);

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

    private function resolveAdminReferrer(?string $code): ?User
    {
        if (!$code || !is_numeric($code)) {
            return null;
        }

        return User::query()
            ->select('id', 'name', 'phone', 'role', 'rand_code')
            ->where('rand_code', (int) $code)
            ->where('role', 'admin')
            ->first();
    }

    private function resolveAdminOrCenterReferrer(?string $code): ?User
    {
        if (!$code || !is_numeric($code)) {
            return null;
        }

        return User::query()
            ->select('id', 'name', 'phone', 'role', 'rand_code')
            ->where('rand_code', (int) $code)
            ->whereIn('role', ['admin', 'center'])
            ->first();
    }

    private function resolveReferrerForRole(string $role, ?string $code): ?User
    {
        return match ($role) {
            'center' => $this->resolveAdminReferrer($code) ?? $this->resolvePrimaryAdminReferrer(),
            'teacher' => $this->resolveAdminOrCenterReferrer($code) ?? $this->resolvePrimaryAdminReferrer(),
            'student' => $this->resolveReferrerUser($code) ?? $this->resolvePrimaryAdminReferrer(),
            default => $this->resolvePrimaryAdminReferrer(),
        };
    }

    private function resolveReferrerForRoleLookup(string $role, ?string $code): ?User
    {
        return match ($role) {
            'center' => $this->resolveAdminReferrer($code),
            'teacher' => $this->resolveAdminOrCenterReferrer($code),
            'student' => $this->resolveReferrerUser($code),
            default => $this->resolveReferrerUser($code),
        };
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
            'student' => 'قوتابی',
            default => $role,
        };
    }
}
