<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; // <-- گرنگ: بەکارهێنە Schema::hasColumn
use App\Models\Department;
use App\Models\College;
use App\Models\University;
use App\Models\Province;

class DashboardController extends Controller
{
    /**
     * داشبۆرد ـی نەخشە
     */
    public function index()
    {
        // تەنیا ئەم 4 پارێزگایە:
        $wanted = ['هەولێر', 'سلێمانی', 'دهۆک', 'هەڵەبجە'];

        // پێشبینی: provinces تیایدا خانەی geojson (text/json) هەیە کە Polygon/MultiPolygonە
        $provinces = Province::query()
            ->whereIn('name', $wanted)
            ->get(['id', 'name', 'geojson']); // اگر ناوت جیاوازە (boundary, geometry, geom_geojson) لێی بگۆرە

        // FeatureCollection بۆ Leaflet
        $features = $provinces
            ->map(function ($p) {
                // ئەگەر geojson لە داتابەیس «string» ـە، parse ـی بکە بۆ array:
                $geom = is_string($p->geojson) ? json_decode($p->geojson, true) : $p->geojson;

                return [
                    'type' => 'Feature',
                    'properties' => [
                        'id' => $p->id,
                        'name' => $p->name,
                    ],
                    'geometry' => $geom, // Polygon/MultiPolygon
                ];
            })
            ->values()
            ->all();

        $provinceGeoJSON = [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];

        return view('website.web.admin.dashboard', [
            'provinceGeoJSON' => $provinceGeoJSON,
        ]);
    }

    /**
     * API: هەرکات لەسەر پارێزگایەک کلیک کرا → زانکۆ/کۆلێژ/پەیمانگاەکان بگەڕێنەوە بە lat/lng
     */
    // ===== Helper ها =====
    private function decodeGeo($json)
    {
        if ($json === null) {
            return null;
        }

        if (is_array($json)) {
            // هەندێک داتابەیس تەنیا coordinates دەنێرن
            if (isset($json['type'])) {
                return $json;
            }
            if (isset($json['coordinates'])) {
                return ['type' => 'Polygon', 'coordinates' => $json['coordinates']];
            }
            return null;
        }

        if ($json instanceof \JsonSerializable) {
            $j = $json->jsonSerialize();
            if (is_array($j)) {
                if (isset($j['type'])) {
                    return $j;
                }
                if (isset($j['coordinates'])) {
                    return ['type' => 'Polygon', 'coordinates' => $j['coordinates']];
                }
            }
            return null;
        }

        if (is_object($json)) {
            $a = json_decode(json_encode($json), true);
            if (is_array($a)) {
                if (isset($a['type'])) {
                    return $a;
                }
                if (isset($a['coordinates'])) {
                    return ['type' => 'Polygon', 'coordinates' => $a['coordinates']];
                }
            }
            return null;
        }

        if (is_string($json) && $json !== '') {
            $a = json_decode($json, true);
            if (is_array($a)) {
                if (isset($a['type'])) {
                    return $a;
                }
                if (isset($a['coordinates'])) {
                    return ['type' => 'Polygon', 'coordinates' => $a['coordinates']];
                }
            }
            return null;
        }

        return null;
    }

    private function imgUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }
        return asset($path);
    }

    private function mapRow($row, $kind = null)
    {
        $base = [
            'id' => $row->id,
            'name' => $row->name,
            'name_en' => $row->name_en ?? null,
            'lat' => isset($row->lat) ? ($row->lat === null ? null : (float) $row->lat) : null,
            'lng' => isset($row->lng) ? ($row->lng === null ? null : (float) $row->lng) : null,
            'image' => $row->image ?? ($row->image ?? null),
            'geojson' => $this->decodeGeo($row->geojson ?? null),
            'kind' => $kind, // uni | col | dep (بۆ front-end)
        ];

        if ($kind === 'dep') {
            // خانە تایبەتی بەش
            $base['local_score'] = $row->local_score ?? null;
            $base['external_score'] = $row->external_score ?? null;
            $base['type'] = $row->type ?? null;
            $base['sex'] = $row->sex ?? null;
            $base['description'] = $row->description ?? null;

            // Breadcrumb names + images
            $base['province_name'] = $row->province->name ?? null;
            $base['province_name_en'] = $row->province->name_en ?? null;
            $base['province_image'] = $row->province->image ?? null;

            $base['university_name'] = $row->university->name ?? null;
            $base['university_name_en'] = $row->university->name_en ?? null;
            $base['university_image'] = $row->university->image ?? null;

            $base['college_name'] = $row->college->name ?? null;
            $base['college_name_en'] = $row->college->name_en ?? null;
            $base['college_image'] = $row->college->image ?? null;
        }

        return $base;
    }

    /** helper: safely pick columns that actually exist in table */
    private function takeExistingCols(string $table, array $wanted): array
    {
        return array_values(array_filter($wanted, fn($c) => Schema::hasColumn($table, $c)));
    }
    // ===== Endpoints =====

    // Basemap: Provinces GeoJSON (FeatureCollection)
    public function provincesGeoJSON()
    {
        $rows = Province::query()
            ->where('status', 1)
            ->get(['id', 'name', 'name_en', 'geojson']);

        $features = [];
        foreach ($rows as $p) {
            $geom = $this->decodeGeo($p->geojson ?? null); // mixed-safe وەشانەکەی پێشومان
            if (!$geom) {
                continue;
            }
            $features[] = [
                'type' => 'Feature',
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'name_en' => $p->name_en,
                ],
                'geometry' => $geom,
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    // /dashboard/provinces/{province}/universities
    public function universitiesByProvince($provinceId)
    {
        $rows = University::where('province_id', $provinceId)
            ->when(Schema::hasColumn('universities', 'status'), fn($q) => $q->where('status', 1))
            ->get(['id', 'name', 'name_en', 'lat', 'lng', 'image', 'geojson']);
        return response()->json(['items' => $rows->map(fn($r) => $this->mapRow($r, 'uni'))->values()]);
    }

    // /dashboard/universities/{university}/colleges
    public function collegesByUniversity($universityId)
    {
        $rows = College::where('university_id', $universityId)
            ->when(Schema::hasColumn('colleges', 'status'), fn($q) => $q->where('status', 1))
            ->get(['id', 'name', 'name_en', 'lat', 'lng', 'image', 'geojson']);
        return response()->json(['items' => $rows->map(fn($r) => $this->mapRow($r, 'col'))->values()]);
    }

    // /dashboard/colleges/{college}/departments
    // GET /dashboard/colleges/{college}/departments
    public function departmentsByCollege($collegeId)
    {
        try {
            // دڵنیابە ریلیشنەکانت لە Department مۆدێڵ هەیە: province(), university(), college()
            $rows = Department::with(['province:id,name,name_en,image', 'university:id,name,name_en,image', 'college:id,name,name_en,image'])
                ->where('college_id', $collegeId)
                ->when(Schema::hasColumn('departments', 'status'), fn($q) => $q->where('status', 1))
                ->get(['id', 'name', 'name_en', 'lat', 'lng', 'image', 'local_score', 'external_score', 'type', 'sex', 'description', 'province_id', 'university_id', 'college_id']);

            return response()->json([
                'items' => $rows->map(fn($r) => $this->mapRow($r, 'dep'))->values(),
            ]);
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'message' => 'Departments endpoint failed',
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500,
            );
        }
    }
}
