<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\ResultDep;
use App\Support\DepartmentAccessScope;

class DashboardStudentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = $user?->student;

        if (!$student) {
            abort(403, 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }

        // Check if student has GIS feature enabled
        $hasGIS = $student->gis == 1;

        // Show GIS map dashboard if enabled, otherwise show simple dashboard
        if ($hasGIS) {
            $isAllDepartments = (int) ($student?->all_departments ?? 0) === 1;
            $ownProvinceName = trim((string) ($student?->province ?? ''));

            if ($isAllDepartments) {
                $mapScope = [
                    'is_restricted' => false,
                    'is_all_departments' => true,
                    'allowed_province_names' => [],
                    'primary_province_name' => null,
                ];
            } else {
                // Fallback for older student records.
                if ($ownProvinceName === '') {
                    $scope = app(DepartmentAccessScope::class)->forStudent($student);
                    $primaryProvinceId = (int) ($scope['primary_province_id'] ?? 0);
                    if ($primaryProvinceId > 0) {
                        $ownProvinceName = Province::query()
                            ->where('id', $primaryProvinceId)
                            ->value('name');
                    }
                }

                $mapScope = [
                    'is_restricted' => true,
                    'is_all_departments' => false,
                    'allowed_province_names' => $ownProvinceName ? [$ownProvinceName] : [],
                    'primary_province_name' => $ownProvinceName ?: null,
                ];
            }

            return view('website.web.student.dashboard-gis', compact('student', 'mapScope'));
        }

        $scope = app(DepartmentAccessScope::class)->forStudent($student);
        $maxSelections = (int) ($scope['max_selections'] ?? 10);
        $selectedDepartmentsCount = ResultDep::query()
            ->where('student_id', $student?->id)
            ->count();
        $finalSelection = ResultDep::query()
            ->where('student_id', $student?->id)
            ->whereNotNull('result_rank')
            ->with('department')
            ->orderBy('result_rank')
            ->first();

        $features = [
            [
                'key' => 'ai_rank',
                'label' => 'ڕیزبەندی کرد بە زیرەکی دەستکرد',
                'icon' => 'fa-robot',
                'active' => (int) ($student?->ai_rank ?? 0) === 1,
            ],
            [
                'key' => 'gis',
                'label' => 'سەیرکردن بە نەخشە',
                'icon' => 'fa-map-location-dot',
                'active' => (int) ($student?->gis ?? 0) === 1,
            ],
            [
                'key' => 'all_departments',
                'label' => 'ڕێزبەندی ٥٠ بەش + بینینی پارێزگاکانی تر',
                'icon' => 'fa-layer-group',
                'active' => (int) ($student?->all_departments ?? 0) === 1,
            ],
        ];

        $activeFeaturesCount = collect($features)->where('active', true)->count();
        $selectionPercent = $maxSelections > 0
            ? min(100, (int) round(($selectedDepartmentsCount / $maxSelections) * 100))
            : 0;

        $dashboard = [
            'max_selections' => $maxSelections,
            'selected_departments_count' => $selectedDepartmentsCount,
            'selection_percent' => $selectionPercent,
            'features' => $features,
            'active_features_count' => $activeFeaturesCount,
            'features_count' => count($features),
            'final_selection' => $finalSelection,
        ];

        return view('website.web.student.dashboard-simple', compact('dashboard', 'student'));
    }
}
