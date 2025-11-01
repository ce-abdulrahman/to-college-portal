{{-- resources/views/website/web/center/student/edit.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
            <a href="{{ route('center.students.index') }}" class="btn btn-outline mb-3">
                <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
            </a>

            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-user-pen me-2"></i> دەستکاریکردنی زانیاری قوتابی
                    </h4>

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> تکایە هەڵەکان چاک بکە:
                            <ul class="mb-0 mt-2 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('center.students.update', $student->id) }}" method="POST"
                        class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Basic (User) --}}
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">ناو</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', data_get($student, 'user.name')) }}" placeholder="ناوی تەواو"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">ناو پێویستە.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="phone" class="form-label">ژمارەی مۆبایل</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    <input type="text" id="phone" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', data_get($student, 'user.phone')) }}"
                                        placeholder="07xxxxxxxx" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">ژمارە پێویستە.</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Student fields --}}
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="mark" class="form-label">نمرە</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-star-half-stroke"></i></span>
                                    <input type="number" step="0.01" id="mark" name="mark"
                                        class="form-control @error('mark') is-invalid @enderror"
                                        value="{{ old('mark', $student->mark) }}" placeholder="مثال: 89.75" required>
                                    @error('mark')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">نمرە پێویستە.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="type" class="form-label">لق (جۆر)</label>
                                <select id="type" name="type"
                                    class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">— هەلبژاردن —</option>
                                    <option value="زانستی" @selected(old('type', $student->type) === 'زانستی')>زانستی</option>
                                    <option value="وێژەیی" @selected(old('type', $student->type) === 'وێژەیی')>وێژەیی</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="year" class="form-label">ساڵ</label>
                                <select id="year" name="year"
                                    class="form-select @error('year') is-invalid @enderror" required>
                                    <option value="">— هەلبژاردن —</option>
                                    @for ($y = 1; $y <= 5; $y++)
                                        <option value="{{ $y }}" @selected((int) old('year', $student->year) === $y)>
                                            {{ $y }}</option>
                                    @endfor
                                </select>
                                @error('year')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="province" class="form-label">پارێزگا</label>
                                <select id="province" name="province"
                                    class="form-select @error('province') is-invalid @enderror" required>
                                    <option value="">— هەلبژاردن —</option>
                                    @foreach ($provinces ?? [] as $prov)
                                        @php
                                            $value = is_array($prov)
                                                ? $prov['name'] ?? ($prov['id'] ?? '')
                                                : (is_object($prov)
                                                    ? $prov->name ?? $prov->id
                                                    : $prov);
                                            $label = is_array($prov)
                                                ? $prov['name'] ?? ($prov['id'] ?? '')
                                                : (is_object($prov)
                                                    ? $prov->name ?? $prov->id
                                                    : $prov);
                                        @endphp
                                        <option value="{{ $value }}" @selected(old('province', $student->province) == $value)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

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
        // Bootstrap validation (optional)
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
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
