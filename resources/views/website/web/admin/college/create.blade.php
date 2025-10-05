@extends('website.web.admin.layouts.app')

@section('content')
    <a href="{{ route('admin.colleges.index') }}" class="btn btn-outline mb-4">
        <i class="fa-solid fa-arrow-right-long me-1"></i> {{ __('گەڕانەوە') }}
    </a>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-building-columns me-2"></i> {{ __('دروست کردنی کۆلێژ') }}
                    </h4>

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

                    <form action="{{ route('admin.colleges.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="university_id" class="form-label">{{ __('هەڵبژاردنی زانکۆ') }}</label>
                            <select id="university_id" name="university_id"
                                class="form-select @error('university_id') is-invalid @enderror" required>
                                <option value="" disabled selected>{{ __('هەڵبژاردنی زانکۆ') }}</option>
                                @foreach ($universities as $uni)
                                    <option value="{{ $uni->id }}" @selected(old('university_id') == $uni->id)>{{ $uni->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('university_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('ناوی کۆلێژ') }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('دۆخ') }}</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror"
                                required>
                                <option value="1" @selected(old('status') === '1')>{{ __('چاڵاک') }}</option>
                                <option value="0" @selected(old('status') === '0')>{{ __('ناچاڵاک') }}</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
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
  <script src="{{ asset('assets/admin/js/pages/colleges/form.js') }}" defer></script>
@endpush
