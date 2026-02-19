<?php

namespace App\Support;

use App\Models\Province;
use App\Models\Student;
use App\Models\User;

class DepartmentAccessScope
{
    public function forUser(?User $user): array
    {
        if (!$user) {
            return $this->unrestricted();
        }

        if ($user->role === 'admin') {
            return $this->unrestricted();
        }

        if ($user->role === 'student') {
            return $this->forStudent($user->student);
        }

        if ($user->role === 'teacher') {
            $isAllDepartments = (int) ($user->teacher->all_departments ?? 0) === 1;
            $maxSelections = $isAllDepartments ? 50 : 20;

            if ($isAllDepartments) {
                return $this->unrestricted($maxSelections);
            }

            $teacherProvince = is_string($user->teacher->province ?? null)
                ? trim((string) $user->teacher->province)
                : '';
            if ($teacherProvince !== '') {
                return $this->restrictedFromSingleProvinceName($teacherProvince, $maxSelections);
            }

            $provinceName = $this->resolvePrimaryProvinceName(
                Student::query()
                ->where('referral_code', $user->rand_code)
                ->whereNotNull('province')
                ->pluck('province')
            );

            return $this->restrictedFromSingleProvinceName($provinceName, $maxSelections);
        }

        if ($user->role === 'center') {
            $isAllDepartments = (int) ($user->center->all_departments ?? 0) === 1;
            $maxSelections = $isAllDepartments ? 50 : 20;

            if ($isAllDepartments) {
                return $this->unrestricted($maxSelections);
            }

            $centerProvince = is_string($user->center->province ?? null)
                ? trim((string) $user->center->province)
                : '';
            if ($centerProvince !== '') {
                return $this->restrictedFromSingleProvinceName($centerProvince, $maxSelections);
            }

            $provinceNames = collect();

            $directStudentProvinces = Student::query()
                ->where('referral_code', $user->rand_code)
                ->whereNotNull('province')
                ->pluck('province');
            $provinceNames = $provinceNames->merge($directStudentProvinces);

            $teacherRandCodes = User::query()
                ->where('role', 'teacher')
                ->whereIn('id', function ($query) use ($user) {
                    $query->select('user_id')
                        ->from('teachers')
                        ->where('referral_code', $user->rand_code);
                })
                ->pluck('rand_code')
                ->filter()
                ->values();

            if ($teacherRandCodes->isNotEmpty()) {
                $teacherStudentProvinces = Student::query()
                    ->whereIn('referral_code', $teacherRandCodes)
                    ->whereNotNull('province')
                    ->pluck('province');

                $provinceNames = $provinceNames->merge($teacherStudentProvinces);
            }

            $provinceName = $this->resolvePrimaryProvinceName($provinceNames);
            return $this->restrictedFromSingleProvinceName($provinceName, $maxSelections);
        }

        return $this->unrestricted();
    }

    public function forStudent(?Student $student): array
    {
        $isAllDepartments = (int) ($student?->all_departments ?? 0) === 1;
        $maxSelections = $isAllDepartments ? 50 : 10;

        if ($isAllDepartments) {
            return [
                'is_all_departments' => true,
                'is_restricted' => false,
                'allowed_province_ids' => [],
                'primary_province_id' => (int) ($student?->province_id ?? 0) ?: null,
                'max_selections' => $maxSelections,
            ];
        }

        $provinceId = (int) ($student?->province_id ?? 0);
        if ($provinceId > 0) {
            return [
                'is_all_departments' => false,
                'is_restricted' => true,
                'allowed_province_ids' => [$provinceId],
                'primary_province_id' => $provinceId,
                'max_selections' => $maxSelections,
            ];
        }

        return [
            'is_all_departments' => false,
            'is_restricted' => true,
            'allowed_province_ids' => [],
            'primary_province_id' => null,
            'max_selections' => $maxSelections,
        ];
    }

    private function unrestricted(?int $maxSelections = null): array
    {
        return [
            'is_all_departments' => true,
            'is_restricted' => false,
            'allowed_province_ids' => [],
            'primary_province_id' => null,
            'max_selections' => $maxSelections,
        ];
    }

    private function restrictedFromSingleProvinceName(?string $provinceName, int $maxSelections): array
    {
        if (!$provinceName) {
            return [
                'is_all_departments' => false,
                'is_restricted' => true,
                'allowed_province_ids' => [],
                'primary_province_id' => null,
                'max_selections' => $maxSelections,
            ];
        }

        $provinceId = Province::query()
            ->where('name', $provinceName)
            ->value('id');

        $provinceId = $provinceId ? (int) $provinceId : null;

        return [
            'is_all_departments' => false,
            'is_restricted' => true,
            'allowed_province_ids' => $provinceId ? [$provinceId] : [],
            'primary_province_id' => $provinceId,
            'max_selections' => $maxSelections,
        ];
    }

    private function resolvePrimaryProvinceName($provinceNames): ?string
    {
        $normalized = collect($provinceNames)
            ->map(function ($name) {
                $name = is_string($name) ? trim($name) : '';
                return $name !== '' ? $name : null;
            })
            ->filter()
            ->values();

        if ($normalized->isEmpty()) {
            return null;
        }

        return $normalized
            ->countBy()
            ->sortDesc()
            ->keys()
            ->first();
    }
}
