<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Province;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function index()
    {
        $users = User::with(['center', 'teacher', 'student'])
            ->latest('id')
            ->get();

        $stats = [
            'total' => $users->count(),
            'active' => $users->where('status', 1)->count(),
            'inactive' => $users->where('status', 0)->count(),
            'admins' => $users->where('role', 'admin')->count(),
            'centers' => $users->where('role', 'center')->count(),
            'teachers' => $users->where('role', 'teacher')->count(),
            'students' => $users->where('role', 'student')->count(),
            'deleted' => User::onlyTrashed()->count(),
        ];

        return view('website.web.admin.user.index', compact('users', 'stats'));
    }

    public function create()
    {
        $provinces = Province::where('status', 1)->orderBy('name')->get(['name']);

        return view('website.web.admin.user.create', [
            'provinces' => $provinces,
            'user' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request);

        if (($data['role'] ?? null) === 'student' && isset($data['student_year'])) {
            $data['student_year'] = (int) $data['student_year'] > 1 ? 2 : 1;
        }

        try {
            DB::transaction(function () use ($data, $request) {
                $user = User::create([
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'phone' => $data['phone'] ?? null,
                    'password' => Hash::make($data['password']),
                    'rand_code' => (int) $data['rand_code'],
                    'role' => $data['role'],
                    'status' => (int) $data['status'],
                ]);

                $this->syncRoleProfile($user, $data, $request);
            });
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['error' => 'هەڵە ڕوویدا: ' . $e->getMessage()])
                ->withInput();
        }

        return redirect()->route('admin.users.index')->with('success', 'بەکارهێنەر بەسەرکەوتوویی دروستکرا.');
    }

    public function show(string $id)
    {
        $user = User::with(['center', 'teacher', 'student'])->findOrFail($id);

        return view('website.web.admin.user.show', compact('user'));
    }

    public function deleted()
    {
        $users = User::onlyTrashed()
            ->with(['center', 'teacher', 'student'])
            ->orderByDesc('deleted_at')
            ->get();

        $stats = [
            'total' => $users->count(),
            'admins' => $users->where('role', 'admin')->count(),
            'centers' => $users->where('role', 'center')->count(),
            'teachers' => $users->where('role', 'teacher')->count(),
            'students' => $users->where('role', 'student')->count(),
        ];

        return view('website.web.admin.user.deleted', compact('users', 'stats'));
    }

    public function adminReferredUsers()
    {
        $primaryAdmin = User::query()
            ->where('role', 'admin')
            ->orderBy('id')
            ->first();

        if (!$primaryAdmin) {
            return redirect()->route('admin.users.index')
                ->withErrors(['error' => 'هیچ ئەدمینێک نەدۆزرایەوە.']);
        }

        $adminRandCode = (string) ($primaryAdmin->rand_code ?? '0');

        $users = User::query()
            ->with(['center', 'teacher', 'student'])
            ->whereIn('role', ['center', 'teacher', 'student'])
            ->latest('id')
            ->get()
            ->filter(function (User $user) use ($adminRandCode) {
                $referralCode = $this->getReferralCodeByRole($user);

                return $referralCode !== null
                    && ($referralCode === $adminRandCode || $referralCode === '0');
            })
            ->values();

        $stats = [
            'total' => $users->count(),
            'centers' => $users->where('role', 'center')->count(),
            'teachers' => $users->where('role', 'teacher')->count(),
            'students' => $users->where('role', 'student')->count(),
            'active' => $users->where('status', 1)->count(),
            'inactive' => $users->where('status', 0)->count(),
        ];

        return view('website.web.admin.user.admin-referrals', compact(
            'users',
            'stats',
            'primaryAdmin',
            'adminRandCode',
        ));
    }

    public function edit(string $id)
    {
        $user = User::with(['center', 'teacher', 'student'])->findOrFail($id);
        $provinces = Province::where('status', 1)->orderBy('name')->get(['name']);

        return view('website.web.admin.user.edit', compact('user', 'provinces'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::with(['center', 'teacher', 'student'])->findOrFail($id);
        $data = $this->validatePayload($request, $user);
        if (($data['role'] ?? null) === 'student' && isset($data['student_year'])) {
            $data['student_year'] = (int) $data['student_year'] > 1 ? 2 : 1;
        }
        $oldRandCode = (string) ($user->rand_code ?? '');
        $wasAdmin = $user->role === 'admin';

        try {
            DB::transaction(function () use ($user, $data, $request, $oldRandCode, $wasAdmin) {
                $user->update([
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'phone' => $data['phone'] ?? null,
                    'rand_code' => (int) $data['rand_code'],
                    'role' => $data['role'],
                    'status' => (int) $data['status'],
                ]);

                if (!empty($data['password'])) {
                    $user->password = Hash::make($data['password']);
                    $user->save();
                }

                $this->syncRoleProfile($user, $data, $request);

                if ($wasAdmin && $user->role === 'admin') {
                    $newRandCode = (string) ($user->rand_code ?? '');
                    if ($newRandCode !== '' && $newRandCode !== $oldRandCode) {
                        $this->syncAdminReferralCodes($user, $oldRandCode, $newRandCode);
                    }
                }
            });
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['error' => 'هەڵە ڕوویدا: ' . $e->getMessage()])
                ->withInput();
        }

        return redirect()->route('admin.users.edit', $user->id)->with('success', 'گۆڕانکارییەکان بەسەرکەوتوویی پاشەکەوتکران.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ((int) auth()->id() === (int) $user->id) {
            return back()->withErrors(['error' => 'ناتوانیت خۆت بسڕیتەوە.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'بەکارهێنەر سڕایەوە.');
    }

    public function restore(string $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if (!$user->trashed()) {
            return back()->withErrors(['error' => 'ئەم بەکارهێنەرە سڕاوە نییە.']);
        }

        $user->restore();

        return redirect()->route('admin.users.deleted')
            ->with('success', 'بەکارهێنەر بەسەرکەوتوویی گەڕێندرایەوە.');
    }

    public function forceDestroy(string $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ((int) auth()->id() === (int) $user->id) {
            return back()->withErrors(['error' => 'ناتوانیت خۆت بسڕیتەوە بە تەواوی.']);
        }

        $user->forceDelete();

        return redirect()->route('admin.users.deleted')
            ->with('success', 'بەکارهێنەر بە تەواوی سڕایەوە.');
    }

    public function searchByCode(Request $request)
    {
        $q = $request->get('q', '');

        $users = User::query()
            ->select('id', 'name', 'code', 'role', 'rand_code')
            ->when(
                $q,
                fn($qr) => $qr->where(function ($w) use ($q) {
                    $w->where('code', 'like', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%")
                        ->orWhere('rand_code', 'like', "%{$q}%");
                }),
            )
            ->limit(20)
            ->get();

        return response()->json([
            'results' => $users->map(
                fn($u) => [
                    'id' => (string) $u->rand_code,
                    'text' => (string) $u->rand_code,
                ],
            ),
        ]);
    }

    public function activate(User $user)
    {
        DB::transaction(function () use ($user) {
            if ((int) $user->status !== 1) {
                $user->update(['status' => 1]);
            }

            if ($user->role === 'student') {
                Student::where('user_id', $user->id)->update(['status' => 1]);
            }
        });

        return back()->with('success', 'بەکارهێنەر بە سەرکەوتوویی چاڵاک کرا.');
    }

    private function validatePayload(Request $request, ?User $user = null): array
    {
        $userId = $user?->id;
        $isUpdate = $user !== null;

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', Rule::unique('users', 'code')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:11'],
            'rand_code' => ['required', 'integer', Rule::unique('users', 'rand_code')->ignore($userId)],
            'role' => ['required', Rule::in(['admin', 'center', 'teacher', 'student'])],
            'status' => ['required', 'in:0,1'],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],

            'center_province' => [
                Rule::requiredIf(fn() => $request->input('role') === 'center'),
                'nullable',
                'string',
                Rule::exists('provinces', 'name'),
            ],
            'center_referral_code' => ['nullable', 'string', 'max:255'],
            'center_address' => ['nullable', 'string', 'max:3000'],
            'center_description' => ['nullable', 'string', 'max:5000'],
            'center_limit_teacher' => ['nullable', 'integer', 'min:0'],
            'center_limit_student' => ['nullable', 'integer', 'min:0'],
            'center_ai_rank' => ['nullable', 'boolean'],
            'center_gis' => ['nullable', 'boolean'],
            'center_all_departments' => ['nullable', 'boolean'],
            'center_queue_hand_department' => ['nullable', 'boolean'],

            'teacher_province' => [
                Rule::requiredIf(fn() => $request->input('role') === 'teacher'),
                'nullable',
                'string',
                Rule::exists('provinces', 'name'),
            ],
            'teacher_referral_code' => ['nullable', 'string', 'max:255'],
            'teacher_limit_student' => ['nullable', 'integer', 'min:0'],
            'teacher_ai_rank' => ['nullable', 'boolean'],
            'teacher_gis' => ['nullable', 'boolean'],
            'teacher_all_departments' => ['nullable', 'boolean'],
            'teacher_queue_hand_department' => ['nullable', 'boolean'],

            'student_mark' => [Rule::requiredIf(fn() => $request->input('role') === 'student'), 'nullable', 'numeric', 'min:0', 'max:100'],
            'student_province' => [
                Rule::requiredIf(fn() => $request->input('role') === 'student'),
                'nullable',
                'string',
                Rule::exists('provinces', 'name'),
            ],
            'student_type' => [Rule::requiredIf(fn() => $request->input('role') === 'student'), 'nullable', Rule::in(['زانستی', 'وێژەیی'])],
            'student_gender' => [Rule::requiredIf(fn() => $request->input('role') === 'student'), 'nullable', Rule::in(['نێر', 'مێ'])],
            'student_year' => [Rule::requiredIf(fn() => $request->input('role') === 'student'), 'nullable', 'integer', 'min:1'],
            'student_referral_code' => ['nullable', 'string', 'max:255'],
            'student_status' => [Rule::requiredIf(fn() => $request->input('role') === 'student'), 'nullable', 'in:0,1'],
            'student_mbti_type' => ['nullable', 'string', 'max:4'],
            'student_ai_rank' => ['nullable', 'boolean'],
            'student_gis' => ['nullable', 'boolean'],
            'student_all_departments' => ['nullable', 'boolean'],
            'student_lat' => [
                Rule::requiredIf(fn() => $request->input('role') === 'student' && $request->boolean('student_ai_rank')),
                'nullable',
                'numeric',
                'between:-90,90',
            ],
            'student_lng' => [
                Rule::requiredIf(fn() => $request->input('role') === 'student' && $request->boolean('student_ai_rank')),
                'nullable',
                'numeric',
                'between:-180,180',
            ],
        ]);
    }

    private function syncRoleProfile(User $user, array $data, Request $request): void
    {
        $role = $data['role'];
        $defaultReferralCode = auth()->user()->rand_code ?? null;

        $this->purgeOtherProfiles($user, $role);

        if ($role === 'center') {
            $user->center()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'province' => $data['center_province'],
                    'referral_code' => $data['center_referral_code'] ?: $defaultReferralCode,
                    'address' => $data['center_address'] ?? null,
                    'description' => $data['center_description'] ?? null,
                    'limit_teacher' => (int) ($data['center_limit_teacher'] ?? 0),
                    'limit_student' => (int) ($data['center_limit_student'] ?? 0),
                    'ai_rank' => $request->boolean('center_ai_rank'),
                    'gis' => $request->boolean('center_gis'),
                    'all_departments' => $request->boolean('center_all_departments'),
                    'queue_hand_department' => $request->boolean('center_queue_hand_department'),
                ],
            );

            return;
        }

        if ($role === 'teacher') {
            $user->teacher()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'province' => $data['teacher_province'],
                    'referral_code' => $data['teacher_referral_code'] ?: $defaultReferralCode,
                    'limit_student' => (int) ($data['teacher_limit_student'] ?? 0),
                    'ai_rank' => $request->boolean('teacher_ai_rank'),
                    'gis' => $request->boolean('teacher_gis'),
                    'all_departments' => $request->boolean('teacher_all_departments'),
                    'queue_hand_department' => $request->boolean('teacher_queue_hand_department'),
                ],
            );

            return;
        }

        if ($role === 'student') {
            $studentAiRank = $request->boolean('student_ai_rank');

            $user->student()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'mark' => (float) $data['student_mark'],
                    'province' => $data['student_province'],
                    'type' => $data['student_type'],
                    'gender' => $data['student_gender'],
                    'year' => (int) $data['student_year'],
                    'referral_code' => $data['student_referral_code'] ?: $defaultReferralCode,
                    'status' => (int) ($data['student_status'] ?? $data['status']),
                    'mbti_type' => $data['student_mbti_type'] ?? null,
                    'lat' => $studentAiRank ? ($data['student_lat'] ?? null) : null,
                    'lng' => $studentAiRank ? ($data['student_lng'] ?? null) : null,
                    'ai_rank' => $studentAiRank,
                    'gis' => $request->boolean('student_gis'),
                    'all_departments' => $request->boolean('student_all_departments'),
                ],
            );
        }
    }

    private function purgeOtherProfiles(User $user, string $keepRole): void
    {
        if ($keepRole !== 'center') {
            Center::where('user_id', $user->id)->delete();
        }

        if ($keepRole !== 'teacher') {
            Teacher::where('user_id', $user->id)->delete();
        }

        if ($keepRole !== 'student') {
            Student::where('user_id', $user->id)->delete();
        }
    }

    private function getReferralCodeByRole(User $user): ?string
    {
        if ($user->role === 'center') {
            return isset($user->center) ? (string) ($user->center->referral_code ?? '') : null;
        }

        if ($user->role === 'teacher') {
            return isset($user->teacher) ? (string) ($user->teacher->referral_code ?? '') : null;
        }

        if ($user->role === 'student') {
            return isset($user->student) ? (string) ($user->student->referral_code ?? '') : null;
        }

        return null;
    }

    private function syncAdminReferralCodes(User $adminUser, string $oldRandCode, string $newRandCode): void
    {
        if ($oldRandCode === '' || $newRandCode === '' || $oldRandCode === $newRandCode) {
            return;
        }

        $primaryAdminId = (int) User::query()
            ->where('role', 'admin')
            ->orderBy('id')
            ->value('id');

        $targetCodes = [$oldRandCode];
        if ((int) $adminUser->id === $primaryAdminId) {
            $targetCodes[] = '0';
        }

        $targetCodes = array_values(array_unique(array_map('strval', $targetCodes)));

        Center::query()
            ->whereIn('referral_code', $targetCodes)
            ->update(['referral_code' => $newRandCode]);

        Teacher::query()
            ->whereIn('referral_code', $targetCodes)
            ->update(['referral_code' => $newRandCode]);

        Student::query()
            ->whereIn('referral_code', $targetCodes)
            ->update(['referral_code' => $newRandCode]);
    }
}
