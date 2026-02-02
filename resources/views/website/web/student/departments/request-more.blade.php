{{-- resources/views/student/departments/request-more.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('title', 'داواکردنی بەشی زیاتر')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Title & Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">داواکردنی بەشی زیاتر</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-building-columns me-1"></i>
                        داواکردنی بەشی زیاتر
                    </h4>
                </div>
            </div>
        </div>
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
                                    <span class="badge bg-success p-2"><i class="fas fa-check me-1"></i> ڕیزبەندی کرد بە
                                        زیرەکی دەستکردing</span>
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
                                <div class="card bg-soft-info border-info-soft border-dashed mb-4 shadow-none text-start">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar-xs flex-shrink-0 me-2">
                                                <span class="avatar-title bg-info rounded-circle fs-13">
                                                    <i class="fa-solid fa-credit-card text-white"></i>
                                                </span>
                                            </div>
                                            <h6 class="mb-0 text-info fw-bold">ڕێنمایی و جۆری چالاککردن</h6>
                                        </div>
                                        <div class="ms-1">
                                            <p class="mb-2 small text-muted lh-lg">
                                                ئەو تایبەتمەندییانەی کە تا ئێستا بۆت چالاک نەکراون، لێرەدا دەتوانیت داوایان
                                                بکەیت. بۆ چالاککردنی هەر کامێکیان، پێویستە بڕی
                                                <span
                                                    class="badge bg-soft-info text-info border border-info-soft fw-bold">3,000</span>
                                                دینار بۆ ژمارەی
                                                <span
                                                    class="badge bg-soft-primary text-primary border border-primary-soft fw-bold">07504342452</span>
                                                بنێریت لە ڕێگای <b>FastPay</b> یان <b>FIB</b>.
                                            </p>
                                            <div class="alert alert-light border-0 mb-0 py-2 px-3 small text-muted">
                                                <i class="fa-solid fa-camera me-1 text-primary"></i>
                                                وێنەی سەرەتا (Receipt) بۆ <b><a
                                                        href="https://t.me/AGHA_ACE">Telegram</a></b> یان <b><a
                                                        href="https://wa.me/9647504342452">WhatsApp</a></b> یان <b><a
                                                        href="viber://chat?number=9647504342452">Viber</a></b> ی هەمان ژمارە
                                                بنێرە بۆ چالاککردنی خێرا.
                                            </div>
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
                                                            سیستەمی نەخشە
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
