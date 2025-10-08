@extends('website.web.admin.layouts.app')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-2xl border bg-white p-4">
            <h2 class="text-lg font-semibold">بەش: {{ $department->name }}</h2>

            @if ($errors->any())
                <div class="mt-3 rounded-lg border border-rose-300 bg-rose-50 p-3 text-rose-700 text-sm">
                    <ul class="list-disc ms-5">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="mt-4 space-y-4" method="POST" action="{{ route('admin.geo.department.update-point', $department) }}">
                @csrf @method('PUT')

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium">Latitude</label>
                        <input id="lat" name="lat" value="{{ old('lat', $department->lat) }}"
                            class="w-full rounded-xl border p-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Longitude</label>
                        <input id="lng" name="lng" value="{{ old('lng', $department->lng) }}"
                            class="w-full rounded-xl border p-2" required />
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
        const lat0 = {{ $department->lat ?? 36.2 }};
        const lng0 = {{ $department->lng ?? 44.0 }};
        const map = L.map('map').setView([lat0, lng0], {{ $department->lat && $department->lng ? 16 : 8 }});
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18
        }).addTo(map);

        const layer = L.layerGroup().addTo(map);
        let marker = null;

        @if ($department->lat && $department->lng)
            marker = L.marker([{{ $department->lat }}, {{ $department->lng }}]).addTo(layer);
        @endif

        map.on('click', (e) => {
            if (marker) layer.clearLayers();
            marker = L.marker(e.latlng).addTo(layer);
            document.getElementById('lat').value = e.latlng.lat.toFixed(6);
            document.getElementById('lng').value = e.latlng.lng.toFixed(6);
        });
    </script>
@endsection
