@extends('website.web.admin.layouts.app')

@section('page_name', 'departments')
@section('view_name', 'edit')

@section('content')


    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-success mb-4">
            <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
        </a>
        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">نوێ کردنی بەش بۆ کۆلێژ یان پەیمانگا </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4"><i class="fa-solid fa-pen-to-square me-2"></i> دەستکاری بەش</h4>

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

                    <form action="{{ route('admin.departments.update', $department->id) }}" method="POST"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf @method('PUT')

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="system_id" class="form-label">سیستەم</label>
                                <select id="system_id" name="system_id" class="form-select" required>
                                    @foreach ($systems as $system)
                                        <option value="{{ $system->id }}" @selected(old('system_id', $department->system_id) == $system->id)>{{ $system->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="province_id" class="form-label">پارێزگا</label>
                                <select id="province_id" name="province_id" class="form-select" required>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}" @selected(old('province_id', $department->province_id) == $province->id)>
                                            {{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="university_id" class="form-label">زانکۆ</label>
                                <select id="university_id" name="university_id" class="form-select" required>
                                    @foreach ($universities as $university)
                                        <option value="{{ $university->id }}" @selected(old('university_id', $department->university_id) == $university->id)>
                                            {{ $university->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="college_id" class="form-label">کۆلێژ/پەیمانگا</label>
                                <select id="college_id" name="college_id" class="form-select" required>
                                    @foreach ($colleges as $college)
                                        <option value="{{ $college->id }}" @selected(old('college_id', $department->college_id) == $college->id)>
                                            {{ $college->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">ناوی بەش</label>
                                <input id="name" name="name" type="text" class="form-control"
                                    value="{{ old('name', $department->name) }}" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="name_en" class="form-label">ناوی بەش (ئینگلیزی)</label>
                                <input id="name_en" name="name_en" type="text"
                                    class="form-control @error('name_en') is-invalid @enderror"
                                    value="{{ old('name_en', $department->name_en) }}" required>
                                @error('name_en')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="local_score" class="form-label">ن. ناوەندی</label>
                                <input id="local_score" name="local_score" type="number" step="0.01"
                                    class="form-control" value="{{ old('local_score', $department->local_score) }}">
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="external_score" class="form-label">ن. ناوخۆی</label>
                                <input id="external_score" name="external_score" type="number" step="0.01"
                                    class="form-control" value="{{ old('external_score', $department->external_score) }}">
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="type" class="form-label">جۆر</label>
                                <select id="type" name="type" class="form-select">
                                    <option value="زانستی" @selected(old('type', $department->type) === 'زانستی')>زانستی</option>
                                    <option value="وێژەیی" @selected(old('type', $department->type) === 'وێژەیی')>وێژەیی</option>
                                    <option value="زانستی و وێژەیی" @selected(old('type', $department->type) === 'زانستی و وێژەیی')>هەردوو
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="sex" class="form-label">ڕەگەز</label>
                                <select id="sex" name="sex" class="form-select">
                                    <option value="نێر" @selected(old('sex', $department->sex) === 'نێر')>نێر</option>
                                    <option value="مێ" @selected(old('sex', $department->sex) === 'مێ')>مێ</option>
                                </select>
                            </div>

                            <div id="map" style="height:420px;border-radius:12px" class="m-3"></div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Latitude</label>
                                    <input id="lat" name="lat"
                                        value="{{ old('lat', $department->lat ?? null) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Longitude</label>
                                    <input id="lng" name="lng"
                                        value="{{ old('lng', $department->lng ?? null) }}" class="form-control">
                                </div>
                                <div class="form-text">لەسەر نەخشە کلیک بکە بۆ دابنانی شوێن.</div>
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">وەسف</label>
                                <textarea id="description" name="description" class="form-control summernote" rows="4">{{ old('description', $department->description) }}</textarea>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">وێنە</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label">دۆخ</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="1" @selected(old('status', $department->status) == 1)>چاڵاک</option>
                                    <option value="0" @selected(old('status', $department->status) == 0)>ناچاڵاک</option>
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


@push('scripts')
    {{-- API base urls (hardcode مەکەین) --}}
    <script>
        window.API_UNI = "{{ route('admin.api.universities') }}"; // ?province_id=ID
        window.API_COLLS = "{{ route('admin.api.colleges') }}"; // ?university_id=ID
    </script>
@endpush
