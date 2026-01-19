// form.js
$(function () {
    const $map = $('#map');
    if ($map.length) {
        if ($map[0]._leaflet_id) {
            $map[0]._leaflet_id = null;
            $map.find('.leaflet-layer, .leaflet-control').remove();
            $map.empty();
        }

        const $newMap = $('<div>', {
            id: 'map',
            css: { height: '420px', borderRadius: '12px', width: '100%' }
        });

        $map.replaceWith($newMap);
        initMap();
    }

    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', e => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    $('input[name="image"]').on('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            showToast('هەڵە', 'تەنها وێنە (JPEG, PNG, GIF, WebP) قبوڵە', 'error');
            $(this).val('');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            showToast('هەڵە', 'قەبارەی وێنە نابێت لە 5MB زیاتر بێت', 'error');
            $(this).val('');
            return;
        }
    });

    function initMap() {
        const map = L.map('map').setView([36.2, 44.0], 8);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = null;

        const geoJsonLayer = L.geoJSON(null, {
            style: {
                color: '#8b5cf6',
                weight: 2,
                fillColor: '#a78bfa',
                fillOpacity: 0.15
            },
            onEachFeature: (feature, layer) => {
                if (feature.properties && feature.properties.name) {
                    layer.bindPopup(String(feature.properties.name));
                }
            }
        }).addTo(map);

        function updateMapWithGeoJSON(geojsonData) {
            geoJsonLayer.clearLayers();
            if (!geojsonData || !geojsonData.trim()) return;

            try {
                const parsed = JSON.parse(geojsonData);
                geoJsonLayer.addData(parsed);
                const bounds = geoJsonLayer.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            } catch (e) {
                console.error('Invalid GeoJSON:', e);
                showToast('هەڵە', 'فۆرماتی GeoJSON نادروستە', 'error');
            }
        }

        map.on('click', e => {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            $('#lat').val(lat.toFixed(6));
            $('#lng').val(lng.toFixed(6));

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup('کۆلێژ: ' + lat.toFixed(6) + ', ' + lng.toFixed(6))
                    .openPopup();
            }
        });

        const $geoTextareas = $('textarea[name="geojson"], textarea[name="geojson_text"]');
        const initialGeoJSON = $geoTextareas.val();
        if (initialGeoJSON) {
            updateMapWithGeoJSON(initialGeoJSON);
        }

        const initialLat = $('#lat').val();
        const initialLng = $('#lng').val();
        if (initialLat && initialLng) {
            marker = L.marker([parseFloat(initialLat), parseFloat(initialLng)])
                .addTo(map)
                .bindPopup('کۆلێژ: ' + initialLat + ', ' + initialLng);
        }

        $geoTextareas.on('input', function () {
            updateMapWithGeoJSON($(this).val());
        });

        $('input[name="geojson_file"]').on('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = ev => {
                const content = ev.target.result;
                $('textarea[name="geojson_text"]').val(content);
                updateMapWithGeoJSON(content);
                showToast('سەرکەوتوو', 'فایلی GeoJSON بارکرا', 'success');
            };
            reader.readAsText(file);
        });

        setTimeout(() => map.invalidateSize(), 100);
    }

    function showToast(title, message, type) {
        if (typeof Swal === 'undefined') return;

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: toast => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: type,
            title,
            text: message
        });
    }
});
