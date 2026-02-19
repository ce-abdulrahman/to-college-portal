<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Models\RequestMoreDepartments;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FeatureRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'center']);
    }

    /**
     * Show the feature request form
     */
    public function showRequestForm()
    {
        $user = Auth::user();
        $center = $user->center;
        
        if (!$center) {
            return redirect()->route('center.dashboard')
                ->with('error', 'زانیاریەکانی سەنتەر تۆمار نەکراوە.');
        }
        
        // Check for existing pending request
        $existingRequest = RequestMoreDepartments::where('center_id', $center->id)
            ->where('status', 'pending')
            ->first();

        $currentTeachersCount = Teacher::where('referral_code', $user->rand_code)->count();
        $currentStudentsCount = Student::where('referral_code', $user->rand_code)->count();

        return view('website.web.center.features.request', compact(
            'center',
            'existingRequest',
            'currentTeachersCount',
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
            'request_limit_teacher' => 'nullable|integer|min:0',
            'request_limit_student' => 'nullable|integer|min:0',
            'receipt_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        
        $user = Auth::user();
        $center = $user->center;
        
        if (!$center) {
            return redirect()->back()->with('error', 'زانیاریەکانی سەنتەر تۆمار نەکراوە.');
        }
        
        // Check for existing pending request
        $existingRequest = RequestMoreDepartments::where('center_id', $center->id)
            ->where('status', 'pending')
            ->first();
        
        if ($existingRequest) {
            return redirect()->back()->with('error', 'تۆ پێشتر داواکاریت ناردووە و چاوەڕوانی پەسەندکردنێ.');
        }

        $requestTypes = $request->input('request_types', []);
        $requestLimitTeacher = max(0, (int) $request->input('request_limit_teacher', 0));
        $requestLimitStudent = max(0, (int) $request->input('request_limit_student', 0));

        if (empty($requestTypes) && $requestLimitTeacher === 0 && $requestLimitStudent === 0) {
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
            'center_id' => $center->id,
            'user_id' => $user->id,
            'user_type' => 'center',
            'request_all_departments' => in_array('all_departments', $requestTypes),
            'request_ai_rank' => in_array('ai_rank', $requestTypes),
            'request_gis' => in_array('gis', $requestTypes),
            'request_queue_hand_department' => in_array('queue_hand_department', $requestTypes),
            'request_limit_teacher' => $requestLimitTeacher,
            'request_limit_student' => $requestLimitStudent,
            'approved_limit_teacher' => 0,
            'approved_limit_student' => 0,
            'reason' => $request->reason,
            'receipt_image' => $receiptPath,
            'status' => 'pending',
        ]);
        
        return redirect()->route('center.dashboard')
            ->with('success', 'داواکاریەکەت بە سەرکەوتوویی نێردرا! چاوەڕوانی وەڵامی بەڕێوەبەر بە.');
    }
    
    /**
     * Cancel a pending request
     */
    public function cancelRequest($id)
    {
        $user = Auth::user();
        $center = $user->center;
        
        if (!$center) {
            return redirect()->back()->with('error', 'زانیاریەکانی سەنتەر تۆمار نەکراوە.');
        }
        
        $request = RequestMoreDepartments::where('center_id', $center->id)
            ->where('id', $id)
            ->where('status', 'pending')
            ->first();
        
        if (!$request) {
            return redirect()->back()->with('error', 'داواکاریەکە نەدۆزرایەوە یان ناتوانرێت سڕێنرێتەوە.');
        }
        
        $request->delete();
        
        return redirect()->route('center.features.request')
            ->with('success', 'داواکاریەکەت سڕدرایەوە.');
    }
    
    /**
     * View request history
     */
    public function requestHistory()
    {
        $user = Auth::user();
        $center = $user->center;
        
        if (!$center) {
            return redirect()->route('center.dashboard')
                ->with('error', 'زانیاریەکانی سەنتەر تۆمار نەکراوە.');
        }
        
        $requests = RequestMoreDepartments::where('center_id', $center->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('website.web.center.features.request-history', compact('center', 'requests'));
    }
}
