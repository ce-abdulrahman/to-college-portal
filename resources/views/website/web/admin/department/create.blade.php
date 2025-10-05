@extends('website.web.admin.layouts.app')

@section('content')
    <a href="{{ route('admin.departments.index') }}" class="btn btn-outline mb-4">
        <i class="fa-solid fa-arrow-right-long me-1"></i> {{ __('گەڕانەوە') }}
    </a>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4"><i class="fa-solid fa-plus me-2"></i> {{ __('زیادکردنی بەش') }}</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> {{ __('هەڵە هەیە لە داهێنان') }}:
                            <ul class="mb-0 mt-2 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.departments.store') }}" method="POST" class="needs-validation"
                        novalidate>
                        @csrf

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="system_id" class="form-label">{{ __('سیستەم') }}</label>
                                <select id="system_id" name="system_id"
                                    class="form-select @error('system_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>{{ __('هەڵبژاردنی سیستەم') }}</option>
                                    @foreach ($systems as $system)
                                        <option value="{{ $system->id }}" @selected(old('system_id') == $system->id)>{{ $system->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('system_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="province_id" class="form-label">{{ __('پارێزگا') }}</label>
                                <select id="province_id" name="province_id"
                                    class="form-select @error('province_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>{{ __('هەڵبژاردنی پارێزگا') }}</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}" @selected(old('province_id') == $province->id)>
                                            {{ $province->name }}</option>
                                    @endforeach
                                </select>
                                @error('province_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="university_id" class="form-label">{{ __('زانکۆ') }}</label>
                                <select id="university_id" name="university_id"
                                    class="form-select @error('university_id') is-invalid @enderror" required disabled>
                                    <option value="">{{ __('هەموو زانکۆكان') }}</option>
                                </select>
                                @error('university_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="college_id" class="form-label">{{ __('کۆلێژ/پەیمانگا') }}</label>
                                <select id="college_id" name="college_id"
                                    class="form-select @error('college_id') is-invalid @enderror" required disabled>
                                    <option value="">{{ __('هەموو کۆلێژەکان') }}</option>
                                </select>
                                @error('college_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">{{ __('ناوی بەش') }}</label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="local_score" class="form-label">{{ __('ن. ناوەندی') }}</label>
                                <input id="local_score" name="local_score" type="number" step="0.01"
                                    class="form-control" value="{{ old('local_score') }}">
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="internal_score" class="form-label">{{ __('ن. ناوخۆی') }}</label>
                                <input id="internal_score" name="internal_score" type="number" step="0.01"
                                    class="form-control" value="{{ old('internal_score') }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="type" class="form-label">{{ __('جۆر') }}</label>
                                <select id="type" name="type" class="form-select">
                                    <option value="زانستی" @selected(old('type') === 'زانستی')>{{ __('زانستی') }}</option>
                                    <option value="وێژەیی" @selected(old('type') === 'وێژەیی')>{{ __('وێژەیی') }}</option>
                                    <option value="زانستی و وێژەیی" @selected(old('type') === 'زانستی و وێژەیی')>{{ __('هەردوو') }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="sex" class="form-label">{{ __('ڕەگەز') }}</label>
                                <select id="sex" name="sex" class="form-select">
                                    <option value="نێر" @selected(old('sex') === 'نێر')>{{ __('نێر') }}</option>
                                    <option value="مێ" @selected(old('sex') === 'مێ')>{{ __('مێ') }}</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">{{ __('وەسف') }}</label>
                                <textarea id="description" name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label">{{ __('دۆخ') }}</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="1" @selected(old('status') === '1')>{{ __('چاڵاک') }}</option>
                                    <option value="0" @selected(old('status') === '0')>{{ __('ناچاڵاک') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('پاشەکەوتکردن') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/pages/departments/form.js') }}" defer></script>
@endpush
