@extends('website.web.admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('center.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('center.teachers.index') }}">مامۆستایەکان</a></li>
                            <li class="breadcrumb-item active">زیادکردن</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-user-plus me-1"></i>
                        زیادکردنی مامۆستا
                    </h4>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <a href="{{ route('center.teachers.index') }}" class="btn btn-outline">
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

                        @php
                            $center = auth()->user()->center;
                            $teacherLimit = $teacherLimit ?? null;
                            $currentTeachersCount = $currentTeachersCount ?? 0;
                            $remainingTeachersCount = $remainingTeachersCount ?? null;
                            $canCreateTeacher = $canCreateTeacher ?? true;
                        @endphp

                        <div
                            class="alert {{ is_null($teacherLimit) ? 'alert-info' : ($canCreateTeacher ? 'alert-warning' : 'alert-danger') }}">
                            <div class="fw-semibold mb-1">
                                <i class="fa-solid fa-people-group me-1"></i>
                                سنووری مامۆستاکان
                            </div>
                            @if (is_null($teacherLimit))
                                <div>بۆ ئێستا سنوور بۆ دروستکردنی مامۆستا دیاری نەکراوە (بێ سنوور).</div>
                            @else
                                <div>
                                    سنوور: <strong>{{ $teacherLimit }}</strong> |
                                    ئێستا: <strong>{{ $currentTeachersCount }}</strong> |
                                    ماوە: <strong>{{ $remainingTeachersCount }}</strong>
                                </div>
                                @unless($canCreateTeacher)
                                    <div class="mt-2">
                                        <div class="mb-2">ناتوانیت مامۆستای نوێ زیاد بکەیت تا سنوورەکە زیاد بکرێت.</div>
                                        <a href="{{ route('center.features.request') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus-circle me-1"></i>سنووری زیاد کردن
                                        </a>
                                    </div>
                                @endunless
                            @endif
                        </div>

                        <form action="{{ route('center.teachers.store') }}" method="POST" class="needs-validation"
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

                                <div class="col-md-6">
                                    <label for="province" class="form-label">پارێزگا</label>
                                    <select class="form-select @error('province') is-invalid @enderror" id="province"
                                        name="province" required>
                                        <option value="">هەڵبژێرە...</option>
                                        @foreach (($provinces ?? collect()) as $province)
                                            <option value="{{ $province->name }}" @selected(old('province', $center?->province) === $province->name)>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="role" class="form-label">قوتابی یان مامۆستا</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role"
                                        name="role" required>
                                        <option value="teacher" @selected(old('role') === 'teacher')>مامۆستا</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">تەنها سەنتەرە دەتوانێت پیشە دیاری بکات.</div>
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

                            </div>

                            {{-- Feature Inheritance Display --}}
                            @if ($center)
                                <div class="alert alert-info mt-4">
                                    <h6 class="mb-2"><i class="fa-solid fa-info-circle me-2"></i>تایبەتمەندییەکانی
                                        وەرگیراو
                                    </h6>
                                    <p class="mb-2 small">ئەمە تایبەتمەندییەکانی سەنتەرەکەتن؛ لە خوارەوە دەتوانیت بۆ ئەم مامۆستایە دیارییان بکەیت.</p>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span class="badge {{ $center->ai_rank ? 'bg-success' : 'bg-danger' }}">
                                            <i class="fa-solid {{ $center->ai_rank ? 'fa-check' : 'fa-times' }} me-1"></i>
                                            ڕیزبەندی کرد بە زیرەکی دەستکرد
                                            {{ $center->ai_rank ? '(چالاکە)' : '(ناچالاکە)' }}
                                        </span>
                                        <span class="badge {{ $center->gis ? 'bg-success' : 'bg-danger' }}">
                                            <i class="fa-solid {{ $center->gis ? 'fa-check' : 'fa-times' }} me-1"></i>
                                            سیستەمی نەخشە {{ $center->gis ? '(چالاکە)' : '(ناچالاکە)' }}
                                        </span>
                                        <span class="badge {{ $center->all_departments ? 'bg-success' : 'bg-danger' }}">
                                            <i
                                                class="fa-solid {{ $center->all_departments ? 'fa-check' : 'fa-times' }} me-1"></i>
                                            ڕێزبەندی 50 بەش {{ $center->all_departments ? '(چالاکە)' : '(ناچالاکە)' }}
                                        </span>
                                        <span
                                            class="badge {{ $center->queue_hand_department ? 'bg-success' : 'bg-danger' }}">
                                            <i
                                                class="fa-solid {{ $center->queue_hand_department ? 'fa-check' : 'fa-times' }} me-1"></i>
                                            Queue Hand Department
                                            {{ $center->queue_hand_department ? '(چالاکە)' : '(ناچالاکە)' }}
                                        </span>
                                    </div>
                                    @if (!$center->ai_rank || !$center->gis || !$center->all_departments || !$center->queue_hand_department)
                                        <p class="mb-0 mt-2 small text-muted">
                                            <i class="fa-solid fa-lightbulb me-1"></i>
                                            ئەگەر پێویستت بە تایبەتمەندی زیاتر هەیە،
                                            <a href="{{ route('center.features.request') }}"
                                                class="text-decoration-none fw-bold">
                                                داواکاری بۆ ئەدمین بنێرە
                                            </a>.
                                        </p>
                                    @endif
                                </div>
                            @endif

                            @include('website.web.center.partials.feature-access-fields', [
                                'center' => $center,
                                'subjectLabel' => 'مامۆستا',
                                'formPrefix' => 'teacher_create',
                                'featureDefinitions' => [
                                    'ai_rank' => 'ڕیزبەندی کرد بە زیرەکی دەستکرد',
                                    'gis' => 'سیستەمی نەخشە',
                                    'all_departments' => 'ڕێزبەندی 50 بەش',
                                    'queue_hand_department' => 'Queue Hand Department',
                                ],
                            ])

                            <hr class="my-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="limit_student" class="form-label">سنووری قوتابی بۆ ئەم مامۆستایە</label>
                                    <input type="number" min="0"
                                        class="form-control @error('limit_student') is-invalid @enderror"
                                        id="limit_student" name="limit_student"
                                        value="{{ old('limit_student', $center?->limit_student) }}"
                                        placeholder="بۆ نموونە: 100">
                                    @error('limit_student')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">ئەگەر بەتاڵ بێت، سنووری سەنتەر بەکاردهێنرێت.</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label">دۆخ</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1" @selected(old('status') === '1')>چاڵاک</option>
                                        <option value="0" @selected(old('status') === '0')>ناچاڵاک</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary" @disabled(!$canCreateTeacher)>
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
            const $codeInput = $('#code');

            if ($codeInput.length) {
                const gen = () => $codeInput.val(Math.floor(100000 + Math.random() * 900000));
                gen();
                $codeInput.on('focus', gen);
            }

        });
    </script>
@endpush
