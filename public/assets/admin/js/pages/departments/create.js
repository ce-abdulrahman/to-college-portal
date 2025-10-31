(function() {
  // ===== Force a clean map container (avoid leftover Leaflet instances) =====
  const MAP_ID = 'dept-map'; // دڵنیابە لە blade — <div id="dept-map">
  const container = L.DomUtil.get(MAP_ID);
  if (!container) return;
  if (container._leaflet_id) { // reset if any previous map was bound to same element
    container._leaflet_id = null;
  }

  // ===== Init map (no provinces, no previous markers) =====
  const latInput = document.getElementById('lat');
  const lngInput = document.getElementById('lng');

  const map = L.map(MAP_ID).setView([36.2, 44.0], 9); // هەمیشە پاک ـ بی‌داتا
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);

  // Only a single selection layer
  const layer = L.featureGroup().addTo(map);
  let marker = null;

  map.on('click', (e) => {
    layer.clearLayers();
    marker = L.marker(e.latlng).addTo(layer);
    if (latInput) latInput.value = e.latlng.lat.toFixed(6);
    if (lngInput) lngInput.value = e.latlng.lng.toFixed(6);
  });

  // ===== Summers description (auto-fill + counter) =====
  $(function() {
    const summersKu =
`دیزاینی “Summers” هەستی گەرمیدا، رۆشنایی و قەبارەی هاوسەنگی تابستان دەبیندێت.
پەلتەی ڕەنگ هێنراوە لە زەردی خۆر، شینی دەریا و گۆڵەپەمەیی؛ ڕووکاری پاک و هەوا هەیە،
فۆنتە چەماوەکان، سێبەیریە ناتوندەکان و وێنەکارییە نزمەکان.
پەیام: ئازادی، ئاسایشی دەریا و کاتژمێرە خۆشحاڵانەکان.`;

    const $desc = $('#description');
    // تەنیا ئەگەر بەتاڵە یان تەنیا اسپەیس هەیە → پڕی بکە
    if ($desc.length && $desc.val().trim() === '') {
      $desc.val(summersKu);
    }

    // Focus style (ئاختیاری)
    $desc.on('focus', function() {
      $(this).css('background-color', '#fffbe6');
    }).on('blur', function() {
      $(this).css('background-color', '#fff');
    });

    // Counter
    const maxLen = 600;
    if ($('#descCount').length === 0) {
      $('<small id="descCount" class="form-text text-muted d-block mt-1"></small>').insertAfter($desc);
    }
    const updateCounter = () => {
      const len = $desc.val().length;
      $('#descCount').text(len + '/' + maxLen + ' پیت').toggleClass('text-danger', len > maxLen);
    };
    $desc.on('input', updateCounter);
    updateCounter();
  });
})();
