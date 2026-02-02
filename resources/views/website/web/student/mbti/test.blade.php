@extends('website.web.admin.layouts.app')

@section('title', 'تاقیکردنەوەی کەسایەتی MBTI')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Title & Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 text-muted">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">تاقیکردنەوەی MBTI</li>
                        </ol>
                    </div>
                    <h4 class="page-title fw-bold text-dark font-primary">
                        <i class="fas fa-brain me-2 text-primary"></i>
                        تاقیکردنەوەی کەسایەتی MBTI
                    </h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">
                <!-- Welcome & Progress Card -->
                <div class="card glass border-0 shadow-lg mb-4 overflow-hidden fade-in-up">
                    <div class="card-header bg-gradient-purple p-4 position-relative">
                        <div class="z-index-1 position-relative d-flex align-items-center">
                            <div
                                class="avatar-lg bg-white-transparent rounded-circle d-flex align-items-center justify-content-center me-4 shadow-sm">
                                <i class="fas fa-user-astronaut fa-2x text-black"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 text-black fw-bold p-1">گەشتێکی نوێ دەستپێبکە بۆ ناسینی خۆت</h3>
                                <p class="mb-0 text-black-50 p-1">قوتابی: {{ $student->user->name }} | کۆد:
                                    {{ $student->user->code }}</p>
                            </div>
                        </div>
                        <!-- Abstract Shapes -->
                        <div class="shape-1"></div>
                        <div class="shape-2"></div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="icon-box-sm bg-soft-primary rounded-3 me-3">
                                        <i class="fas fa-lightbulb text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">چۆن وەڵام بدەمەوە؟</h6>
                                        <p class="text-muted small mb-0">لەم تاقیکردنەوەیەدا ٣٦ پرسیار هەیە. پلەیەک لە نێوان
                                            ١ بۆ ١٠ هەڵبژێرە. (١ = بەهیچ شێوەیەک من نیم، ١٠ = بە تەواوی منم).</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="progress-wrapper bg-light rounded-4 p-3 border-soft">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small fw-bold text-muted">ڕێژەی تەواوبوون</span>
                                        <span id="progress-text" class="small fw-bold text-primary">0%</span>
                                    </div>
                                    <div class="progress rounded-pill shadow-none" style="height: 10px;">
                                        <div id="main-progress-bar" class="progress-bar bg-gradient-success rounded-pill"
                                            role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <div class="mt-2 text-center">
                                        <small id="answered-count" class="text-muted fw-bold">0 / 36 پرسیار</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dimension Stepper -->
                <div class="dimension-stepper mb-5 position-relative fade-in">
                    <div class="stepper-track"></div>
                    <div class="stepper-progress" id="stepper-progress"></div>
                    <div class="d-flex justify-content-between align-items-center position-relative px-2">
                        @php
                            $dimensions = [
                                'EI' => ['icon' => 'fa-users', 'label' => 'کۆمەڵایەتی'],
                                'SN' => ['icon' => 'fa-eye', 'label' => 'هەست/ژیرێتی'],
                                'TF' => ['icon' => 'fa-brain', 'label' => 'بیرکردنەوە'],
                                'JP' => ['icon' => 'fa-gavel', 'label' => 'ڕێکخستن'],
                            ];
                        @endphp
                        @foreach ($dimensions as $key => $dim)
                            <div class="step-item {{ $loop->first ? 'active' : '' }}" data-dimension="{{ $key }}">
                                <div class="step-icon">
                                    <i class="fas {{ $dim['icon'] }}"></i>
                                    <div class="step-check"><i class="fas fa-check"></i></div>
                                </div>
                                <span class="step-label d-none d-md-block">{{ $dim['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="{{ route('student.mbti.store') }}" id="mbtiForm">
                    @csrf

                    @foreach ($questions as $dimension => $dimensionQuestions)
                        <div class="dimension-section {{ $loop->first ? 'active' : 'd-none' }}"
                            id="section-{{ $dimension }}">
                            @foreach ($dimensionQuestions as $qIndex => $question)
                                <div class="question-card glass border-0 shadow-sm mb-4 fade-in-right"
                                    style="animation-delay: {{ $qIndex * 0.1 }}s">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start mb-4">
                                            <div class="question-number me-3">
                                                <span>{{ $loop->parent->index * 9 + $loop->iteration }}</span>
                                            </div>
                                            <h5 class="question-text fw-bold mb-0 pt-2">{{ $question->question_ku }}</h5>
                                        </div>

                                        <div class="rating-scale-modern">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <div class="modern-rating-box">
                                                        <input type="radio" name="answers[{{ $question->id }}]"
                                                            id="q{{ $question->id }}_{{ $i }}"
                                                            value="{{ $i }}" class="modern-rating-input"
                                                            required>
                                                        <label for="q{{ $question->id }}_{{ $i }}"
                                                            class="modern-rating-label">
                                                            {{ $i }}
                                                        </label>
                                                    </div>
                                                @endfor
                                            </div>
                                            <div class="d-flex justify-content-between mt-3 px-1">
                                                <span class="small fw-bold text-danger opacity-75">بەهیچ شێوەیەک من
                                                    نیم</span>
                                                <span class="small fw-bold text-success opacity-75">بە تەواوی منم</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between mt-4 pb-5">
                                @if (!$loop->first)
                                    <button type="button"
                                        class="btn btn-light btn-lg rounded-pill px-4 shadow-sm prev-btn">
                                        <i class="fas fa-chevron-right ms-2 scale-rtl"></i> گەڕانەوە بۆ پێشوو
                                    </button>
                                @else
                                    <div></div>
                                @endif

                                @if (!$loop->last)
                                    <button type="button"
                                        class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg next-btn">
                                        بەردەوامبە <i class="fas fa-chevron-left me-2 scale-rtl"></i>
                                    </button>
                                @else
                                    <button type="submit"
                                        class="btn btn-success btn-lg rounded-pill px-5 shadow-lg finish-btn">
                                        کۆتایی و بینینی ئەنجام <i class="fas fa-paper-plane me-2 scale-rtl"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </form>
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

        .bg-gradient-purple {
            background: linear-gradient(135deg, var(--purple-primary), var(--purple-dark));
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #00B894, #55EFC4);
        }

        .bg-white-transparent {
            background: rgba(255, 255, 255, 0.2);
        }

        .icon-box-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-soft-primary {
            background: rgba(108, 92, 231, 0.1);
        }

        .border-soft {
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Welcome Card Shapes */
        .shape-1,
        .shape-2 {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .shape-1 {
            width: 150px;
            height: 150px;
            top: -50px;
            left: -50px;
        }

        .shape-2 {
            width: 100px;
            height: 100px;
            bottom: -20px;
            right: 10%;
        }

        /* Stepper Styles */
        .dimension-stepper {
            max-width: 800px;
            margin: 0 auto;
        }

        .stepper-track {
            position: absolute;
            top: 25px;
            left: 40px;
            right: 40px;
            height: 4px;
            background: #e9ecef;
            border-radius: 10px;
            z-index: 0;
        }

        .stepper-progress {
            position: absolute;
            top: 25px;
            left: 40px;
            width: 0%;
            height: 4px;
            background: var(--purple-primary);
            border-radius: 10px;
            z-index: 1;
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [dir="rtl"] .stepper-progress {
            left: auto;
            right: 40px;
        }

        .dimension-stepper .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            z-index: 2;
            width: 80px;
            transition: all 0.3s ease;
        }

        .dimension-stepper .step-icon {
            width: 54px;
            height: 54px;
            background: white;
            border: 3px solid #e9ecef;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #adb5bd;
            margin-bottom: 10px;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
        }

        .step-check {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 22px;
            height: 22px;
            background: var(--success-vibrant);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            opacity: 0;
            transform: scale(0);
            transition: all 0.3s ease;
        }

        .dimension-stepper .step-item.active .step-icon {
            background: white;
            border-color: var(--purple-primary);
            color: var(--purple-primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(108, 92, 231, 0.15);
        }

        .dimension-stepper .step-item.completed .step-icon {
            border-color: var(--success-vibrant);
            color: var(--success-vibrant);
        }

        .dimension-stepper .step-item.completed .step-check {
            opacity: 1;
            transform: scale(1);
        }

        .step-label {
            font-size: 12px;
            font-weight: 700;
            color: #6c757d;
            white-space: nowrap;
        }

        .step-item.active .step-label {
            color: var(--purple-primary);
        }

        /* Question Cards */
        .question-card {
            border-radius: 20px;
            border: 2px solid transparent !important;
            transition: all 0.3s ease;
        }

        .question-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.06) !important;
        }

        .question-card.answered {
            border-color: rgba(0, 184, 148, 0.2) !important;
            background: rgba(0, 184, 148, 0.02) !important;
        }

        .question-number {
            width: 44px;
            height: 44px;
            background: #f8f9fa;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: var(--purple-primary);
            transition: all 0.3s ease;
        }

        .answered .question-number {
            background: var(--success-vibrant);
            color: white;
        }

        .question-text {
            color: #2D3436;
            line-height: 1.6;
            font-size: 1.15rem;
        }

        /* Modern Rating Style (Based on User Radio Snippet) */
        .rating-scale-modern {
            position: relative;
            z-index: 1;
        }

        .modern-rating-box {
            position: relative;
            flex: 1;
            min-width: 45px;
        }

        .modern-rating-input {
            display: none;
            visibility: hidden;
        }

        .modern-rating-label {
            position: relative;
            padding-inline-start: 2em;
            padding-inline-end: 1em;
            line-height: 2;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #9098A9;
            transition: 0.25s all ease;
            width: 100%;
            height: 2em;
        }

        .modern-rating-label:before {
            box-sizing: border-box;
            content: " ";
            position: absolute;
            top: 0.3em;
            inset-inline-start: 0;
            display: block;
            width: 1.4em;
            height: 1.4em;
            border: 2px solid #9098A9;
            border-radius: 50%;
            z-index: -1;
            transition: 0.25s all ease;
        }

        .modern-rating-label:after {
            content: " ";
            position: absolute;
            top: 0.65em;
            inset-inline-start: 0.35em;
            display: block;
            width: 0.7em;
            height: 0.7em;
            background: #474bff;
            border-radius: 50%;
            opacity: 0;
            transform: scale(0);
            transition: all 0.25s ease;
        }

        .modern-rating-input:checked+.modern-rating-label {
            padding-inline-start: 1em;
            color: #0004ad;
        }

        .modern-rating-input:checked+.modern-rating-label:before {
            top: 0;
            inset-inline-start: 0;
            width: 100%;
            height: 2em;
            background: #e0e1ff;
            border-color: #474bff;
            border-radius: 6px;
        }

        .modern-rating-input:checked+.modern-rating-label:after {
            opacity: 1;
            transform: scale(1);
            top: 0.75em;
            inset-inline-start: 0.5em;
            width: 0.5em;
            height: 0.5em;
            background: #474bff;
        }

        @media (max-width: 576px) {
            .modern-rating-box {
                min-width: 35px;
            }

            .modern-rating-label:before {
                width: 1.8em;
                height: 1.8em;
            }
        }

        /* Animations */
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .fade-in-right {
            animation: fadeInRight 0.5s ease-out both;
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

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
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

        @media (max-width: 768px) {
            .rating-label-circle {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }

            .rating-line {
                top: 27px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const totalQuestions = 36;
            let answeredCount = 0;

            // Update Progress
            function updateProgress() {
                const checked = $('.modern-rating-input:checked').length;
                const percent = Math.round((checked / totalQuestions) * 100);
                $('#main-progress-bar').css('width', percent + '%');
                $('#progress-text').text(percent + '%');
                $('#answered-count').text(checked + ' / ' + totalQuestions + ' پرسیار');

                // Track current section completion for stepper
                let completedCount = 0;
                $('.dimension-section').each(function() {
                    const id = $(this).attr('id').replace('section-', '');
                    const sectionQuestions = $(this).find('.question-card').length;
                    const sectionAnswered = $(this).find('.modern-rating-input:checked').length;

                    if (sectionAnswered === sectionQuestions) {
                        $(`.step-item[data-dimension="${id}"]`).addClass('completed');
                        completedCount++;
                    } else {
                        $(`.step-item[data-dimension="${id}"]`).removeClass('completed');
                    }
                });

                // Update stepper line progress
                const stepperProgressPercent = (completedCount / 4) * 100;
                $('#stepper-progress').css('width', stepperProgressPercent + '%');
            }

            $('.modern-rating-input').on('click', function() {
                const card = $(this).closest('.question-card');
                card.addClass('answered');
                updateProgress();
            });

            // Navigation logic
            $('.next-btn').on('click', function() {
                const currentSection = $(this).closest('.dimension-section');
                const unanswered = currentSection.find('.question-card').filter(function() {
                    return $(this).find('.modern-rating-input:checked').length === 0;
                });

                if (unanswered.length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'بوەستە!',
                        text: 'تکایە هەموو پرسیارەکانی ئەم بەشە وەڵام بدەوە پێش ئەوەی بەردەوام بیت.',
                        confirmButtonText: 'باشە',
                        confirmButtonColor: '#6C5CE7'
                    });
                    unanswered.first()[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    unanswered.first().addClass('shake-effect');
                    setTimeout(() => unanswered.first().removeClass('shake-effect'), 500);
                    return;
                }

                const nextSection = currentSection.next('.dimension-section');
                if (nextSection.length) {
                    currentSection.addClass('d-none').removeClass('active');
                    nextSection.removeClass('d-none').addClass('active');

                    const dimId = nextSection.attr('id').replace('section-', '');
                    $('.step-item').removeClass('active');
                    $(`.step-item[data-dimension="${dimId}"]`).addClass('active');

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });

            $('.prev-btn').on('click', function() {
                const currentSection = $(this).closest('.dimension-section');
                const prevSection = currentSection.prev('.dimension-section');
                if (prevSection.length) {
                    currentSection.addClass('d-none').removeClass('active');
                    prevSection.removeClass('d-none').addClass('active');

                    const dimId = prevSection.attr('id').replace('section-', '');
                    $('.step-item').removeClass('active');
                    $(`.step-item[data-dimension="${dimId}"]`).addClass('active');

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });

            // Form Submit Check
            $('#mbtiForm').on('submit', function(e) {
                const checked = $('.modern-rating-input:checked').length;
                if (checked < totalQuestions) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'پرسیارەکان تەواو نەکراون!',
                        text: `تۆ تەنها ${checked} پرسیارت لە ${totalQuestions} وەڵامداوە.`,
                        confirmButtonText: 'پێداچوونەوە',
                        confirmButtonColor: '#d63031'
                    });
                }
            });

            // Click on stepper to switch if completed
            $('.step-item').on('click', function() {
                const targetDim = $(this).data('dimension');
                const targetSection = $(`#section-${targetDim}`);

                // Allow moving to any section if current or previous are completed (standard UX for psych tests)
                $('.dimension-section').addClass('d-none').removeClass('active');
                targetSection.removeClass('d-none').addClass('active');
                $('.step-item').removeClass('active');
                $(this).addClass('active');
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endpush

