@extends('website.web.admin.layouts.app')

@section('title', 'داواکردنی تایبەتمەندی')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('center.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">داواکردنی تایبەتمەندی</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-envelope-open-text me-1"></i>
                        داواکردنی تایبەتمەندی
                    </h4>
                </div>
            </div>
        </div>

        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-warning shadow-lg">
                        <div class="card-header bg-warning text-white py-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-envelope-open-text fa-2x"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1">داواکردنی تایبەتمەندی</h4>
                                    <p class="mb-0">سەنتەر: {{ $center->user->name }} | کۆد: {{ $center->user->code }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if ($existingRequest)
                                @if ($existingRequest->isPending())
                                    <div class="alert alert-info">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clock fa-2x me-3"></i>
                                            <div>
                                                <h5 class="alert-heading">داواکاری چاوەڕوانە!</h5>
                                                <p class="mb-0">تۆ پێشتر داواکاریت ناردووە و لە چاوەڕوانی پەسەندکردنێ.</p>
                                                <p class="mb-0 mt-2"><strong>کات:</strong>
                                                    {{ $existingRequest->created_at->format('Y/m/d - H:i') }}</p>
                                                <p class="mb-0"><strong>هۆکار:</strong> {{ $existingRequest->reason }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <form method="POST"
                                            action="{{ route('center.features.cancel-request', $existingRequest->id) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger me-2">
                                                <i class="fas fa-times me-1"></i>هەڵوەشاندنەوەی داواکاری
                                            </button>
                                        </form>
                                        <a href="{{ route('center.dashboard') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>گەڕانەوە
                                        </a>
                                    </div>
                                @endif
                            @else
                                @if ($center->all_departments == 1 && $center->ai_rank == 1 && $center->gis == 1)
                                    <div class="alert alert-success">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star fa-2x me-3"></i>
                                            <div>
                                                <h5 class="alert-heading">هەموو تایبەتمەندییەکان چالاکن!</h5>
                                                <p class="mb-0">سەرجەم تایبەتمەندییەکانی سیستم بۆ ئەم سەنتەرە چالاک کراون
                                                    و
                                                    پێویست بە داواکاری نوێ ناکات.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <a href="{{ route('center.dashboard') }}" class="btn btn-success">
                                            <i class="fas fa-home me-1"></i>گەڕانەوە بۆ داشبۆرد
                                        </a>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle fa-2x me-3"></i>
                                            <div>
                                                <h5 class="alert-heading">سەرنج بدە!</h5>
                                                <p class="mb-0">ئەگەر پێویستت بە تایبەتمەندی زیاتر هەیە، تکایە
                                                    داواکاریەکەت
                                                    لێرە
                                                    بنێرە.</p>
                                                <p class="mb-0 mt-2">داواکاریەکەت دەنێردرێت بۆ بەڕێوەبەری سیستم و پاش چەند
                                                    خولەکێک
                                                    وەڵامی دەدرێتەوە.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('center.features.submit-request') }}"
                                        id="requestForm" enctype="multipart/form-data">
                                        @csrf
                                        {{-- Form content remains here --}}

                                        <div class="mb-4">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-list-check me-1"></i>جۆرەکانی داواکاری
                                            </label>
                                            <div class="card bg-soft-info border-info-soft border-dashed mb-4 shadow-none">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="avatar-xs flex-shrink-0 me-2">
                                                            <span class="avatar-title bg-info rounded-circle fs-13">
                                                                <i class="fa-solid fa-credit-card text-white"></i>
                                                            </span>
                                                        </div>
                                                        <h6 class="mb-0 text-info fw-bold">ڕێنمایی و جۆری چالاککردن</h6>
                                                    </div>
                                                    @php
                                                        $featurePrices = json_decode(
                                                            $appSettings['feature_prices'] ?? '',
                                                            true,
                                                        ) ?? [
                                                            '1' => 3000,
                                                            '2' => 5000,
                                                            '3' => 6000,
                                                        ];
                                                    @endphp
                                                    <div class="ms-1">
                                                        <p class="mb-2 small text-muted lh-lg">
                                                            هەر جۆرێک کە پێویستت پێیەتی هەڵیبژێرە. بۆ چالاککردنی هەر
                                                            کامێکیان،
                                                            پێویستە بڕی
                                                            <span id="featurePriceText"
                                                                class="badge bg-soft-info text-info border border-info-soft fw-bold fx-text fx-gradient">{{ number_format($featurePrices['1'] ?? 3000) }}</span>
                                                            دینار بۆ ئەم ژمارەیە
                                                            <span
                                                                class="badge bg-soft-primary text-primary border border-primary-soft fw-bold">07504342452</span>
                                                            بنێریت لە ڕێگت <span class="fx-text fx-glitch"
                                                                data-text="FastPay">FastPay</span> یان <span
                                                                class="fx-text fx-extrude">FIB</span>.
                                                        </p>
                                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                                            <span class="badge bg-light text-dark border">1 =>
                                                                {{ number_format($featurePrices['1'] ?? 3000) }}</span>
                                                            <span class="badge bg-light text-dark border">2 =>
                                                                {{ number_format($featurePrices['2'] ?? 5000) }}</span>
                                                            <span class="badge bg-light text-dark border">3 =>
                                                                {{ number_format($featurePrices['3'] ?? 6000) }}</span>
                                                        </div>
                                                        <div
                                                            class="alert alert-light border-0 mb-0 py-2 px-3 small text-muted">
                                                            <i class="fa-solid fa-camera me-1 text-primary"></i>
                                                            وێنەی سەرەتا (Receipt) بۆ <b><a href="https://t.me/AGHA_ACE"
                                                                    class="fx-text fx-glitch"
                                                                    data-text="Telegram">Telegram</a></b> یان <b><a
                                                                    href="https://wa.me/9647504342452"
                                                                    class="fx-text fx-glitch"
                                                                    data-text="WhatsApp">WhatsApp</a></b> یان
                                                            <b><a href="viber://chat?number=9647504342452"
                                                                    class="fx-text fx-glitch"
                                                                    data-text="Viber">Viber</a></b> ی هەمان
                                                            ژمارە بنێرە بۆ چالاککردنی خێرا.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- مۆڵەتی ٥٠ بەش -->
                                                <div class="col-md-4 mb-3">
                                                    <div
                                                        class="card h-100 border-{{ $center->all_departments == 1 ? 'success' : 'warning' }}">
                                                        <div class="card-body">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="request_types[]" value="all_departments"
                                                                    id="allDepartmentsCheck"
                                                                    {{ $center->all_departments == 1 ? 'disabled checked' : '' }}>
                                                                <label class="form-check-label fw-bold"
                                                                    for="allDepartmentsCheck">
                                                                    <i class="fas fa-layer-group me-2"></i>مۆڵەتی ٥٠ بەش
                                                                </label>
                                                            </div>
                                                            <p class="card-text mt-2">
                                                                <small>
                                                                    @if ($center->all_departments == 1)
                                                                        <span class="text-success">
                                                                            <i class="fas fa-check-circle me-1"></i>پێشتر
                                                                            پەسەند
                                                                            کراوە
                                                                        </span>
                                                                    @else
                                                                        مۆڵەتی هەڵبژاردنی ٥٠ بەش لەبری ٢٠ بەش
                                                                    @endif
                                                                </small>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- سیستەمی AI -->
                                                <div class="col-md-4 mb-3">
                                                    <div
                                                        class="card h-100 border-{{ $center->ai_rank == 1 ? 'success' : 'warning' }}">
                                                        <div class="card-body">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="request_types[]" value="ai_rank"
                                                                    id="aiRankCheck"
                                                                    {{ $center->ai_rank == 1 ? 'disabled checked' : '' }}>
                                                                <label class="form-check-label fw-bold" for="aiRankCheck">
                                                                    <i class="fas fa-robot me-2"></i>سیستەمی AI
                                                                </label>
                                                            </div>
                                                            <p class="card-text mt-2">
                                                                <small>
                                                                    @if ($center->ai_rank == 1)
                                                                        <span class="text-success">
                                                                            <i class="fas fa-check-circle me-1"></i>پێشتر
                                                                            پەسەند
                                                                            کراوە
                                                                        </span>
                                                                    @else
                                                                        ڕیزکردنی بەشەکان بەهۆی سیستەمی دەستکردی زیرەک
                                                                    @endif
                                                                </small>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- سیستەمی نەخشە (GIS) -->
                                                <div class="col-md-4 mb-3">
                                                    <div
                                                        class="card h-100 border-{{ $center->gis == 1 ? 'success' : 'warning' }}">
                                                        <div class="card-body">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="request_types[]" value="gis" id="gisCheck"
                                                                    {{ $center->gis == 1 ? 'disabled checked' : '' }}>
                                                                <label class="form-check-label fw-bold" for="gisCheck">
                                                                    <i class="fas fa-map-marked-alt me-2"></i>سیستەمی نەخشە
                                                                    (GIS)
                                                                </label>
                                                            </div>
                                                            <p class="card-text mt-2">
                                                                <small>
                                                                    @if ($center->gis == 1)
                                                                        <span class="text-success">
                                                                            <i class="fas fa-check-circle me-1"></i>پێشتر
                                                                            پەسەند
                                                                            کراوە
                                                                        </span>
                                                                    @else
                                                                        هەڵبژاردنی بەشەکان لەسەر نەخشە
                                                                    @endif
                                                                </small>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label for="receipt_image" class="form-label fw-bold">
                                                    <i class="fas fa-image text-primary me-2"></i>وێنەی پارەدانەکەت
                                                    (Receipt)
                                                </label>
                                                <input type="file"
                                                    class="form-control @error('receipt_image') is-invalid @enderror"
                                                    id="receipt_image" name="receipt_image" accept="image/*">
                                                @error('receipt_image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div id="receiptPreview" class="mt-3 d-none">
                                                    <div class="small text-muted mb-2">پێشبینینی وێنە</div>
                                                    <img src="#" alt="Receipt Preview"
                                                        class="img-fluid rounded border shadow-sm">
                                                </div>
                                            </div>

                                            <div id="requestTypesError" class="text-danger small mt-2 d-none">
                                                <i class="fas fa-exclamation-circle me-1"></i>کەمترین یەک جۆر هەڵبژێرە.
                                            </div>
                                        </div>

                                        <!-- هۆکار -->
                                        <div class="mb-4">
                                            <label for="reason" class="form-label fw-bold">
                                                <i class="fas fa-comment-dots me-1"></i>هۆکاری داواکاری
                                            </label>
                                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="6"
                                                placeholder="هۆکاری داواکردنەکانت بنووسە...">{{ old('reason') }}</textarea>
                                            @error('reason')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">بە ڕوونی هۆکارەکانت بۆ هەر جۆرێک ڕوون بکەرەوە. کەمتر لە
                                                ٥٠٠
                                                پیت.
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-warning btn-lg px-5">
                                                <i class="fas fa-paper-plane me-2"></i>ناردنی داواکاری
                                            </button>
                                            <a href="{{ route('center.dashboard') }}"
                                                class="btn btn-secondary btn-lg px-5 ms-2">
                                                <i class="fas fa-arrow-left me-2"></i>گەڕانەوە
                                            </a>
                                        </div>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('requestForm');
            if (!form) return;

            const checkboxes = form.querySelectorAll('input[name="request_types[]"]:not(:disabled)');
            const priceText = document.getElementById('featurePriceText');
            const priceMap = @json($featurePrices ?? ['1' => 3000, '2' => 5000, '3' => 6000]);
            const errorDiv = document.getElementById('requestTypesError');
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

            checkboxes.forEach(cb => cb.addEventListener('change', updatePrice));
            updatePrice();

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
                }
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        errorDiv.classList.add('d-none');
                    }
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
        });
    </script>
@endpush
