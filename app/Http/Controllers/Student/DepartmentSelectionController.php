<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\RequestMoreDepartments;
use App\Models\ResultDep;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepartmentSelectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'student']);
    }

    /**
     * نمایشی پەیجی هەڵبژاردنی بەش
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }

        // بەشە هەڵبژێردراوەکانی ئێستا
        $selectedDepartments = ResultDep::where('student_id', $student->id)
            ->with(['department' => function($query) {
                $query->with('university');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // هەموو بەشەکانی گونجاو بۆ قوتابی (بەپێی تیپ و نمرە و جێندەر)
        $availableDepartments = Department::where('status', 1)
            ->where(function($query) use ($student) {
                $query->where('type', $student->type)
                      ->orWhere('type', 'زانستی و وێژەیی');
            })
            ->where(function($query) use ($student) {
                $query->where('sex', $student->gender)
                      ->orWhere('sex', 'هەردووکیان');
            })
            ->where('local_score', '<=', $student->mark)
            ->with('university')
            ->orderBy('local_score', 'desc')
            ->paginate(20);

        // سنووری هەڵبژاردن
        $maxSelections = $student->all_departments == 1 ? 50 : 20;
        $currentCount = $selectedDepartments->count();

        return view('website.web.student.departments.selection', compact(
            'student',
            'selectedDepartments',
            'availableDepartments',
            'maxSelections',
            'currentCount'
        ));
    }

    /**
     * زیادکردنی بەشێک
     */
    public function addDepartment(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'زانیاریەکانی قوتابی تۆمار نەکراوە.'
            ], 400);
        }

        // سنووری هەڵبژاردن
        $maxSelections = $student->all_departments == 1 ? 50 : 20;
        $currentCount = ResultDep::where('student_id', $student->id)->count();

        if ($currentCount >= $maxSelections) {
            return response()->json([
                'success' => false,
                'message' => 'تۆ گەیشتویتە بە سنووری هەڵبژاردنەکان (' . $maxSelections . ' بەش).'
            ], 400);
        }

        // دڵنیایی لەوەی پێشتر هەڵی نەبژاردووە
        $existing = ResultDep::where('student_id', $student->id)
            ->where('department_id', $request->department_id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە پێشتر هەڵتبژاردووە.'
            ], 400);
        }

        // دڵنیایی لە گونجاوبوونی بەش
        $department = Department::with('university')->findOrFail($request->department_id);
        
        // پشکنینی تیپ
        if (!in_array($department->type, [$student->type, 'زانستی و وێژەیی'])) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە گونجاو نییە بۆ تیپەکەت.'
            ], 400);
        }

        // پشکنینی جێندەر
        if (!in_array($department->sex, [$student->gender, 'هەردووکیان'])) {
            return response()->json([
                'success' => false,
                'message' => 'ئەم بەشە گونجاو نییە بۆ جێندەرەکەت.'
            ], 400);
        }

        // پشکنینی نمرە
        if ($department->local_score > $student->mark) {
            return response()->json([
                'success' => false,
                'message' => 'نمرەکەت پێویست نییە بۆ ئەم بەشە. پێویستە ' . $department->local_score . ' نمرە.'
            ], 400);
        }

        // دروستکردنی تۆمارەکە
        DB::beginTransaction();
        try {
            $resultDep = ResultDep::create([
                'user_id' => $user->id,
                'student_id' => $student->id,
                'department_id' => $request->department_id,
                'status' => 'pending',
                'system_id' => System::where('status', 1)->first()->id ?? 1,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'بەشەکە بە سەرکەوتوویی زیاد کرا.',
                'data' => [
                    'id' => $resultDep->id,
                    'department_name' => $department->name, // name_ku یە
                    'local_mark' => $department->local_score,
                    'university_name' => $department->university->name ?? 'نەناسراو',
                    'created_at' => $resultDep->created_at->format('Y/m/d H:i'),
                    'remaining' => $maxSelections - ($currentCount + 1)
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'هەڵەیەک ڕوویدا: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * سڕینەوەی بەشێک
     */
    public function removeDepartment(Request $request, $id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'زانیاریەکانی قوتابی تۆمار نەکراوە.'
            ], 400);
        }

        $resultDep = ResultDep::where('student_id', $student->id)
            ->where('id', $id)
            ->first();

        if (!$resultDep) {
            return response()->json([
                'success' => false,
                'message' => 'تۆمارەکە نەدۆزرایەوە.'
            ], 404);
        }

        $departmentName = $resultDep->department->name_ku;
        $resultDep->delete();

        // ئەندازەی ماوە
        $maxSelections = $student->all_departments == 1 ? 50 : 20;
        $currentCount = ResultDep::where('student_id', $student->id)->count();

        return response()->json([
            'success' => true,
            'message' => 'بەشەکە (' . $departmentName . ') سڕدرایەوە.',
            'data' => [
                'remaining' => $maxSelections - $currentCount
            ]
        ]);
    }

    /**
     * پێداچوونەوەی لیستی بەشە هەڵبژێردراوەکان
     */
    public function getSelectedDepartments()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'زانیاریەکانی قوتابی تۆمار نەکراوە.'
            ], 400);
        }

        $selectedDepartments = ResultDep::where('student_id', $student->id)
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'department_name' => $item->department->name_ku,
                    'local_mark' => $item->department->local_mark,
                    'created_at' => $item->created_at->format('Y/m/d H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $selectedDepartments,
            'count' => $selectedDepartments->count(),
            'max' => $student->all_departments == 1 ? 50 : 20
        ]);
    }

    public function showRequestForm()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('student.departments.selection')
                ->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }
        
        // پشکنین ئەگەر پێشتر داواکاری هەبێت
        $existingRequest = RequestMoreDepartments::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        return view('website.web.student.departments.request-more', compact('student', 'existingRequest'));
    }
    
    public function submitRequest(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
            'request_types' => 'required|array|min:1',
            'request_types.*' => 'in:all_departments,ai_rank,gis',
        ]);
        
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->back()->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }
        
        // پشکنین ئەگەر پێشتر داواکاری هەبێت
        $existingRequest = RequestMoreDepartments::where('student_id', $student->id)
            ->where('status', 'pending')
            ->first();
        
        if ($existingRequest) {
            return redirect()->back()->with('error', 'تۆ پێشتر داواکاریت ناردووە و چاوەڕوانی پەسەندکردنێ.');
        }
        
        // دروستکردنی داواکاری نوێ
        RequestMoreDepartments::create([
            'student_id' => $student->id,
            'user_id' => $user->id,
            'request_all_departments' => in_array('all_departments', $request->request_types),
            'request_ai_rank' => in_array('ai_rank', $request->request_types),
            'request_gis' => in_array('gis', $request->request_types),
            'reason' => $request->reason,
            'status' => 'pending',
        ]);
        
        return redirect()->route('student.departments.selection')
            ->with('success', 'داواکاریەکەت بە سەرکەوتوویی نێردرا! چاوەڕوانی وەڵامی بەڕێوەبەر بە.');
    }
    
    /**
     * سڕینەوەی داواکاری
     */
    public function cancelRequest($id)
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->back()->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }
        
        $request = RequestMoreDepartments::where('student_id', $student->id)
            ->where('id', $id)
            ->where('status', 'pending')
            ->first();
        
        if (!$request) {
            return redirect()->back()->with('error', 'داواکاریەکە نەدۆزرایەوە یان ناتوانرێت سڕێنرێتەوە.');
        }
        
        $request->delete();
        
        return redirect()->route('student.departments.selection')
            ->with('success', 'داواکاریەکەت سڕدرایەوە.');
    }
    
    /**
     * بینینی مێژووی داواکاریەکان
     */
    public function requestHistory()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('student.departments.selection')
                ->with('error', 'زانیاریەکانی قوتابی تۆمار نەکراوە.');
        }
        
        $requests = RequestMoreDepartments::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('website.web.student.departments.request-history', compact('student', 'requests'));
    }
}