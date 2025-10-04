<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\University;
use App\Models\College;
use App\Models\Department;

class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colleges = College::with('university')->get();
        return view('website.web.admin.college.index', compact('colleges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $universities = University::where('status', 1)->get();
        return view('website.web.admin.college.create', compact('universities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        College::create($request->all());

        return redirect()->route('admin.colleges.index')->with('success', 'College created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $college = College::findOrFail($id);
        $departments = Department::where('college_id', $college->id)->get(); // Assuming a college has many departments
        return view('website.web.admin.college.show', compact('college', 'departments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $college = College::findOrFail($id);
        $universities = University::where('status', 1)->get();
        return view('website.web.admin.college.edit', compact('college', 'universities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $college = College::findOrFail($id);
        $college->update($request->all());

        return redirect()->route('admin.colleges.index')->with('success', 'College updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $college = College::findOrFail($id);
        $college->delete();

        return redirect()->route('admin.colleges.index')->with('success', 'College deleted successfully.');
    }
}
