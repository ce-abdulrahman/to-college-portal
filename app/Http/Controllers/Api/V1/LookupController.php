<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\University;
use App\Models\College;
use App\Http\Resources\UniversityResource;
use App\Http\Resources\CollegeResource;

class LookupController extends Controller
{
    public function universities(Request $request)
    {
        $pid = (int) $request->query('province_id');
        abort_if($pid <= 0, 422, 'پارێزگا نەدۆزرایەوە!');

        $items = University::with('province:id,name')
            ->select('id','name','province_id','status')
            ->where('province_id', $pid)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return UniversityResource::collection($items);
    }

    public function colleges(Request $request)
    {
        $uid = (int) $request->query('university_id');
        abort_if($uid <= 0, 422, 'زانکۆ نەدۆزرایەوە!');

        $items = College::with('university:id,name')
            ->select('id','name','university_id','status')
            ->where('university_id', $uid)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return CollegeResource::collection($items);
    }
}
