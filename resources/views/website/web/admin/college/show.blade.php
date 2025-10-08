@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.colleges.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i>گەڕانەوە
        </a>

        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">زانیاری کۆلێژ</div>
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
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                        <td>{{ $college->id }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-building-columns me-1 text-muted"></i> {{ __('ناو') }}
                                        </th>
                                        <td class="fw-semibold">{{ $college->name }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-school me-1 text-muted"></i> {{ __('زانکۆ') }}</th>
                                        <td>{{ $college->university->name ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> {{ __('پارێزگا') }}</th>
                                        <td>{{ $college->university->province->name ?? '—' }}</td>
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
                                        <th><i class="fa-regular fa-calendar-plus me-1 text-muted"></i>
                                            {{ __('دروستکراوە لە') }}</th>
                                        <td>{{ $college->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-regular fa-clock me-1 text-muted"></i> {{ __('گۆڕدراوە لە') }}
                                        </th>
                                        <td>{{ $college->updated_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.colleges.edit', $college->id) }}" class="btn btn-primary">
                            <i class="fa-solid fa-pen-to-square me-1"></i> گۆڕین
                        </a>
                        <a href="{{ route('admin.colleges.index') }}" class="btn btn-outline">
                            <i class="fa-solid fa-list me-1"></i> لیستەکە
                        </a>
                    </div>
                </div>
            </div>

            <div class="card glass fade-in">
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-12 col-xl-7">
                            {{-- Map --}}
                            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
                            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                            <div id="map-college" style="height: 460px; border-radius: 14px;"></div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const map = L.map('map-college').setView([36.2, 44.0], 7);
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

        @if ($college->geojson)
            try {
                const gj = @json($college->geojson);
                layer.addData(gj);
                const b = layer.getBounds();
                if (b.isValid()) map.fitBounds(b, {
                    padding: [20, 20]
                });
            } catch (e) {
                console.error('GeoJSON error:', e);
            }
        @else
            // fallback zoom to region
            map.setView([36.2, 44.0], 7);
        @endif
    </script>
@endpush
