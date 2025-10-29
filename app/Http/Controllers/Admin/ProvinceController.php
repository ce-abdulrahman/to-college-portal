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
            'status' => ['required', 'in:0,1'], // یان boolean
            'geojson' => ['nullable', 'json'], // ⬅️ گرنگ: json
            'geojson_file' => ['nullable', 'file', 'mimes:json,geojson,txt', 'max:20480'],
            'image' => ['nullable', 'file', 'image', 'max:2048'],
        ]);

        // وێنە
        $imagePath = $this->uploadImage($request, 'image'); // دەکرێت UploadImageش کار بکات، بەڵام ئەم ناوە ئاساییترە

        // بنیات نانینی payload
        $payload = [
            'name' => $data['name'],
            'name_en' => $data['name_en'],
            'status' => (bool) $data['status'],
            'image' => $imagePath ?: null,
        ];

        // خوێندنەوەی GeoJSON لە تێکست یان فایل
        $geojson = null;

        if (!empty($data['geojson'])) {
            $geojson = json_decode($data['geojson'], true); // json rule وائەدەداتەوە
        }

        if ($request->hasFile('geojson_file')) {
            // پاشەکەوتی فایل
            $path = $request->file('geojson_file')->store('geojson/provinces', 'public');
            $payload['geojson_path'] = $path;

            // دڵنیابوون لە JSON بوونی ناوەڕۆک
            $fileJson = json_decode($request->file('geojson_file')->get(), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $geojson = $fileJson;
            } else {
                return back()
                    ->withErrors(['geojson_file' => 'فایڵەکە JSON دروست نییە.'])
                    ->withInput();
            }
        }

        if (!is_null($geojson)) {
            $payload['geojson'] = $geojson;
        }

        $province = new Province();
        $province->name = $payload['name'];
        $province->name_en = $payload['name_en'];
        $province->image = $payload['image'];
        $province->status = $payload['status'];

        if (!empty($payload['geojson'])) {
            $province->geojson = json_encode($payload['geojson']);
        }

        $province->geojson_path = $payload['geojson_path'] ?? null;
        $province->save();

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
            'geojson' => ['nullable', 'string'],
            'geojson_file' => ['nullable', 'file', 'mimes:json,geojson,txt', 'max:20480'],
            'image' => ['nullable', 'file', 'image', 'max:2048'], // optional image upload
        ]);

        $imagePath = $this->uploadImage($request, 'image', $province->image);
        $data['image'] = !empty($imagePath) ? $imagePath : $province->image;

        $payload = [
            'name' => $data['name'],
            'name_en' => $data['name_en'],
            'image' => $data['image'],
            'status' => (bool) $data['status'],
            'geojson' => $data['geojson'],
            'geojson_path' => $province->geojson_path,
        ];

        // Refresh GeoJSON only if new text/file provided
        if (!empty($data['geojson'])) {
            $payload['geojson'] = json_decode($data['geojson'], true);
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

        $province->name = $payload['name'];
        $province->name_en = $payload['name_en'];
        $province->image = $payload['image'];

        if (!empty($payload['geojson'])) {
            $province->geojson = json_encode($payload['geojson']);
        }

        $province->geojson_path = $payload['geojson_path'] ?? $province->geojson_path;
        $province->status = (bool) $data['status'];

        $province->save();

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

        if (!empty($province->image)) {
            $this->DeleteImage($province->image);
        }

        $province->delete();

        return redirect()->route('admin.provinces.index')->with('success', 'پاریزگا سڕایەوە.');
    }
}
