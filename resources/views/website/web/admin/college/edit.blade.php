@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.colleges.index') }}">کۆلێژکان</a></li>
                        <li class="breadcrumb-item active">نوێکردنەوەی کۆلێژ</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-building-columns me-1"></i>
                    نوێکردنەوەی کۆلێژ
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-pen-to-square me-2"></i> دەستکاری کۆلێژ
                    </h4>

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

                    <form id="collegeForm" action="{{ route('admin.colleges.update', $college->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="university_id" class="form-label">هەڵبژاردنی زانکۆ</label>
                                <select id="university_id" name="university_id" class="form-select" required>
                                    <option value="" disabled>هەڵبژاردنی زانکۆ</option>
                                    @foreach ($universities as $uni)
                                        <option value="{{ $uni->id }}" {{ old('university_id', $college->university_id) == $uni->id ? 'selected' : '' }}>
                                            {{ $uni->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">ناوی کۆلێژ</label>
                                <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $college->name) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">ناوی کۆلێژ (ئینگلیزی)</label>
                                <input type="text" name="name_en" class="form-control" required value="{{ old('name_en', $college->name_en) }}" placeholder="نموونە: College of Science">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">دۆخ</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="1" {{ old('status', $college->status) == 1 ? 'selected' : '' }}>چاڵاک</option>
                                    <option value="0" {{ old('status', $college->status) == 0 ? 'selected' : '' }}>ناچاڵاک</option>
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">وێنە</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                @if($college->image)
                                    <small class="text-muted">وێنەی ئێستا:</small>
                                    <img src="{{ $college->image }}" alt="{{ $college->name }}" class="mt-2" style="height: 60px; border-radius: 6px;">
                                @endif
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label">GeoJSON (ئارەزوومەندانە)</label>
                                <textarea name="geojson" rows="4" class="form-control">{{ is_array($college->geojson) ? json_encode($college->geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $college->geojson }}</textarea>
                            </div>

                            <div class="col-12 mb-4">
                                <h5 class="mb-3"><i class="fa-solid fa-map-pin me-2"></i> شوێنەکە دیاری بکە</h5>
                                <div id="location-map" style="height: 400px; border-radius: 12px; border: 2px solid #dee2e6;"></div>
                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Latitude</label>
                                        <input id="lat" name="lat" type="number" step="0.000001" value="{{ old('lat', $college->lat) }}" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Longitude</label>
                                        <input id="lng" name="lng" type="number" step="0.000001" value="{{ old('lng', $college->lng) }}" class="form-control" readonly>
                                    </div>
                                </div>
                                <small class="text-muted">لەسەر نەخشە کلیک بکە بۆ دیاریکردنی شوێن</small>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.colleges.show', $college->id) }}" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-eye me-1"></i> پیشاندان
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
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let map, marker;
        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');
        
        const initialLat = parseFloat(latInput.value) || 33.3128;
        const initialLng = parseFloat(lngInput.value) || 44.3615;
        
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
    });
</script>
@endpush