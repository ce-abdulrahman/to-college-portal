

@extends('website.web.student.layouts.app')

@section('title', 'ئەنجامی تاقیکردنەوە')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- کارتی ئەنجام -->
            <div class="card border-success shadow-lg mb-4">
                <div class="card-header bg-success text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-trophy me-2"></i>
                            ئەنجامی تاقیکردنەوەی جۆری کەسی
                        </h4>
                        <form method="POST" action="{{ route('student.mbti.retake') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fas fa-redo me-1"></i>دووبارەکردنەوە
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- زانیاریەکانی قوتابی -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg me-3">
                                    <span class="avatar-title bg-primary rounded-circle display-5">
                                        {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ $student->user->name }}</h5>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-id-card me-1"></i>کۆد: {{ $student->user->code }}
                                    </p>
                                    @if($student->year)
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-calendar me-1"></i>ساڵ: {{ $student->year }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="mb-2">
                                <small class="text-muted">کاتی تاقیکردنەوە:</small>
                                @if($answers->count() > 0)
                                <div class="fw-bold">{{ $answers->first()->created_at->format('Y/m/d - H:i') }}</div>
                                @endif
                            </div>
                            <div>
                                <small class="text-muted">ژمارەی وەڵامەکان:</small>
                                <div class="fw-bold">{{ $answers->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- جۆری MBTI -->
                    <div class="text-center mb-4">
                        <div class="mbti-badge display-2 fw-bold text-success mb-3">
                            {{ $student->mbti_type }}
                        </div>
                        <h4 class="mb-2">{{ $student->mbti_full_name }}</h4>
                        <p class="text-muted fs-5">{{ $student->mbti_kurdish_description }}</p>
                    </div>

                    <!-- نمرەکان -->
                    <div class="row mb-4">
                        @foreach(['EI', 'SN', 'TF', 'JP'] as $dimension)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        @switch($dimension)
                                            @case('EI')
                                                <i class="fas fa-users me-2 text-primary"></i>کۆمەڵایەتی (E/I)
                                                @break
                                            @case('SN')
                                                <i class="fas fa-eye me-2 text-success"></i>هەست (S/N)
                                                @break
                                            @case('TF')
                                                <i class="fas fa-brain me-2 text-warning"></i>بیرکردنەوە (T/F)
                                                @break
                                            @case('JP')
                                                <i class="fas fa-gavel me-2 text-info"></i>ڕێکخستن (J/P)
                                                @break
                                        @endswitch
                                    </h6>
                                    
                                    @php
                                        if($dimension == 'EI') {
                                            $left = 'E'; $right = 'I'; $leftScore = $scores['E']; $rightScore = $scores['I'];
                                        } elseif($dimension == 'SN') {
                                            $left = 'S'; $right = 'N'; $leftScore = $scores['S']; $rightScore = $scores['N'];
                                        } elseif($dimension == 'TF') {
                                            $left = 'T'; $right = 'F'; $leftScore = $scores['T']; $rightScore = $scores['F'];
                                        } else {
                                            $left = 'J'; $right = 'P'; $leftScore = $scores['J']; $rightScore = $scores['P'];
                                        }
                                        
                                        $total = $leftScore + $rightScore;
                                        $leftPercent = $total > 0 ? ($leftScore / $total) * 100 : 50;
                                        $rightPercent = $total > 0 ? ($rightScore / $total) * 100 : 50;
                                    @endphp
                                    
                                    <div class="progress mb-2" style="height: 30px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $leftPercent }}%">
                                            <span class="fw-bold">{{ $left }}</span>
                                        </div>
                                        <div class="progress-bar bg-secondary" style="width: {{ $rightPercent }}%">
                                            <span class="fw-bold">{{ $right }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <small class="text-primary">{{ $leftScore }} نمرە</small>
                                        <small class="text-secondary">{{ $rightScore }} نمرە</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- چارتی ئاستەکان -->
            <div class="card border-info shadow-sm mb-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-radar me-2"></i>
                        چارتی ئاستەکان
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="mbtiChart" height="250"></canvas>
                </div>
            </div>

            <!-- تواناکان و کارە گونجاوەکان -->
            <div class="row">
                <!-- تواناکانی سەرەکی -->
                <div class="col-md-6 mb-4">
                    <div class="card border-success h-100">
                        <div class="card-header bg-success text-white py-2">
                            <h6 class="mb-0">
                                <i class="fas fa-star me-2"></i>
                                تواناکانی سەرەکی
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @foreach($mbtiInfo['strengths'] as $strength)
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    {{ $strength }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- کارە گونجاوەکان -->
                <div class="col-md-6 mb-4">
                    <div class="card border-primary h-100">
                        <div class="card-header bg-primary text-white py-2">
                            <h6 class="mb-0">
                                <i class="fas fa-briefcase me-2"></i>
                                کارە گونجاوەکان
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($mbtiInfo['careers'] as $career)
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-user-tie me-1"></i>{{ $career }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- دووبارەکردنەوە -->
            <div class="card border-warning">
                <div class="card-body text-center py-4">
                    <h5 class="mb-3">ئەگەر ویستت دەستکاری بکەی یان دووبارە تاقیکردنەوە بکەی:</h5>
                    <form method="POST" action="{{ route('student.mbti.retake') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-redo me-2"></i>دووبارەکردنەوەی تاقیکردنەوە
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.mbti-badge {
    display: inline-block;
    padding: 20px 50px;
    border-radius: 20px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
    font-family: 'Courier New', monospace;
}

.avatar-lg {
    width: 70px;
    height: 70px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    font-weight: bold;
    color: white;
}

.progress {
    border-radius: 15px;
    overflow: hidden;
}

.progress-bar {
    font-size: 1rem;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // چارتی ئاستەکان
    const ctx = document.getElementById('mbtiChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['E', 'I', 'S', 'N', 'T', 'F', 'J', 'P'],
            datasets: [{
                label: 'ئاستەکانی من',
                data: [
                    {{ $scores['E'] }},
                    {{ $scores['I'] }},
                    {{ $scores['S'] }},
                    {{ $scores['N'] }},
                    {{ $scores['T'] }},
                    {{ $scores['F'] }},
                    {{ $scores['J'] }},
                    {{ $scores['P'] }}
                ],
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 3,
                pointBackgroundColor: 'rgba(40, 167, 69, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    angleLines: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    suggestedMin: 0,
                    suggestedMax: Math.max({{ max($scores) }}, 50),
                    ticks: {
                        stepSize: 20
                    },
                    pointLabels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush


