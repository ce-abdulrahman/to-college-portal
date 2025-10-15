@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.universities.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title" style="font-size: 32px">
                <i class="fa-solid fa-map-pin me-1 text-muted"></i> نوێ کردنەوەی زانکۆی
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-pen-to-square me-2"></i> نوێکردنەوەی زانکۆ
                    </h4>

                    <form action="{{ route('admin.universities.update', $university->id) }}" method="POST" enctype="multipart/form-data"
                        class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- Province --}}
                            <div class="col-md-6">
                                <label for="province_id" class="form-label">
                                    <i class="fa-solid fa-map-pin me-1 text-muted"></i> هەڵبژاردنی پارێزگا <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="province_id" name="province_id" required>
                                    <option value="" disabled>— هەڵبژێرە —</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}"
                                            {{ $university->province_id == $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">تکایە پارێزگا هەڵبژێرە.</div>
                            </div>

                            {{-- Name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fa-solid fa-tag me-1 text-muted"></i> ناوی زانکۆ <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $university->name }}" required minlength="2" placeholder="ناوی زانکۆ...">
                                <div class="invalid-feedback">تکایە ناوی دروست بنوسە (کەمتر نیە لە ٢ پیت).</div>
                            </div>

                            {{-- Area (optional) --}}
                            <div class="mb-3">
                                <label class="form-label">GeoJSON (Optional)</label>
                            <textarea name="geojson_text" rows="6" class="form-control">{{ is_array($university->geojson) ? json_encode($university->geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $university->geojson }}</textarea>

                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload GeoJSON (Optional)</label>
                                <input type="file" name="geojson_file" class="form-control" accept=".geojson,.json,.txt">
                            </div>

                            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
                            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                            <div id="map" style="height:420px;border-radius:12px" class="mt-3"></div>

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


                            {{-- Status --}}
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    <i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="status" name="status" required>
                                    <option @selected($university->status == 1) value="1">چاڵاک</option>
                                    <option @selected($university->status == 0) value="0">ناچاڵاک</option>
                                </select>
                                <div class="invalid-feedback">تکایە دۆخ هەڵبژێرە.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.universities.index') }}" class="btn btn-outline">
                                <i class="fa-solid fa-xmark me-1"></i> ڕەتکردنەوە
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوتکردنی گۆڕانکاری
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

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

        // preload (edit)
        @isset($university)
            @if (!empty($university->geojson))
                try {
                    const gj = @json($university->geojson);
                    area.addData(gj);
                    const b = area.getBounds();
                    if (b.isValid()) map.fitBounds(b, {
                        padding: [20, 20]
                    });
                } catch (e) {}
            @endif
            @if (!empty($university->lat) && !empty($university->lng))
                marker = L.marker([{{ $university->lat }}, {{ $university->lng }}]).addTo(markers);
                map.setView([{{ $university->lat }}, {{ $university->lng }}], 15);
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


        (() => {
            const forms = document.querySelectorAll('.needs-validation');
            forms.forEach(form => {
                form.addEventListener('submit', e => {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });
        })();
    </script>
@endpush
