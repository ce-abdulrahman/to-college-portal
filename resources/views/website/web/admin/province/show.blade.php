@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.provinces.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class=" d-lg-block text-center flex-grow-1">
                <div class="navbar-page-title" style="font-size: 32px">
                    <i class="fa-solid fa-map-pin me-1 text-muted"></i> زانیاری پارێزگا
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.provinces.edit', $province->id) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-pen-to-square me-1"></i>
                </a>
                <form action="{{ route('admin.provinces.destroy', $province->id) }}" method="POST"
                    onsubmit="return confirm('دڵنیایت؟');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fa-solid fa-trash-can me-1"></i>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-table-list me-2"></i> زانیاری تەواوی پارێزگا
                    </h4>
                    <div class="table-wrap">
                        <div class="table-responsive table-scroll-x">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                        <td>{{ $province->id }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-image me-1 text-muted"></i> وێنە</th>
                                        <td>
                                            <img src="{{ $province->image }}" alt="{{ $province->name }}"
                                                style="height:80px;max-width:100%;border-radius:6px;object-fit:cover">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> ناو</th>
                                        <td class="fw-semibold">{{ $province->name }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ</th>
                                        <td>
                                            @if ($province->status)
                                                <span class="badge bg-success">چاڵاک</span>
                                            @else
                                                <span class="badge bg-danger">ناچاڵاک</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-calendar-check me-1 text-muted"></i> دروستکراوە لە</th>
                                        <td>{{ $province->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-calendar-pen me-1 text-muted"></i> گۆڕدراوە لە</th>
                                        <td>{{ $province->updated_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>


                </div>
                {{-- دەتوانیت لێرەدا زانیاری پەیوەندیدار لە یونیڤەرسیتییەکانی ئەم پارێزگایەش پیشان بدەی --}}
            </div>

            @isset($universities)
                <div class="card glass fade-in mt-3">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h4 class="card-title mb-4">
                                <i class="fa-solid fa-building me-2"></i> زانکۆکانی ئەم پارێزگایە
                            </h4>
                            <span class="chip"><i class="fa-solid fa-database"></i> کۆی گشتی:
                                {{ count($universities) }}</span>
                        </div>

                        <div class="table-wrap">
                            <div class="table-responsive table-scroll-x">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width:60px">#</th>
                                            <th>ناو</th>
                                            <th style="width:120px">دۆخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($universities as $i => $u)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td class="fw-semibold"><i class="fa-solid fa-school me-1 text-muted"></i>
                                                    {{ $u->name }}</td>
                                                <td>
                                                    @if ($u->status)
                                                        <span class="badge bg-success">چاڵاک</span>
                                                    @else
                                                        <span class="badge bg-danger">ناچاڵاک</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">هیچ زانیارییەک نیە
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endisset

            <div class="card-body">
                {{-- Map --}}
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <div id="map-province" style="height: 460px; border-radius: 14px;"></div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            // 1) پێش هەموو شتێک، دڵنیابە container هەیە
            const ID = 'map-province';
            const el = document.getElementById(ID);
            if (!el) return; // پەڕەکە container نییە، هیچ مەکە

            // 2) ڕێگری لە دوبارە-دەستپێکردن
            if (el._leaflet_id) el._leaflet_id = null;

            // 3) دابینکردنی مەپ
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

            // 4) یارمەتیدەری پارس و نۆرمەڵکردنی GeoJSON
            function normalizeGeoJSON(input) {
                try {
                    // ئەگەر string ـە، parse بکە
                    if (typeof input === 'string') input = JSON.parse(input);
                } catch (e) {
                    return null; // ناتوێت بخوێنرێت
                }
                if (!input) return null;

                // ئەگەر لیستی Features ـە، بیکە بە FeatureCollection
                if (Array.isArray(input)) {
                    return {
                        type: 'FeatureCollection',
                        features: input
                    };
                }

                // ئەگەر Geometry خاوەنی type/coordinates ـە، بیکە بە Feature
                if (input.type && input.coordinates) {
                    return {
                        type: 'Feature',
                        geometry: input,
                        properties: {}
                    };
                }

                // ئەگەر Feature/FeatureCollection هەیە، هەمانە بڕەوە
                if (input.type === 'Feature' || input.type === 'FeatureCollection') {
                    return input;
                }

                return null;
            }

            let any = false;

            // 5) Province GeoJSON
            @php
                // لە Blade: ئەگەر DB ـت TEXT هەیە، بۆ دڵنیابوون بۆ JS هەمیشە array بده‌ین
                $pGeo = is_string($province->geojson) ? json_decode($province->geojson, true) : $province->geojson;
            @endphp
            @if (!empty($pGeo))
                try {
                    const gjRaw = @json($pGeo); // هەرگیز string لەوێ نەهێنن
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
                        console.warn('Province GeoJSON invalid.');
                    }
                } catch (e) {
                    console.error(e);
                }
            @endif

            // 6) Province marker (lat/lng: تێبینی—GeoJSON لە ڕێکەوتی [lng,lat] ـە)
            @if ($province->lat && $province->lng)
                L.marker([{{ $province->lat }}, {{ $province->lng }}])
                    .addTo(markers)
                    .bindPopup(`<strong>{{ e($province->name) }}</strong>`);
                any = true;
            @endif

            // 7) Universities
            @isset($universities)
                @foreach ($universities as $university)
                    @php
                        $uGeo = is_string($university->geojson ?? null) ? json_decode($university->geojson, true) : $university->geojson ?? null;
                    @endphp
                    @if ($university->lat && $university->lng)
                        L.marker([{{ $university->lat }}, {{ $university->lng }}])
                            .addTo(markers)
                            .bindPopup(`<strong>{{ e($university->name) }}</strong>`);
                        any = true;
                    @endif
                    @if (!empty($uGeo))
                        try {
                            const ugjRaw = @json($uGeo);
                            const ugj = normalizeGeoJSON(ugjRaw);
                            if (ugj) {
                                L.geoJSON(ugj, {
                                    style: {
                                        color: '#2563eb',
                                        weight: 2,
                                        fillColor: '#3b82f6',
                                        fillOpacity: 0.15
                                    }
                                }).addTo(mapU);
                                any = true;
                            } else {
                                console.warn('University GeoJSON invalid for: {{ e($university->name) }}');
                            }
                        } catch (e) {
                            console.error(e);
                        }
                    @endif
                @endforeach
            @endisset

            if (!any) mapU.setView([36.2, 44.0], 8);

            setTimeout(() => mapU.invalidateSize(), 300);
        })();
    </script>
@endpush
