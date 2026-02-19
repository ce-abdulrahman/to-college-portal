{{-- resources/views/website/web/teacher/student/edit.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('teacher.students.index') }}">قوتابیەکان</a></li>
                            <li class="breadcrumb-item active">دەستکاری</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-user-edit me-1"></i>
                        دەستکاریکردنی زانیاری قوتابی
                    </h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-xl-8 mx-auto">
                <a href="{{ route('teacher.students.index') }}" class="btn btn-outline mb-3">
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

                        @php
                            $teacher = auth()->user()->teacher;
                            $canActivateStudent = $canActivateStudent ?? true;
                            $currentStatusValue = (string) old('status', (string) ((int) data_get($student, 'status', data_get($student, 'user.status', 0))));
                        @endphp

                        <form action="{{ route('teacher.students.update', $student->id) }}" method="POST"
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
                                            value="{{ old('name', data_get($student, 'user.name')) }}"
                                            placeholder="ناوی تەواو" required>
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

                                <div class="col-12 col-md-6">
                                    <label for="status" class="form-label">دۆخ</label>
                                    <select id="status" name="status"
                                        class="form-select @error('status') is-invalid @enderror" @disabled(!$canActivateStudent)>
                                        <option value="1" @selected($currentStatusValue === '1')>چاڵاک</option>
                                        <option value="0" @selected($currentStatusValue === '0')>ناچاڵاک</option>
                                    </select>
                                    @if (!$canActivateStudent)
                                        <input type="hidden" name="status" value="{{ $currentStatusValue === '1' ? '1' : '0' }}">
                                        <small class="text-danger d-block mt-1">
                                            سنووری قبوڵکردنی قوتابی تەواو بووە. هەتا سنوور زیاد نەکرێت چاڵاککردن ناچالاکە.
                                        </small>
                                    @endif
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" id="lat" name="lat" value="{{ old('lat') }}">
                                <input type="hidden" id="lng" name="lng" value="{{ old('lng') }}">
                            </div>

                            @include('website.web.center.partials.feature-access-fields', [
                                'center' => $teacher,
                                'currentModel' => $student,
                                'subjectLabel' => 'قوتابی',
                                'formPrefix' => 'teacher_student_edit',
                                'ownerLabel' => 'مامۆستا',
                            ])

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

        (function() {
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            const form = document.querySelector('form.needs-validation');
            let locatingInProgress = false;

            function getAiRankValue() {
                const checked = document.querySelector('input[name="ai_rank"]:checked');
                if (checked) return String(checked.value);

                const hidden = document.querySelector('input[name="ai_rank"][type="hidden"]');
                return hidden ? String(hidden.value) : '0';
            }

            function shouldRequireLocation() {
                return getAiRankValue() === '1';
            }

            function hasLocation() {
                const lat = String(latInput?.value ?? '').trim();
                const lng = String(lngInput?.value ?? '').trim();
                return lat !== '' && lng !== '';
            }

            function fillLocationFromBrowser() {
                return new Promise((resolve) => {
                    if (!latInput || !lngInput || !navigator.geolocation || locatingInProgress) {
                        resolve(false);
                        return;
                    }

                    locatingInProgress = true;
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            latInput.value = Number(position.coords.latitude).toFixed(7);
                            lngInput.value = Number(position.coords.longitude).toFixed(7);
                            locatingInProgress = false;
                            resolve(true);
                        },
                        () => {
                            locatingInProgress = false;
                            resolve(false);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0,
                        }
                    );
                });
            }

            async function syncLocationRequirement() {
                const requireLocation = shouldRequireLocation();
                if (latInput) latInput.required = requireLocation;
                if (lngInput) lngInput.required = requireLocation;

                if (!requireLocation) {
                    if (latInput) latInput.value = '';
                    if (lngInput) lngInput.value = '';
                    return;
                }

                if (!hasLocation()) {
                    await fillLocationFromBrowser();
                }
            }

            document.querySelectorAll('input[name="ai_rank"]').forEach((el) => {
                el.addEventListener('change', () => {
                    void syncLocationRequirement();
                });
            });

            if (form) {
                form.addEventListener('submit', async function(event) {
                    if (!shouldRequireLocation() || hasLocation()) {
                        return;
                    }

                    event.preventDefault();
                    const ok = await fillLocationFromBrowser();
                    if (ok) {
                        if (typeof form.requestSubmit === 'function') {
                            form.requestSubmit();
                        } else {
                            form.submit();
                        }
                        return;
                    }

                    alert('نەتوانرا شوێنی قوتابی وەربگیرێت. تکایە ڕێگەبدە بە Location.');
                });
            }

            void syncLocationRequirement();
        })();
    </script>
@endpush
