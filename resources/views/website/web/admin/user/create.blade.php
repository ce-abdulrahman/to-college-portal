@extends('website.web.admin.layouts.app')

@section('page_name', 'users')
@section('view_name', 'create')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">بەکارهێنەران</a></li>
                            <li class="breadcrumb-item active">دروستکردن</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fa-solid fa-user-plus me-2"></i>
                        زیادکردنی بەکارهێنەر
                    </h4>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    @include('website.web.admin.user.partials.form', ['submitLabel' => 'دروستکردن'])
                </form>
            </div>
        </div>
    </div>
@endsection
