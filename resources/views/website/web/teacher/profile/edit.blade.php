@extends('website.web.admin.layouts.app')

@section('title', 'پرۆفایلی مامۆستا')

@section('content')
    <div class="container-fluid py-4">
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success">زانیارییەکان بەسەرکەوتوویی نوێکرانەوە.</div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="alert alert-success">پاسۆرد بەسەرکەوتوویی گۆڕدرێت.</div>
        @endif

        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">پرۆفایل</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-user me-1"></i>
                        پرۆفایلی مامۆستا
                    </h4>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">دەستکاری زانیاری سەرەکی</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('teacher.profile.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">ناو</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ژمارەی مۆبایل</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-primary w-100">پاشەکەوتکردن</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">گۆڕینی پاسۆرد</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label class="form-label">پاسۆردی ئێستا</label>
                                <input type="password" name="current_password" class="form-control">
                                @error('current_password', 'updatePassword')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">پاسۆردی نوێ</label>
                                <input type="password" name="password" class="form-control">
                                @error('password', 'updatePassword')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">دڵنیاکردنەوەی پاسۆرد</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                            <button class="btn btn-outline-primary w-100">گۆڕین</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">زانیاری مامۆستا</h6>
                        <span class="badge {{ $user->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $user->status ? 'چالاک' : 'ناچالاک' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">ناو</div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">ژمارەی مۆبایل</div>
                                <div class="fw-semibold">{{ $user->phone ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">کۆدی داخیل بوون</div>
                                <div class="fw-semibold">{{ $user->code ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-muted small">پیشە</div>
                                <div class="fw-semibold">{{ $user->role === 'center' ? 'سەنتەر' : 'مامۆستا' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">کۆدی بانگێشت کردن</div>
                                <div class="fw-semibold">{{ $teacher?->referral_code ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">سیستەمی زیرەکی دەستکرد</div>
                                <div class="fw-semibold">
                                    @if ($teacher && $teacher->ai_rank)
                                        <span class="badge bg-success ms-2">چاڵاکە</span>
                                    @else
                                        <span class="badge bg-danger ms-2">ناچاڵاک</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">سیستەمی نەخشە</div>
                                <div class="fw-semibold">
                                    @if ($teacher && $teacher->gis)
                                        <span class="badge bg-success ms-2">چاڵاکە</span>
                                    @else
                                        <span class="badge bg-danger ms-2">ناچاڵاک</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">ڕێزبەندی کردنی بەشی زیاتر</div>
                                <div class="fw-semibold">
                                    @if ($teacher && $teacher->all_departments)
                                        <span class="badge bg-success ms-2">بەڵێ</span>
                                    @else
                                        <span class="badge bg-danger ms-2">نەخێر</span>
                                    @endif
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="col-md-6">
                                <div class="text-muted small">بەرواری تۆمارکردنی هەژمار</div>
                                <div class="fw-semibold">
                                    {{ $user->created_at ? $user->created_at->format('Y-m-d') : '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">دوا نوێکردنەوەی هەژمار</div>
                                <div class="fw-semibold">
                                    {{ $user->updated_at ? $user->updated_at->format('Y-m-d') : '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">بەرواری تۆمارکردنی مامۆستا</div>
                                <div class="fw-semibold">
                                    {{ $teacher?->created_at ? $teacher->created_at->format('Y-m-d') : '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">دوا نوێکردنەوەی مامۆستا</div>
                                <div class="fw-semibold">
                                    {{ $teacher?->updated_at ? $teacher->updated_at->format('Y-m-d') : '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
