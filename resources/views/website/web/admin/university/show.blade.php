@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.universities.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">{{ __('زانیاری زانکۆ') }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.universities.edit', $university->id) }}" class="btn btn-outline-success btn-sm ">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form action="{{ route('admin.universities.destroy', $university->id) }}" method="POST"
                onsubmit="return confirm('دڵنیایت؟');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            {{-- University basic info --}}
            <div class="card glass fade-in mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-building-columns me-2"></i> زانیاری بنەڕەتی زانکۆ
                    </h4>

                    <div class="table-wrap">
                        <div class="table-responsive table-scroll-x">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                        <td>{{ $university->id }}</td>
                                    </tr>

                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-image me-1 text-muted"></i> وێنە</th>
                                        <td>
                                            <img src="{{ $university->image }}" alt="{{ $university->name }}"
                                                style="height:80px;max-width:100%;border-radius:6px;object-fit:cover">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> {{ __('پارێزگا') }}</th>
                                        <td>{{ $university->province->name ?? '—' }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-school me-1 text-muted"></i> {{ __('ناوی زانکۆ') }}</th>
                                        <td class="fw-semibold">{{ $university->name }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> تەوەرەیی X - Longitude</th>
                                        <td>
                                            {{ $university->lng ?? '—' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> تەوەرەیی Y - Latitude</th>
                                        <td>
                                            {{ $university->lat ?? '—' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> {{ __('دۆخ') }}</th>
                                        <td>
                                            @if ($university->status)
                                                <span class="badge bg-success">{{ __('چاڵاک') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('ناچاڵاک') }}</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-regular fa-calendar-plus me-1 text-muted"></i>
                                            {{ __('دروستکراوە لە') }}</th>
                                        <td>{{ $university->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-regular fa-clock me-1 text-muted"></i> {{ __('گۆڕدراوە لە') }}
                                        </th>
                                        <td>{{ $university->updated_at?->format('Y-m-d H:i') ?? '—' }}</td>
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
                            {{ count($colleges) }}</span>
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
                                    @forelse ($colleges as $index => $college)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td class="fw-semibold">
                                                <i class="fa-solid fa-building-columns me-1 text-muted"></i>
                                                {{ $college->name }}
                                            </td>
                                            <td>
                                                <i class="fa-solid fa-map-pin me-1 text-muted"></i>
                                                {{ $college->lng ?? '—' }}
                                            </td>
                                            <td>
                                                <i class="fa-solid fa-map-pin me-1 text-muted"></i>
                                                {{ $college->lat ?? '—' }}
                                            </td>
                                            <td>
                                                @if ($college->status)
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
        </div>

        <div class="card-body">
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <div id="map-university" style="height: 460px; border-radius: 14px;"></div>

        </div>


    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const ID = 'map-university';
            const el = document.getElementById(ID);
            if (!el) return;

            // ڕێگری لە دوبارە-دەستپێکردن
            if (el._leaflet_id) el._leaflet_id = null;

            const mapU = L.map(ID).setView([36.2, 44.0], 8);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(mapU);

            const area = L.geoJSON(null, {
                style: {
                    color: '#16a34a',
                    weight: 2,
                    fillColor: '#22c55e',
                    fillOpacity: 0.12
                }
            }).addTo(mapU);
            const markers = L.layerGroup().addTo(mapU);

            let any = false;

            // یارمەتیدەر: نۆرمەڵکردنی GeoJSON (string/array/Feature/FC/Geometry)
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

            // University geojson
            @php
                $uGeo = is_string($university->geojson ?? null) ? json_decode($university->geojson, true) : $university->geojson ?? null;
            @endphp
            @if (!empty($uGeo))
                try {
                    const gjRaw = @json($uGeo);
                    const gj = normalizeGeoJSON(gjRaw);
                    if (gj) {
                        area.addData(gj);
                        const b = area.getBounds();
                        if (b.isValid()) {
                            mapU.fitBounds(b, {
                                padding: [20, 20]
                            });
                            any = true;
                        }
                    } else {
                        console.warn('University GeoJSON invalid');
                    }
                } catch (e) {
                    console.error(e);
                }
            @endif

            // University marker
            @if ($university->lat && $university->lng)
                L.marker([{{ $university->lat }}, {{ $university->lng }}]).addTo(markers)
                    .bindPopup(`<strong>{{ e($university->name) }}</strong>`);
                any = true;
            @endif

            // Colleges markers + polygons
            @foreach ($colleges as $college)
                @php
                    $cGeo = is_string($college->geojson ?? null) ? json_decode($college->geojson, true) : $college->geojson ?? null;
                @endphp
                @if ($college->lat && $college->lng)
                    L.marker([{{ $college->lat }}, {{ $college->lng }}]).addTo(markers)
                        .bindPopup(`<strong>{{ e($college->name) }}</strong>`);
                    any = true;
                @endif
                @if (!empty($cGeo))
                    try {
                        const cgjRaw = @json($cGeo);
                        const cgj = normalizeGeoJSON(cgjRaw);
                        if (cgj) {
                            L.geoJSON(cgj, {
                                style: {
                                    color: '#2563eb',
                                    weight: 2,
                                    fillColor: '#3b82f6',
                                    fillOpacity: 0.15
                                }
                            }).addTo(mapU);
                            any = true;
                        } else {
                            console.warn('College GeoJSON invalid: {{ e($college->name) }}');
                        }
                    } catch (e) {
                        console.error(e);
                    }
                @endif
            @endforeach

            if (!any) mapU.setView([36.2, 44.0], 8);

            setTimeout(() => mapU.invalidateSize(), 300);
        })();
    </script>
@endpush
