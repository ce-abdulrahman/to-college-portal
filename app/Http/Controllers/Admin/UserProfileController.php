<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Province;
use App\Models\Student;
use App\Services\DepartmentSelector;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $provinces = Province::all();
        return view('website.web.admin.user.index', compact('users', 'provinces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::all();
        return view('website.web.admin.user.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, DepartmentSelector $selector)
    {
        // 1) ڤالیدەیشنی بنەڕەتی
        $base = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:users,code'],
            'phone' => ['nullable', 'string', 'max:11'],
            'password' => ['required', 'string', 'min:8'],
            'rand_code' => ['required', 'integer', 'unique:users,rand_code'],
            'role' => ['required', Rule::in(['admin', 'center', 'teacher', 'student'])],
            // هەرکە لە فۆرمەکەت هەیە، ڕاگرتووە؛ دەتوانیت دابنێیت:
            'status' => ['required', 'in:1,0'],
        ]);

        $student = [];
        if ($request->role === 'student') {
            // 2) ڤالیدەیشنی تایبەتی قوتابی
            $student = $request->validate([
                'mark' => ['required', 'numeric'],
                'province' => ['required', 'string', 'max:255'],
                'type' => ['required', 'string', Rule::in(['زانستی', 'وێژەیی'])],
                'gender' => ['required', 'string', Rule::in(['نێر', 'مێ'])],
                'year' => ['required', 'integer', 'min:1'],
                'referral_code' => ['nullable', 'string', 'max:255'],

                // تەنیا کاتێک queue = yes → پێویست، هەروەها بە numeric/min:0
                'queue' => ['nullable', 'in:yes,no'],
                'zankoline_num' => ['required_if:queue,yes', 'nullable', 'numeric', 'min:0'],
                'parallel_num' => ['required_if:queue,yes', 'nullable', 'numeric', 'min:0'],
                'evening_num' => ['required_if:queue,yes', 'nullable', 'numeric', 'min:0'],
            ]);
        }

        // هەردوو set ـەکە پێکبخە
        $data = array_merge($base, $student);

        try {
            DB::transaction(function () use ($data, $selector, $request) {
                // 3) دروستکردنی بەکارهێنەر
                $user = User::create([
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'password' => Hash::make($data['password']),
                    'role' => $data['role'],
                    'status' => (int) ($data['status'] ?? 1),
                    'phone' => $data['phone'] ?? null,
                    'rand_code' => (int) $data['rand_code'],
                ]);

                // Defaul values for flags
                $ai_rank = $request->has('ai_rank') ? 1 : 0;
                $gis = $request->has('gis') ? 1 : 0;
                $all_departments = $request->has('all_departments') ? 1 : 0;
                $creator_code = auth()->user()->rand_code ?? null;

                // Handle Role Specific Tables
                if ($data['role'] === 'center') {
                    \App\Models\Center::create([
                        'user_id' => $user->id,
                        'address' => $request->address,
                        'description' => $request->description,
                        'ai_rank' => $ai_rank,
                        'gis' => $gis,
                        'all_departments' => $all_departments,
                        'referral_code' => $creator_code,
                    ]);
                } elseif ($data['role'] === 'teacher') {
                    \App\Models\Teacher::create([
                        'user_id' => $user->id,
                        'ai_rank' => $ai_rank,
                        'gis' => $gis,
                        'all_departments' => $all_departments,
                        'referral_code' => $creator_code,
                    ]);
                } elseif ($data['role'] === 'student') {
                    Student::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'mark' => (float) $data['mark'],
                            'province' => $data['province'],
                            'type' => $data['type'],
                            'gender' => $data['gender'],
                            'year' => (int) $data['year'],
                            'referral_code' => $data['referral_code'] ?? $creator_code,
                            'status' => 1,
                            // Admin likely sets these for students too if they exist on student table
                            'ai_rank' => $ai_rank,
                            'gis' => $gis,
                            'all_departments' => $all_departments,
                        ],
                    );

                    // 5) ئەگەر queue=yes → ڕێزبەندی
                    if (($data['queue'] ?? 'no') === 'yes') {
                        $selector->build($user->id, $data['province'], $data['type'], $data['gender'], (int) $data['year'], (float) $data['mark'], $data['zankoline_num'] ?? null, $data['parallel_num'] ?? null, $data['evening_num'] ?? null);
                    }
                }
            });
        } catch (InvalidArgumentException $e) {
            return back()
                ->withErrors(['choices' => $e->getMessage()])
                ->withInput();
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['error' => 'هەڵە ڕوویدا: ' . $e->getMessage()])
                ->withInput();
        }

        return redirect()->route('admin.users.index')->with('success', 'بەکارهێنەر دروستکرا بەسەرکەوتوویی.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrfail($id);
        return view('website.web.admin.user.edit', compact('user'));
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
                'role' => 'required',
            ]);
            $user->update([
                'name' => $request->name,
                'code' => $request->code,
                'phone' => $request->phone,
                'role' => $request->role,
            ]);
        }
        return redirect()->route('admin.users.index')->with('success', 'ئەدمینی نوێکردنەوە بەسەرکەوتوویی تەواو بوو.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrfail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'بەکارهێنەر سڕایەوە بەسەرکەوتوویی.');
    }

    // GET /sadm/users/search-by-code?q=...
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

        // فۆرماتێکی Select2: { results: [{id,text},...] }
        return response()->json([
            'results' => $users->map(
                fn($u) => [
                    // تۆ وتویت “تەنها rand_code بێت” → id/text هەردووکیان rand_code
                    'id' => (string) $u->rand_code,
                    'text' => (string) $u->rand_code,
                ],
            ),
        ]);
    }
}
