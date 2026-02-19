@extends('website.web.admin.layouts.app')

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

        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                {{-- Province Information --}}
                <div class="card glass fade-in mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-table-list me-2"></i> زانیاری تەواوی پارێزگا
                        </h4>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fa-solid fa-hashtag fa-2x text-muted mb-2"></i>
                                        <h5 class="mb-1">#</h5>
                                        <p class="fs-4 mb-0">{{ $province->id }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-9 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $province->image }}" alt="{{ $province->name }}"
                                                class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                            <div>
                                                <h4 class="mb-1">{{ $province->name }}</h4>
                                                <p class="text-muted mb-0">{{ $province->name_en }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-toggle-on me-2"></i> دۆخ
                                        </h6>
                                        <p class="card-text">
                                            @if ($province->status)
                                                <span class="badge bg-success">چاڵاک</span>
                                            @else
                                                <span class="badge bg-danger">ناچاڵاک</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-calendar-check me-2"></i>
                                            دروستکراوە</h6>
                                        <p class="card-text">{{ $province->created_at?->format('Y-m-d H:i') ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-calendar-pen me-2"></i>
                                            گۆڕدراوە</h6>
                                        <p class="card-text">{{ $province->updated_at?->format('Y-m-d H:i') ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            @if ($province->lat && $province->lng)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title text-muted"><i class="fa-solid fa-map-pin me-2"></i> شوێن
                                            </h6>
                                            <p class="card-text">
                                                Latitude: {{ $province->lat }}<br>
                                                Longitude: {{ $province->lng }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.provinces.edit', $province->id) }}" class="btn btn-primary">
                                <i class="fa-solid fa-pen-to-square me-1"></i> دەستکاری
                            </a>
                            <a href="{{ route('admin.provinces.index') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-list me-1"></i> لیستەکە
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Universities in this province --}}
                @isset($universities)
                    <div class="card glass fade-in mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">
                                    <i class="fa-solid fa-building me-2"></i> زانکۆکانی ئەم پارێزگایە
                                </h4>
                                <span class="badge bg-info">
                                    <i class="fa-solid fa-database me-1"></i> کۆی گشتی: {{ count($universities) }}
                                </span>
                            </div>

                            @if ($universities->count() > 0)
                                <div class="row">
                                    @foreach ($universities as $university)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $university->name }}</h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span
                                                            class="badge {{ $university->status ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $university->status ? 'چاڵاک' : 'ناچاڵاک' }}
                                                        </span>
                                                        <a href="{{ route('admin.universities.show', $university->id) }}"
                                                            class="btn btn-sm btn-outline-info">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fa-solid fa-info-circle me-1"></i>
                                    هیچ زانکۆیەک لەم پارێزگایەدا نیە.
                                </div>
                            @endif
                        </div>
                    </div>
                @endisset

                {{-- Map Section --}}
                @if ($province->geojson || $province->lat)
                    <div class="card glass fade-in">
                        <div class="card-body">
                            <h4 class="card-title mb-4">
                                <i class="fa-solid fa-map me-2"></i> نەخشەی پارێزگا
                            </h4>
                            <div id="province-map" style="height: 500px; border-radius: 10px;"
                                data-geojson="{{ json_encode($province->geojson) }}" data-lat="{{ $province->lat }}"
                                data-lng="{{ $province->lng }}" data-name="{{ $province->name }}">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapEl = document.getElementById('province-map');
            if (!mapEl) return;

            const geojsonData = mapEl.dataset.geojson ? JSON.parse(mapEl.dataset.geojson) : null;
            const lat = parseFloat(mapEl.dataset.lat);
            const lng = parseFloat(mapEl.dataset.lng);
            const name = mapEl.dataset.name || 'پارێزگا';

            const defaultLat = 33.3128;
            const defaultLng = 44.3615;
            const defaultZoom = 8;

            const map = L.map('province-map').setView(
                lat && lng ? [lat, lng] : [defaultLat, defaultLng],
                defaultZoom
            );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            const provinceIcon = L.icon({
                iconUrl: '{{ asset('assets/admin/images/map-marker-province.png') }}',
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40]
            });

            // Add GeoJSON if available
            if (geojsonData) {
                L.geoJSON(geojsonData, {
                    style: {
                        color: '#16a34a',
                        weight: 2,
                        fillColor: '#22c55e',
                        fillOpacity: 0.15
                    }
                }).addTo(map).bindPopup(`<strong>${name}</strong>`);
            }

            // Add marker if coordinates are available
            if (lat && lng) {
                L.marker([lat, lng], {
                        icon: provinceIcon
                    })
                    .addTo(map)
                    .bindPopup(`<strong>${name}</strong>`);
            }

            // Add universities if available
            @isset($universities)
                @foreach ($universities as $university)
                    @if ($university->lat && $university->lng)
                        L.marker([{{ $university->lat }}, {{ $university->lng }}])
                            .addTo(map)
                            .bindPopup(`<strong>{{ $university->name }}</strong>`);
                    @endif
                @endforeach
            @endisset
        });
    </script>
@endpush
