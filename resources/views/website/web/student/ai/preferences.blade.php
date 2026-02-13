@extends('website.web.admin.layouts.app')

@section('title', 'تایبەتمەندیەکانی ڕیزبەندی')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Title & Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 text-muted">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">تایبەتمەندیەکان</li>
                        </ol>
                    </div>
                    <h4 class="page-title fw-bold font-primary">
                        <i class="fas fa-sliders-h me-2 text-primary"></i>
                        دیاریکردنی فیلتەرەکان
                    </h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                @if (!empty($aiRestricted))
                    <div class="card glass border-0 shadow-lg mb-4 overflow-hidden fade-in-up">
                        <div class="card-header bg-warning text-white p-4">
                            <h5 class="mb-0">
                                <i class="fas fa-lock me-2"></i>
                                سیستەمی AI بۆت چالاک نییە
                            </h5>
                        </div>
                        <div class="card-body p-5 text-center">
                            <div class="mb-4">
                                <div class="icon-box-lg bg-soft-warning rounded-circle mx-auto mb-3">
                                    <i class="fas fa-lock text-warning fa-2x"></i>
                                </div>
                                <h5 class="fw-bold">تایبەتمەندی AI چالاک نییە</h5>
                                <p class="text-muted px-4">
                                    بۆ ئەوەی بتوانیت AI Ranking بەکاربهێنیت، پێویستە داواکاری بنێریت بۆ بەڕێوەبەر.
                                </p>
                            </div>
                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                <a href="{{ route('student.departments.request-more') }}"
                                    class="btn btn-warning fw-bold px-4 rounded-pill shadow-sm">
                                    <i class="fas fa-paper-plane me-1"></i> ناردنی داواکاری بۆ چالاککردن
                                </a>
                                <a href="{{ route('student.dashboard') }}"
                                    class="btn btn-outline-secondary px-4 rounded-pill">
                                    <i class="fas fa-arrow-left me-1"></i> گەڕانەوە
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Instructions Card -->
                    <div class="alert alert-info border-0 rounded-3 mb-4 fade-in-up" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-info me-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <h5 class="alert-heading">بۆ خوێندن!</h5>
                                <p class="mb-0">لەمانە هەڵبژێرە کاتێک AI ئەنجامی ڕیزبەندیت دەکات. ئەم فیلتەرانە
                                    توانایی دەدات بەشەکانی مناسیب بە تۆ بۆ پیشاندار کات.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences Form Card -->
                    <div class="card glass border-0 shadow-lg mb-4 overflow-hidden fade-in-up">
                        <div class="card-header bg-gradient-primary p-4">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-cogs me-2"></i>
                                تایبەتمەندیەکانی ڕیزبەندی
                            </h5>
                        </div>

                        <form id="preferencesForm" class="card-body p-4">
                            @csrf

                            <!-- Personal Considerations Section -->
                            <div class="section-divider mb-4">
                                <h6 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-user me-2 text-primary"></i>
                                    تایبەتمەندیەکانی کەسی
                                </h6>

                                <div class="form-check form-switch mb-3 p-3 bg-light rounded-2 transition-all hover-shadow">
                                    <input class="form-check-input" type="checkbox" id="personality"
                                        name="consider_personality" value="1"
                                        @if($preference->consider_personality) checked @endif>
                                    <label class="form-check-label" for="personality">
                                        <strong>جۆری کەسی (MBTI) بە هیچ بگرە</strong>
                                        <br>
                                        <small class="text-muted">ئایە AI جۆری کەسیتی (ئەگەر بتێپۆی) بە بڕوام بگرێت بۆ
                                            باشترین بەشەکان بۆ پیشاندار بکات</small>
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3 p-3 bg-light rounded-2 transition-all hover-shadow">
                                    <input class="form-check-input" type="checkbox" id="markBonus" name="use_mark_bonus"
                                        value="1" @if($preference->use_mark_bonus) checked @endif>
                                    <label class="form-check-label" for="markBonus">
                                        <strong>بۆنەسی نمرەی خوێندن بەکاربێنە</strong>
                                        <br>
                                        <small class="text-muted">ئایە بۆنەسی نمرە بۆ بەشەکانت زیاد بکات بەپێی نمرەی
                                            ئیمتیحان</small>
                                    </label>
                                </div>
                            </div>

                            <hr>

                            <!-- Geographic Preferences Section -->
                            <div class="section-divider mb-4">
                                <h6 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                    پیشتری شوێنگەل
                                </h6>

                                <div class="form-check form-switch mb-3 p-3 bg-light rounded-2 transition-all hover-shadow">
                                    <input class="form-check-input" type="checkbox" id="nearby"
                                        name="prefer_nearby_departments" value="1"
                                        @if($preference->prefer_nearby_departments) checked @endif
                                        onchange="toggleProvinceSelect()">
                                    <label class="form-check-label" for="nearby">
                                        <strong>بەشەکانی نزیکترین پاریزگا</strong>
                                        <br>
                                        <small class="text-muted">بەشەکانی نزیکترین بە پاریزگای تۆ بۆ پریویت کات</small>
                                    </label>
                                </div>

                                <div id="provinceSelectGroup" class="ms-4 mb-3"
                                    style="@if(!$preference->prefer_nearby_departments) display: none; @endif">
                                    <label for="province" class="form-label fw-5 text-muted">پاریزگای پریویت</label>
                                    <select id="province" name="province_filter"
                                        class="form-select form-select-lg rounded-2">
                                        <option value="">-- پاریزگای تۆی نوێنە --</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}"
                                                @if($preference->province_filter == $province->id) selected @endif>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <!-- System Preferences Section (for year 1 students) -->
                            @if ($student->year == 1)
                                <div class="section-divider mb-4">
                                    <h6 class="fw-bold text-dark mb-3">
                                        <i class="fas fa-layer-group me-2 text-primary"></i>
                                        سیستەمەکانی خوێندن
                                    </h6>

                                    <small class="text-muted d-block mb-3">کام سیستەمی خوێندن پریویتی تۆیە؟</small>

                                    @foreach ($systems as $system)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox"
                                                id="system{{ $system->id }}" name="preferred_systems[]"
                                                value="{{ $system->id }}"
                                                @if(in_array($system->id, $preference->preferred_systems ?? [])) checked @endif>
                                            <label class="form-check-label" for="system{{ $system->id }}">
                                                {{ $system->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <hr>
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-flex gap-3 mt-5 print-hide">
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1 rounded-2">
                                    <i class="fas fa-check me-2"></i>
                                    پاشەکشانی و بۆ پرسیارەکان بڕۆ
                                </button>
                                <a href="{{ route('student.dashboard') }}"
                                    class="btn btn-outline-secondary btn-lg rounded-2">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    گەڕاوە
                                </a>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
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

        .transition-all {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .section-divider {
            padding: 1.5rem 0;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-soft-warning {
            background: rgba(255, 193, 7, 0.1);
        }

        .icon-box-lg {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
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

    <script>
        function toggleProvinceSelect() {
            const checkbox = document.getElementById('nearby');
            const provinceGroup = document.getElementById('provinceSelectGroup');
            if (!checkbox || !provinceGroup) {
                return;
            }
            provinceGroup.style.display = checkbox.checked ? 'block' : 'none';
        }

        const preferencesForm = document.getElementById('preferencesForm');
        if (preferencesForm) {
            // Handle form submission
            preferencesForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData);

                // Convert checkboxes to proper format
                data.consider_personality = document.getElementById('personality').checked ? 1 : 0;
                data.use_mark_bonus = document.getElementById('markBonus').checked ? 1 : 0;
                data.prefer_nearby_departments = document.getElementById('nearby').checked ? 1 : 0;
                data.mark_bonus_enabled = 1; // Default to enabled
                data.preferred_systems = Array.from(document.querySelectorAll(
                    'input[name="preferred_systems[]"]:checked'
                )).map(cb => cb.value);
                data.province_filter = document.getElementById('province').value || null;

                try {
                    const response = await fetch('{{ route('student.ai-ranking.save-preferences') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Show success message
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-success alert-dismissible fade show';
                        alert.innerHTML = `
                            ${result.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        const container = document.querySelector('.container-fluid');
                        if (container) {
                            container.prepend(alert);
                        } else {
                            document.body.prepend(alert);
                        }

                        // Redirect after 1 second
                        setTimeout(() => {
                            window.location.href = result.redirect;
                        }, 1500);
                    } else {
                        alert('ھێڵە: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('ھێڵە لە دەست کەوتن');
                }
            });
        }
    </script>
@endsection
