@extends('website.web.admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('teacher.students.index') }}">قوتابیەکان</a></li>
                            <li class="breadcrumb-item active">زیادکردن</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-user-plus me-1"></i>
                        زیادکردنی قوتابی نوێ
                    </h4>
                </div>
            </div>
        </div>

        <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
        </a>

        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                <div class="card glass fade-in">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-user-plus me-2"></i> زیادکردنی قوتابی
                        </h4>
                        <p class="text-muted small mb-4">
                            زانیاری سەرەکی و تایبەتمەندییەکانی قوتابی پڕ بکەرەوە بۆ دروستکردنی هەژمار.
                        </p>

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

                        @php
                            $teacher = auth()->user()->teacher;
                            $studentLimit = $studentLimit ?? null;
                            $currentStudentsCount = $currentStudentsCount ?? 0;
                            $remainingStudentsCount = $remainingStudentsCount ?? null;
                            $canCreateStudent = $canCreateStudent ?? true;
                            $showExtra = old('role') === 'student' || request()->has('student');
                        @endphp

                        <div
                            class="alert {{ is_null($studentLimit) ? 'alert-info' : ($canCreateStudent ? 'alert-warning' : 'alert-danger') }}">
                            <div class="fw-semibold mb-1">
                                <i class="fa-solid fa-user-graduate me-1"></i>
                                سنووری قوتابی بۆ مامۆستا
                            </div>
                            @if (is_null($studentLimit))
                                <div>بۆ ئێستا سنوور دیاری نەکراوە (بێ سنوور).</div>
                            @else
                                <div>
                                    سنوور: <strong>{{ $studentLimit }}</strong> |
                                    ئێستا: <strong>{{ $currentStudentsCount }}</strong> |
                                    ماوە: <strong>{{ $remainingStudentsCount }}</strong>
                                </div>
                                @unless($canCreateStudent)
                                    <div class="mt-2">
                                        <div class="mb-2">ناتوانیت قوتابیی نوێ زیاد بکەیت تا سنوورەکە زیاد بکرێت.</div>
                                        <a href="{{ route('teacher.features.request') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus-circle me-1"></i>سنووری زیاد کردن
                                        </a>
                                    </div>
                                @endunless
                            @endif
                        </div>

                        <form action="{{ route('teacher.students.store') }}" method="POST" class="needs-validation"
                            novalidate>
                            @csrf

                            {{-- زانیاری سەرەکی --}}
                            <div class="border rounded-3 p-3 mb-4 bg-light-subtle">
                                <div class="fw-semibold mb-3">
                                    <i class="fa-solid fa-address-card me-1"></i> زانیاری سەرەکی
                                </div>
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
                                    <label for="role" class="form-label">قوتابی</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role"
                                        name="role" required>
                                        <option value="student" @selected(old('role') === 'student')>قوتابی</option>
                                    </select>
                                </div>

                            </div>
                            </div>

                            {{-- Feature Inheritance Display --}}
                            @if ($teacher)
                                <div class="alert alert-info mt-4">
                                    <h6 class="mb-2"><i class="fa-solid fa-info-circle me-2"></i>تایبەتمەندییەکانی
                                        وەرگیراو
                                    </h6>
                                    <p class="mb-2 small">ئەمە تایبەتمەندییەکانی تۆن؛ لە خوارەوە دەتوانیت بۆ ئەم قوتابییە دیارییان بکەیت.</p>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span class="badge {{ $teacher->ai_rank ? 'bg-success' : 'bg-danger' }}">
                                            <i class="fa-solid {{ $teacher->ai_rank ? 'fa-check' : 'fa-times' }} me-1"></i>
                                            ڕیزبەندی کرد بە زیرەکی دەستکرد
                                            {{ $teacher->ai_rank ? '(چالاکە)' : '(ناچالاکە)' }}
                                        </span>
                                        <span class="badge {{ $teacher->gis ? 'bg-success' : 'bg-danger' }}">
                                            <i class="fa-solid {{ $teacher->gis ? 'fa-check' : 'fa-times' }} me-1"></i>
                                            GIS {{ $teacher->gis ? '(چالاکە)' : '(ناچالاکە)' }}
                                        </span>
                                        <span
                                            class="badge {{ $teacher->all_departments ? 'bg-success' : 'bg-danger' }}">
                                            <i
                                                class="fa-solid {{ $teacher->all_departments ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                ڕێزبەندی 50 بەش
                                            {{ $teacher->all_departments ? '(چالاکە)' : '(ناچالاکە)' }}
                                        </span>
                                    </div>

                                </div>
                            @endif

                            @include('website.web.center.partials.feature-access-fields', [
                                'center' => $teacher,
                                'subjectLabel' => 'قوتابی',
                                'formPrefix' => 'teacher_student_create',
                                'ownerLabel' => 'مامۆستا',
                            ])

                            @include('website.web.teacher.student.info-student', [
                                'provinces' => $provinces,
                                'showExtra' => $showExtra,
                            ])

                            <div class="border rounded-3 p-3 mt-4 bg-light-subtle">
                                <div class="fw-semibold mb-3">
                                    <i class="fa-solid fa-toggle-on me-1"></i> دۆخی هەژمار
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">دۆخ</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="1" @selected(old('status') === '1')>چاڵاک</option>
                                            <option value="0" @selected(old('status') === '0')>ناچاڵاک</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 gap-2">
                                <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-xmark me-1"></i> هەڵوەشاندنەوە
                                </a>
                                <button type="submit" class="btn btn-primary" @disabled(!$canCreateStudent)>
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

            const $latInput = $('#lat');
            const $lngInput = $('#lng');
            const $form = $('form.needs-validation').first();
            let locatingInProgress = false;

            const getAiRankValue = () => {
                const checkedVal = $('input[name="ai_rank"]:checked').val();
                if (typeof checkedVal !== 'undefined') return String(checkedVal);

                const hiddenVal = $('input[name="ai_rank"][type="hidden"]').val();
                return typeof hiddenVal !== 'undefined' ? String(hiddenVal) : '0';
            };

            const shouldRequireLocation = () => getAiRankValue() === '1';
            const hasLocation = () => {
                const lat = String($latInput.val() ?? '').trim();
                const lng = String($lngInput.val() ?? '').trim();
                return lat !== '' && lng !== '';
            };

            const fillLocationFromBrowser = () => new Promise((resolve) => {
                if (!$latInput.length || !$lngInput.length || !navigator.geolocation || locatingInProgress) {
                    resolve(false);
                    return;
                }

                locatingInProgress = true;
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        $latInput.val(Number(position.coords.latitude).toFixed(7));
                        $lngInput.val(Number(position.coords.longitude).toFixed(7));
                        locatingInProgress = false;
                        resolve(true);
                    },
                    () => {
                        locatingInProgress = false;
                        resolve(false);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0,
                    }
                );
            });

            const syncLocationRequirement = async () => {
                const requireLocation = shouldRequireLocation();
                if ($latInput.length) $latInput.prop('required', requireLocation);
                if ($lngInput.length) $lngInput.prop('required', requireLocation);

                if (!requireLocation) {
                    $latInput.val('');
                    $lngInput.val('');
                    return;
                }

                if (!hasLocation()) {
                    await fillLocationFromBrowser();
                }
            };

            $(document).on('change', 'input[name="ai_rank"]', function() {
                void syncLocationRequirement();
            });

            if ($form.length) {
                $form.on('submit', async function(e) {
                    if (!shouldRequireLocation() || hasLocation()) {
                        return;
                    }

                    e.preventDefault();
                    const ok = await fillLocationFromBrowser();
                    if (ok) {
                        if (typeof this.requestSubmit === 'function') {
                            this.requestSubmit();
                        } else {
                            this.submit();
                        }
                        return;
                    }

                    alert('نەتوانرا شوێنی قوتابی وەربگیرێت. تکایە ڕێگەبدە بە Location.');
                });
            }

            void syncLocationRequirement();

        });
    </script>
@endpush
