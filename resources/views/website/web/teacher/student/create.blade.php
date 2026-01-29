@extends('website.web.admin.layouts.app')

@section('content')
    <a href="{{ route('teacher.students.index') }}" class="btn btn-outline mb-4">
        <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
    </a>

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

                    <form action="{{ route('teacher.students.store') }}" method="POST" class="needs-validation" novalidate>
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
                                <label for="role" class="form-label">قوتابی</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role"
                                    name="role" required>
                                    <option value="student" @selected(old('role') === 'student')>قوتابی</option>
                                </select>
                            </div>

                            <input type="hidden" id="rand_code" name="rand_code" value="{{ old('rand_code') }}" readonly>

                        </div>

                        {{-- Feature Inheritance Display --}}
                        @php
                            $teacher = auth()->user()->teacher;
                            $showExtra = old('role') === 'student' || request()->has('student');
                        @endphp

                        @if ($teacher)
                            <div class="alert alert-info mt-4">
                                <h6 class="mb-2"><i class="fa-solid fa-info-circle me-2"></i>تایبەتمەندییەکانی وەرگیراو
                                </h6>
                                <p class="mb-2 small">ئەم قوتابییە ئەم تایبەتمەندیانە لە تۆوە وەردەگرێت:</p>
                                <div class="d-flex gap-3 flex-wrap">
                                    <span class="badge {{ $teacher->ai_rank ? 'bg-success' : 'bg-secondary' }}">
                                        <i class="fa-solid {{ $teacher->ai_rank ? 'fa-check' : 'fa-times' }} me-1"></i>
                                        AI Rank {{ $teacher->ai_rank ? '(چالاکە)' : '(ناچالاکە)' }}
                                    </span>
                                    <span class="badge {{ $teacher->gis ? 'bg-success' : 'bg-secondary' }}">
                                        <i class="fa-solid {{ $teacher->gis ? 'fa-check' : 'fa-times' }} me-1"></i>
                                        GIS {{ $teacher->gis ? '(چالاکە)' : '(ناچالاکە)' }}
                                    </span>
                                    <span class="badge {{ $teacher->all_departments ? 'bg-success' : 'bg-secondary' }}">
                                        <i
                                            class="fa-solid {{ $teacher->all_departments ? 'fa-check' : 'fa-times' }} me-1"></i>
                                        All Departments (50) {{ $teacher->all_departments ? '(چالاکە)' : '(ناچالاکە)' }}
                                    </span>
                                </div>
                                @if (!$teacher->ai_rank || !$teacher->gis || !$teacher->all_departments)
                                    <p class="mb-0 mt-2 small text-muted">
                                        <i class="fa-solid fa-lightbulb me-1"></i>
                                        ئەگەر پێویستت بە تایبەتمەندی زیاتر هەیە،
                                        <a href="{{ route('teacher.features.request') }}"
                                            class="text-decoration-none fw-bold">
                                            داواکاری بۆ ئەدمین بنێرە
                                        </a>.
                                    </p>
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

        });
    </script>
@endpush
