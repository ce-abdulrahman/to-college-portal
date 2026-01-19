<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System;
use App\Models\Department;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $systems = System::all();
        return view('website.web.admin.system.index', compact('systems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('website.web.admin.system.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        System::create($validated);

        return redirect()->route('admin.systems.index')->with('success', 'سیستەم بە سەرکەوتوو زیاد بوو.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $system = System::findOrFail($id);
        $departments = Department::where(['status' => 1, 'system_id' => $system->id])->get();
        return view('website.web.admin.system.show', compact('system', 'departments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $system = System::findOrFail($id);
        return view('website.web.admin.system.edit', compact('system'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $system = System::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);
        $system->update($validated);

        return redirect()->route('admin.systems.index')->with('success', 'سیستەم بە سەرکەوتوو نوێ کرایەوە.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $system = System::findOrFail($id);
        $system->delete();

        return redirect()->route('admin.systems.index')->with('success', 'سیستەم بە سەرکەوتوو سڕایەوە.');
    }
}
