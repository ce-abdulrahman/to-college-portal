@extends('website.web.admin.layouts.app')

@section('title', 'پرسیارەکانی AI بۆ ڕیزبەندی')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Title & Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 text-muted">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">پشکنی AI</li>
                        </ol>
                    </div>
                    <h4 class="page-title fw-bold  font-primary">
                        <i class="fas fa-robot me-2 text-primary"></i>
                        پرسیارەکانی زیرەکی دەستکرد
                    </h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">
                <!-- AI Intro & Progress Card -->
                <div class="card glass border-0 shadow-lg mb-4 overflow-hidden fade-in-up">
                    <div class="card-header bg-gradient-ai p-4 position-relative">
                        <div class="z-index-1 position-relative d-flex align-items-center">
                            <div
                                class="avatar-lg bg-white-transparent rounded-circle d-flex align-items-center justify-content-center me-4 shadow-sm pulse-ai">
                                <i class="fas fa-brain fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h3 class="mb-1  fw-bold">ڕێبەری زیرەکی دەستکرد بۆ هەڵبژاردنی بەش</h3>
                                <p class="mb-0">قوتابی: {{ $student->user->name }} | ئەم پشکنینە یارمەتی AI
                                    دەدات باشترین بەشەکانت بۆ پێشنیار بکات.</p>
                            </div>
                        </div>
                        <div class="ai-shapes">
                            <div class="ai-shape-1"></div>
                            <div class="ai-shape-2"></div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="icon-box-sm bg-soft-info rounded-3 me-3">
                                        <i class="fas fa-microchip text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">چۆن کار دەکات؟</h6>
                                        <p class="text-muted small mb-0">وەڵامی ئەم پرسیارانە بدەوە بە ڕاستگۆیی. AI
                                            شیکردنەوە بۆ داتا جیاوازەکانت دەکات و ئەنجامەکان ڕیز دەکات.</p>
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
                                        <div id="main-progress-bar" class="progress-bar bg-gradient-primary rounded-pill"
                                            role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Category Stepper -->
                <div class="ai-stepper mb-5 position-relative fade-in">
                    <div class="stepper-track"></div>
                    <div class="stepper-progress" id="stepper-progress"></div>
                    <div class="d-flex justify-content-between align-items-center position-relative px-2">
                        @php
                            $categories = [
                                'personality' => ['icon' => 'fa-user-tie', 'label' => 'کەسایەتی'],
                                'interest' => ['icon' => 'fa-heart', 'label' => 'ئارەزوو'],
                                'location' => ['icon' => 'fa-map-marker-alt', 'label' => 'شوێن'],
                                'priority' => ['icon' => 'fa-flag', 'label' => 'پێشەنگی'],
                            ];
                        @endphp
                        @foreach ($categories as $key => $cat)
                            <div class="step-item text-center {{ $loop->first ? 'active' : '' }}" data-category="{{ $key }}">
                                <div class="step-icon">
                                    <i class="fas {{ $cat['icon'] }} text-{{ $cat['icon'] == 'fa-user-tie' ? 'primary' : ($cat['icon'] == 'fa-heart' ? 'danger' : ($cat['icon'] == 'fa-map-marker-alt' ? 'info' : 'warning')) }}"></i>
                                    <div class="step-check"><i class="fas fa-check text-success"></i></div>
                                </div>
                                <span class="step-label d-none d-md-block">{{ $cat['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <form id="aiQuestionnaireForm" method="POST" action="{{ route('student.ai-ranking.submit') }}">
                    @csrf

                    @foreach (['personality', 'interest', 'location', 'priority'] as $catKey)
                        @if (isset($questions[$catKey]))
                            <div class="category-section {{ $loop->first ? 'active d-block' : 'd-none' }}"
                                id="section-{{ $catKey }}">
                                @foreach ($questions[$catKey] as $qIndex => $question)
                                    <div class="question-card glass border-0 shadow-sm mb-4 fade-in-right"
                                        style="animation-delay: {{ $qIndex * 0.1 }}s">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-start mb-4">
                                                <div class="question-number me-3">
                                                    <span>{{ $loop->iteration }}</span>
                                                </div>
                                                <h5 class="question-text fw-bold mb-0 pt-2">{{ $question->question_ku }}
                                                </h5>
                                            </div>

                                            <div class="options-grid">
                                                @php
                                                    // Handle both JSON string and already-cast array
                                                    $options = is_array($question->options)
                                                        ? $question->options
                                                        : json_decode($question->options, true);

                                                    if (!$options || !is_array($options)) {
                                                        if ($catKey == 'interest') {
                                                            $options = [
                                                                ['text' => 'زۆر حەزێکی پێیە'],
                                                                ['text' => 'حەزێکی پێیە'],
                                                                ['text' => 'مامناوەند'],
                                                                ['text' => 'کەم حەزێکی پێیە'],
                                                                ['text' => 'هیچ حەزێکی پێی نییە'],
                                                            ];
                                                        } else {
                                                            $options = [
                                                                ['text' => 'بەڵێ'],
                                                                ['text' => 'نەخێر'],
                                                                ['text' => 'نازانم'],
                                                            ];
                                                        }
                                                    }
                                                @endphp

                                                <div class="row g-3">
                                                    @foreach ($options as $oIndex => $option)
                                                        <div class="col-md-6 col-lg-4">
                                                            <div class="modern-option-wrapper">
                                                                <input type="radio" name="answers[{{ $question->id }}]"
                                                                    id="q{{ $question->id }}_{{ $oIndex }}"
                                                                    value="{{ $option['text'] }}" class="modern-ai-input"
                                                                    required>
                                                                <label for="q{{ $question->id }}_{{ $oIndex }}"
                                                                    class="modern-ai-label">
                                                                    <span class="option-indicator"></span>
                                                                    <span class="option-text">{{ $option['text'] }}</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between mt-4 pb-5">
                                    @if (!$loop->first)
                                        <button type="button"
                                            class="btn btn-light btn-lg rounded-pill px-4 shadow-sm prev-btn">
                                            <i class="fas fa-chevron-right ms-2 scale-rtl"></i> گەڕانەوە
                                        </button>
                                    @else
                                        <div></div>
                                    @endif

                                    @if (!$loop->last)
                                        <button type="button"
                                            class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg next-btn">
                                            دواتر <i class="fas fa-chevron-left me-2 scale-rtl"></i>
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="btn btn-success btn-lg rounded-pill px-5 shadow-lg finish-btn"
                                            id="submitBtn">
                                            شیکردنەوەی AI دەستپێبکە <i class="fas fa-robot ms-2"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </form>
            </div>
        </div>
    </div>

    <!-- AI Analysis Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass border-0 shadow-2xl overflow-hidden">
                <div class="modal-body text-center py-5 position-relative">
                    <div class="ai-loader-container mb-4">
                        <div class="ai-orb"></div>
                        <div class="ai-rings">
                            <div class="ring"></div>
                            <div class="ring"></div>
                            <div class="ring"></div>
                        </div>
                        <i class="fas fa-brain fa-3x text-white position-absolute top-50 start-50 translate-middle"></i>
                    </div>

                    <h3 class="fw-bold mb-3 font-primary">زیرەکی دەستکرد شیکردنەوە دەکات</h3>
                    <p class="text-muted px-4" id="ai-status-text">خەریکی بەراوردکردنی تواناکانتە لەگەڵ بەشەکانی زانکۆ...
                    </p>

                    <div class="px-5 mt-4">
                        <div class="progress rounded-pill bg-soft-primary" style="height: 8px;">
                            <div id="ai-progress-bar" class="progress-bar bg-gradient-primary rounded-pill"
                                style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        :root {
            --ai-primary: #0984e3;
            --ai-secondary: #6c5ce7;
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

        .bg-gradient-ai {
            background: linear-gradient(135deg, var(--ai-primary), var(--ai-secondary));
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #0984e3, #6c5ce7);
        }

        .bg-white-transparent {
            background: rgba(255, 255, 255, 0.2);
        }

        .ai-shapes .ai-shape-1,
        .ai-shapes .ai-shape-2 {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .ai-shape-1 {
            width: 200px;
            height: 200px;
            top: -100px;
            left: -50px;
        }

        .ai-shape-2 {
            width: 120px;
            height: 120px;
            bottom: -40px;
            right: 10%;
        }

        .pulse-ai {
            animation: pulse 2s infinite ease-in-out;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        /* Stepper */
        .ai-stepper {
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
        }

        .stepper-progress {
            position: absolute;
            top: 25px;
            left: 40px;
            width: 0%;
            height: 4px;
            background: var(--ai-primary);
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        [dir="rtl"] .stepper-progress {
            left: auto;
            right: 40px;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            z-index: 2;
            width: 80px;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            background: white;
            border: 3px solid #e9ecef;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #adb5bd;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
        }

        .step-item.active .step-icon {
            border-color: var(--ai-primary);
            color: var(--ai-primary);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(9, 132, 227, 0.2);
        }

        .step-item.completed .step-icon {
            border-color: #00b894;
            color: #00b894;
        }

        .step-check {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background: #00b894;
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

        .step-item.completed .step-check {
            opacity: 1;
            transform: scale(1);
        }

        .step-label {
            font-size: 12px;
            font-weight: 700;
            color: #6c757d;
            margin-top: 8px;
        }

        /* Question Cards */
        .question-card {
            border-radius: 20px;
            border: 2px solid transparent !important;
            transition: all 0.3s ease;
        }

        .question-card.answered {
            border-color: rgba(9, 132, 227, 0.2) !important;
            background: rgba(9, 132, 227, 0.02) !important;
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
            color: var(--ai-primary);
        }

        /* Modern AI Options */
        .modern-option-wrapper {
            position: relative;
            width: 100%;
        }

        .modern-ai-input {
            display: none;
            visibility: hidden;
        }

        .modern-ai-label {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            min-height: 65px;
            background: white;
            border: 2px solid #f1f3f5;
            border-radius: 16px;
            cursor: pointer;
            transition: 0.2s all ease;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .option-indicator {
            width: 22px;
            height: 22px;
            border: 2px solid #dcdde1;
            border-radius: 50%;
            margin-right: 15px;
            position: relative;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        [dir="rtl"] .option-indicator {
            margin-right: 0;
            margin-left: 15px;
        }

        .modern-ai-label:hover {
            border-color: var(--ai-primary);
            background: rgba(9, 132, 227, 0.02);
        }

        .modern-ai-input:checked+.modern-ai-label {
            border-color: var(--ai-primary);
            background: #f0f7ff;
            color: var(--ai-primary);
        }

        .modern-ai-input:checked+.modern-ai-label .option-indicator {
            border-color: var(--ai-primary);
            background: var(--ai-primary);
        }

        .modern-ai-input:checked+.modern-ai-label .option-indicator:after {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        /* AI Analyzer Styles */
        .ai-loader-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }

        .ai-orb {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #0984e3, #6c5ce7);
            border-radius: 50%;
            filter: blur(20px);
            opacity: 0.6;
            animation: ai-glow 2s infinite alternate;
        }

        .ring {
            position: absolute;
            border: 2px solid rgba(9, 132, 227, 0.3);
            border-radius: 50%;
            animation: ai-rotate 3s linear infinite;
        }

        .ring:nth-child(1) {
            inset: 0;
            animation-duration: 4s;
        }

        .ring:nth-child(2) {
            inset: 10px;
            animation-duration: 3s;
            animation-direction: reverse;
        }

        .ring:nth-child(3) {
            inset: 20px;
            animation-duration: 2s;
        }

        @keyframes ai-glow {
            from {
                opacity: 0.4;
                transform: scale(0.95);
            }

            to {
                opacity: 0.8;
                transform: scale(1.05);
            }
        }

        @keyframes ai-rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .fade-in-right {
            animation: fadeInRight 0.5s ease-out both;
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

        @media print {
            body {
                background: #fff !important;
            }

            .page-title-right,
            .breadcrumb,
            .ai-stepper,
            .progress-wrapper,
            .btn,
            .modal,
            .stepper-track,
            .stepper-progress {
                display: none !important;
            }

            .category-section {
                display: block !important;
                page-break-before: always;
            }

            .category-section:first-of-type {
                page-break-before: auto;
            }

            .question-card {
                page-break-inside: avoid;
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            const totalQuestions = $('.modern-ai-input').length / 3; // Approximation or just use card count
            const questionCardsCount = $('.question-card').length;

            function updateProgress() {
                const checked = $('.modern-ai-input:checked').length;
                const percent = Math.round((checked / questionCardsCount) * 100);
                $('#main-progress-bar').css('width', percent + '%');
                $('#progress-text').text(percent + '%');

                let completedCount = 0;
                $('.category-section').each(function() {
                    const id = $(this).attr('id').replace('section-', '');
                    const sectionCards = $(this).find('.question-card').length;
                    const sectionAnswered = $(this).find('.modern-ai-input:checked').length;

                    if (sectionAnswered === sectionCards) {
                        $(`.step-item[data-category="${id}"]`).addClass('completed');
                        completedCount++;
                    } else {
                        $(`.step-item[data-category="${id}"]`).removeClass('completed');
                    }
                });

                const stepperProgressPercent = (completedCount / 4) * 100;
                $('#stepper-progress').css('width', stepperProgressPercent + '%');
            }

            $('.modern-ai-input').on('click', function() {
                $(this).closest('.question-card').addClass('answered');
                updateProgress();
            });

            // Navigation
            $('.next-btn').on('click', function() {
                const currentSection = $(this).closest('.category-section');
                const unanswered = currentSection.find('.question-card').filter(function() {
                    return $(this).find('.modern-ai-input:checked').length === 0;
                });

                if (unanswered.length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'بوەستە!',
                        text: 'تکایە هەموو پرسیارەکانی ئەم بەشە وەڵام بدەوە.',
                        confirmButtonText: 'باشە',
                        confirmButtonColor: '#0984e3'
                    });
                    unanswered.first()[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return;
                }

                const nextSection = currentSection.next('.category-section');
                if (nextSection.length) {
                    currentSection.addClass('d-none').removeClass('active');
                    nextSection.removeClass('d-none').addClass('active');
                    const catId = nextSection.attr('id').replace('section-', '');
                    $('.step-item').removeClass('active');
                    $(`.step-item[data-category="${catId}"]`).addClass('active');
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });

            $('.prev-btn').on('click', function() {
                const currentSection = $(this).closest('.category-section');
                const prevSection = currentSection.prev('.category-section');
                if (prevSection.length) {
                    currentSection.addClass('d-none').removeClass('active');
                    prevSection.removeClass('d-none').addClass('active');
                    const catId = prevSection.attr('id').replace('section-', '');
                    $('.step-item').removeClass('active');
                    $(`.step-item[data-category="${catId}"]`).addClass('active');
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });

            // AI Submit
            $('#aiQuestionnaireForm').on('submit', function(e) {
                e.preventDefault();

                const checked = $('.modern-ai-input:checked').length;
                if (checked < questionCardsCount) {
                    Swal.fire({
                        icon: 'error',
                        title: 'تەواو نەکراوە',
                        text: 'تکایە هەموو پرسیارەکان وەڵام بدەوە.'
                    });
                    return;
                }

                $('#loadingModal').modal('show');

                // Advanced status updates
                const statuses = [
                    'خەریکی ناسینی کەسایەتیتە...',
                    'شیکردنەوەی حەز و ئارەزووەکانت...',
                    'بەراوردکردنی نمرەکانت لەگەڵ ساڵی پار...',
                    'ڕێکخستنی باشترین پێشنیارەکان...'
                ];
                let sIdx = 0;
                const statusInterval = setInterval(() => {
                    $('#ai-status-text').fadeOut(300, function() {
                        $(this).text(statuses[sIdx]).fadeIn(300);
                        sIdx = (sIdx + 1) % statuses.length;
                    });
                }, 2500);

                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += 2;
                    if (progress <= 95) $('#ai-progress-bar').css('width', progress + '%');
                }, 200);

                $.ajax({
                    url: '{{ route('student.ai-ranking.submit') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        clearInterval(statusInterval);
                        clearInterval(progressInterval);
                        if (response.success) {
                            $('#ai-progress-bar').css('width', '100%');
                            setTimeout(() => {
                                $('#loadingModal').modal('hide');
                                window.location.href = response.redirect;
                            }, 800);
                        } else {
                            $('#loadingModal').modal('hide');
                            Swal.fire({
                                icon: 'error',
                                title: 'هەڵە',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        clearInterval(statusInterval);
                        clearInterval(progressInterval);
                        $('#loadingModal').modal('hide');
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            const firstError = Object.values(xhr.responseJSON.errors)[0]?.[0];
                            Swal.fire({
                                icon: 'error',
                                title: 'هەڵە',
                                text: firstError || 'تکایە زانیاریەکان دوبارە چاوپێکەوە بکە.'
                            });
                            return;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'هەڵە',
                            text: 'هەڵەیەک لە سێرڤەر ڕوویدا.'
                        });
                    }
                });
            });
        });
    </script>
@endpush
