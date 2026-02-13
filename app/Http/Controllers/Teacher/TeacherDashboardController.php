<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TeacherDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $teacher = $user->teacher;
        
        // Check if teacher has GIS feature enabled
        $hasGIS = $teacher && $teacher->gis == 1;
        
        // Show GIS map dashboard if enabled, otherwise show simple dashboard
        if ($hasGIS) {
            return view('website.web.teacher.dashboard-gis');
        } else {
            return view('website.web.teacher.dashboard-simple');
        }
    }

    public function departments(Request $request)
    {
        $limit = (int) $request->input('limit', 25);
        if (!in_array($limit, [10, 25, 50, 100], true)) {
            $limit = 25;
        }

        $systemId = $request->input('system_id');
        $provinceId = $request->input('province_id');
        $universityId = $request->input('university_id');
        $collegeId = $request->input('college_id');
        $search = trim((string) $request->input('search', ''));

        $query = Department::with(['system', 'province', 'university', 'college'])
            ->where('status', 1);

        if ($systemId) {
            $query->where('system_id', $systemId);
        }

        if ($provinceId) {
            $query->where('province_id', $provinceId);
        }

        if ($universityId) {
            $query->where('university_id', $universityId);
        }

        if ($collegeId) {
            $query->where('college_id', $collegeId);
        }

        if ($search !== '') {
            $driver = DB::getDriverName();
            $boolean = collect(preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY))
                ->map(function ($term) {
                    $term = preg_replace('/[^\p{L}\p{N}_]+/u', '', $term);
                    return $term ? '+' . $term . '*' : null;
                })
                ->filter()
                ->implode(' ');

            $query->where(function ($sub) use ($search, $driver, $boolean) {
                if (in_array($driver, ['mysql', 'mariadb'], true) && $boolean !== '') {
                    $sub->whereRaw('MATCH(name, name_en) AGAINST (? IN BOOLEAN MODE)', [$boolean]);
                    $sub->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%");
                } else {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%");
                }

                $sub->orWhereHas('university', fn($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('college', fn($c) => $c->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('province', fn($p) => $p->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('system', fn($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        $departments = $query->latest()->paginate($limit)->withQueryString();

        $systems = Cache::remember('departments.systems', now()->addMinutes(10), function () {
            return System::where('status', 1)->get();
        });

        $provinces = Cache::remember('departments.provinces.system:' . ($systemId ?: 'all'), now()->addMinutes(10), function () use ($systemId) {
            return Province::where('status', 1)
                ->when($systemId, function ($q) use ($systemId) {
                    $q->whereIn('id', function ($sub) use ($systemId) {
                        $sub->select('province_id')
                            ->from('departments')
                            ->where('system_id', $systemId)
                            ->where('status', 1);
                    });
                })->get();
        });

        $universities = Cache::remember(
            'departments.universities.system:' . ($systemId ?: 'all') . '.province:' . ($provinceId ?: 'all'),
            now()->addMinutes(10),
            function () use ($systemId, $provinceId) {
                return University::where('status', 1)
                    ->when($provinceId, fn($q) => $q->where('province_id', $provinceId))
                    ->when($systemId, function ($q) use ($systemId) {
                        $q->whereIn('id', function ($sub) use ($systemId) {
                            $sub->select('university_id')
                                ->from('departments')
                                ->where('system_id', $systemId)
                                ->where('status', 1);
                        });
                    })->get();
            }
        );

        $colleges = Cache::remember(
            'departments.colleges.system:' . ($systemId ?: 'all') . '.university:' . ($universityId ?: 'all'),
            now()->addMinutes(10),
            function () use ($systemId, $universityId) {
                return College::where('status', 1)
                    ->when($universityId, fn($q) => $q->where('university_id', $universityId))
                    ->when($systemId, function ($q) use ($systemId) {
                        $q->whereIn('id', function ($sub) use ($systemId) {
                            $sub->select('college_id')
                                ->from('departments')
                                ->where('system_id', $systemId)
                                ->where('status', 1);
                        });
                    })->get();
            }
        );

        return view('website.web.teacher.departments.index', compact(
            'departments',
            'systems',
            'provinces',
            'universities',
            'colleges'
        ));
    }

    public function show(string $id)
    {
        $department = Department::findOrFail($id);
        return view('website.web.teacher.departments.show', compact('department'));
    }

}
