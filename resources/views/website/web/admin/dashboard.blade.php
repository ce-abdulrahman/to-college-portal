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

    <h1 style="text-align: center" class="m-3">
        سیستەمێکی گەورەی Geo-Education Dashboard
    </h1>

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

@section('scripts')
  {{-- Leaflet JS --}}
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  {{-- 🔽 یەکێک هەڵبژێرە: Vite یان public/js  --}}
  {{-- Option A: Vite --}}
  {{--  @vite('resources/js/dashboard-map.js')  --}}

  {{-- Option B: public/js  (ئەگەر Vite بەکارناهیەنیت، ئەم هەڵەی خوارەوە بکەرەوە و ژووری سەروو بکاڕەوە) --}}
  <script src="{{ asset('js/dashboard-map.js') }}"></script>

  {{-- URL ـی GeoJSON ـی پارێزگاکان لە Laravel --}}
  <script>
    window.PROVINCES_URL = "{{ route('provinces.geojson') }}";
  </script>
@endsection
