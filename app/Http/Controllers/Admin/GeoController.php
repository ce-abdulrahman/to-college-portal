<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\University;
use App\Models\College;
use App\Models\Department;

class GeoController extends Controller
{
    /* ========================= Provinces (AREA) ========================= */

    public function editProvinceArea(Province $province)
    {
        // viewی سادەی textarea/file upload دەتوانیت بخۆیت دروست بکەیت
        $province = Province::findOrFail($province);
        return view('website.web.admin.geo.province-area', compact('province'));
    }

    public function updateProvinceArea(Request $request, Province $province)
    {
        // دوو ڕێگا: 1) paste کردن لە textarea  2) upload کردن فایل .geojson
        $data = $request->validate([
            'geojson_text' => ['nullable', 'string'],
            'geojson_file' => ['nullable', 'file', 'mimes:json,geojson,txt', 'max:20480'], // 20MB
        ]);

        $geo = $this->resolveGeojsonInput($data['geojson_text'] ?? null, $request->file('geojson_file'));
        $province->update(['geojson' => $geo]); // cast به‌ 'array' لە مودێڵ

        return back()->with('success', 'Province area (GeoJSON) updated.');
    }

    /* ====================== Universities (AREA + POINT) ====================== */

    public function editUniversityGeo(University $university)
    {
        $university = University::all();
        return view('website.web.admin.geo.university-geo', compact('university'));
    }

    public function updateUniversityGeo(Request $request, University $university)
    {
        $data = $request->validate([
            // AREA
            'geojson_text' => ['nullable', 'string'],
            'geojson_file' => ['nullable', 'file', 'mimes:json,geojson,txt', 'max:20480'],
            // POINT
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $payload = [];
        // area (optional)
        if (!empty($data['geojson_text']) || $request->hasFile('geojson_file')) {
            $payload['geojson'] = $this->resolveGeojsonInput($data['geojson_text'] ?? null, $request->file('geojson_file'));
        }
        // point (optional)
        if ($request->filled('lat') && $request->filled('lng')) {
            $payload['lat'] = (float) $data['lat'];
            $payload['lng'] = (float) $data['lng'];
        }

        if (empty($payload)) {
            return back()->withErrors(['geo' => 'هیچ گۆڕانکاریەک دانەناوە.']);
        }

        $university->update($payload);
        return back()->with('success', 'University geo updated.');
    }

    /* ======================= Colleges (AREA + POINT) ======================= */

    public function editCollegeGeo(College $college)
    {
        $college = College::all();
        return view('website.web.admin.geo.college-geo', compact('college'));
    }

    public function updateCollegeGeo(Request $request, College $college)
    {
        $data = $request->validate([
            'geojson_text' => ['nullable', 'string'],
            'geojson_file' => ['nullable', 'file', 'mimes:json,geojson,txt', 'max:20480'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $payload = [];
        if (!empty($data['geojson_text']) || $request->hasFile('geojson_file')) {
            $payload['geojson'] = $this->resolveGeojsonInput($data['geojson_text'] ?? null, $request->file('geojson_file'));
        }
        if ($request->filled('lat') && $request->filled('lng')) {
            $payload['lat'] = (float) $data['lat'];
            $payload['lng'] = (float) $data['lng'];
        }

        if (empty($payload)) {
            return back()->withErrors(['geo' => 'هیچ گۆڕانکاریەک دانەناوە.']);
        }

        $college->update($payload);
        return back()->with('success', 'College geo updated.');
    }

    /* ========================= Departments (POINT) ========================= */

    public function editDepartmentPoint(Department $department)
    {
        $department = Department::all();
        return view('website.web.admin.geo.department-point', compact('department'));
    }

    public function updateDepartmentPoint(Request $request, Department $department)
    {
        $data = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $department->update([
            'lat' => (float) $data['lat'],
            'lng' => (float) $data['lng'],
        ]);

        return back()->with('success', 'Department point updated.');
    }

    /* ============================== Helpers ============================== */

    /**
     * هەر دوو ئێنپوت (textarea/file) بگۆڕە بۆ GeoJSON valid (array) ـی PHP
     */
    protected function resolveGeojsonInput(?string $text, $file): array
    {
        $raw = null;

        if ($file) {
            $raw = file_get_contents($file->getRealPath());
        } elseif ($text !== null) {
            $raw = trim($text);
        }

        if (!$raw) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'geojson' => 'هیچ داتای GeoJSON نییە.',
            ]);
        }

        $json = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || !$this->looksLikeGeojson($json)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'geojson' => 'GeoJSON نادروستە. تکایە Polygon/MultiPolygon یەکی دروست بنێرە.',
            ]);
        }

        return $json;
    }

    /**
     * چەککردنی بنەڕەتی: type ∈ Feature/FeatureCollection/Polygon/MultiPolygon
     */
    protected function looksLikeGeojson($json): bool
    {
        if (!is_array($json) || empty($json['type'])) {
            return false;
        }

        $type = $json['type'];

        if (in_array($type, ['Polygon', 'MultiPolygon'], true)) {
            return !empty($json['coordinates']);
        }

        if ($type === 'Feature') {
            return !empty($json['geometry']) && $this->looksLikeGeojson($json['geometry']);
        }

        if ($type === 'FeatureCollection') {
            if (empty($json['features']) || !is_array($json['features'])) {
                return false;
            }
            // بەلایەنی کەم یەک feature هەبێت
            foreach ($json['features'] as $f) {
                if ($this->looksLikeGeojson($f)) {
                    return true;
                }
            }
            return false;
        }

        return false;
    }
}
