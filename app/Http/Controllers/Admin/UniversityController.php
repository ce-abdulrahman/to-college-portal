<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;
use App\Models\Department;
use App\Http\Controllers\Concerns\HandlesGeo;

class UniversityController extends Controller
{
    use HandlesGeo;

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
        $data = $request->validate([
            'name'         => ['required','string','max:255','unique:universities,name'],
            'province_id'  => ['required','exists:provinces,id'],
            'status'       => ['required','boolean'],

            'geojson_text' => ['nullable','string'],
            'geojson_file' => ['nullable','file','mimes:json,geojson,txt','max:20480'],
            'lat'          => ['nullable','numeric','between:-90,90'],
            'lng'          => ['nullable','numeric','between:-180,180'],
        ]);

        $payload = [
            'name'        => $data['name'],
            'province_id' => (int)$data['province_id'],
            'status'      => (bool)$data['status'],
        ];

        if (!empty($data['geojson_text']) || $request->hasFile('geojson_file')) {
            $payload['geojson'] = $this->resolveGeojsonInput($data['geojson_text'] ?? null, $request->file('geojson_file'));
        }
        if ($request->filled('lat') && $request->filled('lng')) {
            $payload['lat'] = (float)$data['lat'];
            $payload['lng'] = (float)$data['lng'];
        }

        University::create($payload);

        return redirect()->route('admin.universities.index')->with('success', 'زانکۆ بە سەرکەوتووی زیاد کرا.');
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
        $university = University::findOrFail($id);

        $data = $request->validate([
            'name'         => ['required','string','max:255','unique:universities,name,'.$university->id],
            'province_id'  => ['required','exists:provinces,id'],
            'status'       => ['required','boolean'],

            'geojson_text' => ['nullable','string'],
            'geojson_file' => ['nullable','file','mimes:json,geojson,txt','max:20480'],
            'lat'          => ['nullable','numeric','between:-90,90'],
            'lng'          => ['nullable','numeric','between:-180,180'],
        ]);

        $payload = [
            'name'        => $data['name'],
            'province_id' => (int)$data['province_id'],
            'status'      => (bool)$data['status'],
        ];

        if (!empty($data['geojson_text']) || $request->hasFile('geojson_file')) {
            $payload['geojson'] = $this->resolveGeojsonInput($data['geojson_text'] ?? null, $request->file('geojson_file'));
        }
        if ($request->filled('lat') && $request->filled('lng')) {
            $payload['lat'] = (float)$data['lat'];
            $payload['lng'] = (float)$data['lng'];
        }

        $university->update($payload);

        return redirect()->route('admin.universities.index')->with('success', 'زانکۆ بە سەرکەوتوویی نوێ کرایەوە.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $university = University::findOrFail($id);
        $university->delete();

        return redirect()->route('admin.universities.index')->with('success', 'زانکۆ بە سەرکەوتوویی سڕایەوە.');
    }
}
