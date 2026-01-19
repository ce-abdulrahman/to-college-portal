@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.systems.index') }}">سیستەمەکانی خوێندن</a></li>
                        <li class="breadcrumb-item active">دروستکردنی سیستەمی نوێ</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-bar me-1"></i>
                    دروستکردنی سیستەمی نوێ
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-diagram-project me-2"></i> دروستکردنی سیستەمی نوێ
                    </h4>

                    <form action="{{ route('admin.systems.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">
                            {{-- Name --}}
                            <div class="col-md-8">
                                <label for="name" class="form-label">
                                    <i class="fa-solid fa-tag me-1 text-muted"></i> ناوی سیستەم <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name" class="form-control" required minlength="2" placeholder="ناوی سیستەم بنوسە...">
                                <div class="invalid-feedback">تکایە ناوێک دروست بنوسە (کەمتر نیە لە ٢ پیت).</div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-4">
                                <label for="status" class="form-label">
                                    <i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ <span class="text-danger">*</span>
                                </label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="1">چاڵاک</option>
                                    <option value="0">ناچاڵاک</option>
                                </select>
                                <div class="invalid-feedback">تکایە دۆخ هەڵبژێرە.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-outline">
                                <i class="fa-solid fa-rotate-left me-1"></i> پاککردنەوە
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-plus me-1"></i> پاشەکەوتکردنی سیستەم
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
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        'use strict';
        
        const forms = document.querySelectorAll('.needs-validation');
        
        forms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    });
</script>
@endpush