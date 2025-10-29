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
use App\Traits\FileUploadTrait;

class UniversityController extends Controller
{
    use HandlesGeo, FileUploadTrait;

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
            'name' => ['required', 'string', 'max:255', 'unique:universities,name'],
            'name_en' => ['required', 'string', 'max:255', 'unique:universities,name_en'],
            'province_id' => ['required', 'exists:provinces,id'],
            'status' => ['required', 'boolean'],
            'geojson' => ['nullable', 'string'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'image' => ['nullable', 'file', 'image', 'max:2048'], // optional image upload
        ]);

        $imagePath = $this->UploadImage($request, 'image');

        $payload = [
            'name' => $data['name'],
            'name_en' => $data['name_en'],
            'province_id' => (int) $data['province_id'],
            'status' => (bool) $data['status'],
            'image' => !empty($imagePath) ? $imagePath : null,
        ];

        $geojson = null;

        if (!empty($data['geojson']) || $request->hasFile('geojson')) {
            $payload['geojson'] = $this->resolveGeojsonInput($data['geojson'] ?? null, $request->file('geojson'));
        }
        if ($request->filled('lat') && $request->filled('lng')) {
            $payload['lat'] = (float) $data['lat'];
            $payload['lng'] = (float) $data['lng'];
        }

        if (!is_null($geojson)) {
            $payload['geojson'] = $geojson;
        }

        $university = new University();
        $university->province_id = $payload['province_id'];
        $university->name = $payload['name'];
        $university->name_en = $payload['name_en'];
        $university->image = $payload['image'];
        $university->status = $payload['status'];
        $university->lat = $payload['lat']; 
        $university->lng = $payload['lng'];

        if (!empty($payload['geojson'])) {
            $university->geojson = json_encode($payload['geojson']);
        }

        $university->save();

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
            'name' => ['required', 'string', 'max:255', 'unique:universities,name,' . $university->id],
            'name_en' => ['required', 'string', 'max:255', 'unique:universities,name_en,' . $university->id],
            'province_id' => ['required', 'exists:provinces,id'],
            'status' => ['required', 'in:0,1'], // boolean لە select، 0/1 دێت
            'geojson' => ['nullable', 'string'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        // وێنە
        $imagePath = $this->uploadImage($request, 'image', $university->image); // یەکێک بکە بە uploadImage
        $image = $imagePath ?: $university->image;

        // بنیات نانینی payload سادە
        $payload = [
            'name' => $data['name'],
            'name_en' => $data['name_en'],
            'province_id' => (int) $data['province_id'],
            'status' => (bool) $data['status'], // 0/1 → bool
            'image' => $image,
            'geojson' => $data['geojson'],
        ];

        // هەڵی پاککردنەوەی GeoJSON بە ئاشکرا
        if (!empty($data['clear_geojson'])) {
            $payload['geojson'] = null;
        } else {
            // هەوڵی نوێکردنەوە لە تێکست/فایل
            $geojson = null;

            if (!empty($data['geojson'])) {
                $parsed = json_decode($data['geojson'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $geojson = $parsed;
                } else {
                    return back()
                        ->withErrors(['geojson' => 'GeoJSON دروست نییە.'])
                        ->withInput();
                }
            }

            // تەنها کاتێ نوێیەک هەیە بنووسە؛ وەڵامی دیارناکراو = پاراستنی کۆن
            if (!is_null($geojson)) {
                $payload['geojson'] = $geojson; // casts=['geojson'=>'array']
            }
        }

        // lat/lng: بەتاڵ نەبوون
        if ($request->filled('lat') && $request->filled('lng')) {
            $payload['lat'] = (float) $data['lat'];
            $payload['lng'] = (float) $data['lng'];
        }

        $university->name = $payload['name'];
        $university->province_id = $payload['province_id'];
        $university->name_en = $payload['name_en'];
        $university->image = $payload['image'];
        $university->lat = $payload['lat'];
        $university->lng = $payload['lng'];

        if (!empty($payload['geojson'])) {
            $university->geojson = json_encode($payload['geojson']);
        }

        $university->status = (bool) $data['status'];

        $university->save();

        return redirect()->route('admin.universities.index')->with('success', 'زانکۆ بە سەرکەوتوویی نوێ کرایەوە.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $university = University::findOrFail($id);

        if (!empty($university->geojson_path)) {
            Storage::disk('public')->delete($university->geojson_path);
        }

        if (!empty($university->image)) {
            $this->DeleteImage($university->image);
        }
        $university->delete();

        return redirect()->route('admin.universities.index')->with('success', 'زانکۆ بە سەرکەوتوویی سڕایەوە.');
    }
}
