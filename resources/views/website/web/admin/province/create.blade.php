@extends('website.web.admin.layouts.app')

@section('page_name', 'province')
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.provinces.index') }}">پارێزگاکان</a></li>
                            <li class="breadcrumb-item active">تەواوی پارێزگاکان</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-map-pin me-1"></i>
                        تەواوی پارێزگاکان
                    </h4>
                </div>
            </div>
        </div>

        @if (auth()->user()->role !== 'admin')
            <div class="alert alert-warning">
                <i class="fa-solid fa-lock me-1"></i> تەنها ئەدمین دەتوانێت پارێزگا دروست بکات.
            </div>
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

                            <form action="{{ route('admin.provinces.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">ناوی پارێزگا</label>
                                        <input type="text" name="name" class="form-control" required
                                            value="{{ old('name') }}" placeholder="نموونە: هەولێر">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">ناوی پارێزگا (ئینگلیزی)</label>
                                        <input type="text" name="name_en" class="form-control" required
                                            value="{{ old('name_en') }}" placeholder="نموونە: Erbil">
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

                                    <div class="col-12">
                                        <label class="form-label">GeoJSON (ئارەزوومەندانە)</label>
                                        <textarea name="geojson" rows="4" class="form-control" placeholder='{"type":"Polygon","coordinates":[...]}'>{{ old('geojson') }}</textarea>
                                        <div class="form-text">دەتوانیت GeoJSON پەیست بکەیت یان فایل بار بکەیت</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">بارکردنی GeoJSON (ئارەزوومەندانە)</label>
                                        <input type="file" name="geojson_file" class="form-control"
                                            accept=".geojson,.json">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">وێنە</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">دۆخ</label>
                                        <select class="form-select" name="status" required>
                                            <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>چاڵاک
                                            </option>
                                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>ناچاڵاک
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2 mt-4">
                                            <a href="{{ route('admin.provinces.index') }}"
                                                class="btn btn-outline-secondary">
                                                <i class="fa-solid fa-times me-1"></i> هەڵوەشاندنەوە
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوت
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let map, marker;
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            const defaultLat = 33.3128;
            const defaultLng = 44.3615;

            const initialLat = parseFloat(latInput.value) || defaultLat;
            const initialLng = parseFloat(lngInput.value) || defaultLng;

            map = L.map('location-map').setView([initialLat, initialLng], 8);

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
        });
    </script>
@endpush
