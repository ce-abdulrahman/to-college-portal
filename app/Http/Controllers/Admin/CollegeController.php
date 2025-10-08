<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\University;
use App\Models\College;
use App\Models\Department;
use App\Models\Province;
use App\Http\Controllers\Concerns\HandlesGeo;

class CollegeController extends Controller
{
    use HandlesGeo;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colleges = College::with('university')->get();
        $provinces = Province::where('status', 1)->get();
        return view('website.web.admin.college.index', compact('colleges','provinces'));
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
        $data = $request->validate([
            'name'          => ['required','string','max:255','unique:colleges,name'],
            'university_id' => ['required','exists:universities,id'],
            'status'        => ['required','boolean'],

            'geojson_text'  => ['nullable','string'],
            'geojson_file'  => ['nullable','file','mimes:json,geojson,txt','max:20480'],
            'lat'           => ['nullable','numeric','between:-90,90'],
            'lng'           => ['nullable','numeric','between:-180,180'],
        ]);

        $payload = [
            'name'          => $data['name'],
            'university_id' => (int)$data['university_id'],
            'status'        => (bool)$data['status'],
        ];

        if (!empty($data['geojson_text']) || $request->hasFile('geojson_file')) {
            $payload['geojson'] = $this->resolveGeojsonInput($data['geojson_text'] ?? null, $request->file('geojson_file'));
        }
        if ($request->filled('lat') && $request->filled('lng')) {
            $payload['lat'] = (float)$data['lat'];
            $payload['lng'] = (float)$data['lng'];
        }

        College::create($payload);

        return redirect()->route('admin.colleges.index')->with('success', 'کۆلێژ یان پەیمانگا بە سەرکەوتوویی زیادکرا.');
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
        $data = $request->validate([
            'name'          => ['required','string','max:255','unique:colleges,name,'.$college->id],
            'university_id' => ['required','exists:universities,id'],
            'status'        => ['required','boolean'],

            'geojson_text'  => ['nullable','string'],
            'geojson_file'  => ['nullable','file','mimes:json,geojson,txt','max:20480'],
            'lat'           => ['nullable','numeric','between:-90,90'],
            'lng'           => ['nullable','numeric','between:-180,180'],
        ]);

        $payload = [
            'name'          => $data['name'],
            'university_id' => (int)$data['university_id'],
            'status'        => (bool)$data['status'],
        ];

        if (!empty($data['geojson_text']) || $request->hasFile('geojson_file')) {
            $payload['geojson'] = $this->resolveGeojsonInput($data['geojson_text'] ?? null, $request->file('geojson_file'));
        }
        if ($request->filled('lat') && $request->filled('lng')) {
            $payload['lat'] = (float)$data['lat'];
            $payload['lng'] = (float)$data['lng'];
        }

        $college->update($payload);

        return redirect()->route('admin.colleges.index')->with('success', 'کۆلێژ یان پەیمانگا بە سەرکەوتوویی نوێ کراوە.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $college = College::findOrFail($id);
        $college->delete();

        return redirect()->route('admin.colleges.index')->with('success', 'کۆلێژ یان پەیمانگا بە سەرکەوتوویی سڕیاوە.');
    }
}
