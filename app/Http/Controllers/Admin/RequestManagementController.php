<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestMoreDepartments;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * لیستی هەموو داواکاریەکان
     */
    public function index(Request $request)
    {
        $query = RequestMoreDepartments::with(['student.user', 'admin'])
            ->orderBy('created_at', 'desc');

        // فیلتەر بەپێی بار
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // گەڕان بەپێی ناوی قوتابی
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(20);

        $stats = [
            'total' => RequestMoreDepartments::count(),
            'pending' => RequestMoreDepartments::where('status', 'pending')->count(),
            'approved' => RequestMoreDepartments::where('status', 'approved')->count(),
            'rejected' => RequestMoreDepartments::where('status', 'rejected')->count(),
        ];

        return view('website.web.admin.requests.index', compact('requests', 'stats'));
    }

    /**
     * بینینی وردەکاری داواکاری
     */
    public function show($id)
    {
        $request = RequestMoreDepartments::with(['student.user', 'student', 'admin'])
            ->findOrFail($id);

        $student = $request->student;
        $selectedCount = $student->resultDeps()->count();

        return view('website.web.admin.requests.show', compact('request', 'student', 'selectedCount'));
    }

    // app/Http\Controllers\Admin\RequestManagementController.php
public function approve(Request $request, $id)
{
    $adminRequest = RequestMoreDepartments::with('student')->findOrFail($id);

    if ($adminRequest->status !== 'pending') {
        return redirect()->back()->with('error', 'تەنها داواکاریەکانی چاوەڕوان دەتوانن پەسەند بکرێن.');
    }

    $validated = $request->validate([
        'notes' => 'nullable|string|max:500',
        'approve_types' => 'required|array|min:1',
        'approve_types.*' => 'in:all_departments,ai_rank,gis',
    ]);

    DB::beginTransaction();
    try {
        // نوێکردنەوەی داواکاری
        $adminRequest->update([
            'status' => 'approved',
            'admin_id' => auth()->id(),
            'admin_notes' => $validated['notes'] ?? null,
            'approved_at' => now(),
        ]);

        // چالاککردنی جۆرە پەسەندکراوەکان
        $student = $adminRequest->student;
        $updates = [];
        
        if (in_array('all_departments', $validated['approve_types']) && $student->all_departments == 0) {
            $updates['all_departments'] = 1;
        }
        
        if (in_array('ai_rank', $validated['approve_types']) && $student->ai_rank == 0) {
            $updates['ai_rank'] = 1;
        }
        
        if (in_array('gis', $validated['approve_types']) && $student->gis == 0) {
            $updates['gis'] = 1;
        }
        
        if (!empty($updates)) {
            $student->update($updates);
        }

        DB::commit();

        $approvedTypes = array_map(function($type) {
            return $type == 'all_departments' ? '٥٠ بەش' : 
                   ($type == 'ai_rank' ? 'سیستەمی AI' : 'سیستەمی نەخشە');
        }, $validated['approve_types']);

        return redirect()->route('admin.requests.index')
            ->with('success', 'داواکاریەکە بە سەرکەوتوویی پەسەند کرا. جۆرەکان: ' . implode('، ', $approvedTypes));
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'هەڵەیەک ڕوویدا: ' . $e->getMessage());
    }
}

    /**
     * ڕەتکردنەوەی داواکاری
     */
    public function reject(Request $request, $id)
    {
        $adminRequest = RequestMoreDepartments::findOrFail($id);

        if ($adminRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'تەنها داواکاریەکانی چاوەڕوان دەتوانن ڕەت بکرێنەوە.');
        }

        $validated = $request->validate([
            'notes' => 'required|string|min:10|max:500',
        ]);

        $userId = Auth::user()->id;

        $adminRequest->update([
            'status' => 'rejected',
            'admin_id' => $userId,
            'admin_notes' => $validated['notes'],
        ]);

        return redirect()->route('admin.requests.index')
            ->with('success', 'داواکاریەکە بە سەرکەوتوویی ڕەتکرایەوە.');
    }

    /**
     * سڕینەوەی داواکاری
     */
    public function destroy($id)
    {
        $request = RequestMoreDepartments::findOrFail($id);
        
        // تەنها دەتوانرێت سڕێنرێتەوە ئەگەر پەسەند یان ڕەت نەکرابێتەوە
        if ($request->status === 'approved' || $request->status === 'rejected') {
            return redirect()->back()->with('error', 'ناتوانرێت داواکاریەکە بسڕێتەوە چونکە پێشتر چارەسەر کراوە.');
        }

        $request->delete();

        return redirect()->route('admin.requests.index')
            ->with('success', 'داواکاریەکە بە سەرکەوتوویی سڕدرایەوە.');
    }

    /**
     * نوێکردنەوەی ئامارەکان بۆ AJAX
     */
    public function stats()
    {
        $stats = [
            'total' => RequestMoreDepartments::count(),
            'pending' => RequestMoreDepartments::where('status', 'pending')->count(),
            'approved' => RequestMoreDepartments::where('status', 'approved')->count(),
            'rejected' => RequestMoreDepartments::where('status', 'rejected')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * دۆزینەوەی داواکاریەکان بۆ AJAX
     */
    public function search(Request $request)
    {
        $query = RequestMoreDepartments::with(['student.user', 'admin'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(10);

        return response()->json([
            'html' => view('website.web.admin.requests.partials.requests-table', compact('requests'))->render(),
            'pagination' => $requests->links()->toHtml()
        ]);
    }
}