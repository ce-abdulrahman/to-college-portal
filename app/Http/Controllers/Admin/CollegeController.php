<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\University;
use App\Models\College;
use App\Models\Department;
use App\Models\Province;
use App\Http\Controllers\Concerns\HandlesGeo;
use App\Traits\FileUploadTrait;


class CollegeController extends Controller
{
    use HandlesGeo, FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $provinces = Province::where('status', 1)->get();
        $colleges = College::where('status', 1)->get();
        $universities = University::where('status', 1)->get();
        return view('website.web.admin.college.index', compact('colleges', 'provinces', 'universities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::where('status', 1)->get();
        $universities = University::where('status', 1)->get();
        return view('website.web.admin.college.create', compact('universities', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:colleges,name'],
            'name_en' => ['required', 'string', 'max:255', 'unique:colleges,name_en'],
            'university_id' => ['required', 'exists:universities,id'],
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
            'university_id' => (int) $data['university_id'],
            'status' => (bool) $data['status'],
            'image' => !empty($imagePath) ? $imagePath : null,
        ];

        if (!empty($data['geojson_text']) || $request->hasFile('geojson_file')) {
            $payload['geojson'] = $this->resolveGeojsonInput($data['geojson_text'] ?? null, $request->file('geojson_file'));
        }
        if ($request->filled('lat') && $request->filled('lng')) {
            $payload['lat'] = (float) $data['lat'];
            $payload['lng'] = (float) $data['lng'];
        }

        $college = new College();
        $college->university_id = $payload['university_id'];
        $college->name = $payload['name'];
        $college->name_en = $payload['name_en'];
        $college->image = $payload['image'];
        $college->status = $payload['status'];
        $college->lat = $payload['lat'];
        $college->lng = $payload['lng'];

        if (!empty($payload['geojson'])) {
            $college->geojson = json_encode($payload['geojson']);
        }

        $college->save();

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
        $college = College::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:colleges,name,' . $college->id],
            'name_en' => ['required', 'string', 'max:255', 'unique:colleges,name_en,' . $college->id],
            'university_id' => ['required', 'exists:universities,id'],
            'status' => ['required', 'in:0,1'], // select 0/1
            'geojson' => ['nullable', 'string'], // ✅
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'image' => ['nullable', 'image', 'max:2048'],
            'clear_geojson' => ['nullable', 'in:1'], // اختیاری: checkbox بۆ سڕینەوە
        ]);

        // وێنە
        $imagePath = $this->uploadImage($request, 'image', $college->image);
        $image = $imagePath ?: $college->image;

        $payload = [
            'name' => $data['name'],
            'name_en' => $data['name_en'],
            'university_id' => (int) $data['university_id'],
            'status' => (bool) $data['status'], // 0/1 → bool
            'image' => $image,
        ];

        // GeoJSON: سڕینەوە بە ئاشکرا یان نوێکردنەوە کاتێک ئەرکی نوێ هەیە
        if (!empty($data['clear_geojson'])) {
            $payload['geojson'] = null;
        } else {
            if ($request->hasFile('geojson_file') || !empty($data['geojson'])) {
                $gj = $this->resolveGeojsonInput($data['geojson'] ?? null, $request->file('geojson_file'));
                if ($gj === null) {
                    return back()
                        ->withErrors(['geojson' => 'GeoJSON دروست نییە.'])
                        ->withInput();
                }
                $payload['geojson'] = $gj; // ⚠️ ئامادە بۆ casts = array
            }
            // هیچ هاتونەوەی نوێ؟ پاشەکەوت مەکە بۆ geojson → پاراستنی کۆن
        }

        // lat/lng: تەنیا ئەگەر هاتبن
        if ($request->filled('lat') && $request->filled('lng')) {
            $payload['lat'] = (float) $data['lat'];
            $payload['lng'] = (float) $data['lng'];
        }

        // پاشەکەوت
        $college->name = $payload['name'];
        $college->university_id = $payload['university_id'];
        $college->name_en = $payload['name_en'];
        $college->image = $payload['image'];
        $college->lat = $payload['lat'];
        $college->lng = $payload['lng'];

        if (!empty($payload['geojson'])) {
            $college->geojson = json_encode($payload['geojson']);
        }

        $college->status = (bool) $data['status'];

        $college->save();

        return redirect()->route('admin.colleges.index')->with('success', 'کۆلێژ یان پەیمانگا بە سەرکەوتوویی نوێ کراوە.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $college = College::findOrFail($id);

        if (!empty($college->geojson_path)) {
            Storage::disk('public')->delete($college->geojson_path);
        }

        if (!empty($college->image)) {
            $this->DeleteImage($college->image);
        }

        $college->delete();

        return redirect()->route('admin.colleges.index')->with('success', 'کۆلێژ یان پەیمانگا بە سەرکەوتوویی سڕیاوە.');
    }
}
