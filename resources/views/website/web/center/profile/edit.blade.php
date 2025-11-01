@extends('website.web.admin.layouts.app')

@section('content')
    <a href="{{ route('center.students.index') }}" class="btn btn-outline mb-4">
        <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
    </a>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-user-pen me-2"></i> نوێکردنەوەی قوتابی
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

                    <form action="{{ route('center.profile.update', $user->id) }}" method="POST" class="needs-validation"
                        novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- Name --}}
                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">ناوی قوتابی</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Code --}}
                            <div class="col-12 col-md-6">
                                <label for="code" class="form-label">کۆد</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                        id="code" name="code" value="{{ old('code', $user->code) }}" readonly>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">ژمارەی مۆبایل</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="role" class="form-label">پیشە</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role"
                                    name="role" required>
                                    <option value="center" @selected(old('role') === 'center')>سەنتەر</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="form-text">تەنها سەنتەر دەتوانێت پیشە دیاری بکات.</div>
                                @enderror
                            </div>

                        </div>


                        <hr class="my-4">

                        {{-- Passwords --}}
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="password_old" class="form-label">تێپەڕەوشەی پێشین</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" class="form-control @error('password_old') is-invalid @enderror"
                                        id="password_old" name="password_old">
                                    @error('password_old')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="password_new" class="form-label">تێپەڕەوشەی نوێ</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                    <input type="password" class="form-control @error('password_new') is-invalid @enderror"
                                        id="password_new" name="password_new">
                                    @error('password_new')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="password_confirmation" class="form-label">دووپاتکردنەوە</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        id="password_confirmation" name="password_confirmation">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

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
