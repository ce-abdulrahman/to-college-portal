// assets/admin/js/pages/provinces/create.js
// preview Leaflet map + optional GeoJSON visualization

(function ($) {
  function resetLeafletIdIfNeeded(id) {
    const node = L.DomUtil.get(id);
    if (node && node._leaflet_id) node._leaflet_id = null;
  }

  $(function () {
    const id = 'map';
    const $el = $('#' + id);
    if (!$el.length) return;

    // fix duplicate init bug
    resetLeafletIdIfNeeded(id);

    const n = L.DomUtil.get('map');
    if (n && n._leaflet_id) n._leaflet_id = null;

    // create map
    const map = L.map(id).setView([35.55, 44.45], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // layer groups
    const previewLayer = L.geoJSON(null, {
      style: {
        color: '#2563eb',
        weight: 2,
        fillColor: '#3b82f6',
        fillOpacity: 0.15
      }
    }).addTo(map);

    // watch textarea for geojson text
    $('textarea[name="geojson"]').on('input', function () {
      const val = $(this).val().trim();
      previewLayer.clearLayers();
      if (!val) return;
      try {
        const gj = JSON.parse(val);
        previewLayer.addData(gj);
        const b = previewLayer.getBounds();
        if (b.isValid()) map.fitBounds(b);
      } catch (e) {
        console.warn('Invalid GeoJSON');
      }
    });

    // on file upload, show preview too
    $('input[name="geojson_file"]').on('change', function (e) {
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function (ev) {
        try {
          const gj = JSON.parse(ev.target.result);
          previewLayer.clearLayers();
          previewLayer.addData(gj);
          const b = previewLayer.getBounds();
          if (b.isValid()) map.fitBounds(b);
        } catch (err) {
          alert('فایلەکە ناتوانرێت بخوێنرێت، تکایە دڵنیابە GeoJSON دروستە.');
        }
      };
      reader.readAsText(file);
    });

    // fix map sizing if inside tab/modal
    setTimeout(() => map.invalidateSize(), 300);
  });
})(jQuery);
