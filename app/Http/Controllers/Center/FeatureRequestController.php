<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Models\RequestMoreDepartments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        // Check for existing request
        $existingRequest = RequestMoreDepartments::where('center_id', $center->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        return view('website.web.center.features.request', compact('center', 'existingRequest'));
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
        
        // Create new request
        RequestMoreDepartments::create([
            'center_id' => $center->id,
            'user_id' => $user->id,
            'user_type' => 'center',
            'request_all_departments' => in_array('all_departments', $request->request_types),
            'request_ai_rank' => in_array('ai_rank', $request->request_types),
            'request_gis' => in_array('gis', $request->request_types),
            'reason' => $request->reason,
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
