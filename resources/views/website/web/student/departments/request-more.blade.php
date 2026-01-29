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
                        @php
                            $allEnabled =
                                $student->all_departments == 1 && $student->ai_rank == 1 && $student->gis == 1;
                            $hasPending = $existingRequest && $existingRequest->isPending();
                            $anyMissing =
                                $student->all_departments == 0 || $student->ai_rank == 0 || $student->gis == 0;
                        @endphp

                        @if ($hasPending)
                            <div class="alert alert-info shadow-sm border-info mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock fa-2x me-3 text-info"></i>
                                    <div>
                                        <h5 class="alert-heading fw-bold">داواکارییەکەت لە پڕۆسەی چاوەڕوانیدایە</h5>
                                        <p class="mb-0 text-dark">تۆ پێشتر داواکاریت ناردووە و لە ئێستادا لەلایەن
                                            بەڕێوەبەرەوە پێداچوونەوەی بۆ دەکرێت.</p>
                                        <div class="mt-2 small">
                                            <strong>کاتی ناردن:</strong>
                                            {{ $existingRequest->created_at->format('Y/m/d - H:i') }}<br>
                                            <strong>ناوەرۆک:</strong> {{ $existingRequest->reason }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($allEnabled)
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                                <h3 class="fw-bold text-success">هەموو تایبەتمەندییەکان چالاککراون!</h3>
                                <p class="text-muted fs-5">تۆ لە ئێستادا خاوەنی هەموو مۆڵەت و تایبەتمەندییە نایابەکانی
                                    سیستەمیت.</p>
                                <div class="d-flex justify-content-center gap-3 mt-4">
                                    <span class="badge bg-success p-2"><i class="fas fa-check me-1"></i> ٥٠ بەش</span>
                                    <span class="badge bg-success p-2"><i class="fas fa-check me-1"></i> AI Ranking</span>
                                    <span class="badge bg-success p-2"><i class="fas fa-check me-1"></i> GIS Map</span>
                                </div>
                                <div class="mt-5">
                                    <a href="{{ route('student.departments.selection') }}"
                                        class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-university me-2"></i>بۆ پەیجی هەڵبژاردنەکان
                                    </a>
                                </div>
                            </div>
                        @elseif($anyMissing)
                            @if (!$hasPending)
                                <div class="alert alert-warning border-warning shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle fa-2x me-3 text-warning"></i>
                                        <div>
                                            <h5 class="alert-heading fw-bold">ناردنی داواکاری نوێ</h5>
                                            <p class="mb-0 text-dark">ئەو تایبەتمەندییانەی کە تا ئێستا بۆت چالاک نەکراون،
                                                لێرەدا دەتوانیت داوایان بکەیت.</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <hr class="my-4">
                                <div class="text-center mb-4">
                                    <p class="text-muted">دەتوانیت داواکارییەکی نوێ بنێریت یان داواکارییەکەی پێشوو
                                        هەڵوەشێنیتەوە.</p>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('student.departments.submit-request') }}" id="requestForm"
                                class="{{ $hasPending ? 'opacity-75' : '' }}">
                                @csrf

                                <div class="mb-4 text-start">
                                    <label class="form-label fw-bold fs-5">
                                        <i class="fas fa-list-check text-primary me-2"></i>جۆرەکانی داواکاری کە دەتەوێت:
                                    </label>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div
                                                class="card h-100 border-{{ $student->all_departments == 1 ? 'success bg-light' : 'primary shadow-sm' }} feature-card">
                                                <div class="card-body">
                                                    <div class="form-check custom-option">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="request_types[]" value="all_departments" id="allDeps"
                                                            {{ $student->all_departments == 1 ? 'disabled checked' : '' }}>
                                                        <label class="form-check-label fw-bold d-block" for="allDeps">
                                                            <i
                                                                class="fas fa-layer-group mb-2 d-block fa-2x text-warning"></i>
                                                            مۆڵەتی ٥٠ بەش
                                                        </label>
                                                    </div>
                                                    @if ($student->all_departments == 1)
                                                        <span class="badge bg-success mt-2">چالاکە</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div
                                                class="card h-100 border-{{ $student->ai_rank == 1 ? 'success bg-light' : 'primary shadow-sm' }} feature-card">
                                                <div class="card-body">
                                                    <div class="form-check custom-option">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="request_types[]" value="ai_rank" id="aiRank"
                                                            {{ $student->ai_rank == 1 ? 'disabled checked' : '' }}>
                                                        <label class="form-check-label fw-bold d-block" for="aiRank">
                                                            <i class="fas fa-robot mb-2 d-block fa-2x text-success"></i>
                                                            سیستەمی AI
                                                        </label>
                                                    </div>
                                                    @if ($student->ai_rank == 1)
                                                        <span class="badge bg-success mt-2">چالاکە</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div
                                                class="card h-100 border-{{ $student->gis == 1 ? 'success bg-light' : 'primary shadow-sm' }} feature-card">
                                                <div class="card-body">
                                                    <div class="form-check custom-option">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="request_types[]" value="gis" id="gisSys"
                                                            {{ $student->gis == 1 ? 'disabled checked' : '' }}>
                                                        <label class="form-check-label fw-bold d-block" for="gisSys">
                                                            <i
                                                                class="fas fa-map-marked-alt mb-2 d-block fa-2x text-info"></i>
                                                            سیستەمی GIS
                                                        </label>
                                                    </div>
                                                    @if ($student->gis == 1)
                                                        <span class="badge bg-success mt-2">چالاکە</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="requestTypesError" class="text-danger small mt-2 d-none">
                                        <i class="fas fa-exclamation-circle me-1"></i>تکایە بەلایەنی کەم یەک دانە هەڵبژێرە.
                                    </div>
                                </div>

                                <div class="mb-4 text-start">
                                    <label for="reason" class="form-label fw-bold">
                                        <i class="fas fa-comment-dots text-primary me-2"></i>هۆکاری داواکارییەکەت ڕوون
                                        بکەرەوە:
                                    </label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="5"
                                        placeholder="بۆچی پێویستت بەم تایبەتمەندییانەیە؟" {{ $hasPending ? 'disabled' : '' }}>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-center pt-3 border-top">
                                    @if (!$hasPending)
                                        <button type="submit" class="btn btn-warning btn-lg px-5 fw-bold shadow">
                                            <i class="fas fa-paper-plane me-2"></i>ناردنی داواکاری بۆ بەڕێوەبەر
                                        </button>
                                    @else
                                        <form method="POST"
                                            action="{{ route('student.departments.cancel-request', $existingRequest->id) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-lg px-5">
                                                <i class="fas fa-times me-2"></i>هەڵوەشاندنەوەی داواکاریی پێشوو
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('student.departments.selection') }}"
                                        class="btn btn-outline-secondary btn-lg px-5 ms-2">
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
            // Ensure form exists before trying to access its elements
            if (!form) return;

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
                    errorDiv.scrollIntoView({
                        behavior: 'smooth'
                    });
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
