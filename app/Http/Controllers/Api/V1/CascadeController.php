<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\College;
use App\Models\University;
use App\Models\Province;

class CascadeController extends Controller
{
    // Controllers (API v1 — بۆ Flutter)
    // ئەم controller بۆ flutter دروست کردووە بۆ داهاتوو بەکاریبێنم
    public function universitiesByProvince(Request $request)
    {
        $data = $request->validate([
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
        ]);

        return University::where('province_id', $data['province_id'])
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function collegesByUniversity(Request $request)
    {
        $data = $request->validate([
            'university_id' => ['required', 'integer', 'exists:universities,id'],
        ]);

        return College::where('university_id', $data['university_id'])
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
