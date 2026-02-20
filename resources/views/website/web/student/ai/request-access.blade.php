@extends('website.web.admin.layouts.app')

@section('title', 'AI ڕێزبەندی - داواکاری چالاککردن')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">AI ڕێزبەندی</li>
                        </ol>
                    </div>
                    <h4 class="page-title mb-0">
                        <i class="fas fa-robot me-1"></i>
                        AI ڕێزبەندی
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card border-warning shadow-sm">
                    <div class="card-body text-center p-4 p-lg-5">
                        <div class="mb-3">
                            <i class="fa-solid fa-triangle-exclamation text-warning" style="font-size: 2.75rem;"></i>
                        </div>
                        <h5 class="mb-2">ئەم تایبەتمەندییە چالاک نییە</h5>
                        <p class="text-muted mb-4">
                            بۆ بەکارهێنانی <strong>ڕێزبەندی ئۆتۆماتیکی AI</strong> پێویستە سەرەتا داواکاری بنێریت.
                            دوای پەسەندکردن دەتوانیت ڕاستەوخۆ هەمان پەیج بەکاربهێنیت.
                        </p>

                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('student.features.request') }}" class="btn btn-warning">
                                <i class="fa-solid fa-paper-plane me-1"></i>
                                ناردنی داواکاری
                            </a>
                            <a href="{{ route('student.departments.selection') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-arrow-left me-1"></i>
                                گەڕانەوە بۆ هەڵبژاردنی بەش
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
