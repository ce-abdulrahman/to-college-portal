@extends('website.web.admin.layouts.app')

@section('page_name', 'universities')
@section('view_name', 'edit')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.universities.index') }}">زانکۆکان</a></li>
                        <li class="breadcrumb-item active">نوێکردنەوەی زانکۆ</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-building-columns me-1"></i>
                    نوێکردنەوەی زانکۆ
                </h4>
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
                                    <i class="fa-solid fa-map-pin me-1 text-muted"></i> هەڵبژاردنی پارێزگا <span class="text-danger">*</span>
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
                                    <i class="fa-solid fa-tag me-1 text-muted"></i> ناوی زانکۆ <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $university->name }}" required minlength="2" placeholder="ناوی زانکۆ...">
                                <div class="invalid-feedback">تکایە ناوی دروست بنوسە (کەمتر نیە لە ٢ پیت).</div>
                            </div>

                            {{-- English Name --}}
                            <div class="col-12">
                                <label for="name_en" class="form-label">ناوی زانکۆ (ئینگلیزی) <span class="text-danger">*</span></label>
                                <input type="text" id="name_en" name="name_en" class="form-control" required
                                    value="{{ old('name_en', $university->name_en) }}" placeholder="نموونە: University of Erbil">
                                <div class="invalid-feedback">ناو پێویستە.</div>
                            </div>

                            {{-- Map Section --}}
                            <div class="col-12">
                                <label class="form-label">شوێنەکە دیاری بکە</label>
                                <div id="university-map-edit" 
                                     style="height: 400px; border-radius: 12px;"
                                     data-geojson='{{ $university->geojson ? json_encode($university->geojson) : "null" }}'
                                     data-lat="{{ $university->lat ?? '' }}"
                                     data-lng="{{ $university->lng ?? '' }}">
                                </div>
                            </div>

                            {{-- Coordinates --}}
                            <div class="col-md-6">
                                <label for="lat" class="form-label">Latitude</label>
                                <input id="lat" name="lat" type="number" step="any"
                                    value="{{ $university->lat ?? '' }}" class="form-control" placeholder="36.1911">
                            </div>
                            <div class="col-md-6">
                                <label for="lng" class="form-label">Longitude</label>
                                <input id="lng" name="lng" type="number" step="any"
                                    value="{{ $university->lng ?? '' }}" class="form-control" placeholder="44.0092">
                            </div>
                            <div class="col-12">
                                <div class="form-text">لەسەر نەخشە کلیک بکە، lat/lng خۆکار پڕ دەبن.</div>
                            </div>

                            {{-- GeoJSON Text --}}
                            <div class="col-12">
                                <label for="geojson_text" class="form-label">GeoJSON (Optional)</label>
                                <textarea id="geojson_text" name="geojson_text" rows="5" class="form-control" placeholder='{"type":"Feature","geometry":{"type":"Point","coordinates":[44.0092,36.1911]}}'>
