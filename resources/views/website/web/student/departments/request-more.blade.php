{{-- resources/views/student/departments/request-more.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('title', 'داواکردنی بەشی زیاتر')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Title & Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">داواکردنی بەشی زیاتر</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-building-columns me-1"></i>
                        داواکردنی بەشی زیاتر
                    </h4>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-warning shadow-lg">
                    <div class="card-header bg-warning text-white py-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-envelope-open-text fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">داواکردنی مۆڵەتی بەشی زیاتر</h4>
                                <p class="mb-0">قوتابی: {{ $student->user->name }} | کۆد: {{ $student->user->code }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @php
                            $allEnabled =
                                $student->all_departments == 1 && $student->ai_rank == 1 && $student->gis == 1;
                            $hasPending = $existingRequest && $existingRequest->isPending();
                            $anyMissing =
                                $student->all_departments == 0 || $student->ai_rank == 0 || $student->gis == 0;
                        @endphp

                        @if ($hasPending)
                            <div class="alert alert-info shadow-sm border-info mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock fa-2x me-3 text-info"></i>
                                    <div>
                                        <h5 class="alert-heading fw-bold">داواکارییەکەت لە پڕۆسەی چاوەڕوانیدایە</h5>
                                        <p class="mb-0 text-dark">تۆ پێشتر داواکاریت ناردووە و لە ئێستادا لەلایەن
                                            بەڕێوەبەرەوە پێداچوونەوەی بۆ دەکرێت.</p>
                                        <div class="mt-2 small">
                                            <strong>کاتی ناردن:</strong>
                                            {{ $existingRequest->created_at->format('Y/m/d - H:i') }}<br>
                                            <strong>ناوەرۆک:</strong> {{ $existingRequest->reason }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($allEnabled)
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                                <h3 class="fw-bold text-success">هەموو تایبەتمەندییەکان چالاککراون!</h3>
                                <p class="text-muted fs-5">تۆ لە ئێستادا خاوەنی هەموو مۆڵەت و تایبەتمەندییە نایابەکانی
                                    سیستەمیت.</p>
                                <div class="d-flex justify-content-center gap-3 mt-4">
                                    <span class="badge bg-success p-2"><i class="fas fa-check me-1"></i> ٥٠ بەش</span>
                                    <span class="badge bg-success p-2"><i class="fas fa-check me-1"></i> ڕیزبەندی کرد بە
                                        زیرەکی دەستکردing</span>
                                    <span class="badge bg-success p-2"><i class="fas fa-check me-1"></i> GIS Map</span>
                                </div>
                                <div class="mt-5">
                                    <a href="{{ route('student.departments.selection') }}"
                                        class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-university me-2"></i>بۆ پەیجی هەڵبژاردنەکان
                                    </a>
                                </div>
                            </div>
                        @elseif($anyMissing)
                            @if (!$hasPending)
                                <div class="card bg-soft-info border-info-soft border-dashed mb-4 shadow-none text-start">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar-xs flex-shrink-0 me-2">
                                                <span class="avatar-title bg-info rounded-circle fs-13 p-2">
                                                    <i class="fa-solid fa-credit-card text-white"></i>
                                                </span>
                                            </div>
                                            <h6 class="mb-0 text-info fw-bold m-2">ڕێنمایی و جۆری چالاککردن</h6>
                                        </div>
                                        @php
                                            $featurePrices = json_decode($appSettings['feature_prices'] ?? '', true) ?? [
                                                '1' => 3000,
                                                '2' => 5000,
                                                '3' => 6000,
                                            ];
                                        @endphp
                                        <div class="ms-1">
                                            <p class="mb-2 small text-muted lh-lg">
                                                ئەو تایبەتمەندییانەی کە تا ئێستا بۆت چالاک نەکراون، لێرەدا دەتوانیت داوایان
                                                بکەیت. بۆ چالاککردن، پێویستە بڕی
                                                <span id="featurePriceText"
                                                    class="badge bg-soft-info text-info border border-info-soft fw-bold fx-text fx-gradient"
                                                    style="font-size: 16px">{{ number_format($featurePrices['1'] ?? 3000) }}</span>
                                                دینار بۆ ژمارەی
                                                <span
                                                    class="badge bg-soft-primary text-primary border border-primary-soft fw-bold fx-text fx-wavy"
                                                    style="font-size: 16px">2542 434 0750</span>
                                                بنێریت لە ڕێگای
                                                <span class="fx-text fx-glitch text-decoration-underline" data-text="FastPay"
                                                    style="font-size: 16px; color: #ED3163;">FastPay</span>
                                                یان
                                                <span class="fx-text fx-extrude text-decoration-underline"
                                                    style="font-size: 16px; color: #121212;">FIB</span>.
                                            </p>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                <span class="badge bg-light text-dark border">1 => {{ number_format($featurePrices['1'] ?? 3000) }}</span>
                                                <span class="badge bg-light text-dark border">2 => {{ number_format($featurePrices['2'] ?? 5000) }}</span>
                                                <span class="badge bg-light text-dark border">3 => {{ number_format($featurePrices['3'] ?? 6000) }}</span>
                                            </div>
                                            <div class="alert alert-light border-0 mb-0 py-2 px-3 small text-muted">
                                                <i class="fa-solid fa-camera me-1 text-primary"></i>
                                                وێنەی پارە دانەکەت بۆ <b><a href="https://t.me/AGHA_ACE"
                                                            class="fx-text fx-glitch" data-text="Telegram">Telegram</a></b>
                                                یان <b><a href="https://wa.me/9647504342452" class="fx-text fx-glitch"
                                                            data-text="WhatsApp">WhatsApp</a></b> یان <b><a
                                                            href="viber://chat?number=9647504342452"
                                                            class="fx-text fx-glitch" data-text="Viber">Viber</a></b> ی هەمان ژمارە
                                                بنێرە بۆ چالاککردنی خێرا.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <hr class="my-4">
                                <div class="text-center mb-4">
                                    <p class="text-muted">دەتوانیت داواکارییەکی نوێ بنێریت یان داواکارییەکەی پێشوو
                                        هەڵوەشێنیتەوە.</p>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('student.departments.submit-request') }}" id="requestForm"
                                class="{{ $hasPending ? 'opacity-75' : '' }}" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-4 text-start">
                                    <label class="form-label fw-bold fs-5">
                                        <i class="fas fa-list-check text-primary me-2"></i>جۆرەکانی داواکاری کە دەتەوێت:
                                    </label>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div
                                                class="card h-100 border-{{ $student->all_departments == 1 ? 'success bg-light' : 'primary shadow-sm' }} feature-card">
                                                <div class="card-body">
                                                    <div class="form-check custom-option">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="request_types[]" value="all_departments" id="allDeps"
                                                            {{ $student->all_departments == 1 ? 'disabled checked' : '' }}>
                                                        <label class="form-check-label fw-bold d-block" for="allDeps">
                                                            <i
                                                                class="fas fa-layer-group mb-2 d-block fa-2x text-warning"></i>
                                                            مۆڵەتی ٥٠ بەش
                                                        </label>
                                                        <div class="small text-muted mt-2">
                                                            دەتوانی زانیاری لەسەر هەموو بەشەکانی تری پارێزگاکان سەیر بکەیت.
                                                        </div>
                                                    </div>
                                                    @if ($student->all_departments == 1)
                                                        <span class="badge bg-success mt-2">چالاکە</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div
                                                class="card h-100 border-{{ $student->ai_rank == 1 ? 'success bg-light' : 'primary shadow-sm' }} feature-card">
                                                <div class="card-body">
                                                    <div class="form-check custom-option">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="request_types[]" value="ai_rank" id="aiRank"
                                                            {{ $student->ai_rank == 1 ? 'disabled checked' : '' }}>
                                                        <label class="form-check-label fw-bold d-block" for="aiRank">
                                                            <i class="fas fa-robot mb-2 d-block fa-2x text-success"></i>
                                                            سیستەمی AI
                                                        </label>
                                                    </div>
                                                    @if ($student->ai_rank == 1)
                                                        <span class="badge bg-success mt-2">چالاکە</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div
                                                class="card h-100 border-{{ $student->gis == 1 ? 'success bg-light' : 'primary shadow-sm' }} feature-card">
                                                <div class="card-body">
                                                    <div class="form-check custom-option">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="request_types[]" value="gis" id="gisSys"
                                                            {{ $student->gis == 1 ? 'disabled checked' : '' }}>
                                                        <label class="form-check-label fw-bold d-block" for="gisSys">
                                                            <i
                                                                class="fas fa-map-marked-alt mb-2 d-block fa-2x text-info"></i>
                                                            سیستەمی نەخشە
                                                        </label>
                                                    </div>
                                                    @if ($student->gis == 1)
                                                        <span class="badge bg-success mt-2">چالاکە</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="requestTypesError" class="text-danger small mt-2 d-none">
                                        <i class="fas fa-exclamation-circle me-1"></i>تکایە بەلایەنی کەم یەک دانە هەڵبژێرە.
                                    </div>
                                </div>

                                <div class="mb-4 text-start">
                                    <label for="receipt_image" class="form-label fw-bold">
                                        <i class="fas fa-image text-primary me-2"></i>وێنەی پارەدانەکەت (Receipt)
                                    </label>
                                    <input type="file" class="form-control @error('receipt_image') is-invalid @enderror"
                                        id="receipt_image" name="receipt_image" accept="image/*"
                                        {{ $hasPending ? 'disabled' : '' }}>
                                    @error('receipt_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="receiptPreview" class="mt-3 d-none">
                                        <div class="small text-muted mb-2">پێشبینینی وێنە</div>
                                        <img src="#" alt="Receipt Preview"
                                            class="img-fluid rounded border shadow-sm">
                                    </div>
                                </div>

                                <div class="mb-4 text-start">
                                    <label for="reason" class="form-label fw-bold">
                                        <i class="fas fa-comment-dots text-primary me-2"></i>هۆکاری داواکارییەکەت ڕوون
                                        بکەرەوە:
                                    </label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="5"
                                        placeholder="بۆچی پێویستت بەم تایبەتمەندییانەیە؟" {{ $hasPending ? 'disabled' : '' }}>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-center pt-3 border-top">
                                    @if (!$hasPending)
                                        <button type="submit" class="btn btn-warning btn-lg px-5 fw-bold shadow">
                                            <i class="fas fa-paper-plane me-2"></i>ناردنی داواکاری بۆ بەڕێوەبەر
                                        </button>
                                        <a href="{{ route('student.departments.selection') }}"
                                            class="btn btn-outline-secondary btn-lg px-5 ms-2">
                                            <i class="fas fa-arrow-left me-2"></i>گەڕانەوە
                                        </a>
                                    @endif
                                </div>
                            </form>

                            @if ($hasPending)
                                <div class="text-center pt-3 border-top">
                                    <form method="POST"
                                        action="{{ route('student.departments.cancel-request', $existingRequest->id) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-lg px-5">
                                            <i class="fas fa-times me-2"></i>هەڵوەشاندنەوەی داواکاریی پێشوو
                                        </button>
                                    </form>
                                    <a href="{{ route('student.departments.selection') }}"
                                        class="btn btn-outline-secondary btn-lg px-5 ms-2">
                                        <i class="fas fa-arrow-left me-2"></i>گەڕانەوە
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // پشکنینی کەمترین یەک جۆر هەڵبژێردراوە
            const form = document.getElementById('requestForm');
            // Ensure form exists before trying to access its elements
            if (!form) return;

            const checkboxes = form.querySelectorAll('input[name="request_types[]"]:not(:disabled)');
            const errorDiv = document.getElementById('requestTypesError');
            const priceMap = @json($featurePrices ?? ['1' => 3000, '2' => 5000, '3' => 6000]);
            const priceText = document.getElementById('featurePriceText');
            const receiptInput = document.getElementById('receipt_image');
            const receiptPreview = document.getElementById('receiptPreview');
            const receiptPreviewImg = receiptPreview ? receiptPreview.querySelector('img') : null;

            function updatePrice() {
                if (!priceText) return;
                let checkedCount = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) checkedCount++;
                });
                const price = priceMap[String(checkedCount)] ?? priceMap[checkedCount] ?? 0;
                priceText.textContent = price ? Number(price).toLocaleString() : '0';
            }

            form.addEventListener('submit', function(e) {
                let checkedCount = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) checkedCount++;
                });

                if (checkedCount === 0) {
                    e.preventDefault();
                    errorDiv.classList.remove('d-none');
                    errorDiv.scrollIntoView({
                        behavior: 'smooth'
                    });
                    return;
                }

            });

            // خۆکار ڕەفتارکردن
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        errorDiv.classList.add('d-none');
                    }
                    updatePrice();
                });
            });

            if (receiptInput && receiptPreview && receiptPreviewImg) {
                receiptInput.addEventListener('change', function() {
                    const file = this.files && this.files[0];
                    if (!file) {
                        receiptPreview.classList.add('d-none');
                        receiptPreviewImg.src = '#';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        receiptPreviewImg.src = e.target.result;
                        receiptPreview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                });
            }

            updatePrice();
        });
    </script>
@endpush
