// Departments Create: Summernote (description) + Province→University→College cascade
// + Leaflet clean map (click to set lat/lng) + Bootstrap validation

(function ($) {
  "use strict";

  // ---------- helpers ----------
  const $id = (s) => document.getElementById(s);
  const enable = (el, on = true) => el && (el.disabled = !on);
  const fillSelect = (el, items, placeholder) => {
    if (!el) return;
    el.innerHTML = `<option value="">${placeholder}</option>`;
    (items || []).forEach(it => {
      const o = document.createElement('option');
      o.value = it.id;
      o.textContent = it.name;
      el.appendChild(o);
    });
  };

  function resetLeafletContainer(id) {
    if (!window.L || !L.DomUtil) return;
    const node = L.DomUtil.get(id);
    if (node && node._leaflet_id) node._leaflet_id = null;
  }

  $(function () {
    // -------- Summernote (optional if .summernote exists) --------
    if ($.fn.summernote && $('.summernote').length) {
      $('.summernote').summernote({
        placeholder: 'وەسف/ڕونکردنەوە بنووسە...',
        height: 180,
        minHeight: 150,
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['para',  ['ul', 'ol', 'paragraph']],
          ['insert',['link']],
          ['view',  ['fullscreen', 'codeview']]
        ]
      });
    }

    // -------- Cascade: Province → University → College --------
    const selProv = $id('province_id');
    const selUni  = $id('university_id');
    const selColl = $id('college_id');

    // API endpoints (ئەمەکان لە Blade دەتوانیت set بکەیت، ئەگەر نەبوون fallback دەکات)
    const UNI_API   = window.API_UNI   || '/admin/api/universities'; // ?province_id=ID
    const COLLS_API = window.API_COLLS || '/admin/api/colleges';     // ?university_id=ID

    // Province change → load universities
    selProv?.addEventListener('change', () => {
      const pid = selProv.value;
      fillSelect(selUni, [], 'هەموو زانکۆكان');  enable(selUni, false);
      fillSelect(selColl, [], 'هەموو کۆلێژەکان'); enable(selColl, false);

      if (!pid) return;
      fetch(`${UNI_API}?province_id=${encodeURIComponent(pid)}`)
        .then(r => r.json())
        .then(list => { fillSelect(selUni, list, 'هەموو زانکۆكان'); enable(selUni, true); })
        .catch(() => fillSelect(selUni, [], 'هەڵە ڕوویدا'));
    });

    // University change → load colleges
    selUni?.addEventListener('change', () => {
      const uid = selUni.value;
      fillSelect(selColl, [], 'هەموو کۆلێژەکان'); enable(selColl, false);

      if (!uid) return;
      fetch(`${COLLS_API}?university_id=${encodeURIComponent(uid)}`)
        .then(r => r.json())
        .then(list => { fillSelect(selColl, list, 'هەموو کۆلێژەکان'); enable(selColl, true); })
        .catch(() => fillSelect(selColl, [], 'هەڵە ڕوویدا'));
    });

    // -------- Leaflet clean map: click → set lat/lng --------
    const MAP_ID = 'map';
    const mapEl = $id(MAP_ID);
    if (mapEl && window.L) {
      resetLeafletContainer(MAP_ID);

      const $lat = $id('lat');
      const $lng = $id('lng');

      const hasLat = $lat && $lat.value !== '' && !Number.isNaN(parseFloat($lat.value));
      const hasLng = $lng && $lng.value !== '' && !Number.isNaN(parseFloat($lng.value));

      const lat0 = hasLat ? parseFloat($lat.value) : 36.2;
      const lng0 = hasLng ? parseFloat($lng.value) : 44.0;
      const zoom0 = (hasLat && hasLng) ? 15 : 9;

      const map = L.map(MAP_ID).setView([lat0, lng0], zoom0);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
      }).addTo(map);

      const layer = L.layerGroup().addTo(map);
      let marker = null;

      if (hasLat && hasLng) {
        marker = L.marker([lat0, lng0]).addTo(layer);
      }

      map.on('click', (e) => {
        if (marker) layer.clearLayers();
        marker = L.marker(e.latlng).addTo(layer);
        if ($lat) $lat.value = e.latlng.lat.toFixed(6);
        if ($lng) $lng.value = e.latlng.lng.toFixed(6);
      });

      // if in tabs/modals
      setTimeout(() => map.invalidateSize(), 300);
    }

    // -------- Bootstrap validation --------
    document.querySelectorAll('.needs-validation').forEach(form => {
      form.addEventListener('submit', e => {
        if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        form.classList.add('was-validated');
      });
    });
  });
})(jQuery);
