(function () {
  // ===== Helpers =====
  const $ = window.jQuery;

  // Wait for DOM + jQuery
  document.addEventListener('DOMContentLoaded', function () {

    // ---------- Summernote ----------
    if (typeof $ === 'function' && $.fn.summernote) {
      const $desc = $('#description');
      $('.summernote').summernote({
        height: 220,
        placeholder: 'وەسف بنووسە...',
        toolbar: [
          ['style', ['bold','italic','underline','clear']],
          ['para', ['ul','ol','paragraph']],
          ['insert', ['link','picture']],
          ['view', ['fullscreen','codeview']]
        ]
      });

      // ئەگەر بەتاڵە → دەقی نمونەیی
      if ($desc.length && ($desc.val() || '').trim() === '') {
        $desc.val(
`دیزاینی “Summers” هەستی گەرمیدا و ڕۆشنایییەکی خۆش پێدەدا.
پەلتەی ڕەنگ: زەردی خۆر، شینی دەریا و گۆڵەپەمەیی؛ ڕووکاری پاک و هەوا هەیە.
پەیام: ئازادی، ئاسایش و کاتژمێرە خۆشحاڵانەکان.`).trigger('summernote.change');
      }

      // Counter (پیتەکانی دەق)
      if ($('#descCount').length === 0) {
        $('<small id="descCount" class="form-text text-muted d-block mt-1"></small>').insertAfter($desc);
      }
      const maxLen = 600;
      const updateCounter = () => {
        const plain = ($desc.val() || '').replace(/<[^>]*>/g,'');
        $('#descCount').text(`${plain.length}/${maxLen} پیت`).toggleClass('text-danger', plain.length > maxLen);
      };
      $desc.on('summernote.change input', updateCounter);
      updateCounter();
    }

    // ---------- Dependent selects (Province → Universities → Colleges) ----------
    const provinceSel   = document.getElementById('province_id');
    const universitySel = document.getElementById('university_id');
    const collegeSel    = document.getElementById('college_id');

    const fillSelect = (sel, items, placeholder) => {
      sel.innerHTML = '';
      const opt0 = document.createElement('option');
      opt0.value = '';
      opt0.textContent = placeholder || 'هەلبژاردن';
      sel.appendChild(opt0);
      (items || []).forEach(it => {
        const o = document.createElement('option');
        o.value = it.id;
        o.textContent = it.name;
        sel.appendChild(o);
      });
    };

    const setDisabled = (sel, st) => { sel.disabled = !!st; };

    // Province → Universities
    if (provinceSel && universitySel) {
      provinceSel.addEventListener('change', async function () {
        const pid = this.value;
        fillSelect(universitySel, [], 'هەموو زانکۆكان');
        fillSelect(collegeSel, [], 'هەموو کۆلێژەکان');
        setDisabled(universitySel, true);
        setDisabled(collegeSel, true);
        if (!pid) return;

        try {
          const url = `${window.API_UNI}?province_id=${encodeURIComponent(pid)}`;
          const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
          const data = await res.json();
          fillSelect(universitySel, data, 'هەموو زانکۆكان');
          setDisabled(universitySel, false);
        } catch (e) {
          console.error('UNI fetch error', e);
        }
      });
    }

    // University → Colleges
    if (universitySel && collegeSel) {
      universitySel.addEventListener('change', async function () {
        const uid = this.value;
        fillSelect(collegeSel, [], 'هەموو کۆلێژەکان');
        setDisabled(collegeSel, true);
        if (!uid) return;

        try {
          const url = `${window.API_COLLS}?university_id=${encodeURIComponent(uid)}`;
          const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
          const data = await res.json();
          fillSelect(collegeSel, data, 'هەموو کۆلێژەکان');
          setDisabled(collegeSel, false);
        } catch (e) {
          console.error('COLLEGES fetch error', e);
        }
      });
    }

    // ---------- Leaflet map ----------
    if (typeof L !== 'undefined') {
      const MAP_ID = 'map'; // هاوبەش لە Blade
      const container = L.DomUtil.get(MAP_ID);
      if (container) {
        if (container._leaflet_id) container._leaflet_id = null;

        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');

        // سنترێکی بنەڕەتی (Kurdistan Region)
        const lat0 = Number(latInput?.value) || 36.2;
        const lng0 = Number(lngInput?.value) || 44.0;

        const map = L.map(MAP_ID).setView([lat0, lng0], (latInput?.value && lngInput?.value) ? 14 : 9);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);

        const layer = L.featureGroup().addTo(map);

        // ئەگەر هەموارەکان لە پێشووتر پڕن، مارکەر بنووسە
        if (latInput?.value && lngInput?.value) {
          const pt = L.latLng(Number(latInput.value), Number(lngInput.value));
          L.marker(pt).addTo(layer);
        }

        map.on('click', (e) => {
          layer.clearLayers();
          L.marker(e.latlng).addTo(layer);
          if (latInput) latInput.value = e.latlng.lat.toFixed(6);
          if (lngInput) lngInput.value = e.latlng.lng.toFixed(6);
        });
      }
    }

    // ---------- HTML5 form validation ----------
    (function () {
      const forms = document.querySelectorAll('.needs-validation');
      Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault(); event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();

  });
})();
