// assets/admin/js/pages/provinces/edit.js
const ta = document.querySelector('textarea[name="geojson_text"]');

(function ($) {
  function resetLeafletIdIfNeeded(id) {
    const node = L.DomUtil.get(id);
    if (node && node._leaflet_id) node._leaflet_id = null;
  }

  $(function () {
    const id = 'map-edit';
    const $map = $('#' + id);
    if (!$map.length) return;

    // fix: avoid "Map container is already initialized"
    resetLeafletIdIfNeeded(id);

    const n = L.DomUtil.get('map-edit');
    if (n && n._leaflet_id) n._leaflet_id = null;

    const map = L.map(id).setView([36.2, 44.0], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const layer = L.geoJSON(null, {
      style: { color: '#2563eb', weight: 2, fillColor: '#3b82f6', fillOpacity: 0.15 }
    }).addTo(map);

    const $ta = $('textarea[name="geojson"]');

    // initial fill from textarea (DB value)
    (function seedFromTextarea() {
      const val = ($ta.val() || '').trim();
      if (!val) return;
      try {
        const gj = JSON.parse(val);
        layer.clearLayers().addData(gj);
        const b = layer.getBounds();
        if (b.isValid()) map.fitBounds(b, { padding: [20,20] });
      } catch (e) { /* ignore */ }
    })();

    // live preview while typing
    let t;
    $ta.on('input', function () {
      clearTimeout(t);
      t = setTimeout(() => {
        const val = ($ta.val() || '').trim();
        layer.clearLayers();
        if (!val) return;
        try {
          const gj = JSON.parse(val);
          layer.addData(gj);
          const b = layer.getBounds();
          if (b.isValid()) map.fitBounds(b, { padding: [20,20] });
        } catch (e) { /* ignore */ }
      }, 350);
    });

    // preview on file upload
    $('input[name="geojson_file"]').on('change', function (e) {
      const file = e.target.files && e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function (ev) {
        try {
          const text = ev.target.result;
          const gj = JSON.parse(text);
          layer.clearLayers().addData(gj);
          const b = layer.getBounds();
          if (b.isValid()) map.fitBounds(b, { padding: [20,20] });
          // optionally sync textarea with file content so it submits too
          $ta.val(JSON.stringify(gj));
        } catch (err) {
          alert('فایلەکە JSON دروست نییە.');
        }
      };
      reader.readAsText(file);
    });

    setTimeout(() => map.invalidateSize(), 300);
  });
})(jQuery);
