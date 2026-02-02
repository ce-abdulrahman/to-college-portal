@extends('website.web.admin.layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('center.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item active">بەشەکان</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-building-columns me-1"></i>
                    ناوی بەشەکەکان
                </h4>
            </div>
        </div>
    </div>

    <livewire:center.department-table />
@endsection
