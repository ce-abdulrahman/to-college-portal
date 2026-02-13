@extends('website.web.admin.layouts.app')

@section('title', 'سیستەمی AI بند کراوە')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Title & Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 text-muted">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">سیستەمی AI</li>
                        </ol>
                    </div>
                    <h4 class="page-title fw-bold font-primary">
                        <i class="fas fa-lock me-2 text-danger"></i>
                        سیستەمی AI چاڵاک نەکراوە
                    </h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <!-- Restricted Access Card -->
                <div class="card glass border-0 shadow-lg overflow-hidden fade-in-up">
                    <div class="card-header bg-gradient-danger p-5 position-relative">
                        <div class="z-index-1 position-relative text-center">
                            <div
                                class="avatar-lg bg-white-transparent rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 shadow-sm">
                                <i class="fas fa-lock-open fa-2x text-danger p-4"></i>
                            </div>
                            <h3 class="mb-2 fw-bold ">سیستەمی AI بند کراوە</h3>
                            <p class="mb-0 ">ئێستا ئەم سیستەمە بۆت دەسپێنەچوو یا بند کراوە</p>
                        </div>
                        <div class="ai-shapes">
                            <div class="ai-shape-1"></div>
                            <div class="ai-shape-2"></div>
                        </div>
                    </div>

                    <div class="card-body p-5">
                        <!-- Alert Box -->
                        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-lg text-warning"></i>
                                </div>
                                <div>
                                    <h5 class="alert-heading mb-2">سیستەمی AI دەسپێنەچوو</h5>
                                    <p class="mb-0">بۆ بەکارهێنانی ئەم سیستەمە، پێویستە مۆڵەت لە بەڕێوەبەری سیستەمە
                                        وەربگریت.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Info Sections -->
                        <div class="row g-4 mt-3">
                            <div class="col-12">
                                <div class="d-flex align-items-start p-4 bg-light rounded-4">
                                    <div class="icon-box-sm bg-soft-primary rounded-3 me-3 flex-shrink-0">
                                        <i class="fas fa-robot text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">سیستەمی دستیاری هۆشمەند</h6>
                                        <p class="text-muted small mb-0">سیستەمی AIمان بەسوود بەکاردێنێت بۆ دیاریکردنی
                                            باشترین بەشەکانی بۆت لە پێی کەسایەتی، حەز و نمرەکانت.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex align-items-start p-4 bg-light rounded-4">
                                    <div class="icon-box-sm bg-soft-info rounded-3 me-3 flex-shrink-0">
                                        <i class="fas fa-key text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">وەربگرتنی مۆڵەت</h6>
                                        <p class="text-muted small mb-0">بۆ وەربگرتنی دەسپێک بۆ بەکارهێنانی سیستەمی AIمان،
                                            تێکێ سر بکە لە بەڕێوەبەری سیستەمە یان ئیتریشە بە پاڵپشتی.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex align-items-start p-4 bg-light rounded-4">
                                    <div class="icon-box-sm bg-soft-success rounded-3 me-3 flex-shrink-0">
                                        <i class="fas fa-headset text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">پاڵپشتی</h6>
                                        <p class="text-muted small mb-0">ئەگەر پرسیاری هێت، تێکێ سر بکە لە بەڕێوەبەری
                                            سیستەمە یا بۆ پاڵپشتی.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Info -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="text-center p-4 border rounded-4 bg-soft-danger">
                                    <div class="mb-3">
                                        <i class="fas fa-info-circle fa-2x text-danger"></i>
                                    </div>
                                    <h6 class="fw-bold mb-2">بارودۆخی قوتابی</h6>
                                    <p class="small text-muted mb-0">
                                        <strong>ناو:</strong> {{ $student->user->name }}<br>
                                        <strong>کۆد:</strong> {{ $student->user->code ?? 'نیشتمان نییە' }}<br>
                                        <strong>بارودۆخی AI:</strong> <span class="badge bg-danger">بند کراوە</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 mt-5 justify-content-center flex-wrap print-hide">
                            <a href="{{ route('student.dashboard') }}"
                                class="btn btn-light btn-lg rounded-pill px-5 shadow-sm">
                                <i class="fas fa-arrow-right ms-2"></i> گەڕانەوە بۆ داشبۆرد
                            </a>
                            <button type="button" class="btn btn-success btn-lg rounded-pill px-5 shadow-lg"
                                id="supportBtn">
                                <i class="fas fa-headset ms-2"></i> پاڵپشتی
                            </button>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="card glass border-0 shadow-lg mt-5 overflow-hidden">
                    <div class="card-header bg-light p-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-question-circle me-2 text-primary"></i>
                            پرسیارەکانی کۆدەنگ
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="accordion accordion-flush" id="faqAccordion">
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq1">
                                        چۆن دەتوانم مۆڵەتی سیستەمی AI بگەم؟
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body pt-0 pb-2">
                                        تێکێ سر بکە لە بەڕێوەبەری سیستەمە یا ئیتریشە لە ئۆفیسی پاڵپشتی بۆ وەربگرتنی مۆڵەت.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq2">
                                        بەشی AI چی کار دەکات؟
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body pt-0 pb-2">
                                        سیستەمی AIمان وەڵامە سوال پێشکەشخۆیت شیکردنەوە دەکات و باشترین بەشەکانی پێشنیار
                                        دەکات بە پێی نمرە، کەسایەتی و حەز و ئارەزووە.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq3">
                                        کە دەتوانم تێکێ سر بکە؟
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body pt-0 pb-2">
                                        دەتوانی بە رێکلامی ئۆتۆماتیک یان چاپڵنێ بە کەسایەتی بۆ پاڵپشتی.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .bg-gradient-danger {
                background: linear-gradient(135deg, #dc3545, #e74c3c);
            }

            .font-primary {
                font-family: 'NizarNastaliqKurdish', 'Wafeq', sans-serif;
            }

            .glass {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.5);
            }

            .icon-box-sm {
                width: 50px;
                height: 50px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .bg-soft-primary {
                background: rgba(13, 110, 253, 0.1);
            }

            .bg-soft-info {
                background: rgba(13, 202, 240, 0.1);
            }

            .bg-soft-success {
                background: rgba(25, 135, 84, 0.1);
            }

            .bg-soft-danger {
                background: rgba(220, 53, 69, 0.1);
            }

            .text-white-50 {
                color: rgba(255, 255, 255, 0.5);
            }

            .fade-in-up {
                animation: fadeInUp 0.5s ease-out both;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
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

            @media print {
                body {
                    background: #fff !important;
                }

                .page-title-right,
                .breadcrumb,
                .btn,
                .print-hide {
                    display: none !important;
                }

                .card,
                .glass {
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
                // نیشاندانی بارودۆخی خۆکار - هەر 30 چرکە
                function checkAIStatus() {
                    $.ajax({
                        url: '{{ route('student.ai-ranking.check-status') }}',
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'active') {
                                window.location.href = '{{ route('student.ai-ranking.questionnaire') }}';
                            }
                        }
                    });
                }

                setInterval(checkAIStatus, 30000);

                // کلیککردن لەسەر بەشی پاڵپشتی
                $('#supportBtn').click(function() {
                    Swal.fire({
                        title: 'پەیوەندی بە پاڵپشتی',
                        html: `
                        <div class="text-start">
                            <div class="mb-3">
                                <p><i class="fas fa-phone me-2 text-primary"></i><strong>ژمارە مۆبایل:</strong></p>
                                <p class="ps-4">+964 770 123 4567</p>
                                <p class="mt-2"><i class="fas fa-envelope me-2 text-success"></i><strong>ئیماڵ:</strong></p>
                                <p class="ps-4">support@university.edu.krd</p>
                                <p class="mt-2"><i class="fas fa-clock me-2 text-warning"></i><strong>کاتی کارکردن:</strong></p>
                                <p class="ps-4">٨:٠٠ - ١٦:٠٠ (شانبە - چوارشەم)</p>
                            </div>
                        </div>
                    `,
                        icon: 'info',
                        confirmButtonText: 'باشە',
                        confirmButtonColor: '#0984e3'
                    });
                });
            });
        </script>
    @endpush
@endsection
