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
                            <li class="breadcrumb-item"><a href="{{ route('admin.colleges.index') }}">کۆلێژکان</a></li>
                            <li class="breadcrumb-item active">زانیاری کۆلێژ</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-building-columns me-1"></i>
                        زانیاری کۆلێژ
                    </h4>
                </div>
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
                                            <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #
                                            </th>
                                            <td>{{ $college->id }}</td>
                                        </tr>
                                        <tr>
                                            <th style="width:260px"><i class="fa-solid fa-image me-1 text-muted"></i> وێنە
                                            </th>
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
                                            <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> تەوەرەیی X - Longitude
                                            </th>
                                            <td>
                                                {{ $college->lng ?? '—' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> تەوەرەیی Y - Latitude
                                            </th>
                                            <td>
                                                {{ $college->lat ?? '—' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> {{ __('دۆخ') }}
                                            </th>
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
                                                <td colspan="5" class="text-center text-muted">
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
                    <div id="map" style="height: 460px; border-radius: 14px;"
                        data-geojson="{{ json_encode($college->geojson) }}" data-lat="{{ $college->lat }}"
                        data-lng="{{ $college->lng }}" data-name="{{ $college->name }}"
                        data-departments="{{ json_encode($departments) }}"></div>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            'use strict';

            // Only run on college show page
            if (!document.getElementById('map')) return;

            // Initialize map for show page
            const initCollegeShowMap = () => {
                const mapEl = document.getElementById('map');
                if (!mapEl) return;

                const lat = parseFloat(mapEl.dataset.lat) || 33.2232;
                const lng = parseFloat(mapEl.dataset.lng) || 43.6793;
                const name = mapEl.dataset.name || 'کۆلێژ';

                // Initialize map
                const map = L.map('map').setView([lat, lng], 15);

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Add main college marker
                const mainMarker = L.marker([lat, lng]).addTo(map)
                    .bindPopup(`<b>${name}</b><br>کۆلێژی سەرەکی`);

                // Add departments if available
                try {
                    const departments = JSON.parse(mapEl.dataset.departments);
                    if (departments && departments.length > 0) {
                        departments.forEach(dept => {
                            if (dept.lat && dept.lng) {
                                L.marker([dept.lat, dept.lng])
                                    .addTo(map)
                                    .bindPopup(`<b>${dept.name}</b><br>کۆلێژ/پەیمانگا`);
                            }
                        });
                    }
                } catch (e) {
                    console.error('Error parsing departments data:', e);
                }

                // Add GeoJSON if available
                try {
                    const geojsonData = JSON.parse(mapEl.dataset.geojson);
                    if (geojsonData) {
                        L.geoJSON(geojsonData).addTo(map);
                    }
                } catch (e) {
                    console.error('Error parsing GeoJSON data:', e);
                }
            };

            // Initialize when DOM is loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCollegeShowMap);
            } else {
                initCollegeShowMap();
            }
        })();
    </script>
@endpush
