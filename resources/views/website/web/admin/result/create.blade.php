@extends('website.web.admin.layouts.app')

@section('page_name', 'result')
@section('view_name', 'create')

@section('content')
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.results.index') }}">لیستی هەڵبژاردراوەکانی قوتابیان</a></li>
                        <li class="breadcrumb-item active">زیادکردنی هەڵبژاردراوەکانی قوتابیان</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-bar me-1"></i>
                    زیادکردنی هەڵبژاردراوەکانی قوتابیان
                </h4>
            </div>
        </div>
    </div> 

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-sitemap me-2"></i> زانیاری بەش
                    </h4>

                    <form action="{{ route('admin.departments.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="row g-3">

                            {{-- System --}}
                            <div class="col-md-6">
                                <label for="system_id" class="form-label">
                                    <i class="fa-solid fa-diagram-project me-1 text-muted"></i> هەڵبژاردنی سیستەم <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="system_id" name="system_id" required>
                                    <option value="" disabled selected>هەڵبژاردنی سیستەم</option>
                                    @foreach ($systems as $system)
                                        <option value="{{ $system->id }}">{{ $system->name }}</option>
                                    @endforeach
                                </select>
                                @error('system_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Province --}}
                            <div class="col-md-6">
                                <label for="province_id" class="form-label">
                                    <i class="fa-solid fa-location-dot me-1 text-muted"></i> هەڵبژاردنی پارێزگا <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <select class="form-select" id="province_id" name="province_id" required>
                                        <option value="" disabled selected>هەڵبژاردنی پارێزگا</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                    <span id="spinner-province"
                                        class="position-absolute top-50 end-0 translate-middle-y me-3 d-none">
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                    </span>
                                </div>
                                <div class="form-text">هەڵبژاردنی پارێزگا ئەنجام بدە بۆ ئاپدەیت‌کردنی زانکۆکان.</div>
                                @error('province_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- University --}}
                            <div class="col-md-6">
                                <label for="university_id" class="form-label">
                                    <i class="fa-solid fa-school me-1 text-muted"></i> هەڵبژاردنی زانکۆ <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <select class="form-select" id="university_id" name="university_id" required disabled>
                                        <option value="" disabled selected>هەڵبژاردنی زانکۆ</option>
                                    </select>
                                    <span id="spinner-university"
                                        class="position-absolute top-50 end-0 translate-middle-y me-3 d-none">
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                    </span>
                                </div>
                                @error('university_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- College --}}
                            <div class="col-md-6">
                                <label for="college_id" class="form-label">
                                    <i class="fa-solid fa-building-columns me-1 text-muted"></i> هەڵبژاردنی کۆلێژ <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <select class="form-select" id="college_id" name="college_id" required disabled>
                                        <option value="" disabled selected>هەڵبژاردنی کۆلێژ</option>
                                    </select>
                                    <span id="spinner-college"
                                        class="position-absolute top-50 end-0 translate-middle-y me-3 d-none">
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                    </span>
                                </div>
                                @error('college_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fa-solid fa-tag me-1 text-muted"></i> ناوی بەش <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="ناوی بەش بنوسە..." required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Scores --}}
                            <div class="col-md-3">
                                <label for="local_score" class="form-label">
                                    <i class="fa-solid fa-percent me-1 text-muted"></i> نمرەی ناوخۆی پارێزگا
                                </label>
                                <input type="number" step="0.01" class="form-control" id="local_score"
                                    name="local_score" placeholder="بۆ نموونە 85.5">
                                @error('local_score')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="internal_score" class="form-label">
                                    <i class="fa-solid fa-percent me-1 text-muted"></i> نمرەی دەرەوەی پارێزگا
                                </label>
                                <input type="number" step="0.01" class="form-control" id="internal_score"
                                    name="internal_score" placeholder="بۆ نموونە 78">
                                @error('internal_score')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Type --}}
                            <div class="col-md-6">
                                <label for="type" class="form-label">
                                    <i class="fa-solid fa-layer-group me-1 text-muted"></i> جۆر <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="زانستی">زانستی</option>
                                    <option value="وێژەیی">وێژەیی</option>
                                    <option value="زانستی و وێژەیی">زانستی و وێژەیی</option>
                                </select>
                                @error('type')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Sex --}}
                            <div class="col-md-6">
                                <label for="sex" class="form-label">
                                    <i class="fa-solid fa-venus-mars me-1 text-muted"></i> ڕەگەز
                                </label>
                                <select class="form-select" id="sex" name="sex">
                                    <option value="نێر">نێر</option>
                                    <option value="مێ">مێ</option>
                                </select>
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label for="description" class="form-label">
                                    <i class="fa-solid fa-note-sticky me-1 text-muted"></i> وەسف
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="کورتە وەسفێک لەسەر بەشەکە..."></textarea>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    <i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1">چاڵاک</option>
                                    <option value="0">ناچاڵاک</option>
                                </select>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-outline">
                                <i class="fa-solid fa-rotate-left me-1"></i> پاککردنەوە
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-plus me-1"></i> زیادکردنی
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
