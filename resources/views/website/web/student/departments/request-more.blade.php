{{-- resources/views/student/departments/request-more.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('title', 'داواکردنی بەشی زیاتر')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-warning shadow-lg">
                <div class="card-header bg-warning text-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-envelope-open-text fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">داواکردنی مۆڵەتی بەشی زیاتر</h4>
                            <p class="mb-0">قوتابی: {{ $student->user->name }} | کۆد: {{ $student->user->code }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($existingRequest)
                        @if($existingRequest->isPending())
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading">داواکاری چاوەڕوانە!</h5>
                                    <p class="mb-0">تۆ پێشتر داواکاریت ناردووە و لە چاوەڕوانی پەسەندکردنێ.</p>
                                    <p class="mb-0 mt-2"><strong>کات:</strong> {{ $existingRequest->created_at->format('Y/m/d - H:i') }}</p>
                                    <p class="mb-0"><strong>هۆکار:</strong> {{ $existingRequest->reason }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <form method="POST" action="{{ route('student.departments.cancel-request', $existingRequest->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger me-2">
                                    <i class="fas fa-times me-1"></i>هەڵوەشاندنەوەی داواکاری
                                </button>
                            </form>
                            <a href="{{ route('student.departments.selection') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>گەڕانەوە
                            </a>
                        </div>
                        @elseif($existingRequest->isApproved())
                        <div class="alert alert-success">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading">داواکاری پەسەندکرا!</h5>
                                    <p class="mb-0">داواکاریەکەت پەسەند کراوە! ئێستا دەتوانی ٥٠ بەش هەڵبژێریت.</p>
                                    <p class="mb-0 mt-2"><strong>کاتی پەسەندکردن:</strong> {{ $existingRequest->approved_at->format('Y/m/d - H:i') }}</p>
                                    @if($existingRequest->admin_notes)
                                    <p class="mb-0"><strong>تێبینی بەڕێوەبەر:</strong> {{ $existingRequest->admin_notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="{{ route('student.departments.selection') }}" class="btn btn-success">
                                <i class="fas fa-university me-1"></i>هەڵبژاردنی بەشەکان
                            </a>
                        </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading">سەرنج بدە!</h5>
                                    <p class="mb-0">ئێستا تۆ دەتوانی تەنها <strong>{{ $student->all_departments == 1 ? '٥٠' : '٢٠' }}</strong> بەش هەڵبژێریت.</p>
                                    <p class="mb-0 mt-2">ئەگەر پێویستت بە هەڵبژاردنی بەشی زیاترە (٥٠ بەش)، تکایە داواکاریەکەت لێرە بنێرە.</p>
                                    <p class="mb-0">داواکاریەکەت دەنێردرێت بۆ بەڕێوەبەری سیستم و پاش چەند خولەکێک وەڵامی دەدرێتەوە.</p>
                                </div>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('student.departments.submit-request') }}" id="requestForm">
                            @csrf
                            
                            <div class="mb-4">
        <label class="form-label fw-bold">
            <i class="fas fa-list-check me-1"></i>جۆرەکانی داواکاری
        </label>
        <div class="alert alert-info mb-3">
            <i class="fas fa-info-circle me-2"></i>
            هەر جۆرێک کە پێویستت پێیەتی هەڵیبژێرە. بەڕێوەبەر هەر جۆرێک پەسەند بکات، بۆ تۆ چالاک دەکرێت.
        </div>
        
        <div class="row">
            <!-- مۆڵەتی ٥٠ بەش -->
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-{{ $student->all_departments == 1 ? 'success' : 'warning' }}">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="request_types[]" 
                                   value="all_departments"
                                   id="allDepartmentsCheck"
                                   {{ $student->all_departments == 1 ? 'disabled checked' : '' }}>
                            <label class="form-check-label fw-bold" for="allDepartmentsCheck">
                                <i class="fas fa-layer-group me-2"></i>مۆڵەتی ٥٠ بەش
                            </label>
                        </div>
                        <p class="card-text mt-2">
                            <small>
                                @if($student->all_departments == 1)
                                <span class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>پێشتر پەسەند کراوە
                                </span>
                                @else
                                مۆڵەتی هەڵبژاردنی ٥٠ بەش لەبری ٢٠ بەش
                                @endif
                            </small>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- سیستەمی AI -->
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-{{ $student->ai_rank == 1 ? 'success' : 'warning' }}">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="request_types[]" 
                                   value="ai_rank"
                                   id="aiRankCheck"
                                   {{ $student->ai_rank == 1 ? 'disabled checked' : '' }}>
                            <label class="form-check-label fw-bold" for="aiRankCheck">
                                <i class="fas fa-robot me-2"></i>سیستەمی AI
                            </label>
                        </div>
                        <p class="card-text mt-2">
                            <small>
                                @if($student->ai_rank == 1)
                                <span class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>پێشتر پەسەند کراوە
                                </span>
                                @else
                                ڕیزکردنی بەشەکان بەهۆی سیستەمی دەستکردی زیرەک
                                @endif
                            </small>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- سیستەمی نەخشە (GIS) -->
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-{{ $student->gis == 1 ? 'success' : 'warning' }}">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="request_types[]" 
                                   value="gis"
                                   id="gisCheck"
                                   {{ $student->gis == 1 ? 'disabled checked' : '' }}>
                            <label class="form-check-label fw-bold" for="gisCheck">
                                <i class="fas fa-map-marked-alt me-2"></i>سیستەمی نەخشە (GIS)
                            </label>
                        </div>
                        <p class="card-text mt-2">
                            <small>
                                @if($student->gis == 1)
                                <span class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>پێشتر پەسەند کراوە
                                </span>
                                @else
                                هەڵبژاردنی بەشەکان لەسەر نەخشە
                                @endif
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="requestTypesError" class="text-danger small mt-2 d-none">
            <i class="fas fa-exclamation-circle me-1"></i>کەمترین یەک جۆر هەڵبژێرە.
        </div>
    </div>
    
    <!-- هۆکار -->
    <div class="mb-4">
        <label for="reason" class="form-label fw-bold">
            <i class="fas fa-comment-dots me-1"></i>هۆکاری داواکاری
        </label>
        <textarea class="form-control @error('reason') is-invalid @enderror" 
                  id="reason" 
                  name="reason" 
                  rows="6" 
                  placeholder="هۆکاری داواکردنەکانت بنووسە...">{{ old('reason') }}</textarea>
        @error('reason')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">بە ڕوونی هۆکارەکانت بۆ هەر جۆرێک ڕوون بکەرەوە. کەمتر لە ٥٠٠ پیت.</div>
    </div>
                            
                            <div class="mb-4">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>نمونەی هۆکاری باش</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="mb-0">
                                            <li>پێویستم بە هەڵبژاردنی بەشە جیاجیاکان هەیە بۆ زیادکردنی ئەگەری قبوڵبوون</li>
                                            <li>پێشبینی دەکەم کە ئاستی نمرەکانم گونجاو بێت بۆ چەندین بەش</li>
                                            <li>ئارەزووم لە خوێندنی چەندین بەشە جیاوازەکانە</li>
                                            <li>پێویستم بە ئەگەری زیاترە بۆ قبوڵبوون لە زانکۆ</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-warning btn-lg px-5">
                                    <i class="fas fa-paper-plane me-2"></i>ناردنی داواکاری
                                </button>
                                <a href="{{ route('student.departments.selection') }}" class="btn btn-secondary btn-lg px-5 ms-2">
                                    <i class="fas fa-arrow-left me-2"></i>گەڕانەوە
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // پشکنینی کەمترین یەک جۆر هەڵبژێردراوە
    const form = document.getElementById('requestForm');
    const checkboxes = form.querySelectorAll('input[name="request_types[]"]:not(:disabled)');
    const errorDiv = document.getElementById('requestTypesError');
    
    form.addEventListener('submit', function(e) {
        let checkedCount = 0;
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) checkedCount++;
        });
        
        if (checkedCount === 0) {
            e.preventDefault();
            errorDiv.classList.remove('d-none');
            errorDiv.scrollIntoView({ behavior: 'smooth' });
        }
    });
    
    // خۆکار ڕەفتارکردن
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                errorDiv.classList.add('d-none');
            }
        });
    });
});
</script>
@endpush