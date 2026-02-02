@extends('website.web.admin.layouts.app')

@section('title', 'بەشەکان')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item active">بەشەکان</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fa-solid fa-graduation-cap me-1"></i>
                    زانکۆ و بەشەکان
                </h4>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @livewire('teacher.department-table')
    </div>
@endsection
