@extends('website.web.admin.layouts.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-2xl border bg-white p-4">
            <h2 class="text-lg font-semibold">پارێزگا: {{ $province->name }}</h2>

            @if ($errors->any())
                <div class="mt-3 rounded-lg border border-rose-300 bg-rose-50 p-3 text-rose-700 text-sm">
                    <ul class="list-disc ms-5">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="mt-4 space-y-4" method="POST" action="{{ route('admin.geo.province.update-area', $province) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label class="block text-sm font-medium">GeoJSON (Paste)</label>
                <textarea name="geojson_text" rows="10" class="w-full rounded-xl border p-2"
                    placeholder='{"type":"Polygon","coordinates":[...]}'>{{ old('geojson_text') }}</textarea>

                <div class="text-center text-gray-500">یا</div>

                <label class="block text-sm font-medium">Upload GeoJSON file</label>
                <input type="file" name="geojson_file" accept=".geojson,.json,.txt"
                    class="w-full rounded-xl border p-2" />

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

        // اگر هەیە GeoJSON ـی هەنووکەی پارێزگا پیشانی بدە
        @if ($province->geojson)
            try {
                const gj = @json($province->geojson);
                layer.addData(gj);
                map.fitBounds(layer.getBounds(), {
                    padding: [20, 20]
                });
            } catch (e) {}
        @endif

        // هنگاوی preview: هەرکات paste بکەیت، خۆکارە لە map پیشان بدات
        const ta = document.querySelector('textarea[name="geojson_text"]');
        let t;
        ta.addEventListener('input', () => {
            clearTimeout(t);
            t = setTimeout(() => {
                try {
                    const gj = JSON.parse(ta.value);
                    layer.clearLayers().addData(gj);
                    map.fitBounds(layer.getBounds(), {
                        padding: [20, 20]
                    });
                } catch (e) {}
            }, 400);
        });
    </script>
@endsection
