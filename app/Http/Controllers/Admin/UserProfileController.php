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
        $users = User::where('role', 'admin')->get();
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
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'code'     => ['required','string','max:255','unique:users,code'],
            'password' => ['required','string','min:8'],
            'role'     => ['required', Rule::in(['admin','student'])],

            // only when role = student
            'mark'     => ['required_if:role,student','numeric'],
            'province' => ['required_if:role,student','string','max:255'],
            'type'     => ['required_if:role,student','string', Rule::in(['زانستی','وێژەیی'])],
            'gender'   => ['required_if:role,student','string', Rule::in(['نێر','مێ'])],
            'year'     => ['required_if:role,student','integer','min:1'],

            // هەڵبژاردنەکان (هاوکار بۆ ڕێزبەندی)
            'zankoline_num' => ['nullable','integer','min:0'],
            'parallel_num'  => ['nullable','integer','min:0'],
            'evening_num'   => ['nullable','integer','min:0'],

            // دکمه‌/چێکبوکس: ئایا ڕێزبەندی بکرێت؟
            'queue' => ['sometimes','string'],
        ]);

        try {
            DB::transaction(function () use ($data, $selector, $request) {
                // 1) دروستکردنی بەکارهێنەر
                $user = User::create([
                    'name'     => $data['name'],
                    'code'     => $data['code'],
                    'password' => Hash::make($data['password']),
                    'role'     => $data['role'],
                ]);

                // 2) ئەگەر student ـە → خەزنکردنی تەنیا خانەکانی students
                if ($data['role'] === 'student') {
                    // ئەگەر پێشووتر create کردووە، create دەکەین؛
                    // ئەگەر دەتەوێت duplicate نەبێت، updateOrCreate بەکاربهێنە.
                    Student::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'mark'     => (float)$data['mark'],
                            'province' => $data['province'],
                            'type'     => $data['type'],    // 'زانستی'/'وێژەیی'
                            'gender'   => $data['gender'],  // 'نێر'/'مێ'
                            'year'     => (int)$data['year'],
                            'status'   => 1,
                        ]
                    );

                    // 3) ئەگەر queue==true → ڕێزبەندی بکە و result_deps پڕ بکە
                    if (($data['queue'] ?? 'no') === 'yes') {
                        $selector->build(
                            $user->id,
                            $data['province'],
                            $data['type'],
                            $data['gender'],
                            (int)$data['year'],
                            (float)$data['mark'],
                            $data['zankoline_num'] ?? null,
                            $data['parallel_num']  ?? null,
                            $data['evening_num']   ?? null,
                        );
                    }
                }
            });
        } catch (InvalidArgumentException $e) {
            // نموونە: کۆی هەڵبژاردنەکان دەبێت 50 بێت
            return back()->withErrors(['choices' => $e->getMessage()])->withInput();
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'هەڵە ڕوویدا: '.$e->getMessage()])->withInput();
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'بەکارهێنەر دروستکرا بەسەرکەوتوویی.');
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
            ]);
            $user->update([
                'name' => $request->name,
                'code' => $request->code,
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
}
