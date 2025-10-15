@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">زانیاری بەش</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-pen-to-square me-1"></i>
            </a>
            <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST"
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
                        <i class="fa-solid fa-table-list me-2"></i> زانیاری تەواوی بەش
                    </h4>

                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                        <td>{{ $department->id }}</td>
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
                                        <th><i class="fa-regular fa-calendar-plus me-1 text-muted"></i> دروستکراوە لە</th>
                                        <td>{{ $department->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-regular fa-clock me-1 text-muted"></i> گۆڕدراوە لە</th>
                                        <td>{{ $department->updated_at?->format('Y-m-d H:i') ?? '—' }}</td>
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

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-primary">
                            <i class="fa-solid fa-pen-to-square me-1"></i> گۆڕین
                        </a>
                        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline">
                            <i class="fa-solid fa-list me-1"></i> لیستەکە
                        </a>
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
                .bindPopup(`<strong>{{ addslashes($department->name) }}</strong>`);
            map.setView([{{ $department->lat }}, {{ $department->lng }}], 15);
            any = true;
        @endif

        if (!any) {
            map.setView([36.2, 44.0], 8);
        }
    </script>
@endpush