@php
    if ($university->geojson) {
        if (is_string($university->geojson)) {
            echo $university->geojson;
        } else {
            echo json_encode($university->geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }
@endphp
</textarea>
                                <div class="form-text">دەتوانیت GeoJSON paste بکەیت.</div>
                            </div>

                            {{-- Image --}}
                            <div class="col-md-6">
                                <label for="image" class="form-label">وێنە</label>
                                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                @if($university->image)
                                    <div class="mt-2">
                                        <small class="text-muted">وێنەی ئێستا:</small><br>
                                        <img src="{{ $university->image }}" alt="{{ $university->name }}" 
                                            style="height: 60px; border-radius: 6px; margin-top: 5px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>

                            {{-- Status --}}
                            <div class="col-md-12">
                                <label for="status" class="form-label">
                                    <i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1" {{ $university->status == 1 ? 'selected' : '' }}>چاڵاک</option>
                                    <option value="0" {{ $university->status == 0 ? 'selected' : '' }}>ناچاڵاک</option>
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

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .leaflet-container { z-index: 1; }
    #geojson_text { font-family: monospace; font-size: 12px; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });

        // Map initialization for edit page with safe GeoJSON handling
        const mapEl = document.getElementById('university-map-edit');
        if (!mapEl) return;

        const rawGeojson = mapEl.dataset.geojson;
        const lat = parseFloat(mapEl.dataset.lat) || null;
        const lng = parseFloat(mapEl.dataset.lng) || null;

        const defaultLat = 36.1911;
        const defaultLng = 44.0092;
        const defaultZoom = 12;
        
        // Create map
        const map = L.map('university-map-edit').setView(
            lat && lng ? [lat, lng] : [defaultLat, defaultLng],
            defaultZoom
        );
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        let marker = null;
        let geojsonLayer = null;

        // Safely parse and add existing GeoJSON
        let existingGeojson = null;
        if (rawGeojson && rawGeojson !== 'null' && rawGeojson !== 'undefined') {
            try {
                existingGeojson = JSON.parse(rawGeojson);
                
                // Validate GeoJSON structure
                if (existingGeojson && 
                    (existingGeojson.type === 'Feature' || 
                     existingGeojson.type === 'FeatureCollection' || 
                     existingGeojson.type === 'GeometryCollection' ||
                     existingGeojson.type === 'Point' ||
                     existingGeojson.type === 'LineString' ||
                     existingGeojson.type === 'Polygon' ||
                     existingGeojson.type === 'MultiPoint' ||
                     existingGeojson.type === 'MultiLineString' ||
                     existingGeojson.type === 'MultiPolygon')) {
                    
                    geojsonLayer = L.geoJSON(existingGeojson, {
                        style: {
                            color: '#2563eb',
                            weight: 2,
                            fillColor: '#3b82f6',
                            fillOpacity: 0.15
                        }
                    }).addTo(map);
                    
                    if (geojsonLayer.getBounds) {
                        const bounds = geojsonLayer.getBounds();
                        if (bounds.isValid()) {
                            map.fitBounds(bounds);
                        }
                    }
                }
            } catch (e) {
                console.warn('Invalid existing GeoJSON data:', e.message);
                // Continue without GeoJSON if it's invalid
            }
        }

        // Add existing marker if coordinates are available
        if (lat && lng) {
            marker = L.marker([lat, lng]).addTo(map)
                .bindPopup(`شوێنی زانکۆ<br>Lat: ${lat}<br>Lng: ${lng}`)
                .openPopup();
        }

        // Click handler for map
        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            
            document.getElementById('lat').value = lat.toFixed(6);
            document.getElementById('lng').value = lng.toFixed(6);
            
            if (marker) {
                map.removeLayer(marker);
            }
            
            marker = L.marker([lat, lng]).addTo(map)
                .bindPopup(`شوێنی نوێ<br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`)
                .openPopup();
        });

        // Handle GeoJSON text input with validation
        const geojsonTextarea = document.getElementById('geojson_text');
        geojsonTextarea.addEventListener('change', function() {
            if (this.value.trim() === '') {
                // Remove the geojson layer if it exists
                if (geojsonLayer) {
                    map.removeLayer(geojsonLayer);
                    geojsonLayer = null;
                }
                return;
            }

            try {
                const geojson = JSON.parse(this.value);
                
                // Validate GeoJSON structure
                if (!geojson || 
                    !(geojson.type === 'Feature' || 
                      geojson.type === 'FeatureCollection' || 
                      geojson.type === 'GeometryCollection' ||
                      geojson.type === 'Point' ||
                      geojson.type === 'LineString' ||
                      geojson.type === 'Polygon' ||
                      geojson.type === 'MultiPoint' ||
                      geojson.type === 'MultiLineString' ||
                      geojson.type === 'MultiPolygon')) {
                    throw new Error('Invalid GeoJSON type');
                }
                
                if (geojsonLayer) {
                    map.removeLayer(geojsonLayer);
                }
                
                geojsonLayer = L.geoJSON(geojson, {
                    style: {
                        color: '#16a34a',
                        weight: 2,
                        fillColor: '#22c55e',
                        fillOpacity: 0.15
                    }
                }).addTo(map);
                
                if (geojsonLayer.getBounds) {
                    const bounds = geojsonLayer.getBounds();
                    if (bounds.isValid()) {
                        map.fitBounds(bounds);
                    }
                }
            } catch (e) {
                console.error('Invalid GeoJSON:', e.message);
                alert('GeoJSON نادروستە! تکایە GeoJSON دروست بنووسە.');
            }
        });

        // Fix map size after load
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    });
</script>
@endpush