// public/assets/admin/js/pages/universities/edit.js
// Edit University: Leaflet preview + parse/normalize GeoJSON (textarea/file) + click-to-fill lat/lng

(function ($) {
  /** Avoid "Map container is already initialized" */
  function resetLeafletContainer(id) {
    const node = L.DomUtil.get(id);
    if (node && node._leaflet_id) node._leaflet_id = null;
  }

  /** Accept string/array/Feature/FeatureCollection/Geometry -> return valid GeoJSON object or null */
  function normalizeGeoJSON(input) {
    try { if (typeof input === 'string') input = JSON.parse(input); } catch (_) { return null; }
    if (!input) return null;
    if (Array.isArray(input)) return { type: 'FeatureCollection', features: input };
    if (input.type === 'Feature' || input.type === 'FeatureCollection') return input;
    if (input.type && input.coordinates) return { type: 'Feature', geometry: input, properties: {} };
    return null;
  }

  $(function () {
    const MAP_ID = 'map';
    const $mapEl = $('#' + MAP_ID);
    if (!$mapEl.length) return;

    // 1) Init map
    resetLeafletContainer(MAP_ID);
    const map = L.map(MAP_ID).setView([36.2, 44.0], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // 2) Layers
    const area    = L.geoJSON(null, { style: { color:'#16a34a', weight:2, fillColor:'#22c55e', fillOpacity:0.12 } }).addTo(map);
    const markers = L.layerGroup().addTo(map);
    let marker = null;

    // 3) DOM refs
    const $ta   = $('textarea[name="geojson"]');
    const $lat  = $('#lat');
    const $lng  = $('#lng');

    // 4) Seed from current textarea (DB value already rendered in Blade)
    function renderFromTextarea() {
      const raw = ($ta.val() || '').trim();
      area.clearLayers();
      if (!raw) return;
      const gj = normalizeGeoJSON(raw);
      if (!gj) return;
      try {
        area.addData(gj);
        const b = area.getBounds();
        if (b.isValid()) map.fitBounds(b, { padding: [20, 20] });
      } catch (_) {}
    }
    if ($ta.length) {
      renderFromTextarea();
      let t;
      $ta.on('input', function () {
        clearTimeout(t);
        t = setTimeout(renderFromTextarea, 300);
      });
    }

    

    // 6) If inputs have lat/lng, place marker initially
    (function seedMarkerFromInputs() {
      const latVal = parseFloat($lat.val());
      const lngVal = parseFloat($lng.val());
      if (!Number.isNaN(latVal) && !Number.isNaN(lngVal)) {
        marker = L.marker([latVal, lngVal]).addTo(markers);
        map.setView([latVal, lngVal], 15);
      }
    })();

    // 7) Click on map -> fill lat/lng + move marker
    map.on('click', function (e) {
      if (marker) markers.clearLayers();
      marker = L.marker(e.latlng).addTo(markers);
      if ($lat.length) $lat.val(e.latlng.lat.toFixed(6));
      if ($lng.length) $lng.val(e.latlng.lng.toFixed(6));
    });

    // 8) Fix map size if inside tabs/modals
    setTimeout(() => map.invalidateSize(), 300);

    // 9) Client-side bootstrap validation (optional)
    document.querySelectorAll('.needs-validation').forEach(form => {
      form.addEventListener('submit', e => {
        if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        form.classList.add('was-validated');
      });
    });
  });
})(jQuery);
