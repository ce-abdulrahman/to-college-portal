/* ===========================
 * jQuery Map App (Leaflet)
 * File: public/js/dashboad-maps.js
 * =========================== */
(function ($, L, w, d) {
  'use strict';

  // ---- Config from Blade ----
  const CFG = w.MAP_CONFIG || {};
  const R = CFG.apiRoutes || {};
  const provincesUrl = CFG.provincesUrl;

  // ---- State ----
  const S = {
    currentLevel: 'provinces',
    current: { province: null, university: null, college: null },
    markers: { uni: {}, col: {}, dep: {} },
  };

  // ---- Map Init ----
  const map = L.map('map', { zoomControl: false, fadeAnimation: true, markerZoomAnimation: true })
    .setView(CFG.initialView.center || [34.7, 43.9], CFG.initialView.zoom || 6);

  L.tileLayer((CFG.tileLayer && CFG.tileLayer.url) || 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: (CFG.tileLayer && CFG.tileLayer.attribution) || '&copy; OpenStreetMap'
  }).addTo(map);

  L.control.zoom({ position: 'topright' }).addTo(map);

  // ---- Layers ----
  const LG = {
    provinces: L.featureGroup().addTo(map),
    uniPoints: L.layerGroup().addTo(map),
    uniPolys: L.layerGroup(),
    colPoints: L.layerGroup().addTo(map),
    colPolys: L.layerGroup(),
    depPoints: L.layerGroup().addTo(map),
    depPolys: L.layerGroup(),
    user: L.layerGroup().addTo(map)
  };

  const overlays = {
    'Universities • Polygons': LG.uniPolys,
    'Colleges • Polygons': LG.colPolys,
    'Departments • Polygons': LG.depPolys
  };
  L.control.layers(null, overlays, { collapsed: true, position: 'topright' }).addTo(map);

  // ---- Helpers ----
  function showListLoading() { $('#loading-indicator').show(); $('#inst-list').hide(); }
  function hideListLoading() { $('#loading-indicator').hide(); $('#inst-list').show(); }
  function listTitle(t) { $('#list-title').text(t || '—'); }
  function listCount(n) { $('#result-count').text((n || 0) + ' found'); }
  function provinceTitle(p) { $('#province-title').text(p?.name || '—'); }

  function popupHTML(item, type) {
    const label = type === 'uni' ? 'University' : type === 'col' ? 'College' : 'Department';
    return `
      <div class="custom-popup">
        <h6>${item.name || label}</h6>
        ${item.name_en ? `<p><small>${item.name_en}</small></p>` : ''}
        ${item.lat != null && item.lng != null ? `<p><small>${item.lat}, ${item.lng}</small></p>` : ''}
      </div>`;
  }
  function colorFor(type) {
    return type === 'uni' ? '#2563eb' : type === 'col' ? '#10b981' : '#f59e0b';
  }
  function makeMarker(item, type) {
    if (item.lat == null || item.lng == null) return null;
    const c = colorFor(type);
    return L.circleMarker([item.lat, item.lng], { radius: 8, color: c, fillColor: c, fillOpacity: 0.85, weight: 2 })
      .bindTooltip(item.name || '', { direction: 'top' })
      .bindPopup(popupHTML(item, type));
  }
  function makePolygon(gj, item, type) {
    if (!gj) return null;
    const c = colorFor(type);
    return L.geoJSON(gj, { style: { color: c, weight: 2, fillOpacity: 0.2 } })
      .bindPopup(popupHTML(item, type));
  }
  function focusLayer(layer) {
    try {
      if (layer.getBounds) map.fitBounds(layer.getBounds().pad(0.12));
      else if (layer.getLatLng) map.setView(layer.getLatLng(), Math.max(14, map.getZoom()), { animate: true });
    } catch (_) {}
  }

  // ---- Provinces ----
  function loadProvinces() {
    $('#inst-list').html(`
      <li class="text-center text-muted py-4">
        <i class="bi bi-map fs-1 d-block mb-2"></i>
        Select a province on the map...
      </li>`);
    listTitle('—'); listCount(0); provinceTitle({name: '—'});

    $.getJSON(provincesUrl)
      .done(function (fc) {
        LG.provinces.clearLayers();
        L.geoJSON(fc, {
          style: { color: '#666', weight: 2, fillOpacity: 0.1 },
          onEachFeature: function (feat, layer) {
            const p = feat.properties || {};
            layer.bindTooltip(`<div class="text-center"><strong>${p.name || 'Province'}</strong>${p.name_en ? `<br><small>${p.name_en}</small>` : ''}</div>`, { sticky: true });
            layer.on('click', function () { onProvinceClick(p, layer); });
            layer.on('mouseover', function () { layer.setStyle({ weight: 3, fillOpacity: 0.2 }); });
            layer.on('mouseout', function () {
              if (!layer._hl) layer.setStyle({ weight: 2, fillOpacity: 0.1 });
            });
          }
        }).addTo(LG.provinces);
        try { map.fitBounds(LG.provinces.getBounds().pad(0.1)); } catch (_) {}
      })
      .fail(function (xhr) {
        console.error('Provinces failed:', xhr.status, xhr.responseText);
        alert('Failed to load provinces.');
      });
  }

  function onProvinceClick(province, layer) {
    // highlight
    LG.provinces.eachLayer(function (l) { try { l.setStyle({ weight: 2, fillOpacity: 0.1 }); l._hl = false; } catch (_) {} });
    try { layer.setStyle({ weight: 4, color: '#3b82f6', fillOpacity: 0.3 }); layer._hl = true; } catch (_) {}

    S.currentLevel = 'universities';
    S.current.province = province;
    provinceTitle(province);
    updateBreadcrumb([
      { name: 'Provinces', level: 'provinces' },
      { name: province.name || 'Province', level: 'universities' }
    ]);
    loadUniversities(province.id);
  }

  // ---- Universities ----
  function loadUniversities(provinceId) {
    showListLoading(); listTitle('Universities');

    $.getJSON(R.universitiesByProvince(provinceId))
      .done(function (data) {
        const items = data.items || [];
        listCount(items.length);
        renderList(items, 'uni');
        renderOnMap(items, 'uni');
        hideListLoading();
      })
      .fail(function (xhr) {
        console.error('Universities failed:', xhr.status, xhr.responseText);
        alert('Failed to load universities.');
      });
  }

  // ---- Colleges ----
  function loadColleges(universityId) {
    showListLoading(); listTitle('Colleges');

    $.getJSON(R.collegesByUniversity(universityId))
      .done(function (data) {
        const items = data.items || [];
        S.current.university = data.university || { id: universityId };
        updateBreadcrumb([
          { name: 'Provinces', level: 'provinces' },
          { name: S.current.province?.name || 'Province', level: 'universities' },
          { name: data.university?.name || 'University', level: 'colleges' },
        ]);
        listCount(items.length);
        renderList(items, 'col');
        renderOnMap(items, 'col');
        hideListLoading();
      })
      .fail(function (xhr) {
        console.error('Colleges failed:', xhr.status, xhr.responseText);
        alert('Failed to load colleges.');
      });
  }

  // ---- Departments ----
  function loadDepartments(collegeId) {
    showListLoading(); listTitle('Departments');

    $.getJSON(R.departmentsByCollege(collegeId))
      .done(function (data) {
        const items = data.items || [];
        S.current.college = data.college || { id: collegeId };
        updateBreadcrumb([
          { name: 'Provinces', level: 'provinces' },
          { name: S.current.province?.name || 'Province', level: 'universities' },
          { name: S.current.university?.name || 'University', level: 'colleges' },
          { name: data.college?.name || 'College', level: 'departments' },
        ]);
        listCount(items.length);
        renderList(items, 'dep');
        renderOnMap(items, 'dep');
        hideListLoading();
      })
      .fail(function (xhr) {
        console.error('Departments failed:', xhr.status, xhr.responseText);
        alert('Failed to load departments.');
      });
  }

  // ---- Render List ----
  function renderList(items, type) {
    const $ul = $('#inst-list').empty();

    if (!items.length) {
      $ul.html(`<li class="text-center text-muted py-4">
        <i class="bi ${type === 'uni' ? 'bi-building' : type === 'col' ? 'bi-house' : 'bi-diagram-3'} fs-1 d-block mb-2"></i>
        No ${type === 'uni' ? 'universities' : type === 'col' ? 'colleges' : 'departments'} found
      </li>`);
      return;
    }

    items.forEach(function (it) {
      const icon = type === 'uni' ? 'bi-building' : type === 'col' ? 'bi-house' : 'bi-diagram-3';
      const canZoom = it.lat != null && it.lng != null;

      const $li = $(`
        <li class="institution-item p-3 border-bottom" data-type="${type}" data-id="${it.id}">
          <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
              <h6 class="fw-bold mb-1"><i class="bi ${icon} me-1"></i> ${it.name || '—'}</h6>
              ${it.name_en ? `<small class="text-muted d-block">${it.name_en}</small>` : ''}
            </div>
            <div class="btn-group btn-group-sm">
              ${canZoom ? `<button class="btn btn-outline-primary action-btn" data-action="zoom"><i class="bi bi-zoom-in"></i></button>` : ''}
              ${type !== 'dep' ? `<button class="btn btn-outline-success action-btn" data-action="${type === 'uni' ? 'load-colleges' : 'load-departments'}"><i class="bi bi-arrow-right"></i></button>` : ''}
            </div>
          </div>
        </li>`);
      $ul.append($li);
    });
  }

  // ---- Render on Map ----
  function clearType(type) {
    if (type === 'uni') { LG.uniPoints.clearLayers(); LG.uniPolys.clearLayers(); S.markers.uni = {}; }
    if (type === 'col') { LG.colPoints.clearLayers(); LG.colPolys.clearLayers(); S.markers.col = {}; }
    if (type === 'dep') { LG.depPoints.clearLayers(); LG.depPolys.clearLayers(); S.markers.dep = {}; }
  }
  function renderOnMap(items, type) {
    clearType(type);

    items.forEach(function (it) {
      const m = makeMarker(it, type);
      if (m) {
        (type === 'uni' ? LG.uniPoints : type === 'col' ? LG.colPoints : LG.depPoints).addLayer(m);
        S.markers[type][it.id] = m;
      }
      if (it.geojson) {
        const p = makePolygon(it.geojson, it, type);
        (type === 'uni' ? LG.uniPolys : type === 'col' ? LG.colPolys : LG.depPolys).addLayer(p);
      }
    });

    const grp = type === 'uni' ? LG.uniPoints : type === 'col' ? LG.colPoints : LG.depPoints;
    if (grp.getLayers().length) { try { map.fitBounds(grp.getBounds().pad(0.1)); } catch (_) {} }
  }

  // ---- Breadcrumb ----
  function updateBreadcrumb(items) {
    const $bc = $('#breadcrumb-list').empty();
    items.forEach(function (it, i) {
      const active = i === items.length - 1;
      const $li = $(`<li class="breadcrumb-item ${active ? 'active' : ''}"></li>`);
      if (active) $li.text(it.name);
      else {
        const $a = $(`<a href="#">${it.name}</a>`);
        $a.on('click', function (e) {
          e.preventDefault(); goToLevel(it.level);
        });
        $li.append($a);
      }
      $bc.append($li);
    });
  }
  function goToLevel(level) {
    S.currentLevel = level;
    if (level === 'provinces') {
      map.setView(CFG.initialView.center || [34.7, 43.9], CFG.initialView.zoom || 6);
      provinceTitle({name:'—'}); listTitle('—'); listCount(0);
      $('#inst-list').html(`<li class="text-center text-muted py-4"><i class="bi bi-map fs-1 d-block mb-2"></i>Select a province...</li>`);
      LG.uniPoints.clearLayers(); LG.uniPolys.clearLayers();
      LG.colPoints.clearLayers(); LG.colPolys.clearLayers();
      LG.depPoints.clearLayers(); LG.depPolys.clearLayers();
      return;
    }
    if (level === 'universities' && S.current.province) loadUniversities(S.current.province.id);
    if (level === 'colleges' && S.current.university) loadColleges(S.current.university.id);
  }

  // ---- Buttons (jQuery bindings) ----
  $('#btn-toggle-uni').on('click', function () {
    toggleGroups([LG.uniPoints, LG.uniPolys]);
  });
  $('#btn-toggle-col').on('click', function () {
    toggleGroups([LG.colPoints, LG.colPolys]);
  });
  $('#btn-toggle-dep').on('click', function () {
    toggleGroups([LG.depPoints, LG.depPolys]);
  });
  $('#btn-home').on('click', function () {
    try { map.fitBounds(LG.provinces.getBounds().pad(0.12)); } catch (_) {}
  });
  $('#btn-fit').on('click', function () {
    const groups = [LG.uniPoints, LG.colPoints, LG.depPoints, LG.uniPolys, LG.colPolys, LG.depPolys];
    let b = null; groups.forEach(g => { try { const bb = g.getBounds(); if (bb && bb.isValid()) b = b ? b.extend(bb) : bb; } catch (_) {} });
    if (b) map.fitBounds(b.pad(0.15));
  });
  $('#btn-locate').on('click', function () {
    if (!navigator.geolocation) return alert('Geolocation not supported.');
    navigator.geolocation.getCurrentPosition(function (pos) {
      const { latitude, longitude, accuracy } = pos.coords;
      LG.user.clearLayers();
      L.circle([latitude, longitude], { radius: accuracy, color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.1, weight: 1 }).addTo(LG.user);
      L.marker([latitude, longitude], { title: 'You' }).addTo(LG.user).bindTooltip('Your location', { direction: 'top' });
      map.setView([latitude, longitude], 14);
    }, function (err) { alert('Failed to locate: ' + err.message); }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 });
  });

  function toggleGroups(arr) {
    arr.forEach(function (g) {
      if (map.hasLayer(g)) map.removeLayer(g); else g.addTo(map);
    });
  }

  // ---- List item actions (delegate) ----
  $('#inst-list').on('click', '.action-btn', function () {
    const $li = $(this).closest('li.institution-item');
    const type = $li.data('type'), id = +$li.data('id');
    const action = $(this).data('action');

    if (action === 'zoom') {
      const m = S.markers[type][id];
      if (m && m.getLatLng) {
        map.setView(m.getLatLng(), 15, { animate: true });
        setTimeout(() => m.openPopup(), 150);
      }
    } else if (action === 'load-colleges') {
      loadColleges(id);
    } else if (action === 'load-departments') {
      loadDepartments(id);
    }
  });

  // ---- Boot ----
  updateBreadcrumb([{ name: 'Provinces', level: 'provinces' }]);
  loadProvinces();

})(jQuery, L, window, document);
