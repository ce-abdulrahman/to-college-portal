<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\University; // یان Institution اگر ناوت جیاوازە

class DashboardController extends Controller
{
    /**
     * داشبۆرد ـی نەخشە
     */
    public function index()
    {
        // تەنیا ئەم 4 پارێزگایە:
        $wanted = ['هەولێر','سلێمانی','دهۆک','هەڵەبجە'];

        // پێشبینی: provinces تیایدا خانەی geojson (text/json) هەیە کە Polygon/MultiPolygonە
        $provinces = Province::query()
            ->whereIn('name', $wanted)
            ->get(['id','name','geojson']); // اگر ناوت جیاوازە (boundary, geometry, geom_geojson) لێی بگۆرە

        // FeatureCollection بۆ Leaflet
        $features = $provinces->map(function($p) {
            // ئەگەر geojson لە داتابەیس «string» ـە، parse ـی بکە بۆ array:
            $geom = is_string($p->geojson) ? json_decode($p->geojson, true) : $p->geojson;

            return [
                'type' => 'Feature',
                'properties' => [
                    'id'   => $p->id,
                    'name' => $p->name,
                ],
                'geometry' => $geom, // Polygon/MultiPolygon
            ];
        })->values()->all();

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
    public function universitiesByProvince(string $id)
    {
        $province = Province::findOrFail($id);
        // پێشبینی: universities تیایدا columns: id, name, type('University','College','Institute'), province_id, lat, lng
        $items = University::query()
            ->where('province_id', $province->id)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get(['id','name','type','lat','lng']);

        // json format بۆ Leaflet markers
        return response()->json([
            'province' => [
                'id'   => $province->id,
                'name' => $province->name,
            ],
            'institutions' => $items,
        ]);
    }

    // GET /api/map/provinces
    public function provinces()
    {
        $wanted = ['هەولێر','سلێمانی','دهۆک','هەڵەبجە'];
        $features = Province::whereIn('name', $wanted)
        ->get(['id','name','geojson'])
        ->filter(fn($p) => !empty($p->geojson))
        ->map(fn($p) => [
            'type' => 'Feature',
            'properties' => ['id' => $p->id, 'name' => $p->name, 'kind' => 'province'],
            'geometry' => is_string($p->geojson) ? json_decode($p->geojson, true) : $p->geojson,
        ])->values()->all();

        return response()->json(['type'=>'FeatureCollection','features'=>$features]);
    }

    // GET /api/map/provinces/{province}/institutions
    public function institutionsByProvince(Province $province)
    {
        $unis = University::where('province_id',$province->id)->get(['id','name','geojson','lat','lng']);
        $cols = College::whereHas('university', fn($q)=>$q->where('province_id',$province->id))
                    ->get(['id','name','geojson','lat','lng']);

        // Area layer (universities/colleges with geojson)
        $areas = collect([$unis,$cols])->flatten()->filter(fn($i)=>!empty($i->geojson))->map(function($i){
            return [
            'type'=>'Feature',
            'properties'=>['id'=>$i->id,'name'=>$i->name,'kind'=>$i instanceof University ? 'university' : 'college'],
            'geometry'=> is_string($i->geojson)? json_decode($i->geojson,true):$i->geojson,
            ];
        })->values();

        // Point markers (fallback & buildings)
        $points = collect([$unis,$cols])->flatten()
        ->filter(fn($i)=>!$i->geojson && $i->lat && $i->lng)
        ->map(fn($i)=>[
            'id'   => $i->id,
            'name' => $i->name,
            'kind' => $i instanceof University ? 'university' : 'college',
            'lat'  => $i->lat,
            'lng'  => $i->lng,
        ])->values();

        return response()->json([
        'areas'  => ['type'=>'FeatureCollection','features'=>$areas],
        'points' => $points,
        ]);
    }

    // GET /api/map/colleges/{college}/departments
    public function departmentsByCollege(College $college)
    {
        $deps = Department::where('college_id',$college->id)
            ->whereNotNull('lat')->whereNotNull('lng')
            ->get(['id','name','lat','lng']);

        return response()->json(['items' => $deps]);
    }

}
