@extends('website.web.admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row g-3 g-md-4">

            {{-- Users --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fw-medium">Total Users</p>
                            <h4 class="mb-0 counter" data-target="36254">0</h4>
                        </div>
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Orders --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fw-medium">Total Orders</p>
                            <h4 class="mb-0 counter" data-target="5543">0</h4>
                        </div>
                        <div class="stat-icon bg-info-subtle text-info">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Revenue --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fw-medium">Total Revenue</p>
                            <h4 class="mb-0 counter" data-target="63548" data-prefix="$">0</h4>
                        </div>
                        <div class="stat-icon bg-success-subtle text-success">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Likes --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fw-medium">Total Likes</p>
                            <h4 class="mb-0 counter" data-target="25862">0</h4>
                        </div>
                        <div class="stat-icon bg-danger-subtle text-danger">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- (Optional) ناوەڕۆکی تر لە خوارەوەی کارتەکان مانند Chart/Latest… --}}
        {{-- Charts & Latest --}}
        <div class="row g-3 g-md-4 mt-1">
            {{-- Sales/Revenue Chart --}}
            {{--  <div class="col-12 col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-graph-up-arrow me-1 text-primary"></i> فروش/داخەوە
                            </h5>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Range">
                                <button class="btn btn-outline-primary active" data-range="7">7ڕۆژ</button>
                                <button class="btn btn-outline-primary" data-range="30">30ڕۆژ</button>
                                <button class="btn btn-outline-primary" data-range="90">90ڕۆژ</button>
                            </div>
                        </div>
                        <canvas id="salesChart" height="120"></canvas>
                    </div>
                </div>
            </div>  --}}

            {{-- Latest Activity / Events --}}
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-clock-history me-1 text-info"></i> دوایین چالاکیەکان
                        </h5>
                        <ul class="list-group list-group-flush latest-list">
                            <li class="list-group-item d-flex align-items-start">
                                <div class="me-2 text-success"><i class="bi bi-check2-circle"></i></div>
                                <div>
                                    <div class="fw-semibold">هەژمارێک تۆمارکرایەوە</div>
                                    <small class="text-muted">ئەمڕۆ • 10:32</small>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <div class="me-2 text-primary"><i class="bi bi-person-plus"></i></div>
                                <div>
                                    <div class="fw-semibold">زیادکردنی بەکارهێنەر</div>
                                    <small class="text-muted">دوێنێ • 15:18</small>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start">
                                <div class="me-2 text-warning"><i class="bi bi-pencil-square"></i></div>
                                <div>
                                    <div class="fw-semibold">نوێکردنەوەی داتا</div>
                                    <small class="text-muted">٣ ڕۆژ پێشتر</small>
                                </div>
                            </li>
                        </ul>
                        <div class="text-end mt-3">
                            <a href="#" class="btn btn-sm btn-outline-secondary">بینینی هەمووی</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    {{--  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  --}}
    <script src="{{ asset('assets/admin/js/dashboard-charts.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            DashboardCharts.initSalesChart('#salesChart');
        });
    </script>
@endpush
