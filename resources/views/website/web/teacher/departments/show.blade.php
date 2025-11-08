@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">زانیاری بەش</div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-table-list me-2"></i> زانیاری تەواوی بەش
                    </h4>

                    <div class="table-wrap">
                        <div class="table-responsive table-scroll-x">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                        <td>1</td>
                                    </tr>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-image me-1 text-muted"></i> وێنە</th>
                                        <td>
                                            <img src="{{ $department->image }}" alt="{{ $department->name }}"
                                                style="height:80px;max-width:100%;border-radius:6px;object-fit:cover">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-cube me-1 text-muted"></i> سیستەم</th>
                                        <td>{{ $department->system->name }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> پارێزگا</th>
                                        <td>{{ $department->province->name }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-school me-1 text-muted"></i> زانکۆ</th>
                                        <td>{{ $department->university->name }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-building-columns me-1 text-muted"></i> کۆلێژ/پەیمانگا</th>
                                        <td>{{ $department->college->name }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-tag me-1 text-muted"></i> ناو</th>
                                        <td class="fw-semibold">{{ $department->name }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-percent me-1 text-muted"></i> ن. ناوەندی</th>
                                        <td>{{ $department->local_score ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-percent me-1 text-muted"></i> ن. ناوخۆی</th>
                                        <td>{{ $department->external_score ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-layer-group me-1 text-muted"></i> جۆر</th>
                                        <td>{{ $department->type }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-venus-mars me-1 text-muted"></i> ڕەگەز</th>
                                        <td>{{ $department->sex ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ</th>
                                        <td>
                                            @if ($department->status)
                                                <span class="badge bg-success">چاڵاک</span>
                                            @else
                                                <span class="badge bg-danger">ناچاڵاک</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-align-left me-1 text-muted"></i> وەسف</th>
                                        <td>{!! nl2br(e($department->description)) !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
            <div class="table-wrap">

                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <div id="map-department" style="height: 440px; border-radius: 14px;" class="m-3"></div>
            </div>


        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const map = L.map('map-department').setView([36.2, 44.0], 7);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        const layer = L.geoJSON(null, {
            style: {
                color: '#2563eb',
                weight: 2,
                fillColor: '#3b82f6',
                fillOpacity: 0.15
            }
        }).addTo(map);

        let any = false;

        @if ($department->lat && $department->lng)
            const m = L.marker([{{ $department->lat }}, {{ $department->lng }}]).addTo(map)
                .bindPopup(`<strong>{{ addslashes($department->image) }}<br />{{ addslashes($department->name) }}</strong>`);
            map.setView([{{ $department->lat }}, {{ $department->lng }}], 15);
            any = true;
        @endif

        if (!any) {
            map.setView([36.2, 44.0], 8);
        }
    </script>
@endpush
