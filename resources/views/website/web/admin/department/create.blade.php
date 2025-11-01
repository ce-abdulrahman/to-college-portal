@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-success mb-4">
            <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
        </a>
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">زیادکردنی بەش بۆ کۆلێژ یان پەیمانگا</div>
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

                    <form action="{{ route('admin.departments.store') }}" method="POST" class="needs-validation"
                          enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="row g-3">
                            {{-- System --}}
                            <div class="col-12 col-md-6">
                                <label for="system_id" class="form-label">سیستەم</label>
                                <select id="system_id" name="system_id"
                                        class="form-select @error('system_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>هەڵبژاردنی سیستەم</option>
                                    @foreach ($systems as $system)
                                        <option value="{{ $system->id }}" @selected(old('system_id') == $system->id)>
                                            {{ $system->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('system_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Province --}}
                            <div class="col-12 col-md-6">
                                <label for="province_id" class="form-label">پارێزگا</label>
                                <select id="province_id" name="province_id"
                                        class="form-select @error('province_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>هەڵبژاردنی پارێزگا</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}" @selected(old('province_id') == $province->id)>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- University (depends on province) --}}
                            <div class="col-12 col-md-6">
                                <label for="university_id" class="form-label">زانکۆ</label>
                                <select id="university_id" name="university_id"
                                        class="form-select @error('university_id') is-invalid @enderror" required disabled>
                                    <option value="">هەموو زانکۆكان</option>
                                </select>
                                @error('university_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- College (depends on university) --}}
                            <div class="col-12 col-md-6">
                                <label for="college_id" class="form-label">کۆلێژ/پەیمانگا</label>
                                <select id="college_id" name="college_id"
                                        class="form-select @error('college_id') is-invalid @enderror" required disabled>
                                    <option value="">هەموو کۆلێژەکان</option>
                                </select>
                                @error('college_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Names --}}
                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">ناوی بەش</label>
                                <input id="name" name="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="name_en" class="form-label">ناوی بەش (ئینگلیزی)</label>
                                <input id="name_en" name="name_en" type="text"
                                       class="form-control @error('name_en') is-invalid @enderror"
                                       value="{{ old('name_en') }}" required>
                                @error('name_en') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Scores --}}
                            <div class="col-12 col-md-3">
                                <label for="local_score" class="form-label">ن. ناوەندی</label>
                                <input id="local_score" name="local_score" type="number" step="0.001"
                                       class="form-control" value="{{ old('local_score') }}">
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="external_score" class="form-label">ن. دەرەوە</label>
                                <input id="external_score" name="external_score" type="number" step="0.001"
                                       class="form-control" value="{{ old('external_score') }}">
                            </div>

                            {{-- Type / Sex --}}
                            <div class="col-12 col-md-3">
                                <label for="type" class="form-label">لق</label>
                                <select id="type" name="type" class="form-select">
                                    <option value="زانستی" @selected(old('type') === 'زانستی')>زانستی</option>
                                    <option value="وێژەیی" @selected(old('type') === 'وێژەیی')>وێژەیی</option>
                                    <option value="زانستی و وێژەیی" @selected(old('type') === 'زانستی و وێژەیی')>هەردوو</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="sex" class="form-label">ڕەگەز</label>
                                <select id="sex" name="sex" class="form-select">
                                    <option value="نێر" @selected(old('sex') === 'نێر')>نێر</option>
                                    <option value="مێ" @selected(old('sex') === 'مێ')>مێ</option>
                                </select>
                            </div>

                            {{-- Leaflet map --}}
                            <div id="map" style="height:420px;border-radius:12px" class="m-3"></div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Latitude</label>
                                    <input id="lat" name="lat" type="number" step="0.000001"
                                           value="{{ old('lat') }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Longitude</label>
                                    <input id="lng" name="lng" type="number" step="0.000001"
                                           value="{{ old('lng') }}" class="form-control">
                                </div>
                                <div class="form-text">لەسەر نەخشە کلیک بکە بۆ دیاریکردنی شوێن.</div>
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label for="description" class="form-label">وەسف</label>
                                <textarea id="description" name="description" class="form-control summernote" rows="4">{{ old('description') }}</textarea>
                            </div>

                            {{-- Image --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">وێنە</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            {{-- Status --}}
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label">دۆخ</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="1" @selected(old('status') === '1')>چاڵاک</option>
                                    <option value="0" @selected(old('status') === '0')>ناچاڵاک</option>
                                </select>
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

@push('head-scripts')
    {{-- Leaflet CSS (ئەگەر لە layout بارنەکردووە) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    {{-- Summernote CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
    {{-- jQuery (پێشو Summernote) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>
    {{-- Leaflet JS (ئەگەر لە layout بارنەکردووە) --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
    {{-- Summernote JS --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/summernote-lite.min.js" defer></script>

    {{-- API base urls (hardcode مەکەین) --}}
    <script>
        window.API_UNI   = "{{ route('admin.api.universities') }}"; // ?province_id=ID
        window.API_COLLS = "{{ route('admin.api.colleges') }}";     // ?university_id=ID
    </script>

    {{-- پەیجی JS ـی تایبەتی --}}
    <script src="{{ asset('assets/admin/js/pages/departments/create.js') }}" defer></script>
@endpush
