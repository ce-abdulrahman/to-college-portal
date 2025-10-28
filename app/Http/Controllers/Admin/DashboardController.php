<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; // <-- گرنگ: بەکارهێنە Schema::hasColumn
use App\Models\Department;
use App\Models\College;
use App\Models\University;
use App\Models\Province;
use Illuminate\Http\JsonResponse;

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
    private function decodeGeo($jsonOrPath)
    {
        if ($jsonOrPath === null) return null;
        if (is_array($jsonOrPath)) return $jsonOrPath;

        if (is_string($jsonOrPath)) {
            $t = trim($jsonOrPath);
            if ($t === '') return null;
            // If looks like JSON
            if ($t[0] === '{' || $t[0] === '[') {
                $a = json_decode($t, true);
                return is_array($a) ? $a : null;
            }
            // Else treat as storage path
            if (Storage::exists($jsonOrPath)) {
                $txt = Storage::get($jsonOrPath);
                $a = json_decode($txt, true);
                return is_array($a) ? $a : null;
            }
        }
        return null;
    }

    private function takeExistingCols(string $table, array $wanted): array
    {
        return array_values(array_filter($wanted, fn($c) => Schema::hasColumn($table, $c)));
    }

    private function mapRow(object $row, ?string $kind = null): array
    {
        $base = [
            'id'       => $row->id,
            'name'     => $row->name ?? null,
            'name_en'  => $row->name_en ?? null,
            'lat'      => isset($row->lat) ? (float) $row->lat : null,
            'lng'      => isset($row->lng) ? (float) $row->lng : null,
            'image'    => $row->image ?? null,
            'image_url'=> $row->image_url ?? ($row->image ?? null),
            'geojson'  => $this->decodeGeo($row->geojson ?? ($row->geojson_path ?? null)),
        ];

        if ($kind === 'dep') {
            $base += [
                'local_score'    => $row->local_score ?? null,
                'external_score' => $row->external_score ?? null,
                'type'           => $row->type ?? null,
                'sex'            => $row->sex ?? null,
                'description'    => $row->description ?? null,
            ];
            $base['province_name']      = $row->province->name   ?? null;
            $base['university_name']    = $row->university->name ?? null;
            $base['college_name']       = $row->college->name    ?? null;
        }
        return $base;
    }

    // GET /api/provinces/geojson
    public function getProvincesGeoJSON(): JsonResponse
    {
        $cols = $this->takeExistingCols('provinces', ['id','name','name_en','geojson','geojson_path','status','image']);
        $rows = Province::query()
            ->when(Schema::hasColumn('provinces','status'), fn($q)=>$q->where('status',1))
            ->get($cols);

        $features = [];
        foreach ($rows as $p) {
            $geom = $this->decodeGeo($p->geojson ?? $p->geojson_path);
            if (!$geom) continue;

            // Accept Feature/FeatureCollection/Geometry
            if (($geom['type'] ?? null) === 'FeatureCollection' && isset($geom['features'])) {
                foreach ($geom['features'] as $f) {
                    if (!isset($f['geometry'])) continue;
                    $features[] = [
                        'type' => 'Feature',
                        'properties' => ['id'=>$p->id,'name'=>$p->name,'name_en'=>$p->name_en,'image'=>$p->image],
                        'geometry' => $f['geometry']
                    ];
                }
            } else {
                $geometry = $geom['geometry'] ?? $geom;
                if (!$geometry) continue;
                $features[] = [
                    'type' => 'Feature',
                    'properties' => ['id'=>$p->id,'name'=>$p->name,'name_en'=>$p->name_en,'image'=>$p->image],
                    'geometry' => $geometry
                ];
            }
        }

        return response()->json(['type'=>'FeatureCollection','features'=>$features]);
    }

    // GET /api/provinces/{id}/universities
    public function getUniversitiesByProvince(int $provinceId): JsonResponse
    {
        $cols = $this->takeExistingCols('universities', ['id','name','name_en','lat','lng','image','image_url','geojson','status','province_id']);
        $rows = University::query()
            ->where('province_id',$provinceId)
            ->when(Schema::hasColumn('universities','status'), fn($q)=>$q->where('status',1))
            ->get($cols);

        return response()->json([
            'items'=>$rows->map(fn($r)=>$this->mapRow($r,'uni'))->values(),
            'counts' => [
                'universities' => $rows->count(),
                'colleges' => $rows->sum('colleges_count'),
                'departments' => $rows->sum('departments_count'),
            ]
        ]);
    }

    // GET /api/universities/{id}/colleges
    public function getCollegesByUniversity(int $universityId): JsonResponse
    {
        $cols = $this->takeExistingCols('colleges', ['id','name','name_en','lat','lng','image','image_url','geojson','status','university_id','type']);
        $rows = College::query()
            ->where('university_id',$universityId)
            ->when(Schema::hasColumn('colleges','status'), fn($q)=>$q->where('status',1))
            ->get($cols);

        return response()->json([
            'university' => University::find($universityId, ['id','name','name_en','image']),
            'items'      => $rows->map(fn($r)=>$this->mapRow($r,'col'))->values()
        ]);
    }

    // GET /api/colleges/{id}/departments
    public function getDepartmentsByCollege(int $collegeId): JsonResponse
    {
        $cols = $this->takeExistingCols('departments', [
            'id','name','name_en','lat','lng','image','image_url', // safe
            // 'geojson', // only if exists in schema
            'local_score','external_score','type','sex','description',
            'province_id','university_id','college_id','status'
        ]);

        $rows = Department::with(['province:id,name','university:id,name','college:id,name'])
            ->where('college_id',$collegeId)
            ->when(Schema::hasColumn('departments','status'), fn($q)=>$q->where('status',1))
            ->get($cols);

        return response()->json([
            'college' => College::find($collegeId, ['id','name','name_en','image']),
            'items'   => $rows->map(fn($r)=>$this->mapRow($r,'dep'))->values()
        ]);
    }
}
