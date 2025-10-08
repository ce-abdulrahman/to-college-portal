@extends('website.web.admin.layouts.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #map {
            height: 540px;
            border-radius: 1rem;
        }

        .sidebar {
            max-height: 540px;
            overflow: auto;
        }
    </style>

    <div class="container-fluid mb-3">
        <div class="row g-3 g-md-4">

            {{-- Users --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fw-medium">کۆی قوتابیان</p>
                            <h4 class="mb-0 counter" data-target="36254">0</h4>
                        </div>
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Orders --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fw-medium">کۆی هەڵبژاردنی قوتابیان</p>
                            <h4 class="mb-0 counter" data-target="5543">0</h4>
                        </div>
                        <div class="stat-icon bg-info-subtle text-info">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Revenue --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fw-medium">بەم زووانە</p>
                            <h4 class="mb-0 counter" data-target="" data-prefix="$">0</h4>
                        </div>
                        <div class="stat-icon bg-success-subtle text-success">
                            <i class="fa-solid fa-route"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Likes --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fw-medium">بەم زووانە</p>
                            <h4 class="mb-0 counter" data-target="">0</h4>
                        </div>
                        <div class="stat-icon bg-danger-subtle text-danger">
                            <i class="fa-solid fa-recycle"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2">
                <div id="map"></div>
            </div>

            <div class="sidebar rounded-2xl border bg-white p-4 shadow-sm">
                <div class="text-sm text-gray-500">پارێزگا</div>
                <h3 id="province-title" class="mt-1 text-xl font-semibold">—</h3>

                <div class="mt-4">
                    <div class="text-sm text-gray-500 mb-2">زانکۆ/کۆلێژ/پەیمانگا</div>
                    <ul id="inst-list" class="space-y-2 text-sm">
                        <li class="text-gray-400">پارێزگایەک هەڵبژێرە لە نەخشە...</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // دیتای GeoJSON لە سرڤەر
        const PROVINCES = @json($provinceGeoJSON);
        const API_UNI = "{{ url('/dashboard/provinces') }}/"; // + {id} + "/universities"

        // map init — ناوچەی کوردستان
        const map = L.map('map', {
                zoomControl: true,
                preferCanvas: true
            })
            .setView([36.2, 44.0], 7);

        // base tiles (free)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // style بۆ پارێزگا
        function provinceStyle() {
            return {
                color: '#2563eb',
                weight: 2,
                fillColor: '#3b82f6',
                fillOpacity: 0.15
            };
        }

        const markersLayer = L.layerGroup().addTo(map);

        function renderInstitutions(list, provinceName) {
            markersLayer.clearLayers();
            const ul = document.getElementById('inst-list');
            ul.innerHTML = '';

            if (!list.length) {
                ul.innerHTML = '<li class="text-gray-400">هیچ پۆینتەکی تۆمارنەکراو بۆ ئەم پارێزگایە (lat/lng) 🔎</li>';
                return;
            }

            const bounds = [];
            list.forEach(item => {
                const m = L.marker([item.lat, item.lng]).addTo(markersLayer)
                    .bindPopup(`<strong>${item.name}</strong><br><small>${item.type}</small>`);
                bounds.push([item.lat, item.lng]);

                const li = document.createElement('li');
                li.innerHTML = `<div class="rounded-lg border p-2">
          <div class="font-medium">${item.name}</div>
          <div class="text-gray-500 text-xs">${item.type} • ${provinceName}</div>
        </div>`;
                ul.appendChild(li);
            });

            if (bounds.length) {
                map.fitBounds(bounds, {
                    padding: [20, 20]
                });
            }
        }

        function onProvinceClick(e) {
            const props = e.target.feature.properties;
            document.getElementById('province-title').textContent = props.name;

            fetch(API_UNI + props.id + '/universities')
                .then(r => r.json())
                .then(json => {
                    renderInstitutions(json.institutions || [], props.name);
                })
                .catch(() => {
                    renderInstitutions([], props.name);
                });
        }

        function onProvinceHover(e) {
            const layer = e.target;
            layer.setStyle({
                weight: 3,
                fillOpacity: 0.25
            });
            layer.bringToFront();
        }

        function onProvinceOut(e) {
            provincesLayer.resetStyle(e.target);
        }

        function eachProvince(feature, layer) {
            layer.on({
                click: onProvinceClick,
                mouseover: onProvinceHover,
                mouseout: onProvinceOut,
            });
            const n = feature.properties?.name ?? '';
            layer.bindTooltip(n, {
                sticky: true,
                direction: 'top'
            });
        }

        const provincesLayer = L.geoJSON(PROVINCES, {
            style: provinceStyle,
            onEachFeature: eachProvince
        }).addTo(map);

        // ڕەحەمی سەرەتایی: باندی هەموو پارێزگاكان
        try {
            map.fitBounds(provincesLayer.getBounds(), {
                padding: [20, 20]
            });
        } catch {}
    </script>
@endpush
