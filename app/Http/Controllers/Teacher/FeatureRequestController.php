<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\RequestMoreDepartments;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FeatureRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'teacher']);
    }

    /**
     * Show the feature request form
     */
    public function showRequestForm()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'زانیاریەکانی مامۆستا تۆمار نەکراوە.');
        }
        
        // Check for existing pending request
        $existingRequest = RequestMoreDepartments::where('teacher_id', $teacher->id)
            ->where('status', 'pending')
            ->first();

        $currentStudentsCount = Student::where('referral_code', $user->rand_code)->count();

        return view('website.web.teacher.features.request', compact(
            'teacher',
            'existingRequest',
            'currentStudentsCount'
        ));
    }
    
    /**
     * Submit a feature request
     */
    public function submitRequest(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
            'request_types' => 'nullable|array',
            'request_types.*' => 'in:all_departments,ai_rank,gis,queue_hand_department',
            'request_limit_student' => 'nullable|integer|min:0',
            'receipt_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->back()->with('error', 'زانیاریەکانی مامۆستا تۆمار نەکراوە.');
        }
        
        // Check for existing pending request
        $existingRequest = RequestMoreDepartments::where('teacher_id', $teacher->id)
            ->where('status', 'pending')
            ->first();
        
        if ($existingRequest) {
            return redirect()->back()->with('error', 'تۆ پێشتر داواکاریت ناردووە و چاوەڕوانی پەسەندکردنێ.');
        }

        $requestTypes = $request->input('request_types', []);
        $requestLimitStudent = max(0, (int) $request->input('request_limit_student', 0));

        if (empty($requestTypes) && $requestLimitStudent === 0) {
            return redirect()->back()
                ->withErrors(['request_types' => 'پێویستە لانیکەم یەک تایبەتمەندی یان زیادکردنی سنوور دیاری بکەیت.'])
                ->withInput();
        }

        $receiptPath = null;
        if ($request->hasFile('receipt_image')) {
            $file = $request->file('receipt_image');
            $uploadDir = public_path('uploads/request');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = 'request_' . $user->id . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $receiptPath = 'uploads/request/' . $fileName;
        }
        
        // Create new request
        RequestMoreDepartments::create([
            'teacher_id' => $teacher->id,
            'user_id' => $user->id,
            'user_type' => 'teacher',
            'request_all_departments' => in_array('all_departments', $requestTypes),
            'request_ai_rank' => in_array('ai_rank', $requestTypes),
            'request_gis' => in_array('gis', $requestTypes),
            'request_queue_hand_department' => in_array('queue_hand_department', $requestTypes),
            'request_limit_teacher' => 0,
            'request_limit_student' => $requestLimitStudent,
            'approved_limit_teacher' => 0,
            'approved_limit_student' => 0,
            'reason' => $request->reason,
            'receipt_image' => $receiptPath,
            'status' => 'pending',
        ]);
        
        return redirect()->route('teacher.dashboard')
            ->with('success', 'داواکاریەکەت بە سەرکەوتوویی نێردرا! چاوەڕوانی وەڵامی بەڕێوەبەر بە.');
    }
    
    /**
     * Cancel a pending request
     */
    public function cancelRequest($id)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->back()->with('error', 'زانیاریەکانی مامۆستا تۆمار نەکراوە.');
        }
        
        $request = RequestMoreDepartments::where('teacher_id', $teacher->id)
            ->where('id', $id)
            ->where('status', 'pending')
            ->first();
        
        if (!$request) {
            return redirect()->back()->with('error', 'داواکاریەکە نەدۆزرایەوە یان ناتوانرێت سڕێنرێتەوە.');
        }
        
        $request->delete();
        
        return redirect()->route('teacher.features.request')
            ->with('success', 'داواکاریەکەت سڕدرایەوە.');
    }
    
    /**
     * View request history
     */
    public function requestHistory()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'زانیاریەکانی مامۆستا تۆمار نەکراوە.');
        }
        
        $requests = RequestMoreDepartments::where('teacher_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('website.web.teacher.features.request-history', compact('teacher', 'requests'));
    }
}
