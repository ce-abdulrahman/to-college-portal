@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.universities.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class="d-none d-lg-block text-center flex-grow-1">
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
                        <div class="table-responsive">
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
                        <div class="table-responsive">
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
        const mapU = L.map('map-university').setView([36.2, 44.0], 8);
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

        // University geojson
        @if ($university->geojson)
            try {
                const gj = @json($university->geojson);
                area.addData(gj);
                const b = area.getBounds();
                if (b.isValid()) {
                    mapU.fitBounds(b, {
                        padding: [20, 20]
                    });
                    any = true;
                }
            } catch (e) {
                console.error(e);
            }
        @endif

        // University marker
        @if ($university->lat && $university->lng)
            L.marker([{{ $university->lat }}, {{ $university->lng }}]).addTo(markers)
                .bindPopup(`<strong>{{ addslashes($university->name) }}</strong>`);
            any = true;
        @endif

        // Colleges markers
        @foreach ($colleges as $college)
            @if ($college->lat && $college->lng)
                L.marker([{{ $college->lat }}, {{ $college->lng }}]).addTo(markers)
                    .bindPopup(`<strong>{{ addslashes($college->name) }}</strong>`);
                any = true;
            @endif
            @if ($college->geojson)
                try {
                    const gj = @json($college->geojson);
                    L.geoJSON(gj, {
                        style: {
                            color: '#2563eb',
                            weight: 2,
                            fillColor: '#3b82f6',
                            fillOpacity: 0.15
                        }
                    }).addTo(mapU);
                    any = true;
                } catch (e) {
                    console.error(e);
                }
            @endif
        @endforeach

        if (!any) {
            mapU.setView([36.2, 44.0], 8);
        }
    </script>
@endpush
