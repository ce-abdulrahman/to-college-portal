{{-- resources/views/admin/requests/show.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('title', 'وردەکاری داواکاری')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- کارتی سەرەکی -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-file-alt me-2"></i>وردەکاری داواکاری
                    </h6>
                    <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>گەڕانەوە
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- زانیاری قوتابی -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user-graduate me-2"></i>زانیاریەکانی قوتابی
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">ناو:</th>
                                    <td>{{ $student->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>کۆد:</th>
                                    <td>{{ $student->user->code }}</td>
                                </tr>
                                <tr>
                                    <th>نمرە:</th>
                                    <td>
                                        <span class="badge bg-primary">{{ $student->mark }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>لق:</th>
                                    <td>{{ $student->type }}</td>
                                </tr>
                                <tr>
                                    <th>بەشە هەڵبژێردراوەکان:</th>
                                    <td>
                                        <span class="badge bg-{{ $selectedCount >= 20 ? 'warning' : 'info' }}">
                                            {{ $selectedCount }} بەش
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>جۆری هەڵبژاردن:</th>
                                    <td>
                                        @if($student->all_departments == 1)
                                        <span class="badge bg-success">٥٠ بەش</span>
                                        @else
                                        <span class="badge bg-warning">٢٠ بەش</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>زانیاریەکانی داواکاری
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">کات:</th>
                                    <td>{{ $request->created_at->format('Y/m/d - H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>بەشەکان:</th>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $request->current_max }} → {{ $request->requested_max }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>بار:</th>
                                    <td>
                                        @if($request->status == 'pending')
                                        <span class="badge bg-warning">چاوەڕوان</span>
                                        @elseif($request->status == 'approved')
                                        <span class="badge bg-success">پەسەندکراو</span>
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                {{ $request->approved_at->format('Y/m/d - H:i') }}
                                            </small>
                                        </div>
                                        @else
                                        <span class="badge bg-danger">ڕەتکراوە</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($request->admin)
                                <tr>
                                    <th>بەڕێوەبەر:</th>
                                    <td>{{ $request->admin->name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- هۆکار -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-comment-dots me-2"></i>هۆکاری داواکاری
                        </h6>
                        <div class="p-3 bg-light rounded">
                            {{ $request->reason }}
                        </div>
                    </div>

                    <!-- تێبینی بەڕێوەبەر -->
                    @if($request->admin_notes)
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-sticky-note me-2"></i>تێبینی بەڕێوەبەر
                        </h6>
                        <div class="p-3 bg-light rounded">
                            {{ $request->admin_notes }}
                        </div>
                    </div>
                    @endif

                    <!-- کردارەکان -->
                    @if($request->status == 'pending')
                    <div class="border-top pt-4 mt-4">
                        <h6 class="mb-3">چارەسەرکردنی داواکاری:</h6>
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.requests.approve', $request->id) }}" class="d-inline">
                                @csrf
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="notes" 
                                           placeholder="تێبینی (ئارەزوومەند)">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-1"></i>پەسەندکردن
                                    </button>
                                </div>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.requests.reject', $request->id) }}" class="d-inline">
                                @csrf
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="notes" 
                                           placeholder="هۆکاری ڕەتکردنەوە" required>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times me-1"></i>ڕەتکردنەوە
                                    </button>
                                </div>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.requests.destroy', $request->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" 
                                        onclick="return confirm('دڵنیای لە سڕینەوەی ئەم داواکاریە؟')">
                                    <i class="fas fa-trash me-1"></i>سڕینەوە
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- کارتی لایەنی -->
        <div class="col-lg-4">
            <!-- کارتی پەسەندکردن -->
            <div class="card shadow border-start border-success border-5 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-success">
                        <i class="fas fa-check-circle me-2"></i>پاش پەسەندکردن
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            قوتابی دەتوانێت ٥٠ بەش هەڵبژێرێت
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            خانەی <code>all_departments</code> دەکرێت بە ١
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            قوتابی ئاگادار دەکرێتەوە
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            داواکاری دەچێتە مێژووەوە
                        </li>
                    </ul>
                    
                    @if($request->status == 'pending')
                    <div class="mt-3 p-3 bg-success bg-opacity-10 rounded">
                        <small class="text-success">
                            <i class="fas fa-lightbulb me-1"></i>
                            پێش پەسەندکردن دڵنیابە لەوەی قوتابی بەڕاستی پێویستی بە بەشی زیاتر هەیە.
                        </small>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- کارتی چالاکیەکان -->
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-info">
                        <i class="fas fa-history me-2"></i>مێژووی چالاکیەکان
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">داواکاری دروست کرا</h6>
                                <p class="text-muted mb-0">{{ $request->created_at->format('Y/m/d - H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($request->status != 'pending')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $request->status == 'approved' ? 'success' : 'danger' }}"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">
                                    {{ $request->status == 'approved' ? 'پەسەندکرا' : 'ڕەتکرایەوە' }}
                                </h6>
                                <p class="text-muted mb-0">
                                    {{ $request->updated_at->format('Y/m/d - H:i') }}
                                    @if($request->admin)
                                    <br>بەڕێوەبەر: {{ $request->admin->name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
.timeline-content {
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.timeline-content:last-child {
    border-bottom: none;
}
</style>
@endpush