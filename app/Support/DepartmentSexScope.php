<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

class DepartmentSexScope
{
    private const FEMALE_ALLOWED_SEXES = [
        'مێ',
        'نێر',
        'هەردووکیان',
        'هەردوو',
        'both',
        'Both',
    ];

    /**
     * Returns null when no filtering is needed.
     */
    public static function allowedForStudent(?string $studentSex): ?array
    {
        $studentSex = trim((string) $studentSex);

        // Requirement: male students can see all department genders.
        if ($studentSex === 'نێر') {
            return null;
        }

        // Requirement: female students can see ['مێ', 'نێر'].
        // We also keep common "both" labels to avoid hiding valid rows.
        if ($studentSex === 'مێ') {
            return self::FEMALE_ALLOWED_SEXES;
        }

        return self::FEMALE_ALLOWED_SEXES;
    }

    public static function applyForStudent(Builder $query, ?string $studentSex, string $column = 'sex'): Builder
    {
        $allowed = self::allowedForStudent($studentSex);
        if ($allowed === null) {
            return $query;
        }

        return $query->whereIn($column, $allowed);
    }

    public static function isAllowedForStudent(?string $departmentSex, ?string $studentSex): bool
    {
        $allowed = self::allowedForStudent($studentSex);
        if ($allowed === null) {
            return true;
        }

        return in_array((string) $departmentSex, $allowed, true);
    }
}
