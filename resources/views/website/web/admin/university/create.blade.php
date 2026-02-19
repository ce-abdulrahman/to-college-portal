@extends('website.web.admin.layouts.app')

@section('page_name', 'universities')
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.universities.index') }}">زانکۆکان</a></li>
                            <li class="breadcrumb-item active">دروستکردنی زانکۆی نوێ</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-building-columns me-1"></i>
                        دروستکردنی زانکۆی نوێ
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-8 mx-auto">
                <div class="card glass fade-in">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-building-columns me-2"></i> دروستکردنی زانکۆ
                        </h4>

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

                        <form action="{{ route('admin.universities.store') }}" method="POST" class="needs-validation"
                            enctype="multipart/form-data" novalidate>
                            @csrf

                            <div class="row g-3">
                                {{-- Province --}}
                                <div class="col-md-6">
                                    <label for="province_id" class="form-label">
                                        <i class="fa-solid fa-map-pin me-1 text-muted"></i> هەڵبژاردنی پارێزگا <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select id="province_id" name="province_id" class="form-select" required>
                                        <option value="" disabled {{ old('province_id') ? '' : 'selected' }}>
                                            هەڵبژێرە...</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}" @selected(old('province_id') == $province->id)>
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
                                    <input id="name" name="name" type="text" class="form-control" required
                                        minlength="2" placeholder="ناوی زانکۆ..." value="{{ old('name') }}">
                                    <div class="invalid-feedback">تکایە ناوی دروست بنوسە (کەمتر نیە لە ٢ پیت).</div>
                                </div>

                                {{-- English Name --}}
                                <div class="col-12">
                                    <label for="name_en" class="form-label">ناوی زانکۆ (ئینگلیزی) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name_en" name="name_en" class="form-control" required
                                        placeholder="نموونە: University of Erbil" value="{{ old('name_en') }}">
                                    <div class="invalid-feedback">ناو پێویستە.</div>
                                </div>

                                {{-- Map Section --}}
                                <div class="col-12">
                                    <label class="form-label">شوێنەکە دیاری بکە</label>
                                    <div id="university-map-create" style="height: 400px; border-radius: 12px;"></div>
                                </div>

                                {{-- Coordinates --}}
                                <div class="col-md-6">
                                    <label for="lat" class="form-label">Latitude</label>
                                    <input id="lat" name="lat" type="number" step="any" class="form-control"
                                        value="{{ old('lat') }}" placeholder="36.1911">
                                </div>
                                <div class="col-md-6">
                                    <label for="lng" class="form-label">Longitude</label>
                                    <input id="lng" name="lng" type="number" step="any" class="form-control"
                                        value="{{ old('lng') }}" placeholder="44.0092">
                                </div>
                                <div class="col-12">
                                    <div class="form-text">لەسەر نەخشە کلیک بکە، lat/lng خۆکار پڕ دەبن.</div>
                                </div>

                                {{-- GeoJSON Text --}}
                                <div class="col-12">
                                    <label for="geojson_text" class="form-label">GeoJSON (Optional)</label>
                                    <textarea id="geojson_text" name="geojson_text" rows="5" class="form-control"
                                        placeholder='{"type":"FeatureCollection","features":[...]}'>{{ old('geojson_text') }}</textarea>
                                    <div class="form-text">دەتوانیت GeoJSON paste بکەیت.</div>
                                </div>

                                {{-- GeoJSON File --}}
                                <div class="col-md-6">
                                    <label for="geojson_file" class="form-label">Upload GeoJSON فایل (Optional)</label>
                                    <input type="file" id="geojson_file" name="geojson_file" class="form-control"
                                        accept=".geojson,.json">
                                </div>

                                {{-- Image --}}
                                <div class="col-md-6">
                                    <label for="image" class="form-label">وێنە</label>
                                    <input type="file" id="image" name="image" class="form-control"
                                        accept="image/*">
                                </div>

                                {{-- Status --}}
                                <div class="col-md-12">
                                    <label for="status" class="form-label">
                                        <i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select id="status" name="status" class="form-select" required>
                                        <option value="1" @selected(old('status') === '1')>چاڵاک</option>
                                        <option value="0" @selected(old('status') === '0')>ناچاڵاک</option>
                                    </select>
                                    <div class="invalid-feedback">تکایە دۆخ هەڵبژێرە.</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-outline">
                                    <i class="fa-solid fa-rotate-left me-1"></i> پاککردنەوە
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-plus me-1"></i> پاشەکەوتکردنی زانکۆ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-container {
            z-index: 1;
        }
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

            // Map initialization for create page
            const mapId = 'university-map-create';
            const mapElement = document.getElementById(mapId);

            if (mapElement) {
                const map = L.map(mapId).setView([36.1911, 44.0092], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                let marker = null;
                let geojsonLayer = null;

                // Click handler for map
                map.on('click', function(e) {
                    const {
                        lat,
                        lng
                    } = e.latlng;

                    document.getElementById('lat').value = lat.toFixed(6);
                    document.getElementById('lng').value = lng.toFixed(6);

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([lat, lng]).addTo(map)
                        .bindPopup(`شوێنی هەڵبژاردن<br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`)
                        .openPopup();
                });

                // Handle GeoJSON text input
                const geojsonTextarea = document.getElementById('geojson_text');
                geojsonTextarea.addEventListener('change', function() {
                    try {
                        const geojson = JSON.parse(this.value);
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
                            map.fitBounds(geojsonLayer.getBounds());
                        }
                    } catch (e) {
                        console.error('Invalid GeoJSON:', e);
                    }
                });

                // Handle GeoJSON file upload
                const geojsonFileInput = document.getElementById('geojson_file');
                geojsonFileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            geojsonTextarea.value = e.target.result;
                            geojsonTextarea.dispatchEvent(new Event('change'));
                        };
                        reader.readAsText(file);
                    }
                });
            }
        });
    </script>
@endpush
