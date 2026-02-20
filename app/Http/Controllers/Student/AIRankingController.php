<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AIRanking;
use App\Models\Department;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AIRankingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'student']);
    }

    public function preferences()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'زانیاریی قوتابی نەدۆزرایەوە.');
        }

        if ((int) ($student->ai_rank ?? 0) !== 1) {
            return view('website.web.student.ai.request-access', compact('student'));
        }

        $systems = System::query()->where('status', 1)->orderBy('id')->get();
        $allowedSystemIdsByYear = $this->allowedSystemIdsByYear((int) ($student->year ?? 1));
        $studentProvinceId = (int) ($student->province_id ?? 0);

        $rankings = AIRanking::query()
            ->where('student_id', $student->id)
            ->with(['department.system', 'department.province', 'department.university', 'department.college'])
            ->orderBy('rank')
            ->take(50)
            ->get()
            ->map(function (AIRanking $ranking) use ($studentProvinceId) {
                $department = $ranking->department;
                if (!$department) {
                    return null;
                }

                $isLocal = (int) $department->province_id === $studentProvinceId;
                $requiredScore = $isLocal
                    ? (float) $department->local_score
                    : (float) $department->external_score;

                return [
                    'rank' => (int) $ranking->rank,
                    'department' => $department,
                    'required_score' => $requiredScore,
                    'score_type' => $isLocal ? 'local_score' : 'external_score',
                    'is_local' => $isLocal,
                ];
            })
            ->filter()
            ->values();

        return view('website.web.student.ai.preferences', [
            'student' => $student,
            'systems' => $systems,
            'allowedSystemIdsByYear' => $allowedSystemIdsByYear,
            'defaultSystemIds' => $allowedSystemIdsByYear,
            'rankedRows' => $rankings,
            'summary' => null,
        ]);
    }

    public function generate(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'زانیاریی قوتابی نەدۆزرایەوە.');
        }

        if ((int) ($student->ai_rank ?? 0) !== 1) {
            return redirect()->route('student.ai-ranking.preferences')
                ->with('error', 'تایبەتمەندی AI بۆ ئەم هەژمارە چالاک نییە.');
        }

        $validated = $request->validate([
            'province_scope' => 'required|in:local_only,include_outside',
            'systems' => 'required|array|min:1',
            'systems.*' => 'integer|exists:systems,id',
        ]);

        $systems = System::query()->where('status', 1)->orderBy('id')->get();
        $allowedSystemIdsByYear = $this->allowedSystemIdsByYear((int) ($student->year ?? 1));
        $selectedSystemIds = collect($validated['systems'])
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->intersect($allowedSystemIdsByYear)
            ->values();

        if ($selectedSystemIds->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'هیچ سیستەمێکی گونجاو بۆ ساڵی تۆ هەڵنەبژێردرا.');
        }

        if ($validated['province_scope'] === 'local_only' && (int) ($student->province_id ?? 0) <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'پارێزگای قوتابی دیاری نەکراوە، ناتوانرێت تەنها ناو پارێزگا هەڵبژێردرێت.');
        }

        [$rankedRows, $markBonus, $markWithBonus] = $this->buildRankings(
            $student,
            $validated['province_scope'],
            $selectedSystemIds
        );

        DB::transaction(function () use ($student, $rankedRows) {
            AIRanking::query()->where('student_id', $student->id)->delete();

            foreach ($rankedRows as $index => $row) {
                AIRanking::query()->create([
                    'student_id' => $student->id,
                    'department_id' => $row['department']->id,
                    'rank' => $index + 1,
                    'result_rank' => null,
                ]);
            }
        });

        $selectedSystemNames = $systems
            ->whereIn('id', $selectedSystemIds->all())
            ->pluck('name')
            ->values()
            ->all();

        $summary = [
            'province_scope' => $validated['province_scope'],
            'selected_system_ids' => $selectedSystemIds->all(),
            'selected_system_names' => $selectedSystemNames,
            'mark_bonus' => $markBonus,
            'mark_with_bonus' => $markWithBonus,
            'count' => $rankedRows->count(),
            'max' => 50,
        ];

        return view('website.web.student.ai.preferences', [
            'student' => $student,
            'systems' => $systems,
            'allowedSystemIdsByYear' => $allowedSystemIdsByYear,
            'defaultSystemIds' => $selectedSystemIds->all(),
            'rankedRows' => $rankedRows,
            'summary' => $summary,
        ])->with('success', 'ڕێزبەندی ئۆتۆماتیکی AI بە سەرکەوتوویی ئەنجامدرا.');
    }

    private function buildRankings($student, string $provinceScope, Collection $selectedSystemIds): array
    {
        $studentMark = (float) ($student->mark ?? 0);
        $markBonus = $this->markBonus($studentMark);
        $markWithBonus = $studentMark + $markBonus;
        $studentProvinceId = (int) ($student->province_id ?? 0);

        $query = Department::query()
            ->visibleForSelection()
            ->whereIn('system_id', $selectedSystemIds->all())
            ->with(['system', 'province', 'university', 'college']);

        if ($provinceScope === 'local_only') {
            if ($studentProvinceId <= 0) {
                return [collect(), $markBonus, $markWithBonus];
            }

            $query->where('province_id', $studentProvinceId);
        }

        if ((string) $student->type === 'زانستی') {
            $query->whereIn('type', ['زانستی', 'زانستی و وێژەیی']);
        } else {
            $query->whereIn('type', ['وێژەیی', 'زانستی و وێژەیی']);
        }

        if ((string) $student->gender === 'مێ') {
            $query->whereIn('sex', ['مێ', 'نێر', 'هەردوو', 'هەردووکیان', 'both', 'Both']);
        } else {
            $query->where('sex', 'نێر');
        }

        $rankedRows = $query->get()
            ->map(function (Department $department) use ($studentProvinceId, $studentMark, $markWithBonus) {
                $isLocal = (int) $department->province_id === $studentProvinceId;
                $requiredScore = $isLocal
                    ? (float) $department->local_score
                    : (float) $department->external_score;

                return [
                    'department' => $department,
                    'required_score' => $requiredScore,
                    'score_type' => $isLocal ? 'local_score' : 'external_score',
                    'is_local' => $isLocal,
                    'gap_from_mark' => round($studentMark - $requiredScore, 3),
                    'eligible' => $requiredScore <= $markWithBonus,
                ];
            })
            ->filter(fn(array $row) => $row['eligible'])
            ->sortByDesc('required_score')
            ->values()
            ->take(50)
            ->map(function (array $row, int $index) {
                $row['rank'] = $index + 1;
                return $row;
            });

        return [$rankedRows, $markBonus, $markWithBonus];
    }

    private function allowedSystemIdsByYear(int $year): array
    {
        if ($year === 1) {
            return System::query()
                ->where('status', 1)
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->values()
                ->all();
        }

        return System::query()
            ->where('status', 1)
            ->whereIn('id', [2, 3])
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }

    private function markBonus(float $mark): float
    {
        if ($mark >= 90) {
            return 2.0;
        }

        if ($mark >= 80) {
            return 3.0;
        }

        if ($mark >= 70) {
            return 3.5;
        }

        if ($mark >= 60) {
            return 4.0;
        }

        return 4.0;
    }
}
