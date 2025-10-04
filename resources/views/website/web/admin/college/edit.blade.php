@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.colleges.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">{{ __('دەستکاری کۆلێژ') }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-pen-to-square me-2"></i> {{ __('نوێکردنەوەی کۆلێژ') }}
                    </h4>

                    <form action="{{ route('admin.colleges.update', $college->id) }}" method="POST"
                        class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- University --}}
                            <div class="col-md-6">
                                <label for="university_id" class="form-label">
                                    <i class="fa-solid fa-building-columns me-1 text-muted"></i> {{ __('زانکۆ') }} <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="university_id" name="university_id" required>
                                    <option value="" disabled>— {{ __('هەڵبژێرە') }} —</option>
                                    @foreach ($universities as $university)
                                        <option value="{{ $university->id }}"
                                            {{ $college->university_id == $university->id ? 'selected' : '' }}>
                                            {{ $university->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">تکایە زانکۆ هەڵبژێرە.</div>
                            </div>

                            {{-- College name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fa-solid fa-building me-1 text-muted"></i> {{ __('ناوی کۆلێژ') }} <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $college->name }}" required minlength="2" placeholder="ناوی کۆلێژ...">
                                <div class="invalid-feedback">تکایە ناوی کۆلێژ بنوسە (کەمتر نیە لە ٢ پیت).</div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    <i class="fa-solid fa-toggle-on me-1 text-muted"></i> {{ __('دۆخ') }} <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1" {{ $college->status == 1 ? 'selected' : '' }}>
                                        {{ __('چاڵاک') }}</option>
                                    <option value="0" {{ $college->status == 0 ? 'selected' : '' }}>
                                        {{ __('ناچاڵاک') }}</option>
                                </select>
                                <div class="invalid-feedback">تکایە دۆخ هەڵبژێرە.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.colleges.index') }}" class="btn btn-outline">
                                <i class="fa-solid fa-xmark me-1"></i> ڕەتکردنەوە
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('پاشەکەوتکردنی گۆڕانکاری') }}
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
        (() => {
            const forms = document.querySelectorAll('.needs-validation');
            forms.forEach(form => {
                form.addEventListener('submit', e => {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });
        })();
    </script>
@endpush
