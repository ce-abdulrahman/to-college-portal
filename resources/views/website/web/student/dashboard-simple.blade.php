@extends('website.web.admin.layouts.app')

@section('title', 'داشبۆردی قوتابی')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title mb-0">
                        <i class="fas fa-home me-2"></i>
                        بەخێربێیت، {{ auth()->user()->name }}
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">سەرەکی</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">نمرەی کۆتایی</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-primary fs-14 mb-0">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                    {{ auth()->user()->student->mark ?? 0 }}
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-primary rounded fs-3">
                                    <i class="fas fa-percent text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">بەشە هەڵبژێردراوەکان</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-success fs-14 mb-0">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value"
                                        data-target="{{ auth()->user()->student->resultDeps()->count() ?? 0 }}">0</span>
                                </h4>
                                <a href="{{ route('student.departments.selection') }}"
                                    class="text-decoration-underline">دیاریکردنی بەش</a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-success rounded fs-3">
                                    <i class="fas fa-clipboard-list text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">تایبەتمەندییەکان</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div class="w-100">
                                @php
                                    $student = auth()->user()->student;
                                @endphp
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge {{ $student->ai_rank ? 'bg-success' : 'bg-secondary' }}">
                                        <i class="fa-solid {{ $student->ai_rank ? 'fa-check' : 'fa-times' }} me-1"></i>
                                        AI Rank
                                    </span>
                                    <span class="badge {{ $student->gis ? 'bg-success' : 'bg-secondary' }}">
                                        <i class="fa-solid {{ $student->gis ? 'fa-check' : 'fa-times' }} me-1"></i>
                                        GIS
                                    </span>
                                    <span class="badge {{ $student->all_departments ? 'bg-success' : 'bg-secondary' }}">
                                        <i
                                            class="fa-solid {{ $student->all_departments ? 'fa-check' : 'fa-times' }} me-1"></i>
                                        50 Departments
                                    </span>
                                </div>
                                @if (!$student->ai_rank || !$student->gis || !$student->all_departments)
                                    <div class="mt-3">
                                        <a href="{{ route('student.departments.request-more') }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-paper-plane me-1"></i>
                                            داواکردنی تایبەتمەندی
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">پرۆفایل</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <i class="fas fa-user-circle"></i>
                                </h4>
                                <a href="{{ route('profile.edit') }}" class="text-decoration-underline">دەستکاری
                                    پرۆفایل</a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info rounded fs-3">
                                    <i class="fas fa-user text-info"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            کردارە خێراکان
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="{{ route('student.departments.selection') }}"
                                    class="btn btn-soft-primary w-100 py-3">
                                    <i class="fas fa-list-check fs-4 d-block mb-2"></i>
                                    هەڵبژاردنی بەشەکان
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('student.mbti.index') }}" class="btn btn-soft-success w-100 py-3">
                                    <i class="fas fa-brain fs-4 d-block mb-2"></i>
                                    تاقیکردنەوەی کەسایەتی MBTI
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('profile.edit') }}" class="btn btn-soft-info w-100 py-3">
                                    <i class="fas fa-edit fs-4 d-block mb-2"></i>
                                    دەستکاری پرۆفایل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Alert if GIS is disabled -->
        @if (!auth()->user()->student->gis)
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        <strong>تێبینی:</strong> تایبەتمەندی GIS (نەخشە) بۆ تۆ ناچالاکە.
                        <a href="{{ route('student.departments.request-more') }}" class="alert-link">داواکاری بکە</a>
                        بۆ چالاککردنی ئەم تایبەتمەندییە و بینینی نەخشەی بەشەکان.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Counter animation
        document.querySelectorAll('.counter-value').forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const increment = target / 200;

            const updateCount = () => {
                const count = +counter.innerText;
                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 10);
                } else {
                    counter.innerText = target;
                }
            };

            updateCount();
        });
    </script>
@endpush
