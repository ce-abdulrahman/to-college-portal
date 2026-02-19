@extends('website.web.admin.layouts.app')

@section('title', 'داواکردنی تایبەتمەندی')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">داشبۆرد</a></li>
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
                                    <p class="mb-0">مامۆستا: {{ $teacher->user->name }} | کۆد: {{ $teacher->user->code }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if ($existingRequest && $existingRequest->isPending())
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
                                    <form method="POST" action="{{ route('teacher.features.cancel-request', $existingRequest->id) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger me-2">
                                            <i class="fas fa-times me-1"></i>هەڵوەشاندنەوەی داواکاری
                                        </button>
                                    </form>
                                    <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>گەڕانەوە
                                    </a>
                                </div>
                            @else
                                @php
                                    $defaultFeaturePrices = [
                                        '1' => 3000,
                                        '2' => 5000,
                                        '3' => 6000,
                                    ];
                                    $baseFeaturePrices = json_decode($appSettings['feature_prices'] ?? '', true);
                                    if (!is_array($baseFeaturePrices)) {
                                        $baseFeaturePrices = $defaultFeaturePrices;
                                    }

                                    $featureMultiplier = 3; // teacher
                                    $featurePrices = [];
                                    foreach ($defaultFeaturePrices as $tier => $defaultPrice) {
                                        $featurePrices[$tier] = (int) ($baseFeaturePrices[$tier] ?? $defaultPrice) * $featureMultiplier;
                                    }

                                    $defaultLimitPrices = [
                                        'teacher' => 5000,
                                        'student' => 1000,
                                    ];
                                    $limitPrices = json_decode($appSettings['limit_prices'] ?? '', true);
                                    if (!is_array($limitPrices)) {
                                        $limitPrices = $defaultLimitPrices;
                                    }
                                    $limitStudentUnitPrice = (int) ($limitPrices['student'] ?? $defaultLimitPrices['student']);
                                    $queueHandDepartmentPrice = (int) ($appSettings['queue_hand_department_price'] ?? 0);
                                @endphp

                                <div class="alert alert-warning">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle fa-2x me-3"></i>
                                        <div>
                                            <h5 class="alert-heading">سەرنج بدە!</h5>
                                            <p class="mb-0">دەتوانیت داوای تایبەتمەندی یان زیادکردنی سنووری قوتابی بنێریت.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-light mb-4">
                                    <div class="card-body">
                                        <h6 class="mb-3"><i class="fas fa-gauge me-2"></i>دۆخی سنوور</h6>
                                        <div class="small text-muted">سنووری قوتابی</div>
                                        <div class="fw-semibold">
                                            {{ is_null($teacher->limit_student) ? 'بێ سنوور' : $teacher->limit_student }}
                                            @if (!is_null($teacher->limit_student))
                                                <span class="text-muted"> (ئێستا: {{ $currentStudentsCount ?? 0 }})</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('teacher.features.submit-request') }}" id="requestForm"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-list-check me-1"></i>جۆرەکانی داواکاری
                                        </label>
                                        <div class="card bg-soft-info border-info-soft border-dashed mb-4 shadow-none">
                                            <div class="card-body p-3">
                                                <p class="mb-2 small text-muted lh-lg">
                                                    نرخی تایبەتمەندی (teacher multiplier): 1 =>
                                                    {{ number_format($featurePrices['1']) }}، 2 =>
                                                    {{ number_format($featurePrices['2']) }}، 3 =>
                                                    {{ number_format($featurePrices['3']) }}.
                                                </p>
                                                <p class="mb-2 small text-muted lh-lg">
                                                    نرخی ڕیزبەندی بەشەکان:
                                                    {{ number_format($queueHandDepartmentPrice) }} دینار.
                                                </p>
                                                <p class="mb-2 small text-muted lh-lg">
                                                    نرخی زیادکردنی سنووری قوتابی: هەر 1 قوتابی =>
                                                    {{ number_format($limitStudentUnitPrice) }} دینار.
                                                </p>
                                                <div class="small">
                                                    کۆی نرخ:
                                                    <span id="totalPriceText"
                                                        class="badge bg-soft-info text-info border border-info-soft fw-bold">0</span>
                                                    دینار
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100 border-{{ $teacher->all_departments == 1 ? 'success' : 'warning' }}">
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="request_types[]" value="all_departments"
                                                                id="allDepartmentsCheck"
                                                                data-tier-feature="1"
                                                                {{ $teacher->all_departments == 1 ? 'disabled checked' : '' }}>
                                                            <label class="form-check-label fw-bold" for="allDepartmentsCheck">
                                                                <i class="fas fa-layer-group me-2"></i>مۆڵەتی ٥٠ بەش
                                                            </label>
                                                            <div class="small text-muted mt-2">
                                                                دەتوانی زانیاری لەسەر هەموو بەشەکانی تری پارێزگاکان سەیر بکەیت.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100 border-{{ $teacher->ai_rank == 1 ? 'success' : 'warning' }}">
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="request_types[]" value="ai_rank" id="aiRankCheck"
                                                                data-tier-feature="1"
                                                                {{ $teacher->ai_rank == 1 ? 'disabled checked' : '' }}>
                                                            <label class="form-check-label fw-bold" for="aiRankCheck">
                                                                <i class="fas fa-robot me-2"></i>سیستەمی AI
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100 border-{{ $teacher->gis == 1 ? 'success' : 'warning' }}">
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="request_types[]" value="gis" id="gisCheck"
                                                                data-tier-feature="1"
                                                                {{ $teacher->gis == 1 ? 'disabled checked' : '' }}>
                                                            <label class="form-check-label fw-bold" for="gisCheck">
                                                                <i class="fas fa-map-marked-alt me-2"></i>سیستەمی نەخشە (GIS)
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <div
                                                    class="card h-100 border-{{ $teacher->queue_hand_department == 1 ? 'success' : 'warning' }}">
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="request_types[]" value="queue_hand_department"
                                                                id="queueHandDepartmentCheck"
                                                                {{ $teacher->queue_hand_department == 1 ? 'disabled checked' : '' }}>
                                                            <label class="form-check-label fw-bold" for="queueHandDepartmentCheck">
                                                                <i class="fas fa-list-ol me-2"></i>ڕیزبەندی بەشەکان
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-1">
                                            <div class="col-md-6">
                                                <label for="request_limit_student" class="form-label fw-bold">
                                                    <i class="fas fa-user-graduate me-1"></i>زیادکردنی سنووری قوتابی
                                                </label>
                                                <input type="number" min="0" class="form-control"
                                                    id="request_limit_student" name="request_limit_student"
                                                    value="{{ old('request_limit_student', 0) }}">
                                            </div>
                                        </div>

                                        <div id="requestTypesError" class="text-danger small mt-2 d-none">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            کەمترین یەک داواکاری هەڵبژێرە (feature یان زیادکردنی سنوور).
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="receipt_image" class="form-label fw-bold">
                                            <i class="fas fa-image text-primary me-2"></i>وێنەی پارەدانەکەت (Receipt)
                                        </label>
                                        <input type="file" class="form-control @error('receipt_image') is-invalid @enderror"
                                            id="receipt_image" name="receipt_image" accept="image/*">
                                        @error('receipt_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div id="receiptPreview" class="mt-3 d-none">
                                            <div class="small text-muted mb-2">پێشبینینی وێنە</div>
                                            <img src="#" alt="Receipt Preview" class="img-fluid rounded border shadow-sm">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="reason" class="form-label fw-bold">
                                            <i class="fas fa-comment-dots me-1"></i>هۆکاری داواکاری
                                        </label>
                                        <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="6"
                                            placeholder="هۆکاری داواکردنەکانت بنووسە...">{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-warning btn-lg px-5">
                                            <i class="fas fa-paper-plane me-2"></i>ناردنی داواکاری
                                        </button>
                                        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary btn-lg px-5 ms-2">
                                            <i class="fas fa-arrow-left me-2"></i>گەڕانەوە
                                        </a>
                                    </div>
                                </form>
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
            const tierFeatureCheckboxes = form.querySelectorAll(
                'input[name="request_types[]"][data-tier-feature="1"]:not(:disabled)'
            );
            const queueHandDepartmentCheckbox = document.getElementById('queueHandDepartmentCheck');
            const totalPriceText = document.getElementById('totalPriceText');
            const featurePriceMap = @json($featurePrices ?? ['1' => 3000, '2' => 5000, '3' => 6000]);
            const queueHandDepartmentPrice = @json($queueHandDepartmentPrice ?? 0);
            const limitStudentUnitPrice = @json($limitStudentUnitPrice ?? 1000);
            const requestLimitStudentInput = document.getElementById('request_limit_student');
            const errorDiv = document.getElementById('requestTypesError');
            const receiptInput = document.getElementById('receipt_image');
            const receiptPreview = document.getElementById('receiptPreview');
            const receiptPreviewImg = receiptPreview ? receiptPreview.querySelector('img') : null;

            function getCheckedTierFeatureCount() {
                let checkedCount = 0;
                tierFeatureCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) checkedCount++;
                });
                return checkedCount;
            }

            function getQueueHandDepartmentPrice() {
                if (!queueHandDepartmentCheckbox || queueHandDepartmentCheckbox.disabled) {
                    return 0;
                }

                return queueHandDepartmentCheckbox.checked ? Number(queueHandDepartmentPrice) : 0;
            }

            function getLimitStudentQty() {
                return Math.max(0, parseInt(requestLimitStudentInput ? requestLimitStudentInput.value : '0', 10) || 0);
            }

            function updatePrice() {
                if (!totalPriceText) return;
                const checkedCount = getCheckedTierFeatureCount();
                const featurePrice = featurePriceMap[String(checkedCount)] ?? featurePriceMap[checkedCount] ?? 0;
                const queueFeaturePrice = getQueueHandDepartmentPrice();
                const limitsPrice = getLimitStudentQty() * limitStudentUnitPrice;
                const totalPrice = Number(featurePrice) + Number(queueFeaturePrice) + Number(limitsPrice);
                totalPriceText.textContent = Number(totalPrice).toLocaleString();
            }

            function hasAnyRequest() {
                const hasQueueRequest = queueHandDepartmentCheckbox
                    && !queueHandDepartmentCheckbox.disabled
                    && queueHandDepartmentCheckbox.checked;
                return getCheckedTierFeatureCount() > 0 || hasQueueRequest || getLimitStudentQty() > 0;
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updatePrice));
            if (requestLimitStudentInput) requestLimitStudentInput.addEventListener('input', updatePrice);
            updatePrice();

            form.addEventListener('submit', function(e) {
                if (!hasAnyRequest()) {
                    e.preventDefault();
                    errorDiv.classList.remove('d-none');
                    errorDiv.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (hasAnyRequest()) errorDiv.classList.add('d-none');
                });
            });
            if (requestLimitStudentInput) {
                requestLimitStudentInput.addEventListener('input', () => {
                    if (hasAnyRequest()) errorDiv.classList.add('d-none');
                });
            }

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
