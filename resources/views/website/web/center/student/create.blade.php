@extends('website.web.admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('center.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('center.students.index') }}">قوتابیەکان</a></li>
                            <li class="breadcrumb-item active">زیادکردن</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-user-plus me-1"></i>
                        زیادکردنی قوتابی
                    </h4>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <a href="{{ route('center.students.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
            </a>
        </div>

        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                <div class="card glass fade-in">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-user-plus me-2"></i> زیادکردنی قوتابی
                        </h4>

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> هەڵە هەیە:
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('center.students.store') }}" method="POST" class="needs-validation"
                            novalidate>
                            @csrf

                            {{-- زانیاری سەرەکی --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="code" class="form-label">کۆدی داخیل بوون</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                            id="code" name="code">
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="name" class="form-label">ناو</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label">تێپەڕەوشە</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">ژمارەی مۆبایل</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="role" class="form-label">قوتابی یان مامۆستا</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role"
                                        name="role" required>
                                        <option value="student" @selected(old('role') === 'student')>قوتابی</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">تەنها سەنتەر دەتوانێت پیشە دیاری بکات.</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="referral_teacher_code" class="form-label">کۆدی بانگێشت</label>
                                    <input type="number"
                                        class="form-control @error('referral_teacher_code') is-invalid @enderror"
                                        id="referral_teacher_code" name="referral_teacher_code"
                                        value="{{ auth()->user()->rand_code }}" readonly>
                                    @error('referral_teacher_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" id="rand_code" name="rand_code" value="{{ old('rand_code') }}"
                                    readonly>

                            </div>

                            {{-- Feature Inheritance Display --}}
                            @php
                                $center = auth()->user()->center;
                                $showExtra = old('role') === 'student' || request()->has('student');
                            @endphp

                            @if ($center)
                                <div class="alert alert-info mt-4">
                                    <h6 class="mb-2"><i class="fa-solid fa-info-circle me-2"></i>تایبەتمەندییەکانی
                                        وەرگیراو
                                    </h6>
                                    <p class="mb-2 small">ئەم قوتابییە ئەم تایبەتمەندیانە لە سەنتەرەکەت وەردەگرێت:</p>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span class="badge {{ $center->ai_rank ? 'bg-success' : 'bg-secondary' }}">
                                            <i class="fa-solid {{ $center->ai_rank ? 'fa-check' : 'fa-times' }} me-1"></i>
                                            ڕیزبەندی کرد بە زیرەکی دەستکرد
                                            {{ $center->ai_rank ? '(چالاکە)' : '(ناچالاکە)' }}
                                        </span>
                                        <span class="badge {{ $center->gis ? 'bg-success' : 'bg-secondary' }}">
                                            <i class="fa-solid {{ $center->gis ? 'fa-check' : 'fa-times' }} me-1"></i>
                                            GIS {{ $center->gis ? '(چالاکە)' : '(ناچالاکە)' }}
                                        </span>
                                        <span class="badge {{ $center->all_departments ? 'bg-success' : 'bg-secondary' }}">
                                            <i
                                                class="fa-solid {{ $center->all_departments ? 'fa-check' : 'fa-times' }} me-1"></i>
                                            All Departments (50) {{ $center->all_departments ? '(چالاکە)' : '(ناچالاکە)' }}
                                        </span>
                                    </div>
                                    @if (!$center->ai_rank || !$center->gis || !$center->all_departments)
                                        <div class="card bg-soft-info border-info-soft border-dashed mt-3 shadow-none">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar-xs flex-shrink-0 me-2">
                                                        <span class="avatar-title bg-info rounded-circle fs-13">
                                                            <i class="fa-solid fa-lightbulb text-white"></i>
                                                        </span>
                                                    </div>
                                                    <h6 class="mb-0 text-info fw-bold">ڕێنمایی چالاککردنی تایبەتمەندی</h6>
                                                </div>
                                                <div class="ms-1">
                                                    @php
                                                        $defaultFeaturePrices = [
                                                            '1' => 3000,
                                                            '2' => 5000,
                                                            '3' => 6000,
                                                        ];
                                                        $baseFeaturePrices = json_decode(
                                                            $appSettings['feature_prices'] ?? '',
                                                            true,
                                                        );
                                                        if (!is_array($baseFeaturePrices)) {
                                                            $baseFeaturePrices = $defaultFeaturePrices;
                                                        }

                                                        $featureMultiplier = 5; // center
                                                        $featurePrices = [];
                                                        foreach ($defaultFeaturePrices as $tier => $defaultPrice) {
                                                            $featurePrices[$tier] = (int) ($baseFeaturePrices[$tier] ?? $defaultPrice) * $featureMultiplier;
                                                        }
                                                    @endphp
                                                    <p class="mb-2 small text-muted lh-lg">
                                                        بۆ هەر تایبەتمەندیەک پێویستە بڕی
                                                        <span
                                                            class="badge bg-soft-info text-info border border-info-soft fw-bold fx-text fx-gradient">{{ number_format($featurePrices['1'] ?? 3000) }}</span>
                                                        دینار بۆ ئەم ژمارەیە
                                                        <span
                                                            class="badge bg-soft-primary text-primary border border-primary-soft fw-bold">07504342452</span>
                                                        بنێریت لە ڕێگای <span class="fx-text fx-glitch"
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
                                                        وێنەی پارەدانەکەت بۆ <b><a href="https://t.me/AGHA_ACE"
                                                                class="fx-text fx-glitch"
                                                                data-text="Telegram">Telegram</a></b> یان <b><a
                                                                href="https://wa.me/9647504342452"
                                                                class="fx-text fx-glitch"
                                                                data-text="WhatsApp">WhatsApp</a></b> یان <b><a
                                                                href="viber://chat?number=9647504342452"
                                                                class="fx-text fx-glitch" data-text="Viber">Viber</a></b>
                                                        ی هەمان
                                                        ژمارە
                                                        بنێرە.
                                                    </div>
                                                    <div class="mt-2 text-center">
                                                        <a href="{{ route('center.features.request') }}"
                                                            class="text-decoration-none fw-bold small">
                                                            <i class="fas fa-paper-plane me-1"></i>ناردنی داواکاری بۆ
                                                            ئەدمین
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @include('website.web.teacher.student.info-student', [
                                'provinces' => $provinces,
                                'showExtra' => $showExtra,
                            ])

                            <hr class="my-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="status" class="form-label">دۆخ</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1" @selected(old('status') === '1')>چاڵاک</option>
                                        <option value="0" @selected(old('status') === '0')>ناچاڵاک</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوتکردن
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



@push('scripts')
    <script>
        $(function() {
            const $roleSel = $('#role');
            const $queueSel = $('#queue');
            const $extra = $('#extra-section');
            const $chooseNum = $('#choose-num');

            function toggleExtra() {
                if ($roleSel.val() === 'student') {
                    $extra.removeClass('d-none');
                    $extra.find('select, input').each(function() {
                        const n = this.name;
                        if (['mark', 'province', 'type', 'gender', 'year'].includes(n)) {
                            $(this).prop('required', true);
                        }
                    });
                } else {
                    $extra.addClass('d-none');
                    $extra.find('select, input').prop('required', false);
                }
            }

            function toggleNum() {
                if ($queueSel.val() === 'yes') {
                    $chooseNum.removeClass('d-none');
                    $chooseNum.find('select, input').each(function() {
                        const n = this.name;
                        if (['zankoline_num', 'parallel_num', 'evening_num'].includes(n)) {
                            $(this).prop('required', true);
                        }
                    });
                } else {
                    $chooseNum.addClass('d-none');
                    $chooseNum.find('select, input').prop('required', false);
                }
            }

            if ($roleSel.length) {
                $roleSel.on('change', toggleExtra);
                toggleExtra();
            }
            if ($queueSel.length) {
                $queueSel.on('change', toggleNum);
                toggleNum();
            }

            // Random codes
            const $randCodeInput = $('#rand_code');

            if ($randCodeInput.length) {
                const gen2 = () => $randCodeInput.val(Math.floor(1000 + Math.random() * 9000));
                gen2();
                $randCodeInput.on('focus', gen2);
            }

            // Random codes
            const $codeInput = $('#code');

            if ($codeInput.length) {
                const gen = () => $codeInput.val(Math.floor(100000 + Math.random() * 900000));
                gen();
                $codeInput.on('focus', gen);
            }

        });
    </script>
@endpush
