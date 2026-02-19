@extends('website.web.admin.layouts.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="container-fluid py-4">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl border bg-white p-4">
                <h2 class="text-lg font-semibold">کۆلێژ: {{ $college->name }}</h2>

                @if ($errors->any())
                    <div class="mt-3 rounded-lg border border-rose-300 bg-rose-50 p-3 text-rose-700 text-sm">
                        <ul class="list-disc ms-5">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="mt-4 space-y-4" method="POST" action="{{ route('admin.geo.college.update-geo', $college) }}"
                    enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <label class="block text-sm font-medium">GeoJSON (اختیاری)</label>
                    <textarea name="geojson_text" rows="8" class="w-full rounded-xl border p-2"></textarea>

                    <label class="block text-sm font-medium">Upload GeoJSON (اختیاری)</label>
                    <input type="file" name="geojson_file" accept=".geojson,.json,.txt"
                        class="w-full rounded-xl border p-2" />

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Latitude</label>
                            <input id="lat" name="lat" value="{{ old('lat', $college->lat) }}"
                                class="w-full rounded-xl border p-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Longitude</label>
                            <input id="lng" name="lng" value="{{ old('lng', $college->lng) }}"
                                class="w-full rounded-xl border p-2" />
                        </div>
                    </div>

                    <div class="text-xs text-gray-500">لەسەر نەخشە کلیک بکە بۆ دابنانی lat/lng</div>

                    <div class="flex gap-3">
                        <a href="{{ url()->previous() }}" class="rounded-xl border px-4 py-2">Back</a>
                        <button class="rounded-xl bg-blue-600 px-4 py-2 text-white">Save</button>
                    </div>
                </form>
            </div>

            <div class="rounded-2xl border bg-white p-2">
                <div id="map" style="height:560px;border-radius:1rem;"></div>
            </div>
        </div>

        <script>
            const map = L.map('map').setView([36.2, 44.0], 8);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18
            }).addTo(map);

            const area = L.geoJSON(null, {
                style: {
                    color: '#f59e0b',
                    weight: 2,
                    fillColor: '#fbbf24',
                    fillOpacity: 0.12
                }
            }).addTo(map);
            const markerLayer = L.layerGroup().addTo(map);
            let marker = null;

            @if ($college->geojson)
                try {
                    const gj = @json($college->geojson);
                    area.addData(gj);
                    map.fitBounds(area.getBounds(), {
                        padding: [20, 20]
                    });
                } catch (e) {}
            @endif
            @if ($college->lat && $college->lng)
                marker = L.marker([{{ $college->lat }}, {{ $college->lng }}]).addTo(markerLayer);
                map.setView([{{ $college->lat }}, {{ $college->lng }}], 15);
            @endif

            document.querySelector('textarea[name="geojson_text"]').addEventListener('input', (ev) => {
                try {
                    const gj = JSON.parse(ev.target.value);
                    area.clearLayers().addData(gj);
                    map.fitBounds(area.getBounds(), {
                        padding: [20, 20]
                    });
                } catch (e) {}
            });

            map.on('click', (e) => {
                if (marker) markerLayer.clearLayers();
                marker = L.marker(e.latlng).addTo(markerLayer);
                document.getElementById('lat').value = e.latlng.lat.toFixed(6);
                document.getElementById('lng').value = e.latlng.lng.toFixed(6);
            });
        </script>
    </div>
@endsection
