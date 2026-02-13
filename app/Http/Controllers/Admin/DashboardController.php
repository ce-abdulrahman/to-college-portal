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
use Illuminate\Support\Facades\Crypt;

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

        return view('website.web.admin.map', [
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
        }
        return null;
    }

    private function encryptId(int $id): string
    {
        $payload = Crypt::encryptString((string) $id);
        // URL-safe base64 (strip padding)
        return rtrim(strtr($payload, '+/', '-_'), '=');
    }

    private function decryptId(string $id): ?int
    {
        if ($id === '') return null;
        if (ctype_digit($id)) return (int) $id;

        $payload = strtr($id, '-_', '+/');
        $pad = strlen($payload) % 4;
        if ($pad) $payload .= str_repeat('=', 4 - $pad);

        try {
            $raw = Crypt::decryptString($payload);
            return ctype_digit((string) $raw) ? (int) $raw : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function mapRow(object $row, ?string $kind = null): array
    {
        $base = [
            'id'       => $this->encryptId((int) $row->id),
            'name'     => $row->name ?? null,
            'name_en'  => $row->name_en ?? null,
            'lat'      => isset($row->lat) ? (float) $row->lat : null,
            'lng'      => isset($row->lng) ? (float) $row->lng : null,
            'image'    => $row->image ?? null,
            'image_url'=> $row->image_url ?? ($row->image ?? null),
            'geojson'  => $this->decodeGeo($row->geojson ?? null),
        ];

        if ($kind === 'dep') {
            $base += [
                'local_score'    => $row->local_score ?? null,
                'external_score' => $row->external_score ?? null,
                'type'           => $row->type ?? null,
                'sex'            => $row->sex ?? null,
                'description'    => $row->description ?? null,
                'province_name'  => $row->province->name   ?? null,
                'university_name'=> $row->university->name ?? null,
                'college_name'   => $row->college->name    ?? null,
            ];
        }
        return $base;
    }

    // GET /api/provinces/geojson
    public function getProvincesGeoJSON(): JsonResponse
    {
        // Hardcoded verified columns for performance
        $rows = Province::query()
            ->where('status', 1)
            ->get(['id','name','name_en','geojson','image']);

        $features = [];
        foreach ($rows as $p) {
            $geom = $this->decodeGeo($p->geojson);
            if (!$geom) continue;

            if (($geom['type'] ?? null) === 'FeatureCollection' && isset($geom['features'])) {
                foreach ($geom['features'] as $f) {
                    if (!isset($f['geometry'])) continue;
                    $features[] = [
                        'type' => 'Feature',
                        'properties' => [
                            'id' => $this->encryptId((int) $p->id),
                            'name' => $p->name,
                            'name_en' => $p->name_en,
                            'image' => $p->image
                        ],
                        'geometry' => $f['geometry']
                    ];
                }
            } else {
                $geometry = $geom['geometry'] ?? $geom;
                if (!$geometry) continue;
                $features[] = [
                    'type' => 'Feature',
                    'properties' => [
                        'id' => $this->encryptId((int) $p->id),
                        'name' => $p->name,
                        'name_en' => $p->name_en,
                        'image' => $p->image
                    ],
                    'geometry' => $geometry
                ];
            }
        }

        return response()->json(['type'=>'FeatureCollection','features'=>$features]);
    }

    // GET /api/provinces/{id}/universities
    public function getUniversitiesByProvince(string $provinceId): JsonResponse
    {
        $pid = $this->decryptId($provinceId);
        if (!$pid) return response()->json(['items' => [], 'counts' => ['universities' => 0]]);

        $rows = University::query()
            ->where('province_id', $pid)
            ->where('status', 1)
            ->get(['id','name','name_en','lat','lng','image','image_url','geojson','province_id']);

        return response()->json([
            'items' => $rows->map(fn($r)=>$this->mapRow($r,'uni'))->values(),
            'counts' => [
                'universities' => $rows->count(),
            ]
        ]);
    }

    // GET /api/universities/{id}/colleges
    public function getCollegesByUniversity(string $universityId): JsonResponse
    {
        $uid = $this->decryptId($universityId);
        if (!$uid) return response()->json(['university' => null, 'items' => []]);

        $rows = College::query()
            ->where('university_id', $uid)
            ->where('status', 1)
            ->get(['id','name','name_en','lat','lng','image','image_url','geojson','university_id','type']);

        return response()->json([
            'university' => University::find($uid, ['id','name','name_en','image']),
            'items'      => $rows->map(fn($r)=>$this->mapRow($r,'col'))->values()
        ]);
    }

    // GET /api/colleges/{id}/departments
    public function getDepartmentsByCollege(string $collegeId): JsonResponse
    {
        $cid = $this->decryptId($collegeId);
        if (!$cid) return response()->json(['college' => null, 'items' => []]);

        $rows = Department::with(['province:id,name','university:id,name','college:id,name'])
            ->where('college_id', $cid)
            ->where('status', 1)
            ->get([
                'id','name','name_en','lat','lng','image','image_url',
                'local_score','external_score','type','sex','description',
                'province_id', 'university_id', 'college_id', 'status'
            ]);

        return response()->json([
            'college' => College::find($cid, ['id','name','name_en','image']),
            'items'   => $rows->map(fn($r)=>$this->mapRow($r,'dep'))->values()
        ]);
    }
}
