@extends('website.web.admin.layouts.app')

@section('title', 'داشبۆردی سەنتەر')

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
                            <li class="breadcrumb-item"><a href="{{ route('center.dashboard') }}">داشبۆرد</a></li>
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
                                <p class="text-uppercase fw-medium text-muted mb-0">مامۆستایان</p>
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
                                    <span class="counter-value"
                                        data-target="{{ auth()->user()->center->teachers()->count() ?? 0 }}">0</span>
                                </h4>
                                <a href="{{ route('center.teachers.index') }}" class="text-decoration-underline">بینینی
                                    هەموو</a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-primary rounded fs-3">
                                    <i class="fas fa-chalkboard-teacher text-primary"></i>
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
                                <p class="text-uppercase fw-medium text-muted mb-0">قوتابیان</p>
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
                                        data-target="{{ auth()->user()->center->students()->count() ?? 0 }}">0</span>
                                </h4>
                                <a href="{{ route('center.students.index') }}" class="text-decoration-underline">بینینی
                                    هەموو</a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-success rounded fs-3">
                                    <i class="fas fa-user-graduate text-success"></i>
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
                                    $center = auth()->user()->center;
                                @endphp
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge {{ $center->ai_rank ? 'bg-success' : 'bg-secondary' }}">
                                        <i class="fa-solid {{ $center->ai_rank ? 'fa-check' : 'fa-times' }} me-1"></i>
                                        AI Rank
                                    </span>
                                    <span class="badge {{ $center->gis ? 'bg-success' : 'bg-secondary' }}">
                                        <i class="fa-solid {{ $center->gis ? 'fa-check' : 'fa-times' }} me-1"></i>
                                        GIS
                                    </span>
                                    <span class="badge {{ $center->all_departments ? 'bg-success' : 'bg-secondary' }}">
                                        <i
                                            class="fa-solid {{ $center->all_departments ? 'fa-check' : 'fa-times' }} me-1"></i>
                                        All Departments
                                    </span>
                                </div>
                                @if (!$center->ai_rank || !$center->gis || !$center->all_departments)
                                    <div class="mt-3">
                                        <a href="{{ route('center.features.request') }}" class="btn btn-sm btn-warning">
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
                                    <i class="fas fa-building"></i>
                                </h4>
                                <a href="{{ route('center.profile.edit', auth()->id()) }}"
                                    class="text-decoration-underline">دەستکاری پرۆفایل</a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info rounded fs-3">
                                    <i class="fas fa-building text-info"></i>
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
                            <div class="col-md-3">
                                <a href="{{ route('center.teachers.create') }}" class="btn btn-soft-primary w-100 py-3">
                                    <i class="fas fa-user-plus fs-4 d-block mb-2"></i>
                                    زیادکردنی مامۆستا
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('center.students.create') }}" class="btn btn-soft-success w-100 py-3">
                                    <i class="fas fa-user-graduate fs-4 d-block mb-2"></i>
                                    زیادکردنی قوتابی
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('center.departments.index') }}" class="btn btn-soft-info w-100 py-3">
                                    <i class="fas fa-building fs-4 d-block mb-2"></i>
                                    بینینی بەشەکان
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('center.profile.edit', auth()->id()) }}"
                                    class="btn btn-soft-warning w-100 py-3">
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
        @if (!auth()->user()->center->gis)
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        <strong>تێبینی:</strong> تایبەتمەندی GIS (نەخشە) بۆ تۆ ناچالاکە.
                        <a href="{{ route('center.features.request') }}" class="alert-link">داواکاری بکە</a>
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
