<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestMoreDepartments;
use App\Models\Student;
use App\Models\Teacher;
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
        $query = RequestMoreDepartments::with(['user', 'admin', 'student', 'teacher', 'center'])
            ->orderBy('created_at', 'desc');

        // فیلتەر بەپێی بار
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // گەڕان بەپێی ناوی بەکارهێنەر (قوتابی، مامۆستا یان سەنتەر)
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
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
        $request = RequestMoreDepartments::with(['user', 'student', 'teacher', 'center', 'admin'])
            ->findOrFail($id);

        $requester = null;
        $selectedCount = 0;

        if ($request->user_type == 'student') {
            $requester = $request->student;
            $selectedCount = $requester ? $requester->resultDeps()->count() : 0;
        } elseif ($request->user_type == 'teacher') {
            $requester = $request->teacher;
        } elseif ($request->user_type == 'center') {
            $requester = $request->center;
        }

        return view('website.web.admin.requests.show', compact('request', 'requester', 'selectedCount'));
    }

    // app/Http\Controllers/Admin/RequestManagementController.php
    public function approve(Request $request, $id)
    {
    $adminRequest = RequestMoreDepartments::with(['student', 'teacher', 'center', 'user'])->findOrFail($id);

    if ($adminRequest->status !== 'pending') {
        return redirect()->back()->with('error', 'تەنها داواکاریەکانی چاوەڕوان دەتوانن پەسەند بکرێن.');
    }

    $validated = $request->validate([
        'notes' => 'nullable|string|max:500',
        'approve_types' => 'nullable|array',
        'approve_types.*' => 'in:all_departments,ai_rank,gis,queue_hand_department',
        'approve_limit_teacher' => 'nullable|integer|min:0',
        'approve_limit_student' => 'nullable|integer|min:0',
    ]);

    $approveTypes = $validated['approve_types'] ?? [];
    $approveLimitTeacher = (int) ($validated['approve_limit_teacher'] ?? 0);
    $approveLimitStudent = (int) ($validated['approve_limit_student'] ?? 0);

    if ($adminRequest->user_type !== 'center' || (int) $adminRequest->request_limit_teacher <= 0) {
        $approveLimitTeacher = 0;
    }
    if ((int) $adminRequest->request_limit_student <= 0) {
        $approveLimitStudent = 0;
    }

    if (empty($approveTypes) && $approveLimitTeacher === 0 && $approveLimitStudent === 0) {
        return redirect()->back()
            ->withErrors(['approve_types' => 'پێویستە لانیکەم یەک جۆری feature یان زیادکردنی سنوور پەسەند بکەیت.'])
            ->withInput();
    }

    $userID = auth()->user()->id;
    DB::beginTransaction();
    try {
        // نوێکردنەوەی داواکاری
        $adminRequest->update([
            'status' => 'approved',
            'admin_id' => $userID,
            'admin_notes' => $validated['notes'] ?? null,
            'approved_at' => now(),
            'approved_limit_teacher' => $approveLimitTeacher,
            'approved_limit_student' => $approveLimitStudent,
        ]);

        // چالاککردنی جۆرە پەسەندکراوەکان
        
        $targetModel = null;
        
        if ($adminRequest->user_type == 'student') {
            $targetModel = $adminRequest->student;
        } elseif ($adminRequest->user_type == 'teacher') {
            $targetModel = $adminRequest->teacher;
        } elseif ($adminRequest->user_type == 'center') {
            $targetModel = $adminRequest->center;
        }

        if ($targetModel) {
            $featureUpdates = [];
            $limitUpdates = [];
            
            if (in_array('all_departments', $approveTypes) && $targetModel->all_departments == 0) {
                $featureUpdates['all_departments'] = 1;
            }
            
            if (in_array('ai_rank', $approveTypes) && $targetModel->ai_rank == 0) {
                $featureUpdates['ai_rank'] = 1;
            }
            
            if (in_array('gis', $approveTypes) && $targetModel->gis == 0) {
                $featureUpdates['gis'] = 1;
            }

            if (
                in_array('queue_hand_department', $approveTypes)
                && $adminRequest->user_type !== 'student'
                && (int) ($targetModel->queue_hand_department ?? 0) === 0
            ) {
                $featureUpdates['queue_hand_department'] = 1;
            }

            if ($adminRequest->user_type === 'center' && $approveLimitTeacher > 0) {
                $currentCount = 0;
                if ($adminRequest->user && $adminRequest->user->rand_code) {
                    $currentCount = Teacher::where('referral_code', $adminRequest->user->rand_code)->count();
                }
                $limitUpdates['limit_teacher'] = $this->resolveNextLimit(
                    $targetModel->limit_teacher ?? null,
                    $approveLimitTeacher,
                    $currentCount
                );
            }

            if ($approveLimitStudent > 0) {
                $currentCount = 0;
                if ($adminRequest->user && $adminRequest->user->rand_code) {
                    $currentCount = Student::where('referral_code', $adminRequest->user->rand_code)->count();
                }
                $limitUpdates['limit_student'] = $this->resolveNextLimit(
                    $targetModel->limit_student ?? null,
                    $approveLimitStudent,
                    $currentCount
                );
            }

            $targetUpdates = array_merge($featureUpdates, $limitUpdates);
            
            if (!empty($targetUpdates)) {
                $targetModel->update($targetUpdates);

                // --- Propagation Logic ---
                if ($adminRequest->user_type == 'center') {
                    $centerUser = $adminRequest->user;
                    if ($centerUser && $centerUser->rand_code) {
                        $centerRandCode = $centerUser->rand_code;

                        // 1. Update all Teachers referred directly by this center
                        if (!empty($featureUpdates)) {
                            Teacher::where('referral_code', $centerRandCode)->update($featureUpdates);
                        }

                        $studentFeatureUpdates = $featureUpdates;
                        unset($studentFeatureUpdates['queue_hand_department']);

                        // 2. Update all Students referred directly by this center
                        if (!empty($studentFeatureUpdates)) {
                            Student::where('referral_code', $centerRandCode)->update($studentFeatureUpdates);
                        }

                        // 3. Update all Students referred by Teachers who belong to this center
                        $teacherRandCodes = User::where('role', 'teacher')
                            ->whereIn('id', function($query) use ($centerRandCode) {
                                $query->select('user_id')->from('teachers')->where('referral_code', $centerRandCode);
                            })
                            ->pluck('rand_code')
                            ->filter();

                        if ($teacherRandCodes->isNotEmpty()) {
                            if (!empty($studentFeatureUpdates)) {
                                Student::whereIn('referral_code', $teacherRandCodes)->update($studentFeatureUpdates);
                            }
                        }
                    }
                } elseif ($adminRequest->user_type == 'teacher') {
                    $teacherUser = $adminRequest->user;
                    if ($teacherUser && $teacherUser->rand_code) {
                        $studentFeatureUpdates = $featureUpdates;
                        unset($studentFeatureUpdates['queue_hand_department']);

                        // Update all Students referred by this teacher
                        if (!empty($studentFeatureUpdates)) {
                            Student::where('referral_code', $teacherUser->rand_code)->update($studentFeatureUpdates);
                        }
                    }
                }
            }
        }

        DB::commit();

        $approvedTypeLabels = [
            'all_departments' => '٥٠ بەش',
            'ai_rank' => 'سیستەمی AI',
            'gis' => 'سیستەمی نەخشە',
            'queue_hand_department' => 'ڕیزبەندی بەشەکان',
        ];
        $approvedTypes = array_map(function ($type) use ($approvedTypeLabels) {
            return $approvedTypeLabels[$type] ?? $type;
        }, $approveTypes);

        if ($approveLimitTeacher > 0) {
            $approvedTypes[] = 'زیادکردنی سنووری مامۆستا +' . $approveLimitTeacher;
        }
        if ($approveLimitStudent > 0) {
            $approvedTypes[] = 'زیادکردنی سنووری قوتابی +' . $approveLimitStudent;
        }

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
        $query = RequestMoreDepartments::with(['user', 'admin', 'student', 'teacher', 'center'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
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

    private function resolveNextLimit($currentLimit, int $increment, int $currentCount): int
    {
        $base = is_null($currentLimit) ? $currentCount : max((int) $currentLimit, $currentCount);
        return $base + max(0, $increment);
    }
}
