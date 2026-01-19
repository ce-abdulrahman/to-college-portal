// public/assets/admin/js/pages/universities/show.js
(function() {
    const ID = 'map-university';
    const el = document.getElementById(ID);
    if (!el) return;

    // Prevent re-initialization
    if (el._leaflet_id) el._leaflet_id = null;

    const mapU = L.map(ID).setView([36.2, 44.0], 8);
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

    // Helper: normalize GeoJSON
    function normalizeGeoJSON(input) {
        try {
            if (typeof input === 'string') input = JSON.parse(input);
        } catch (_) {
            return null;
        }
        if (!input) return null;
        if (Array.isArray(input)) return {
            type: 'FeatureCollection',
            features: input
        };
        if (input.type === 'Feature' || input.type === 'FeatureCollection') return input;
        if (input.type && input.coordinates) return {
            type: 'Feature',
            geometry: input,
            properties: {}
        };
        return null;
    }

    // University geojson
    if (!empty($uGeo))
        try {
            const gjRaw = json($uGeo);
            const gj = normalizeGeoJSON(gjRaw);
            if (gj) {
                area.addData(gj);
                const b = area.getBounds();
                if (b.isValid()) {
                    mapU.fitBounds(b, {
                        padding: [20, 20]
                    });
                    any = true;
                }
            } else {
                console.warn('University GeoJSON invalid');
            }
        } catch (e) {
            console.error(e);
        }
    endif

    // University marker
    if ($university->lat && $university->lng)
        L.marker([{{ $university->lat }}, {{ $university->lng }}]).addTo(markers)
            .bindPopup(`<strong>{{ e($university->name) }}</strong>`);
        any = true;
    endif

    // Colleges markers + polygons
    foreach ($colleges as $college)
        if ($college->lat && $college->lng)
            L.marker([{{ $college->lat }}, {{ $college->lng }}]).addTo(markers)
                .bindPopup(`<strong>{{ e($college->name) }}</strong>`);
            any = true;
        @endif
        @if (!empty($cGeo))
            try {
                const cgjRaw = @json($cGeo);
                const cgj = normalizeGeoJSON(cgjRaw);
                if (cgj) {
                    L.geoJSON(cgj, {
                        style: {
                            color: '#2563eb',
                            weight: 2,
                            fillColor: '#3b82f6',
                            fillOpacity: 0.15
                        }
                    }).addTo(mapU);
                    any = true;
                } else {
                    console.warn('College GeoJSON invalid: {{ e($college->name) }}');
                }
            } catch (e) {
                console.error(e);
            }
        @endif
    @endforeach

    if (!any) mapU.setView([36.2, 44.0], 8);

    setTimeout(() => mapU.invalidateSize(), 300);
})();