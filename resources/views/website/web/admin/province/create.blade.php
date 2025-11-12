@extends('website.web.admin.layouts.app')

@section('page_name', 'province')
@section('view_name', 'create')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.provinces.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
        </a>

        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title" style="font-size: 32px">
                <i class="fa-solid fa-map-pin me-1 text-muted"></i> دروستکردنی پارێزگای نوێ
            </div>
        </div>
    </div>

    @if (auth()->user()->role !== 'admin')
        <div class="alert alert-warning"><i class="fa-solid fa-lock me-1"></i> تەنها ئەدمین دەتوانێت پارێزگا دروست بکات.</div>
    @else
        <div class="row">
            <div class="col-12 col-xl-8 mx-auto">
                <div class="card glass fade-in">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class="fa-solid fa-map-location-dot me-2"></i> پارێزگای نوێ</h4>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> هەڵە هەیە:
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.provinces.store') }}" method="POST" class="needs-validation"
                            novalidate enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">ناوی پارێزگا</label>
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ old('name') }}" placeholder="نموونە: هەولێر">
                                    <div class="invalid-feedback">ناو پێویستە.</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">ناوی پارێزگا (ئینگلیزی)</label>
                                    <input type="text" name="name_en" class="form-control" required
                                        value="{{ old('name_en') }}" placeholder="نموونە: هەولێر">
                                    <div class="invalid-feedback">ناو پێویستە.</div>
                                </div>

                                {{-- GeoJSON (Optional) --}}
                                <div class="mb-3">
                                    <label class="form-label">ڕووبەر (GeoJSON)</label>
                                    <textarea name="geojson" rows="8" class="form-control" placeholder='{"type":"Polygon","coordinates":[...]'>{{ old('geojson') }}</textarea>
                                    <div class="form-text">دەتوانیت GeoJSON paste بکەیت یان فایل upload بکەیت.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Upload GeoJSON (Optional)</label>
                                    <input type="file" name="geojson_file" class="form-control"
                                        accept=".geojson,.json,.txt">
                                </div>

                                <div id="map" style="height:420px;width:100%;border-radius:12px"></div>


                                <div class="col-12 col-md-6">
                                    <label class="form-label">وێنە</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">دۆخ</label>
                                    <select class="form-select" name="status" required>
                                        <option value="1" @selected(old('status') === '1')>چاڵاک</option>
                                        <option value="0" @selected(old('status') === '0')>ناچاڵاک</option>
                                    </select>
                                    <div class="invalid-feedback">دۆخ دیاری بکە.</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوت
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
