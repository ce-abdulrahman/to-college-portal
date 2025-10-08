<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\University;
use App\Http\Controllers\Concerns\HandlesGeo;

class ProvinceController extends Controller
{
    use HandlesGeo;

    /**
     * Display a listing of the resource.
     */
    public function index(Province $province)
    {
        $provinces = Province::all();
        $province = Province::findOrFail($province);
        return view('website.web.admin.province.index', compact('provinces', 'province'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('website.web.admin.province.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:255','unique:provinces,name'],
            'status'       => ['required','boolean'],
            'geojson_text' => ['nullable','string'],
            'geojson_file' => ['nullable','file','mimes:json,geojson,txt','max:20480'],
        ]);

        $payload = [
            'name'   => $data['name'],
            'status' => (bool)$data['status'],
        ];

        if (!empty($data['geojson_text']) || $request->hasFile('geojson_file')) {
            $payload['geojson'] = $this->resolveGeojsonInput($data['geojson_text'] ?? null, $request->file('geojson_file'));
        }

        Province::create($payload);

        return redirect()->route('admin.provinces.index')->with('success', 'پاریزگا بە سەرکەوتووی دروستکرا.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $province = Province::findOrFail($id);
        $universities = University::where('province_id', $province->id)->get(); // Assuming a Province has many Universities
        return view('website.web.admin.province.show', compact('province', 'universities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $province = Province::findOrFail($id);
        return view('website.web.admin.province.edit', compact('province'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $province = Province::findOrFail($id);

        $data = $request->validate([
            'name'         => ['required','string','max:255','unique:provinces,name,'.$province->id],
            'status'       => ['required','boolean'],
            'geojson_text' => ['nullable','string'],
            'geojson_file' => ['nullable','file','mimes:json,geojson,txt','max:20480'],
        ]);

        $payload = [
            'name'   => $data['name'],
            'status' => (bool)$data['status'],
        ];

        if (!empty($data['geojson_text']) || $request->hasFile('geojson_file')) {
            $payload['geojson'] = $this->resolveGeojsonInput($data['geojson_text'] ?? null, $request->file('geojson_file'));
        }

        $province->update($payload);

        return redirect()->route('admin.provinces.index')->with('success', 'پاریزگا بە سەرکەوتووی نوێ کراوە.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
