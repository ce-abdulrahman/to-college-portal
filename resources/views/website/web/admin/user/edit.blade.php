@extends('website.web.admin.layouts.app')

@section('page_name', 'users')
@section('view_name', 'edit')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">بەکارهێنەران</a></li>
                        <li class="breadcrumb-item active">نوێکردنەوەی بەکارهێنەر</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-user-edit me-1"></i>
                    نوێکردنەوەی بەکارهێنەر
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-user-pen me-2"></i> نوێکردنەوەی بەکارهێنەر
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

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- Name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fa-solid fa-user me-1 text-muted"></i> ناو <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $user->name) }}" required>
                                <div class="invalid-feedback">تکایە ناو بنووسە.</div>
                            </div>

                            {{-- Code --}}
                            <div class="col-md-6">
                                <label for="code" class="form-label">
                                    <i class="fa-solid fa-hashtag me-1 text-muted"></i> کۆد
                                </label>
                                <input type="text" class="form-control" id="code" name="code" 
                                       value="{{ old('code', $user->code) }}" readonly>
                                <div class="form-text">کۆدی بەکارهێنەر ناتوانرێ بگۆڕدرێت</div>
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    <i class="fa-solid fa-phone me-1 text-muted"></i> ژمارەی مۆبایل
                                </label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="{{ old('phone', $user->phone) }}">
                            </div>

                            {{-- Role --}}
                            <div class="col-md-6">
                                <label for="role" class="form-label">
                                    <i class="fa-solid fa-user-tag me-1 text-muted"></i> پیشە <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>ئەدمین</option>
                                    <option value="center" {{ old('role', $user->role) === 'center' ? 'selected' : '' }}>سەنتەر</option>
                                    <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>مامۆستا</option>
                                    <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>قوتابی</option>
                                </select>
                                <div class="invalid-feedback">تکایە پیشە هەڵبژێرە.</div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    <i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>چاڵاک</option>
                                    <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>ناچاڵاک</option>
                                </select>
                                <div class="invalid-feedback">تکایە دۆخ هەڵبژێرە.</div>
                            </div>
                        </div>

                        {{-- Password Change Section --}}
                        <div class="mt-4 pt-3 border-top">
                            <h5 class="mb-4">
                                <i class="fa-solid fa-lock me-2"></i> گۆڕینی تێپەڕەوشە
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="password_old" class="form-label">وشەی کۆن</label>
                                    <input type="password" class="form-control" id="password_old" name="password_old">
                                    <div class="form-text">بە بەتاڵی جێبهێڵە ئەگەر ناتەوێت بگۆڕیت</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="password_new" class="form-label">وشەی نوێ</label>
                                    <input type="password" class="form-control" id="password_new" name="password_new">
                                    <div class="form-text">لەکاتی گۆڕیندا، وشەی نوێ بنووسە</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">دووبارەکردنەوەی وشە</label>
                                    <input type="password" class="form-control" id="password_confirmation" 
                                           name="password_confirmation">
                                    <div class="form-text">وشەی نوێ دووبارە بکەرەوە</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                                <i class="fa-solid fa-xmark me-1"></i> پاشگەزبوونەوە
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوتکردنی گۆڕانکاری
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
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
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

        // Password validation
        const passwordNewInput = document.getElementById('password_new');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const passwordOldInput = document.getElementById('password_old');

        function validatePasswords() {
            if (passwordNewInput.value || passwordConfirmInput.value) {
                if (passwordNewInput.value !== passwordConfirmInput.value) {
                    passwordConfirmInput.setCustomValidity('تێپەڕەوشەکان یەک ناگرنەوە');
                    return false;
                }
                
                if (!passwordOldInput.value) {
                    passwordOldInput.setCustomValidity('تێپەڕەوشەی پێشین پێویستە بۆ گۆڕینی تێپەڕەوشە');
                    return false;
                }
            }
            
            passwordConfirmInput.setCustomValidity('');
            passwordOldInput.setCustomValidity('');
            return true;
        }

        if (passwordNewInput && passwordConfirmInput) {
            passwordNewInput.addEventListener('input', validatePasswords);
            passwordConfirmInput.addEventListener('input', validatePasswords);
            passwordOldInput.addEventListener('input', validatePasswords);
        }
    });
</script>
@endpush