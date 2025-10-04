@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.universities.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">{{ __('دەستکاری زانکۆ') }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-pen-to-square me-2"></i> {{ __('نوێکردنەوەی زانکۆ') }}
                    </h4>

                    <form action="{{ route('admin.universities.update', $university->id) }}" method="POST"
                        class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- Province --}}
                            <div class="col-md-6">
                                <label for="province_id" class="form-label">
                                    <i class="fa-solid fa-map-pin me-1 text-muted"></i> {{ __('هەڵبژاردنی پارێزگا') }} <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="province_id" name="province_id" required>
                                    <option value="" disabled>— {{ __('هەڵبژێرە') }} —</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}"
                                            {{ $university->province_id == $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">تکایە پارێزگا هەڵبژێرە.</div>
                            </div>

                            {{-- Name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fa-solid fa-tag me-1 text-muted"></i> {{ __('ناوی زانکۆ') }} <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $university->name }}" required minlength="2" placeholder="ناوی زانکۆ...">
                                <div class="invalid-feedback">تکایە ناوی دروست بنوسە (کەمتر نیە لە ٢ پیت).</div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    <i class="fa-solid fa-toggle-on me-1 text-muted"></i> {{ __('دۆخ') }} <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="status" name="status" required>
                                    <option @selected($university->status == 1) value="1">{{ __('چاڵاک') }}</option>
                                    <option @selected($university->status == 0) value="0">{{ __('ناچاڵاک') }}</option>
                                </select>
                                <div class="invalid-feedback">تکایە دۆخ هەڵبژێرە.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.universities.index') }}" class="btn btn-outline">
                                <i class="fa-solid fa-xmark me-1"></i> ڕەتکردنەوە
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
