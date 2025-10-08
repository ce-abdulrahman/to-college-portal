<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Student;
use App\Models\ResultDep;
use App\Models\System;
use App\Models\Province;
use Illuminate\Support\Facades\DB;

class DepartmentSelector
{
    /**
     * 1) خەزنکردنی داتا لە students
     * 2) ڕێزبەندی و هەڵبژاردنی بەشەکان لە departments
     * 3) خەزنکردنی تەنیا (user_id, student_id, department_id) لە result_deps
     */
    public function build(
        int $user_id,
        string $province,   // ناوی پارێزگا (بۆ map کردن بۆ province_id)
        string $type,       // 'زانستی' یان 'وێژەیی'
        string $gender,     // 'نێر' یان 'مێ'
        int $year,          // 1,2,3...
        float $mark,
        ?int $zankoline_num = null,
        ?int $parallel_num  = null,
        ?int $evening_num   = null
    ): array {

        // 0) سنووردانی هەڵبژاردنەکان
        if (is_null($zankoline_num) && is_null($parallel_num) && is_null($evening_num)) {
            $zankoline_num = 7;
            $parallel_num  = 7;
            $evening_num   = 7;
        } else {
            $zankoline_num = $zankoline_num ?? 0;
            $parallel_num  = $parallel_num  ?? 0;
            $evening_num   = $evening_num   ?? 0;
        }

        $sumChoices = $zankoline_num + $parallel_num + $evening_num;
        if ($sumChoices !== 50) {
            throw new \InvalidArgumentException('کۆی هەڵبژاردنەکان دەبێت 50 بێت.');
        }

        // 1) خەزنکردن/نوێکردنەوەی قوتابی لە students (by user_id)
        $student = Student::updateOrCreate(
            ['user_id' => $user_id],
            [
                'mark'    => $mark,
                'province'=> $province,   // خۆی سترینگە لە students
                'type'    => $type,       // 'زانستی'/'وێژەیی'
                'gender'  => $gender,     // 'نێر'/'مێ'
                'year'    => $year,
                'status'  => 1,
            ]
        );

        // 2) ڕێوشوێنەکانی فلتەرکردن
        $bonus = $this->calcBonus($mark);

        // a) map کردن ناوی پارێزگا → province_id
        $provinceId = Province::where('name', $province)->value('id');
        if (!$provinceId) {
            // ئەگەر ناوی پارێزگا نەدۆزرایەوە، دەتوانیت هەر یان throw بکەیت یان فلتەری پارێزگا لاببەیت
            // بۆ ئاسوودەیی، هێشتا بە فلتەری تر دەچین بەبێ پارێزگا
        }

        // b) type: کورتە-کورتە
        //   قوتابی 'زانستی' → بەشەکانی ['زانستی','زانستی و وێژەیی']
        //   قوتابی 'وێژەیی' → بەشەکانی ['وێژەیی','زانستی و وێژەیی']
        $typeAcceptable = $type === 'زانستی'
            ? ['زانستی', 'زانستی و وێژەیی']
            : ['وێژەیی', 'زانستی و وێژەیی'];

        // c) gender: هەمان ڕەگەز یان 'هەردوو' (ئەگەر لە داتابەیسەکەت بە 'both' نوسراوە، ئەوە بەکاربەرە)
        $genderAcceptable = ['هەردوو', $gender];

        // d) systems: بەپێی year → system_id ـەکان بدۆزەوە
        //   year > 1 → ['پاراڵیل','ئێواران']
        //   year == 1 → ['زانکۆلاین','پاراڵیل','ئێواران']
        $wantedSystemNames = $year > 1
            ? ['پاراڵیل', 'ئێواران']
            : ['زانکۆلاین', 'پاراڵیل', 'ئێواران'];

        $systems = System::whereIn('name', $wantedSystemNames)->pluck('id','name'); // ['name' => id]

        // 3) بنەڕەتی فلتەر لە departments
        $base = Department::query()
            ->when($provinceId, fn($q) => $q->where('province_id', $provinceId)) // departments.province_id
            ->whereIn('type', $typeAcceptable)                                   // departments.type
            ->whereIn('sex', $genderAcceptable);                                 // departments.sex

        // 4) بە پێی سیستەمەکان لیمیت کردنی دەرئەنجام
        $zankoline = collect();
        $parallel  = collect();
        $evening   = collect();

        if ($systems->has('زانکۆلاین')) {
            $zankoline = (clone $base)
                ->where('system_id', $systems['زانکۆلاین'])
                ->limit($zankoline_num)
                ->get(['id']); // تەنیا id پێویستە
        }

        if ($systems->has('پاراڵیل')) {
            $parallel = (clone $base)
                ->where('system_id', $systems['پاراڵیل'])
                ->limit($parallel_num)
                ->get(['id']);
        }

        if ($systems->has('ئێواران')) {
            $evening = (clone $base)
                ->where('system_id', $systems['ئێواران'])
                ->limit($evening_num)
                ->get(['id']);
        }

        // 5) خەزنکردنی تەنیا (user_id, student_id, department_id) لە result_deps
        //    تکایە هەوڵدە «duplicate» نەبێت بۆ یەک (user_id,student_id,department_id)
        $created = [];
        DB::transaction(function() use ($user_id, $student, $zankoline, $parallel, $evening, &$created) {

            $deptIds = $zankoline->pluck('id')
                        ->merge($parallel->pluck('id'))
                        ->merge($evening->pluck('id'))
                        ->unique()
                        ->values();

            foreach ($deptIds as $depId) {
                $rd = ResultDep::firstOrCreate(
                    [
                        'user_id'       => $user_id,
                        'student_id'    => $student->id,
                        'department_id' => $depId,
                    ],
                    ['status' => 1]
                );
                $created[] = [
                    'user_id'       => $rd->user_id,
                    'student_id'    => $rd->student_id,
                    'department_id' => $rd->department_id,
                    'id'            => $rd->id,
                ];
            }
        });

        return [
            'student_saved' => [
                'id'       => $student->id,
                'user_id'  => $student->user_id,
                'mark'     => $student->mark,
                'province' => $student->province,
                'type'     => $student->type,
                'gender'   => $student->gender,
                'year'     => $student->year,
            ],
            'bonus'   => round($bonus, 3),
            'totals'  => [
                'zankoline' => (int)($zankoline?->count() ?? 0),
                'parallel'  => (int)($parallel?->count() ?? 0),
                'evening'   => (int)($evening?->count() ?? 0),
                'sum'       => (int)((($zankoline?->count() ?? 0) + ($parallel?->count() ?? 0) + ($evening?->count() ?? 0))),
            ],
            // تەنیا ID ـەکان دەگەنێین چونکە لە result_deps هەمانان خەزن دەکەین
            'eligible_department_ids' => [
                'zankoline' => $zankoline->pluck('id'),
                'parallel'  => $parallel->pluck('id'),
                'evening'   => $evening->pluck('id'),
            ],
            'result_deps_created' => $created, // لیستی ریکۆردە نوێکان
        ];
    }

    protected function calcBonus(float $mark): float
    {
        if ($mark < 60)  return 0.0;
        if ($mark < 71)  return 5.123;
        if ($mark < 80)  return 4.579;
        if ($mark < 85)  return 3.759;
        if ($mark < 90)  return 2.759;
        if ($mark < 95)  return 1.357;
        return 0.0;
    }
}
