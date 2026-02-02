@extends('website.web.admin.layouts.app')

@section('title', 'ئەنجامی تاقیکردنەوەی کەسایەتی')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Title & Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 text-muted">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">ئەنجامی MBTI</li>
                        </ol>
                    </div>
                    <h4 class="page-title fw-bold text-dark font-primary">
                        <i class="fas fa-trophy me-2 text-warning"></i>
                        ئەنجامی کەسایەتی
                    </h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">
                <!-- Result Hero Card -->
                <div class="card glass border-0 shadow-lg mb-4 overflow-hidden fade-in-up">
                    <div class="card-header bg-gradient-success p-5 position-relative text-center">
                        <div class="z-index-1 position-relative">
                            <div
                                class="mb-3 d-inline-block p-3 bg-white-transparent rounded-circle shadow-sm pulse-animation">
                                <i class="fas fa-medal fa-4x text-white"></i>
                            </div>
                            <h1 class="display-3 fw-bold text-white mb-2 font-primary letter-spacing-lg"
                                id="mbti-type-title">
                                {{ $student->mbti_type }}
                            </h1>
                            <h3 class="text-white fw-bold mb-3 opacity-90">{{ $student->mbti_full_name }}</h3>
                            <div class="d-flex justify-content-center gap-2 mb-3">
                                <span class="badge bg-white-transparent px-3 py-2 rounded-pill"><i
                                        class="fas fa-user me-1"></i> {{ $student->user->name }}</span>
                                <span class="badge bg-white-transparent px-3 py-2 rounded-pill"><i
                                        class="fas fa-clock me-1"></i>
                                    {{ $answers->count() > 0 ? $answers->first()->created_at->format('Y/m/d') : '' }}</span>
                            </div>
                        </div>
                        <!-- Abstract Shapes -->
                        <div class="shape-1"></div>
                        <div class="shape-2"></div>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="mx-auto" style="max-width: 800px;">
                            <p class="fs-5 text-dark lh-lg mb-4">
                                <i class="fas fa-quote-right text-success opacity-50 me-2"></i>
                                {{ $student->mbti_kurdish_description }}
                                <i class="fas fa-quote-left text-success opacity-50 ms-2"></i>
                            </p>
                        </div>
                        <div class="d-flex justify-content-center gap-3">
                            <form method="POST" action="{{ route('student.mbti.retake') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-soft-warning px-4 py-2 rounded-pill fw-bold">
                                    <i class="fas fa-redo me-2"></i> دووبارەکردنەوە
                                </button>
                            </form>
                            <button class="btn btn-primary px-4 py-2 rounded-pill fw-bold" onclick="window.print()">
                                <i class="fas fa-print me-2"></i> بڵاوکردنەوە (PDF)
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Dimension Scores -->
                    <div class="col-xl-7">
                        <div class="card glass border-0 shadow-sm h-100 fade-in-left">
                            <div class="card-header bg-transparent border-0 pt-4 px-4">
                                <h5 class="fw-bold mb-0"><i class="fas fa-sliders text-primary me-2"></i> شرۆڤەی ڕەهەندەکانی
                                    کەسایەتی</h5>
                            </div>
                            <div class="card-body p-4">
                                @foreach (['EI', 'SN', 'TF', 'JP'] as $dimension)
                                    @php
                                        if ($dimension == 'EI') {
                                            $left = 'E';
                                            $right = 'I';
                                            $leftLabel = 'کۆمەڵایەتی';
                                            $rightLabel = 'تاکەکەسی';
                                            $leftScore = $scores['E'];
                                            $rightScore = $scores['I'];
                                            $color = 'primary';
                                        } elseif ($dimension == 'SN') {
                                            $left = 'S';
                                            $right = 'N';
                                            $leftLabel = 'هەست';
                                            $rightLabel = 'ژیرێتی';
                                            $leftScore = $scores['S'];
                                            $rightScore = $scores['N'];
                                            $color = 'success';
                                        } elseif ($dimension == 'TF') {
                                            $left = 'T';
                                            $right = 'F';
                                            $leftLabel = 'بیرکردنەوە';
                                            $rightLabel = 'هەست';
                                            $leftScore = $scores['T'];
                                            $rightScore = $scores['F'];
                                            $color = 'warning';
                                        } else {
                                            $left = 'J';
                                            $right = 'P';
                                            $leftLabel = 'ڕێکخستن';
                                            $rightLabel = 'چاودێری';
                                            $leftScore = $scores['J'];
                                            $rightScore = $scores['P'];
                                            $color = 'info';
                                        }
                                        $total = $leftScore + $rightScore;
                                        $leftPercent = $total > 0 ? ($leftScore / $total) * 100 : 50;
                                        $rightPercent = 100 - $leftPercent;
                                    @endphp

                                    <div class="dimension-row mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span
                                                class="small fw-bold {{ $leftPercent >= 50 ? 'text-' . $color : 'text-muted' }}">{{ $leftLabel }}
                                                ({{ $left }})</span>
                                            <span
                                                class="small fw-bold {{ $rightPercent > 50 ? 'text-' . $color : 'text-muted' }}">{{ $rightLabel }}
                                                ({{ $right }})</span>
                                        </div>
                                        <div class="progress rounded-pill shadow-none border-soft"
                                            style="height: 35px; background: #f1f3f5;">
                                            <div class="progress-bar bg-gradient-{{ $color }} rounded-pill-start progress-animation"
                                                role="progressbar" style="width: {{ $leftPercent }}%"
                                                data-target="{{ $leftPercent }}">
                                                <span class="fw-bold ms-2">{{ round($leftPercent) }}%</span>
                                            </div>
                                            <div class="progress-bar bg-soft-{{ $color }} text-{{ $color }} rounded-pill-end"
                                                role="progressbar" style="width: {{ $rightPercent }}%">
                                                <span class="fw-bold me-2">{{ round($rightPercent) }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Radar Chart Card -->
                    <div class="col-xl-5">
                        <div class="card glass border-0 shadow-sm h-100 fade-in-right">
                            <div class="card-header bg-transparent border-0 pt-4 px-4">
                                <h5 class="fw-bold mb-0"><i class="fas fa-chart-radar text-info me-2"></i> نەخشەی کەسایەتی
                                </h5>
                            </div>
                            <div class="card-body p-4 d-flex align-items-center justify-content-center">
                                <div style="width: 100%; height: 350px;">
                                    <canvas id="mbtiRadarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Strengths & Careers -->
                <div class="row g-4 mt-2">
                    <div class="col-md-6 fade-in-up" style="animation-delay: 0.2s">
                        <div class="card glass border-0 shadow-sm h-100">
                            <div class="card-header bg-soft-success border-0 py-3">
                                <h5 class="mb-0 text-success fw-bold"><i class="fas fa-star me-2"></i> تواناشاناکان
                                    (Strengths)</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    @foreach ($mbtiInfo['strengths'] as $strength)
                                        <div class="col-12">
                                            <div
                                                class="d-flex align-items-center p-3 bg-light rounded-4 border-soft transition-hover">
                                                <div
                                                    class="icon-box-sm bg-success text-white rounded-circle me-3 shadow-sm">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <span class="fw-bold text-dark">{{ $strength }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 fade-in-up" style="animation-delay: 0.4s">
                        <div class="card glass border-0 shadow-sm h-100">
                            <div class="card-header bg-soft-primary border-0 py-3">
                                <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-briefcase me-2"></i> کارە گونجاوەکان
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($mbtiInfo['careers'] as $career)
                                        <div class="career-tag p-3 bg-white border-soft rounded-4 text-center transition-hover shadow-sm"
                                            style="flex: 1 1 calc(50% - 10px); min-width: 150px;">
                                            <div
                                                class="avatar-sm bg-soft-primary text-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <span class="fw-bold small">{{ $career }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Final Message -->
                <div class="card glass border-0 bg-gradient-purple shadow-lg mt-5 mb-5 fade-in-up">
                    <div class="card-body p-5 text-center text-white">
                        <i class="fas fa-rocket fa-3x mb-4 opacity-50 pulse-animation"></i>
                        <h2 class="fw-bold mb-3 font-primary">ئامادەی بۆ هەنگاوی داهاتوو؟</h2>
                        <p class="fs-5 opacity-80 mb-4 mx-auto" style="max-width: 700px;">ئێستا کە جۆری کەسایەتی خۆت
                            دەزانیت، دەتوانیت بە زیرەکی دەستکرد (AI) باشترین بەشی زانکۆ هەڵبژێریت کە لەگەڵ کەسایەتیت
                            دەگونجێت.</p>
                        <a href="{{ route('student.ai-ranking.questionnaire') }}"
                            class="btn btn-white btn-lg rounded-pill px-5 fw-bold shadow-lg">
                            هەڵبژاردنی بەش بە زیرەکی دەستکرد <i class="fas fa-arrow-left ms-2 scale-rtl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        :root {
            --purple-primary: #6C5CE7;
            --purple-dark: #4834D4;
            --success-vibrant: #00B894;
            --glass-bg: rgba(255, 255, 255, 0.9);
        }

        .font-primary {
            font-family: 'NizarNastaliqKurdish', 'Wafeq', sans-serif;
        }

        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #00B894, #00cec9);
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, var(--purple-primary), var(--purple-dark));
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #6C5CE7, #a29bfe);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #fdcb6e, #f1c40f);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #0984e3, #74b9ff);
        }

        .bg-white-transparent {
            background: rgba(255, 255, 255, 0.2);
        }

        .bg-soft-primary {
            background: rgba(108, 92, 231, 0.1) !important;
        }

        .bg-soft-success {
            background: rgba(0, 184, 148, 0.1) !important;
        }

        .bg-soft-warning {
            background: rgba(253, 203, 110, 0.1) !important;
        }

        .bg-soft-info {
            background: rgba(9, 132, 227, 0.1) !important;
        }

        .btn-soft-warning {
            background: rgba(253, 203, 110, 0.2);
            color: #e67e22;
            border: none;
        }

        .btn-white {
            background: white;
            color: var(--purple-primary);
            border: none;
        }

        .btn-white:hover {
            background: #f8f9fa;
            color: var(--purple-dark);
        }

        .border-soft {
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        .shape-1,
        .shape-2 {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .shape-1 {
            width: 250px;
            height: 250px;
            top: -100px;
            left: -100px;
        }

        .shape-2 {
            width: 150px;
            height: 150px;
            bottom: -50px;
            right: 5%;
        }

        .rounded-pill-start {
            border-top-left-radius: 50rem !important;
            border-bottom-left-radius: 50rem !important;
        }

        .rounded-pill-end {
            border-top-right-radius: 50rem !important;
            border-bottom-right-radius: 50rem !important;
        }

        [dir="rtl"] .rounded-pill-start {
            border-top-right-radius: 50rem !important;
            border-bottom-right-radius: 50rem !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        [dir="rtl"] .rounded-pill-end {
            border-top-left-radius: 50rem !important;
            border-bottom-left-radius: 50rem !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .transition-hover {
            transition: all 0.3s ease;
        }

        .transition-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05) !important;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out both;
        }

        .fade-in-left {
            animation: fadeInLeft 0.6s ease-out both;
        }

        .fade-in-right {
            animation: fadeInRight 0.6s ease-out both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .scale-rtl {
            transform: scaleX(-1);
        }

        [dir="rtl"] .scale-rtl {
            transform: scaleX(1);
        }

        .letter-spacing-lg {
            letter-spacing: 5px;
        }

        @media print {

            .navbar,
            .breadcrumb,
            .btn,
            .dimension-stepper {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            body {
                background: white !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Radar Chart
            const ctx = document.getElementById('mbtiRadarChart').getContext('2d');
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['E', 'I', 'S', 'N', 'T', 'F', 'J', 'P'],
                    datasets: [{
                        label: 'ئاستەکانی کەسایەتی',
                        data: [
                            {{ $scores['E'] }}, {{ $scores['I'] }},
                            {{ $scores['S'] }}, {{ $scores['N'] }},
                            {{ $scores['T'] }}, {{ $scores['F'] }},
                            {{ $scores['J'] }}, {{ $scores['P'] }}
                        ],
                        backgroundColor: 'rgba(108, 92, 231, 0.2)',
                        borderColor: '#6C5CE7',
                        pointBackgroundColor: '#6C5CE7',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#6C5CE7',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                display: true,
                                color: 'rgba(0,0,0,0.05)'
                            },
                            suggestedMin: 0,
                            suggestedMax: 100,
                            ticks: {
                                display: false,
                                stepSize: 20
                            },
                            pointLabels: {
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                color: '#444'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Progress bar animation
            $('.progress-animation').each(function() {
                const target = $(this).data('target');
                $(this).css('width', '0%').animate({
                    width: target + '%'
                }, 1500);
            });
        });
    </script>
@endpush
