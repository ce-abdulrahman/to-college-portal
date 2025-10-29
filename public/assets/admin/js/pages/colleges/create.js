// public/assets/admin/js/pages/colleges/create.js
// Create College: Leaflet preview + normalize GeoJSON (textarea/file) + click-to-fill lat/lng

(function ($) {
  function resetLeafletContainer(id) {
    const node = L.DomUtil.get(id);
    if (node && node._leaflet_id) node._leaflet_id = null;
  }

  function normalizeGeoJSON(input) {
    try { if (typeof input === 'string') input = JSON.parse(input); } catch (_) { return null; }
    if (!input) return null;
    if (Array.isArray(input)) return { type:'FeatureCollection', features: input };
    if (input.type === 'Feature' || input.type === 'FeatureCollection') return input;
    if (input.type && input.coordinates) return { type:'Feature', geometry: input, properties:{} };
    return null;
  }

  $(function () {
    const MAP_ID = 'map';
    const $mapEl = $('#' + MAP_ID);
    if (!$mapEl.length) return;

    // Init map safely
    resetLeafletContainer(MAP_ID);
    const map = L.map(MAP_ID).setView([36.2, 44.0], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const area    = L.geoJSON(null, { style: { color:'#16a34a', weight:2, fillColor:'#22c55e', fillOpacity:0.12 } }).addTo(map);
    const markers = L.layerGroup().addTo(map);
    let marker = null;

    // DOM refs
    const $ta   = $('textarea[name="geojson_text"]');
    const $file = $('input[name="geojson_file"]');
    const $lat  = $('#lat');
    const $lng  = $('#lng');

    // Seed from old() textarea (if exists)
    function renderFromTextarea() {
      const raw = ($ta.val() || '').trim();
      area.clearLayers();
      if (!raw) return;
      const gj = normalizeGeoJSON(raw);
      if (!gj) return;
      try {
        area.addData(gj);
        const b = area.getBounds();
        if (b.isValid()) map.fitBounds(b, { padding:[20,20] });
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

    // File -> preview + sync textarea
    if ($file.length) {
      $file.on('change', function (e) {
        const f = e.target.files && e.target.files[0]; if (!f) return;
        const r = new FileReader();
        r.onload = function (ev) {
          const gj = normalizeGeoJSON(ev.target.result);
          area.clearLayers();
          if (!gj) { alert('فایلەکە JSON دروست نییە.'); return; }
          area.addData(gj);
          const b = area.getBounds();
          if (b.isValid()) map.fitBounds(b, { padding:[20,20] });
          if ($ta.length) $ta.val(JSON.stringify(gj)); // بۆ ناردنی هەمان داتا لەگەڵ فۆرم
        };
        r.readAsText(f);
      });
    }

    // If lat/lng already filled (old input), drop initial marker
    (function seedMarker() {
      const la = parseFloat($lat.val());
      const ln = parseFloat($lng.val());
      if (!Number.isNaN(la) && !Number.isNaN(ln)) {
        marker = L.marker([la, ln]).addTo(markers);
        map.setView([la, ln], 15);
      }
    })();

    // Click on map -> fill lat/lng + marker
    map.on('click', function (e) {
      if (marker) markers.clearLayers();
      marker = L.marker(e.latlng).addTo(markers);
      if ($lat.length) $lat.val(e.latlng.lat.toFixed(6));
      if ($lng.length) $lng.val(e.latlng.lng.toFixed(6));
    });

    // Invalidate size (tabs/modals)
    setTimeout(() => map.invalidateSize(), 300);

    // Bootstrap client-side validation
    document.querySelectorAll('.needs-validation').forEach(form => {
      form.addEventListener('submit', e => {
        if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        form.classList.add('was-validated');
      });
    });
  });
})(jQuery);
