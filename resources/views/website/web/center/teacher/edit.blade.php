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
                            <li class="breadcrumb-item active">دەستکاریکردن</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-user-pen me-1"></i>
                        نوێکردنەوەی مامۆستا
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
            <div class="col-12 col-xl-8 mx-auto">
                <div class="card glass fade-in">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-user-pen me-2"></i> نوێکردنەوەی مامۆستا
                        </h4>

                        {{-- هەڵەکان --}}
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
                            $info = 'بۆ گۆڕینی ئەم زانیاریانە تەنها بەڕێوبەر ئەم وێبسایتە بکە! 7504342452';
                            $center = auth()->user()->center;
                        @endphp

                        <form action="{{ route('center.teachers.update', $teacher->id) }}" method="POST"
                            class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                {{-- Name --}}
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label">ناوی مامۆستا</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $teacher->user->name) }}"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">ژمارەی مۆبایل</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $teacher->user->phone) }}">
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
                                            <option value="{{ $province->name }}" @selected(old('province', $teacher->province) === $province->name)>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Code --}}
                                <div class="col-12 col-md-6">
                                    <abbr title="{!! $info !!}"> <label for="code" class="form-label">کۆد
                                            چوونەژوورەوە</label> </abbr>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                            id="code" name="code" value="{{ old('code', $teacher->user->code) }}"
                                            readonly>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">

                                    <abbr title="{{ $info }}"> <label for="rand_code" class="form-label">کۆد
                                            بانگێشت
                                            کردن</label> </abbr>

                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                                        <input type="text" class="form-control" id="rand_code"
                                            value="{{ $teacher->user->rand_code }}" readonly disabled>
                                    </div>
                                </div>



                                <div class="col-12 col-md-6">

                                    <abbr title="{{ $info }}"> <label for="role"
                                            class="form-label">پیشە</label>
                                    </abbr>

                                    <select class="form-select @error('role') is-invalid @enderror" id="role"
                                        name="role" readonly>
                                        <option value="teacher" @selected(old('role') === 'teacher')>مامۆستا</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">تەنها سەنتەر دەتوانێت پیشە دیاری بکات.</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="limit_student" class="form-label">سنووری قوتابی بۆ مامۆستا</label>
                                    <input type="number" min="0"
                                        class="form-control @error('limit_student') is-invalid @enderror"
                                        id="limit_student" name="limit_student"
                                        value="{{ old('limit_student', $teacher->limit_student) }}">
                                    @error('limit_student')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">
                                            سنووری سەنتەر:
                                            {{ is_null($center?->limit_student) ? 'بێ سنوور' : $center->limit_student }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label">دۆخ</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1" @selected((string) old('status', $teacher->user->status) === '1')>چاڵاک</option>
                                        <option value="0" @selected((string) old('status', $teacher->user->status) === '0')>ناچاڵاک</option>
                                    </select>
                                </div>

                            </div>

                            @include('website.web.center.partials.feature-access-fields', [
                                'center' => $center,
                                'currentModel' => $teacher,
                                'subjectLabel' => 'مامۆستا',
                                'formPrefix' => 'teacher_edit',
                                'featureDefinitions' => [
                                    'ai_rank' => 'ڕیزبەندی کرد بە زیرەکی دەستکرد',
                                    'gis' => 'سیستەمی نەخشە',
                                    'all_departments' => 'ڕێزبەندی 50 بەش',
                                    'queue_hand_department' => 'Queue Hand Department',
                                ],
                            ])

                            <hr class="my-4">

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
        // Validation
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endpush
