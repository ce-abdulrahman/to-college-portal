@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.colleges.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i>گەڕانەوە
        </a>

        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title" style="font-size: 32px">
                <i class="fa-solid fa-building-columns me-1 text-muted"></i> زانیاری کۆلێژ
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.colleges.edit', $college->id) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-pen-to-square me-1"></i>
            </a>
            <form action="{{ route('admin.colleges.destroy', $college->id) }}" method="POST"
                onsubmit="return confirm('دڵنیایت؟');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fa-solid fa-trash-can me-1"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-table-list me-2"></i> {{ __('زانیاری تەواوی کۆلێژ') }}
                    </h4>

                    <div class="table-wrap">
                        <div class="table-responsive table-scroll-x">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                        <td>{{ $college->id }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-image me-1 text-muted"></i> وێنە</th>
                                        <td>
                                            <img src="{{ $college->image }}" alt="{{ $college->name }}"
                                                style="height:80px;max-width:100%;border-radius:6px;object-fit:cover">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> {{ __('پارێزگا') }}</th>
                                        <td>{{ $college->university->province->name ?? '—' }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-school me-1 text-muted"></i> {{ __('زانکۆ') }}</th>
                                        <td>{{ $college->university->name ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-building-columns me-1 text-muted"></i>
                                            {{ __('ناو') }}
                                        </th>
                                        <td class="fw-semibold">{{ $college->name }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> تەوەرەیی X - Longitude</th>
                                        <td>
                                            {{ $college->lng ?? '—' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> تەوەرەیی Y - Latitude</th>
                                        <td>
                                            {{ $college->lat ?? '—' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> {{ __('دۆخ') }}</th>
                                        <td>
                                            @if ($college->status)
                                                <span class="badge bg-success">{{ __('چاڵاک') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('ناچاڵاک') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <i class="fa-regular fa-calendar-plus me-1 text-muted"></i>
                                            دروستکراوە لە
                                        </th>
                                        <td>{{ $college->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-regular fa-clock me-1 text-muted"></i> گۆڕدراوە لە
                                        </th>
                                        <td>{{ $college->updated_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            {{-- Colleges / Institutes of this University --}}
            <div class="card glass fade-in">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-building me-2"></i> کۆلێژ/پەیمانگاکانی ئەم زانکۆیە
                        </h4>
                        <span class="chip"><i class="fa-solid fa-database"></i> کۆی گشتی:
                            {{ count($departments) }}</span>
                    </div>


                    <div class="table-wrap">
                        <div class="table-responsive table-scroll-x">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>ناو</th>
                                        <th>X</th>
                                        <th>Y</th>
                                        <th style="width:120px">دۆخ</th>
                                        {{-- هەلبژاردن: ئەگەر خانەی تر هەیە وەکو جۆر/ژمارەی بەشەکان، لێرە زیاد بکە --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($departments as $index => $department)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td class="fw-semibold">
                                                <i class="fa-solid fa-building-columns me-1 text-muted"></i>
                                                {{ $department->name }}
                                            </td>
                                            <td>
                                                <i class="fa-solid fa-map-pin me-1 text-muted"></i>
                                                {{ $department->lng ?? '—' }}
                                            </td>
                                            <td>
                                                <i class="fa-solid fa-map-pin me-1 text-muted"></i>
                                                {{ $department->lat ?? '—' }}
                                            </td>
                                            <td>
                                                @if ($department->status)
                                                    <span class="badge bg-success">چاڵاک</span>
                                                @else
                                                    <span class="badge bg-danger">ناچاڵاک</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                <i class="fa-solid fa-circle-info me-1"></i>
                                                هیچ کۆلێژ/پەیمانگایەک بۆ ئەم زانکۆیە نەدۆزرایەوە
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                {{-- Map --}}
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <div id="map-college" style="height: 460px; border-radius: 14px;"></div>

            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const MAP_ID = 'map-college';

            // اگر پەیجەکە دووبارە render بوو
            function resetLeafletContainer(id) {
                const n = L.DomUtil.get(id);
                if (n && n._leaflet_id) n._leaflet_id = null;
            }

            function normalizeGeoJSON(input) {
                try {
                    if (typeof input === 'string') input = JSON.parse(input);
                } catch (_) {
                    return null;
                }
                if (!input) return null;
                if (Array.isArray(input)) return {
                    type: 'FeatureCollection',
                    features: input
                };
                if (input.type === 'Feature' || input.type === 'FeatureCollection') return input;
                if (input.type && input.coordinates) return {
                    type: 'Feature',
                    geometry: input,
                    properties: {}
                };
                return null;
            }

            const el = document.getElementById(MAP_ID);
            if (!el) return;

            resetLeafletContainer(MAP_ID);

            const map = L.map(MAP_ID).setView([36.2, 44.0], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const area = L.geoJSON(null, {
                style: {
                    color: '#2563eb',
                    weight: 2,
                    fillColor: '#3b82f6',
                    fillOpacity: 0.15
                }
            }).addTo(map);
            const markers = L.layerGroup().addTo(map);

            // 1) لایەرەکان پڕ بکە
            let anything = false;

            // College GeoJSON
            @if ($college->geojson)
                try {
                    const gj = normalizeGeoJSON(@json($college->geojson));
                    if (gj) {
                        area.addData(gj);
                        anything = true;
                    }
                } catch (e) {
                    console.error(e);
                }
            @endif

            // College point
            @if ($college->lat && $college->lng)
                L.marker([{{ $college->lat }}, {{ $college->lng }}]).addTo(markers)
                    .bindPopup(`<strong>{{ addslashes($college->name) }}</strong>`);
                anything = true;
            @endif

            // Departments
            @isset($departments)
                @foreach ($departments as $department)
                    @if ($department->lat && $department->lng)
                        L.marker([{{ $department->lat }}, {{ $department->lng }}]).addTo(markers)
                            .bindPopup(`<strong>{{ addslashes($department->name) }}</strong>`);
                        anything = true;
                    @endif
                    @if ($department->geojson)
                        try {
                            const dgj = normalizeGeoJSON(@json($department->geojson));
                            if (dgj) {
                                L.geoJSON(dgj, {
                                    style: {
                                        color: '#16a34a',
                                        weight: 2,
                                        fillColor: '#22c55e',
                                        fillOpacity: 0.12
                                    }
                                }).addTo(map);
                                anything = true;
                            }
                        } catch (e) {
                            console.error(e);
                        }
                    @endif
                @endforeach
            @endisset

            // 2) Bounds: تەنها لەیره‌کان بخە ناو گرووپ، نەخێر LayerGroup ـەکە خۆی
            if (anything) {
                const layersForBounds = [];
                // هەموو لەیره‌کانی area
                area.eachLayer(l => layersForBounds.push(l));
                // هەموو markers ـەکانی tak
                markers.eachLayer(m => layersForBounds.push(m));
                const boundsGroup = L.featureGroup(layersForBounds);
                const b = boundsGroup.getBounds();
                if (b.isValid()) map.fitBounds(b, {
                    padding: [20, 20]
                });
            } else {
                map.setView([36.2, 44.0], 8);
            }

            setTimeout(() => map.invalidateSize(), 300);
        })();
    </script>
@endpush
