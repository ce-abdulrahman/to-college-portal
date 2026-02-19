@extends('website.web.admin.layouts.app')

@section('page_name', 'department')
@section('view_name', 'create')

@section('content')
    <div class="container-fluid py-4">
        {{-- Actions bar --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}">بەشەکان</a></li>
                            <li class="breadcrumb-item active">زیادکردنی بەش</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-building-columns me-1"></i>
                        زیادکردنی بەش
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                <div class="card glass fade-in">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class="fa-solid fa-plus me-2"></i> زیادکردنی بەش</h4>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> هەڵە هەیە لە داهێنان:
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.departments.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                {{-- System --}}
                                <div class="col-md-6">
                                    <label for="system_id" class="form-label">سیستەم</label>
                                    <select id="system_id" name="system_id" class="form-select" required>
                                        <option value="" disabled selected>هەڵبژاردنی سیستەم</option>
                                        @foreach ($systems as $system)
                                            <option value="{{ $system->id }}"
                                                {{ old('system_id') == $system->id ? 'selected' : '' }}>
                                                {{ $system->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Province --}}
                                <div class="col-md-6">
                                    <label for="province_id" class="form-label">پارێزگا</label>
                                    <select id="province_id" name="province_id" class="form-select" required>
                                        <option value="" disabled selected>هەڵبژاردنی پارێزگا</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}"
                                                {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- University (depends on province) --}}
                                <div class="col-md-6">
                                    <label for="university_id" class="form-label">زانکۆ</label>
                                    <select id="university_id" name="university_id" class="form-select" required>
                                        <option value="">تکایە یەکەم پارێزگا هەڵبژێرە</option>
                                    </select>
                                </div>

                                {{-- College (depends on university) --}}
                                <div class="col-md-6">
                                    <label for="college_id" class="form-label">کۆلێژ/پەیمانگا</label>
                                    <select id="college_id" name="college_id" class="form-select" required>
                                        <option value="">تکایە یەکەم زانکۆ هەڵبژێرە</option>
                                    </select>
                                </div>

                                {{-- Names --}}
                                <div class="col-md-6">
                                    <label for="name" class="form-label">ناوی بەش</label>
                                    <input id="name" name="name" type="text" class="form-control"
                                        value="{{ old('name') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="name_en" class="form-label">ناوی بەش (ئینگلیزی)</label>
                                    <input id="name_en" name="name_en" type="text" class="form-control"
                                        value="{{ old('name_en') }}" required>
                                </div>

                                {{-- Scores --}}
                                <div class="col-md-3">
                                    <label for="local_score" class="form-label">ن. ناوەندی</label>
                                    <input id="local_score" name="local_score" type="number" step="0.001"
                                        class="form-control" value="{{ old('local_score') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="external_score" class="form-label">ن. دەرەوە</label>
                                    <input id="external_score" name="external_score" type="number" step="0.001"
                                        class="form-control" value="{{ old('external_score') }}">
                                </div>

                                {{-- Type / Sex --}}
                                <div class="col-md-3">
                                    <label for="type" class="form-label">لق</label>
                                    <select id="type" name="type" class="form-select">
                                        <option value="زانستی" {{ old('type') === 'زانستی' ? 'selected' : '' }}>زانستی
                                        </option>
                                        <option value="وێژەیی" {{ old('type') === 'وێژەیی' ? 'selected' : '' }}>وێژەیی
                                        </option>
                                        <option value="زانستی و وێژەیی"
                                            {{ old('type') === 'زانستی و وێژەیی' ? 'selected' : '' }}>هەردوو</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="sex" class="form-label">ڕەگەز</label>
                                    <select id="sex" name="sex" class="form-select">
                                        <option value="نێر" {{ old('sex') === 'نێر' ? 'selected' : '' }}>نێر</option>
                                        <option value="مێ" {{ old('sex') === 'مێ' ? 'selected' : '' }}>مێ</option>
                                    </select>
                                </div>

                                {{-- Map Section --}}
                                <div class="col-12">
                                    <div class="card mt-3">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0"><i class="fa-solid fa-map me-2"></i> دیاریکردنی شوێن</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="location-map"
                                                style="height: 400px; border-radius: 10px; border: 2px solid #dee2e6;">
                                            </div>
                                            <div class="row g-3 mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Latitude</label>
                                                    <input id="lat" name="lat" type="number" step="0.000001"
                                                        value="{{ old('lat') }}" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Longitude</label>
                                                    <input id="lng" name="lng" type="number" step="0.000001"
                                                        value="{{ old('lng') }}" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <small class="text-muted">لەسەر نەخشە کلیک بکە بۆ دیاریکردنی شوێن</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="col-12">
                                    <label for="description" class="form-label">وەسف</label>
                                    <textarea id="description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                                </div>

                                {{-- Image --}}
                                <div class="col-md-6">
                                    <label class="form-label">وێنە</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>

                                {{-- Status --}}
                                <div class="col-md-6">
                                    <label for="status" class="form-label">دۆخ</label>
                                    <select id="status" name="status" class="form-select" required>
                                        <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>چاڵاک
                                        </option>
                                        <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>ناچاڵاک
                                        </option>
                                    </select>
                                </div>

                                {{-- Submit Button --}}
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <a href="{{ route('admin.departments.index') }}"
                                            class="btn btn-outline-secondary">
                                            <i class="fa-solid fa-times me-1"></i> هەڵوەشاندنەوە
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوتکردن
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- jQuery (پێویستی Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <!-- Summernote RTL -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/lang/summernote-fa-IR.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Map initialization
            let map, marker;
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            const defaultLat = 33.3128;
            const defaultLng = 44.3615;

            const initialLat = parseFloat(latInput.value) || defaultLat;
            const initialLng = parseFloat(lngInput.value) || defaultLng;

            map = L.map('location-map').setView([initialLat, initialLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            if (latInput.value && lngInput.value) {
                marker = L.marker([initialLat, initialLng]).addTo(map);
            }

            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);

                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng]).addTo(map);
            });

            // Dynamic dropdowns
            const provinceSelect = document.getElementById('province_id');
            const universitySelect = document.getElementById('university_id');
            const collegeSelect = document.getElementById('college_id');

            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;

                if (!provinceId) {
                    universitySelect.innerHTML = '<option value="">تکایە یەکەم پارێزگا هەڵبژێرە</option>';
                    collegeSelect.innerHTML = '<option value="">تکایە یەکەم زانکۆ هەڵبژێرە</option>';
                    return;
                }

                fetch(`{{ route('admin.api.universities') }}?province_id=${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">هەموو زانکۆكان</option>';
                        data.forEach(university => {
                            options +=
                                `<option value="${university.id}">${university.name}</option>`;
                        });
                        universitySelect.innerHTML = options;
                    });
            });

            universitySelect.addEventListener('change', function() {
                const universityId = this.value;

                if (!universityId) {
                    collegeSelect.innerHTML = '<option value="">تکایە یەکەم زانکۆ هەڵبژێرە</option>';
                    return;
                }

                fetch(`{{ route('admin.api.colleges') }}?university_id=${universityId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">هەموو کۆلێژەکان</option>';
                        data.forEach(college => {
                            options += `<option value="${college.id}">${college.name}</option>`;
                        });
                        collegeSelect.innerHTML = options;
                    });
            });

            // Summernote initialization for description
            const descriptionTextarea = document.getElementById('description');
            if (descriptionTextarea) {
                $(descriptionTextarea).summernote({
                    height: 200,
                    lang: 'fa-IR',
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    callbacks: {
                        onChange: function(contents, $editable) {
                            descriptionTextarea.value = contents;
                        }
                    }
                });

                // دڵنیابوون لەوەی کە نرخی کۆن لەسەر دەمێنێتەوە
                $(descriptionTextarea).summernote('code', descriptionTextarea.value);
            }
        });
    </script>
@endpush
