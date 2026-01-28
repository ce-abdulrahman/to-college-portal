@extends('website.web.admin.layouts.app')
@section('content')
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
            </div>
        </div>

        <!-- start row -->
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="row g-3">

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="fs-14 mb-1">Website Traffic</div>
                                </div>

                                <div class="d-flex align-items-baseline mb-2">
                                    <div class="fs-22 mb-0 me-2 fw-semibold text-black">91.6K</div>
                                    <div class="me-auto">
                                        <span class="text-primary d-inline-flex align-items-center">
                                            15%
                                            <i data-feather="trending-up" class="ms-1"
                                                style="height: 22px; width: 22px;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div id="website-visitors" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="fs-14 mb-1">Conversion rate</div>
                                </div>

                                <div class="d-flex align-items-baseline mb-2">
                                    <div class="fs-22 mb-0 me-2 fw-semibold text-black">15%</div>
                                    <div class="me-auto">
                                        <span class="text-danger d-inline-flex align-items-center">
                                            10%
                                            <i data-feather="trending-down" class="ms-1"
                                                style="height: 22px; width: 22px;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div id="conversion-visitors" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="fs-14 mb-1">Session duration</div>
                                </div>

                                <div class="d-flex align-items-baseline mb-2">
                                    <div class="fs-22 mb-0 me-2 fw-semibold text-black">90 Sec</div>
                                    <div class="me-auto">
                                        <span class="text-success d-inline-flex align-items-center">
                                            25%
                                            <i data-feather="trending-up" class="ms-1"
                                                style="height: 22px; width: 22px;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div id="session-visitors" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="fs-14 mb-1">Active Users</div>
                                </div>

                                <div class="d-flex align-items-baseline mb-2">
                                    <div class="fs-22 mb-0 me-2 fw-semibold text-black">2,986</div>
                                    <div class="me-auto">
                                        <span class="text-success d-inline-flex align-items-center">
                                            4%
                                            <i data-feather="trending-up" class="ms-1"
                                                style="height: 22px; width: 22px;"></i>
                                        </span>
                                    </div>
                                </div>
                                <div id="active-users" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                    {{-- لە داشبۆردی قوتابی --}}
                    @php
                        $student = Auth::user();
                    @endphp
@if($student->gis == 1)
<div class="col-md-4 mb-4">
    <div class="card border-success h-100">
        <div class="card-body text-center">
            <h5 class="card-title">نەخشەی بەشەکان</h5>
            <p class="card-text">هەڵبژاردنی بەشەکان لەسەر نەخشە</p>
            <a href="{{ route('student.gis.index') }}" class="btn btn-success">
                <i class="fas fa-map-marked-alt me-2"></i>چوونە ناوەوە
            </a>
        </div>
    </div>
</div>
@else
<div class="col-md-4 mb-4">
    <div class="card border-warning h-100">
        <div class="card-body text-center">
            <h5 class="card-title">نەخشەی بەشەکان</h5>
            <p class="card-text">مۆڵەتی بەکارهێنانی نەخشەت نییە</p>
            <a href="{{ route('student.departments.request-more') }}" class="btn btn-warning">
                <i class="fas fa-envelope me-2"></i>داواکاری
            </a>
        </div>
    </div>
</div>
@endif
                </div>
            </div> <!-- end sales -->
        </div> <!-- end row -->

    </div>
@endsection
