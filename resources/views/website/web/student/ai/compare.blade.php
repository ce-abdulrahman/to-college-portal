@extends('website.web.student.layouts.app')

@section('title', 'پێوانەی ڕیزبەندیەکان - AI')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.ai-ranking.results') }}">ئەنجامەکانی AI</a></li>
                        <li class="breadcrumb-item active">پێوانەکردن</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-balance-scale me-1"></i>
                    پێوانەی ڕیزبەندیەکان
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-primary shadow-lg mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>پێوانەی باشترین ٥ بەش</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ڕیز</th>
                                    <th>ناوی بەش</th>
                                    <th>نمرەی گشتی</th>
                                    <th>نمرەی ئەکادیمی</th>
                                    <th>نمرەی کەسایەتی</th>
                                    <th>کردار</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topDepartments as $index => $dept)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $dept->department->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $dept->department->university->name ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $dept->score >= 80 ? 'success' : ($dept->score >= 60 ? 'info' : 'warning') }}" style="width: {{ $dept->score }}%">
                                                <small>{{ $dept->score }}%</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $factors = json_decode($dept->match_factors, true);
                                            $academic = $factors['academic_match'] ?? 0;
                                        @endphp
                                        <span class="badge bg-{{ $academic >= 80 ? 'success' : 'info' }}">{{ $academic }}%</span>
                                    </td>
                                    <td>
                                        @php
                                            $personality = $factors['personality_match'] ?? 0;
                                        @endphp
                                        <span class="badge bg-{{ $personality >= 80 ? 'success' : 'info' }}">{{ $personality }}%</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('student.ai-ranking.department-details', $dept->department_id) }}" class="btn btn-sm btn-outline-primary" title="وردەکاری">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        بەشی نیشتمان نییە
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>چارتەی پێوانەکردن</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="comparisonChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>ئامارەکان</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between">
                                    <strong>باشترین نمرە:</strong>
                                    <span class="badge bg-success">{{ $topDepartments->max('score') }}%</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <strong>تێکڕای نمرە:</strong>
                                    <span class="badge bg-info">{{ round($topDepartments->avg('score'), 1) }}%</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <strong>کەمترین نمرە:</strong>
                                    <span class="badge bg-warning">{{ $topDepartments->min('score') }}%</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <strong>جیاوازی:</strong>
                                    <span class="badge bg-danger">{{ ($topDepartments->max('score') - $topDepartments->min('score')) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="mb-3"><i class="fas fa-cog me-2"></i>کارەکان</h5>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('student.ai-ranking.results') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>گەڕانەوە بۆ ئەنجامەکان
                        </a>
                        <button class="btn btn-outline-primary" id="printBtn">
                            <i class="fas fa-print me-2"></i>پرنت کردن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0984e3, #6c5ce7);
    }

    .table-hover tbody tr {
        transition: background-color 0.3s ease;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(9, 132, 227, 0.05);
    }

    .progress-bar {
        font-size: 12px;
        line-height: 20px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js"></script>
<script>
    $(document).ready(function() {
        // چارتی پێوانەکردن
        const ctx = document.getElementById('comparisonChart').getContext('2d');
        const comparisonChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($topDepartments->pluck('department.name')->toArray()),
                datasets: [{
                    label: 'نمرەی گشتی',
                    data: @json($topDepartments->pluck('score')->toArray()),
                    backgroundColor: [
                        'rgba(0, 184, 148, 0.7)',
                        'rgba(9, 132, 227, 0.7)',
                        'rgba(108, 92, 231, 0.7)',
                        'rgba(253, 203, 110, 0.7)',
                        'rgba(225, 112, 85, 0.7)'
                    ],
                    borderColor: [
                        'rgba(0, 184, 148, 1)',
                        'rgba(9, 132, 227, 1)',
                        'rgba(108, 92, 231, 1)',
                        'rgba(253, 203, 110, 1)',
                        'rgba(225, 112, 85, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

        // پرنت کردن
        $('#printBtn').click(function() {
            window.print();
        });
    });
</script>
@endpush
@endsection
