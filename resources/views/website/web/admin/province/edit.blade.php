@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.provinces.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
        </a>
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">دەستکاری پارێزگا</div>
        </div>
        <div></div>
    </div>

    @if (auth()->user()->role !== 'admin')
        <div class="alert alert-warning"><i class="fa-solid fa-lock me-1"></i> تەنها ئەدمین دەتوانێت دەستکاری بکات.</div>
    @else
        <div class="row">
            <div class="col-12 col-xl-8 mx-auto">
                <div class="card glass fade-in">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class="fa-solid fa-pen-to-square me-2"></i> دەستکاری:
                            {{ $province->name }}</h4>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> هەڵە هەیە:
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.provinces.update', $province->id) }}" method="POST"
                            class="needs-validation" novalidate enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">ناوی پارێزگا</label>
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ old('name', $province->name) }}">
                                    <div class="invalid-feedback">ناو پێویستە.</div>
                                </div>

                                <div class="mb-3">
                                    <textarea name="geojson_text" rows="8" class="form-control">
                                        {{ old('geojson_text', $province->geojson ? json_encode($province->geojson) : '') }}
                                    </textarea>
                                </div>

                                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                                <div id="map" style="height:420px;border-radius:12px" class="mt-3"></div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">دۆخ</label>
                                    <select class="form-select" name="status" required>
                                        <option value="1" @selected(old('status', (int) $province->status) === 1)>چاڵاک</option>
                                        <option value="0" @selected(old('status', (int) $province->status) === 0)>ناچاڵاک</option>
                                    </select>
                                    <div class="invalid-feedback">دۆخ دیاری بکە.</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوت
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        const map = L.map('map').setView([36.2, 44.0], 7);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18
        }).addTo(map);
        const layer = L.geoJSON(null, {
            style: {
                color: '#2563eb',
                weight: 2,
                fillColor: '#3b82f6',
                fillOpacity: 0.15
            }
        }).addTo(map);

        const ta = document.querySelector('textarea[name="geojson_text"]');
        if (ta) {
            let t;
            ta.addEventListener('input', () => {
                clearTimeout(t);
                t = setTimeout(() => {
                    try {
                        const gj = JSON.parse(ta.value);
                        layer.clearLayers().addData(gj);
                        const b = layer.getBounds();
                        if (b.isValid()) map.fitBounds(b, {
                            padding: [20, 20]
                        });
                    } catch (e) {}
                }, 350);
            });
        }
    </script>
@endpush
