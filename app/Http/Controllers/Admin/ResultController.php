<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResultDep;
use App\Models\Student;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ResultDep::with([
            'student.user',
            'department.system',
            'department.province',
            'department.university',
            'department.college',
        ]);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.user', function ($uq) use ($search) {
                    $uq->where('name', 'like', '%' . $search . '%')
                       ->orWhere('code', 'like', '%' . $search . '%');
                })
                ->orWhereHas('department', function ($dq) use ($search) {
                    $dq->where('name', 'like', '%' . $search . '%')
                       ->orWhereHas('system', fn($sq) => $sq->where('name', 'like', '%' . $search . '%'))
                       ->orWhereHas('province', fn($pq) => $pq->where('name', 'like', '%' . $search . '%'))
                       ->orWhereHas('university', fn($uq) => $uq->where('name', 'like', '%' . $search . '%'))
                       ->orWhereHas('college', fn($cq) => $cq->where('name', 'like', '%' . $search . '%'));
                });
            });
        }

        $results = $query->orderByDesc('id')->get();

        $students = Student::with('user')
            ->whereHas('user', function ($q) {
                $q->where('role', 'student')->where('status', 1);
            })
            ->orderBy('id')
            ->get();

        return view('website.web.admin.result.index', compact('results', 'students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = ResultDep::with([
            'student.user',
            'department.system',
            'department.province',
            'department.university',
            'department.college',
        ])->findOrFail($id);

        return view('website.web.admin.result.show', compact('result'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = ResultDep::findOrFail($id);
        $result->delete();

        return redirect()
            ->route('admin.results.index')
            ->with('success', 'هەڵبژاردنەکە بە سەرکەوتوویی سڕایەوە.');
    }
}
