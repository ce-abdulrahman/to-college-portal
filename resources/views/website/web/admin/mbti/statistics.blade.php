{{-- resources/views/admin/mbti-results/statistics.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">ئاماری تاقیکردنەوەی MBTI</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- کارتە سەرەکیەکان -->
            <div class="col-xl-3 col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <h5 class="text-muted fw-normal mt-0">کۆی بەکارهێنەران</h5>
                        <h3 class="mt-3 mb-3">{{ $totalUsers }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <h5 class="text-muted fw-normal mt-0">تاقیکردنەوەیان کردووە</h5>
                        <h3 class="mt-3 mb-3">{{ $testedUsers }}</h3>
                        <p class="mb-0 text-muted">
                            <span
                                class="text-success me-2">{{ $totalUsers > 0 ? round(($testedUsers / $totalUsers) * 100, 2) : 0 }}%</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <h5 class="text-muted fw-normal mt-0">تاقیکردنەوەیان نەکردووە</h5>
                        <h3 class="mt-3 mb-3">{{ $untestedUsers }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <h5 class="text-muted fw-normal mt-0">کۆی وەڵامەکان</h5>
                        <h3 class="mt-3 mb-3">{{ $totalAnswers }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- دیاگرامی دابەشبوونی جۆرەکان -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">دابەشبوونی جۆرەکانی MBTI</h5>
                        <canvas id="typeDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- تێکڕای نمرەکان -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">تێکڕای نمرەکان</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>بەش</th>
                                        <th>لا</th>
                                        <th>تێکڕا</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dimensionAverages as $dimension => $sides)
                                        @foreach ($sides as $side => $average)
                                            <tr>
                                                <td>{{ $dimension }}</td>
                                                <td>{{ $side }}</td>
                                                <td>{{ $average }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // دیاگرامی دابەشبوونی جۆرەکان
            const typeCtx = document.getElementById('typeDistributionChart').getContext('2d');

            const labels = @json(array_keys($typeDistribution->toArray()));
            const data = @json(array_values($typeDistribution->toArray()));

            new Chart(typeCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'ژمارەی بەکارهێنەران',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
