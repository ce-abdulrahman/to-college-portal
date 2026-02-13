<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\RequestMoreDepartments;
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
        
        return view('website.web.teacher.features.request', compact('teacher', 'existingRequest'));
    }
    
    /**
     * Submit a feature request
     */
    public function submitRequest(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
            'request_types' => 'required|array|min:1',
            'request_types.*' => 'in:all_departments,ai_rank,gis',
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
            'request_all_departments' => in_array('all_departments', $request->request_types),
            'request_ai_rank' => in_array('ai_rank', $request->request_types),
            'request_gis' => in_array('gis', $request->request_types),
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
