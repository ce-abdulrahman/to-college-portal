<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\University;
use Illuminate\Support\Facades\Storage;
use App\Traits\FileUploadTrait;

class ProvinceController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $provinces = Province::latest()->paginate(15);
        return view('website.web.admin.province.index', compact('provinces'));
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
            'name' => ['required', 'string', 'max:255', 'unique:provinces,name'],
            'name_en' => ['required', 'string', 'max:255', 'unique:provinces,name_en'],
            'status' => ['required', 'in:0,1'], // یان ['required','boolean']
            'geojson_text' => ['nullable', 'string'],
            'geojson_file' => ['nullable', 'file', 'mimes:json,geojson,txt', 'max:20480'],
            'image' => ['nullable', 'file', 'image', 'max:2048'], // optional image upload
        ]);

        $imagePath = $this->UploadImage($request, 'image');

        $payload = [
            'name' => $data['name'],
            'name_en' => $data['name_en'],
            'status' => (bool) $data['status'],
            'image' => !empty($imagePath) ? $imagePath : null,
        ];

        // Resolve GeoJSON from text or uploaded file
        $geojson = null;
        if (!empty($data['geojson_text'])) {
            $geojson = json_decode($data['geojson_text'], true);
        }
        if ($request->hasFile('geojson_file')) {
            $path = $request->file('geojson_file')->store('geojson/provinces', 'public');
            $payload['geojson_path'] = $path;

            $fileJson = json_decode($request->file('geojson_file')->get(), true);
            if ($fileJson) {
                $geojson = $fileJson;
            }
        }
        if (!is_null($geojson)) {
            $payload['geojson'] = $geojson;
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
        $universities = University::all();
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
            'name' => ['required', 'string', 'max:255', 'unique:provinces,name,' . $province->id],
            'name_en' => ['required', 'string', 'max:255', 'unique:provinces,name_en,' . $province->id],
            'status' => ['required', 'in:0,1'], // یان ['required','boolean']
            'geojson_text' => ['nullable', 'string'],
            'geojson_file' => ['nullable', 'file', 'mimes:json,geojson,txt', 'max:20480'],
            'image' => ['nullable', 'file', 'image', 'max:2048'], // optional image upload
        ]);

        $imagePath = $this->uploadImage($request, 'image', $province->image);
        $data['image'] = !empty($imagePath) ? $imagePath : $province->image;

        $payload = [
            'name' => $data['name'],
            'name_en' => $data['name_en'],
            'status' => (bool) $data['status'],
        ];

        // Refresh GeoJSON only if new text/file provided
        if (!empty($data['geojson_text'])) {
            $payload['geojson'] = json_decode($data['geojson_text'], true);
        }

        if ($request->hasFile('geojson_file')) {
            // optionally delete old file
            if (!empty($province->geojson_path)) {
                Storage::disk('public')->delete($province->geojson_path);
            }

            $path = $request->file('geojson_file')->store('geojson/provinces', 'public');
            $payload['geojson_path'] = $path;

            $fileJson = json_decode($request->file('geojson_file')->get(), true);
            if ($fileJson) {
                $payload['geojson'] = $fileJson;
            }
        }

        $province->update($payload);

        return redirect()->route('admin.provinces.index')->with('success', 'پاریزگا بە سەرکەوتووی نوێکراوە.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $province = Province::findOrFail($id);

        if (!empty($province->geojson_path)) {
            Storage::disk('public')->delete($province->geojson_path);
        }

        $this->DeleteImage($province->image);

        $province->delete();

        return redirect()->route('admin.provinces.index')->with('success', 'پاریزگا سڕایەوە.');
    }
}
