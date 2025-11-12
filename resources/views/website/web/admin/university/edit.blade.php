@extends('website.web.admin.layouts.app')

@section('page_name', 'universities')
@section('view_name', 'edit')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.universities.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title" style="font-size: 32px">
                <i class="fa-solid fa-map-pin me-1 text-muted"></i> نوێ کردنەوەی زانکۆی
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-pen-to-square me-2"></i> نوێکردنەوەی زانکۆ
                    </h4>

                    <form action="{{ route('admin.universities.update', $university->id) }}" method="POST"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- Province --}}
                            <div class="col-md-6">
                                <label for="province_id" class="form-label">
                                    <i class="fa-solid fa-map-pin me-1 text-muted"></i> هەڵبژاردنی پارێزگا <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="province_id" name="province_id" required>
                                    <option value="" disabled>— هەڵبژێرە —</option>
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
                                    <i class="fa-solid fa-tag me-1 text-muted"></i> ناوی زانکۆ <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $university->name }}" required minlength="2" placeholder="ناوی زانکۆ...">
                                <div class="invalid-feedback">تکایە ناوی دروست بنوسە (کەمتر نیە لە ٢ پیت).</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">ناوی زانکۆ (ئینگلیزی)</label>
                                <input type="text" name="name_en" class="form-control" required
                                    value="{{ old('name_en', $university->name_en) }}" placeholder="نموونە: هەولێر">
                                <div class="invalid-feedback">ناو پێویستە.</div>
                            </div>

                            {{-- Area (optional) --}}
                            <div class="mb-3">
                                <label class="form-label">GeoJSON (Optional)</label>
                                <textarea name="geojson" rows="6" class="form-control">{{ is_array($university->geojson) ? json_encode($university->geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $university->geojson }}</textarea>
                            </div>


                             <div id="map" style="height:420px;border-radius:12px" class="m-3"></div>


                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Latitude</label>
                                    <input id="lat" name="lat" type="number" step="any"
                                        value="{{ old('lat', $university->lat ?? null) }}" class="form-control">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Longitude</label>
                                    <input id="lng" name="lng" type="number" step="any"
                                        value="{{ old('lng', $university->lng ?? null) }}" class="form-control">
                                </div>
                            </div>


                            <div class="col-12 col-md-6">
                                <label class="form-label">وێنە</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            {{-- Status --}}
                            <div class="col-md-12">
                                <label for="status" class="form-label">
                                    <i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="status" name="status" required>
                                    <option @selected($university->status == 1) value="1">چاڵاک</option>
                                    <option @selected($university->status == 0) value="0">ناچاڵاک</option>
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
