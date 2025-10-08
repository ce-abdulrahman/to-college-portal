@extends('website.web.admin.layouts.app')

@section('content')


    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.colleges.index') }}" class="btn btn-outline mb-4">
            <i class="fa-solid fa-arrow-right-long me-1"></i> {{ __('گەڕانەوە') }}
        </a>
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">نوێ کردنەوە کۆلێژ یان پەیمانگا </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-pen-to-square me-2"></i> دەستکاری کۆلێژ
                    </h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> هەڵە هەیە لە داهێنان:
                            <ul class="mb-0 mt-2 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.colleges.update', $college->id) }}" method="POST" enctype="multipart/form-data"
                        class="needs-validation" novalidate>
                        @csrf @method('PUT')

                        <div class="mb-3">
                            <label for="university_id" class="form-label">هەڵبژاردنی زانکۆ</label>
                            <select id="university_id" name="university_id"
                                class="form-select @error('university_id') is-invalid @enderror" required>
                                <option value="" disabled>هەڵبژاردنی زانکۆ</option>
                                @foreach ($universities as $uni)
                                    <option value="{{ $uni->id }}" @selected(old('university_id', $college->university_id) == $uni->id)>{{ $uni->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('university_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">ناوی کۆلێژ</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $college->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Area (optional) --}}
                        <div class="mb-3">
                            <label class="form-label">GeoJSON (Optional)</label>
                            <textarea name="geojson_text" rows="6" class="form-control">{{ old('geojson_text') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload GeoJSON (Optional)</label>
                            <input type="file" name="geojson_file" class="form-control" accept=".geojson,.json,.txt">
                        </div>

                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        <div id="map" style="height:420px;border-radius:12px" class="m-3"></div>

                        {{-- Point (optional) --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Latitude</label>
                                <input id="lat" name="lat" value="{{ old('lat', $university->lat ?? null) }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Longitude</label>
                                <input id="lng" name="lng" value="{{ old('lng', $university->lng ?? null) }}"
                                    class="form-control">
                            </div>
                            <div class="form-text">لەسەر نەخشە کلیک بکە، lat/lng خۆکار پڕ دەبن.</div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('دۆخ') }}</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror"
                                required>
                                <option value="1" @selected(old('status', $college->status) == 1)>{{ __('چاڵاک') }}</option>
                                <option value="0" @selected(old('status', $college->status) == 0)>{{ __('ناچاڵاک') }}</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('پاشەکەوتکردن') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/pages/colleges/form.js') }}" defer></script>

    <script>
        const map = L.map('map').setView([36.2, 44.0], 8);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18
        }).addTo(map);
        const area = L.geoJSON(null, {
            style: {
                color: '#16a34a',
                weight: 2,
                fillColor: '#22c55e',
                fillOpacity: 0.12
            }
        }).addTo(map);
        const markers = L.layerGroup().addTo(map);
        let marker = null;

        {{-- textarea/file + lat/lng inputs (وەکو جیاکردنی لەسەرەوە) --}}
        {{-- preload section: --}}
        @isset($college)
            @if (!empty($college->geojson))
                try {
                    const gj = @json($college->geojson);
                    area.addData(gj);
                    const b = area.getBounds();
                    if (b.isValid()) map.fitBounds(b, {
                        padding: [20, 20]
                    });
                } catch (e) {}
            @endif
            @if (!empty($college->lat) && !empty($college->lng))
                marker = L.marker([{{ $college->lat }}, {{ $college->lng }}]).addTo(markers);
                map.setView([{{ $college->lat }}, {{ $college->lng }}], 15);
            @endif
        @endisset


        // paste-preview
        const ta = document.querySelector('textarea[name="geojson_text"]');
        if (ta) {
            let t;
            ta.addEventListener('input', () => {
                clearTimeout(t);
                t = setTimeout(() => {
                    try {
                        const gj = JSON.parse(ta.value);
                        area.clearLayers().addData(gj);
                        const b = area.getBounds();
                        if (b.isValid()) map.fitBounds(b, {
                            padding: [20, 20]
                        });
                    } catch (e) {}
                }, 350);
            });
        }

        map.on('click', (e) => {
            if (marker) markers.clearLayers();
            marker = L.marker(e.latlng).addTo(markers);
            document.getElementById('lat').value = e.latlng.lat.toFixed(6);
            document.getElementById('lng').value = e.latlng.lng.toFixed(6);
        });
    </script>
@endpush
