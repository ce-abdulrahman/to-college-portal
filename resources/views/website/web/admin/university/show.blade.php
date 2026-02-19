@extends('website.web.admin.layouts.app')

@section('page_name', 'universities')
@section('view_name', 'show')

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
                            <li class="breadcrumb-item active">زانکۆی {{ $university->name }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-building-columns me-1"></i>
                        زانکۆی {{ $university->name }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                {{-- University Information --}}
                <div class="card glass fade-in mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-building-columns me-2"></i> زانیاری زانکۆ
                        </h4>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fa-solid fa-hashtag fa-2x text-muted mb-2"></i>
                                        <h5 class="mb-1">#</h5>
                                        <p class="fs-4 mb-0">{{ $university->id }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            @if ($university->image)
                                                <img src="{{ $university->image }}" alt="{{ $university->name }}"
                                                    class="rounded me-3"
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 80px; height: 80px;">
                                                    <i class="fa-solid fa-building-columns fa-2x text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="mb-1">{{ $university->name }}</h4>
                                                <p class="text-muted mb-0">{{ $university->name_en }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fa-solid fa-map-pin fa-2x text-muted mb-2"></i>
                                        <h5 class="mb-1">پارێزگا</h5>
                                        <p class="fs-4 mb-0">{{ $university->province->name ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-toggle-on me-2"></i> دۆخ
                                        </h6>
                                        <p class="card-text">
                                            @if ($university->status)
                                                <span class="badge bg-success">چاڵاک</span>
                                            @else
                                                <span class="badge bg-danger">ناچاڵاک</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-calendar-check me-2"></i>
                                            دروستکراوە</h6>
                                        <p class="card-text">{{ $university->created_at?->format('Y-m-d H:i') ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-calendar-pen me-2"></i>
                                            گۆڕدراوە</h6>
                                        <p class="card-text">{{ $university->updated_at?->format('Y-m-d H:i') ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            @if ($university->lat && $university->lng)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title text-muted"><i class="fa-solid fa-map-pin me-2"></i> شوێن
                                            </h6>
                                            <p class="card-text">
                                                Latitude: {{ $university->lat }}<br>
                                                Longitude: {{ $university->lng }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.universities.edit', $university->id) }}" class="btn btn-primary">
                                <i class="fa-solid fa-pen-to-square me-1"></i> دەستکاری
                            </a>
                            <a href="{{ route('admin.universities.index') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-list me-1"></i> لیستەکە
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Map Section --}}
                @if ($university->geojson || ($university->lat && $university->lng))
                    <div class="card glass fade-in mb-4">
                        <div class="card-body">
                            <h4 class="card-title mb-4">
                                <i class="fa-solid fa-map me-2"></i> نەخشەی زانکۆ
                            </h4>
                            <div id="university-map-show" style="height: 500px; border-radius: 10px;"
                                data-geojson='{{ $university->geojson ? json_encode($university->geojson) : 'null' }}'
                                data-lat="{{ $university->lat ?? '' }}" data-lng="{{ $university->lng ?? '' }}"
                                data-name="{{ $university->name }}">
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Colleges Section --}}
                <div class="card glass fade-in">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title mb-0">
                                <i class="fa-solid fa-building me-2"></i> کۆلێژ/پەیمانگاکانی ئەم زانکۆیە
                            </h4>
                            <div class="d-flex gap-2">
                                <span class="badge bg-info">
                                    <i class="fa-solid fa-database me-1"></i> کۆی گشتی: {{ count($colleges) }}
                                </span>
                                <a href="{{ route('admin.colleges.create') }}?university_id={{ $university->id }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-plus me-1"></i> زیادکردنی کۆلێژ
                                </a>
                            </div>
                        </div>

                        @if ($colleges->count() > 0)
                            <div class="table-responsive">
                                <table id="colleges-table" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ناوی کۆلێژ/پەیمانگا</th>
                                            <th>زانکۆ</th>
                                            <th>دۆخ</th>
                                            <th>کردار</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($colleges as $index => $college)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $college->name }}</td>
                                                <td>{{ $college->university->name ?? '—' }}</td>
                                                <td>
                                                    @if ($college->status)
                                                        <span class="badge bg-success">چاڵاک</span>
                                                    @else
                                                        <span class="badge bg-danger">ناچاڵاک</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.colleges.show', $college->id) }}"
                                                        class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.colleges.edit', $college->id) }}"
                                                        class="btn btn-sm btn-outline-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.colleges.destroy', $college->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('دڵنیایت دەتەوێت ئەم کۆلێژە بسڕیتەوە؟');">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                هیچ کۆلێژ/پەیمانگایەک لەم زانکۆیەدا نیە.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        .leaflet-container {
            z-index: 1;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable for colleges
            const kurdishLanguage = {
                "sEmptyTable": "هیچ تۆمارێک نیە",
                "sInfo": "نیشاندانی _START_ بۆ _END_ لە _TOTAL_ تۆمار",
                "sInfoEmpty": "نیشاندانی 0 بۆ 0 لە 0 تۆمار",
                "sInfoFiltered": "(پاڵێوراوە لە _MAX_ کۆی تۆمار)",
                "sInfoPostFix": "",
                "sInfoThousands": ",",
                "sLengthMenu": "نیشاندانی _MENU_ تۆمار",
                "sLoadingRecords": "بارکردن...",
                "sProcessing": "پڕۆسەکردن...",
                "sSearch": "گەڕان:",
                "sZeroRecords": "هیچ تۆمارێکی هاوشێوە نەدۆزرایەوە",
                "oPaginate": {
                    "sFirst": "یەکەم",
                    "sLast": "کۆتا",
                    "sNext": "داهاتوو",
                    "sPrevious": "پێشوو"
                },
                "oAria": {
                    "sSortAscending": ": چڕکردن بۆ ڕیزکردنی بەرزبوونەوە",
                    "sSortDescending": ": چڕکردن بۆ ڕیزکردنی نزموونەوە"
                }
            };

            // Initialize DataTable for colleges
            const collegesTable = $('#colleges-table');
            if (collegesTable.length) {
                collegesTable.DataTable({
                    language: kurdishLanguage,
                    order: [],
                    pageLength: 10,
                    responsive: true
                });
            }

            // Map initialization for show page with safe GeoJSON handling
            const mapEl = document.getElementById('university-map-show');
            if (!mapEl) return;

            const rawGeojson = mapEl.dataset.geojson;
            const lat = parseFloat(mapEl.dataset.lat) || null;
            const lng = parseFloat(mapEl.dataset.lng) || null;
            const name = mapEl.dataset.name || 'زانکۆ';

            const defaultLat = 36.1911;
            const defaultLng = 44.0092;
            const defaultZoom = 12;

            // Create map
            const map = L.map('university-map-show').setView(
                lat && lng ? [lat, lng] : [defaultLat, defaultLng],
                defaultZoom
            );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Add university icon
            const universityIcon = L.icon({
                iconUrl: '{{ asset('assets/admin/images/university-marker.png') }}' ||
                    'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40]
            });

            // Safely parse and add GeoJSON
            let geojsonData = null;
            if (rawGeojson && rawGeojson !== 'null' && rawGeojson !== 'undefined') {
                try {
                    geojsonData = JSON.parse(rawGeojson);

                    // Validate GeoJSON structure
                    if (geojsonData &&
                        (geojsonData.type === 'Feature' ||
                            geojsonData.type === 'FeatureCollection' ||
                            geojsonData.type === 'GeometryCollection' ||
                            geojsonData.type === 'Point' ||
                            geojsonData.type === 'LineString' ||
                            geojsonData.type === 'Polygon' ||
                            geojsonData.type === 'MultiPoint' ||
                            geojsonData.type === 'MultiLineString' ||
                            geojsonData.type === 'MultiPolygon')) {

                        L.geoJSON(geojsonData, {
                            style: {
                                color: '#2563eb',
                                weight: 2,
                                fillColor: '#3b82f6',
                                fillOpacity: 0.15
                            },
                            onEachFeature: function(feature, layer) {
                                if (feature.properties && feature.properties.name) {
                                    layer.bindPopup(`<strong>${feature.properties.name}</strong>`);
                                } else {
                                    layer.bindPopup(`<strong>${name}</strong>`);
                                }
                            }
                        }).addTo(map);
                    }
                } catch (e) {
                    console.warn('Invalid GeoJSON data:', e.message);
                    // Continue without GeoJSON if it's invalid
                }
            }

            // Add marker if coordinates are available
            if (lat && lng) {
                L.marker([lat, lng], {
                        icon: universityIcon
                    })
                    .addTo(map)
                    .bindPopup(`<strong>${name}</strong>`)
                    .openPopup();

                // Center map on marker
                map.setView([lat, lng], defaultZoom);
            }

            // Add colleges markers if available
            @isset($colleges)
                @foreach ($colleges as $college)
                    @if ($college->lat && $college->lng)
                        L.marker([{{ $college->lat }}, {{ $college->lng }}])
                            .addTo(map)
                            .bindPopup(`<strong>{{ addslashes($college->name) }}</strong>`);
                    @endif
                @endforeach
            @endisset

            // Fix map size after load
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        });
    </script>
@endpush
