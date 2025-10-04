<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;
use App\Models\Department;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $universities = University::with('province')->get();
        return view('website.web.admin.university.index', compact('universities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::all();
        return view('website.web.admin.university.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        University::create($request->all());

        return redirect()->route('admin.universities.index')->with('success', 'University created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $university = University::findOrFail($id);
        $colleges = College::where('university_id', $university->id)->get(); // Assuming a university has many colleges
        return view('website.web.admin.university.show', compact('university', 'colleges'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $university = University::findOrFail($id);
        $provinces = Province::where('status', 1)->get();
        return view('website.web.admin.university.edit', compact('university', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $university = University::findOrFail($id);
        $university->update($request->all());

        return redirect()->route('admin.universities.index')->with('success', 'University updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $university = University::findOrFail($id);
        $university->delete();

        return redirect()->route('admin.universities.index')->with('success', 'University deleted successfully.');
    }
}
