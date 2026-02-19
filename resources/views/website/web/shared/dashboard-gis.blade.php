@php
    $dashboardTitle = $dashboardTitle ?? 'داشبۆردی نەخشە';
    $homeRoute = $homeRoute ?? '#';
    $quickRoute = $quickRoute ?? null;
    $quickLabel = $quickLabel ?? null;
    $quickIcon = $quickIcon ?? 'bi bi-arrow-up-right-circle';
    $mapScope = $mapScope ?? [];
@endphp

<div class="container-fluid py-3 gis-dashboard">
    <div class="row g-3">
        <div class="col-lg-8 col-xl-9">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h5 class="mb-1">{{ $dashboardTitle }}</h5>
                            <p class="small text-muted mb-0">نیشاندانی زانکۆ، کۆلێژ و بەشەکان لەسەر نەخشە</p>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ $homeRoute }}" class="btn btn-outline-secondary"><i class="bi bi-house-door"></i></a>
                            <button type="button" id="btn-fit" class="btn btn-outline-secondary"><i class="bi bi-bounding-box"></i></button>
                            <button type="button" id="btn-locate" class="btn btn-outline-secondary"><i class="bi bi-geo-alt"></i></button>
                            @if ($quickRoute && $quickLabel)
                                <a href="{{ $quickRoute }}" class="btn btn-outline-primary"><i class="{{ $quickIcon }} me-1"></i>{{ $quickLabel }}</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column p-3">
                    @if (!empty($mapScope['is_restricted']))
                        @if (!empty($mapScope['primary_province_name']))
                            <div class="alert alert-warning py-2 px-3 small mb-3">
                                <i class="bi bi-shield-check me-1"></i>
                                تەنها پارێزگای <strong>{{ $mapScope['primary_province_name'] }}</strong> پیشان دەدرێت.
                            </div>
                        @else
                            <div class="alert alert-danger py-2 px-3 small mb-3">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                پارێزگای هەژمارەکەت دیارینەکراوە، بۆیە داتای نەخشە پیشان نادرێت.
                            </div>
                        @endif
                    @endif

                    <div class="row g-2 mb-3">
                        <div class="col-4"><button id="toggle-universities" type="button" class="btn btn-sm btn-outline-primary active w-100"><i class="bi bi-building"></i></button></div>
                        <div class="col-4"><button id="toggle-colleges" type="button" class="btn btn-sm btn-outline-success active w-100"><i class="bi bi-bank2"></i></button></div>
                        <div class="col-4"><button id="toggle-departments" type="button" class="btn btn-sm btn-outline-warning active w-100"><i class="bi bi-diagram-3"></i></button></div>
                    </div>

                    <nav aria-label="breadcrumb" class="mb-2">
                        <ol class="breadcrumb mb-0" id="breadcrumb-list"></ol>
                    </nav>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 id="list-title" class="mb-0">پارێزگاکان</h6>
                        <span id="result-count" class="small text-muted">0</span>
                    </div>

                    <input id="search-input" type="search" class="form-control form-control-sm mb-2" placeholder="گەڕان بە ناو...">

                    <div id="list-error" class="alert alert-danger small py-2 px-3 d-none mb-2"></div>

                    <div id="loading-indicator" class="text-center py-4 d-none">
                        <div class="spinner-border spinner-border-sm text-primary"></div>
                        <div class="small text-muted mt-2">بارکردن...</div>
                    </div>

                    <div id="list-empty" class="alert alert-light border small d-none mb-0">هیچ داتایەک نەدۆزرایەوە.</div>
                    <div id="items-list" class="list-group list-group-flush flex-grow-1 overflow-auto"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .gis-dashboard #map { height: calc(100vh - 220px); min-height: 520px; }
        .gis-dashboard .map-fallback {
            height: 100%;
            min-height: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            text-align: center;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            color: #475569;
        }
        .gis-dashboard .list-group-item { border: 0; border-bottom: 1px solid #eef2f6; padding: .75rem .25rem; text-align: right; }
        .gis-dashboard .list-group-item:hover { background: #f8fafc; }
        .gis-dashboard .list-group-item.active { background: #eff6ff; color: #0f172a; }
        .gis-dashboard .type-badge { font-size: .7rem; }

        .gis-dashboard .gis-marker {
            background: transparent;
            border: 0;
        }

        .gis-dashboard .gis-marker-pin {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: var(--marker-color, #2563eb);
            border: 2px solid #ffffff;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.35);
            font-size: 0.9rem;
            line-height: 1;
        }

        .gis-dashboard .dep-popup-table {
            width: 100%;
            font-size: 0.78rem;
            border-collapse: collapse;
        }

        .gis-dashboard .dep-popup-table td {
            border-bottom: 1px dashed #e2e8f0;
            padding: 4px 0;
            vertical-align: top;
        }

        .gis-dashboard .dep-popup-key {
            color: #64748b;
            white-space: nowrap;
            padding-left: 10px;
            width: 92px;
        }

        .gis-dashboard .dep-popup-val {
            color: #0f172a;
            font-weight: 600;
        }

        @media (max-width:991.98px){ .gis-dashboard #map { height: 58vh; min-height: 420px; } }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        (() => {
            const mapContainer = document.getElementById('map');
            if (!mapContainer) return;

            if (typeof L === 'undefined') {
                mapContainer.innerHTML = `
                    <div class="map-fallback">
                        <div>
                            <div class="fw-semibold mb-2">نەخشە بار نەبوو</div>
                            <div class="small">
                                Leaflet library load نەبوو. زۆرجار لە dev بەهۆی CDN/network ڕوودەدات.
                            </div>
                        </div>
                    </div>
                `;
                console.error('Leaflet is not loaded. Check CDN/network or CSP settings.');
                return;
            }

            const cfg = {
                scope: @json($mapScope),
                init: { center: [35.55, 44.35], zoom: 6 },
                routes: {
                    provinces: @json(route('admin.api.provinces.geojson')),
                    universitiesTemplate: @json(route('admin.api.provinces.universities', ['id' => '__ID__'])),
                    collegesTemplate: @json(route('admin.api.universities.colleges', ['id' => '__ID__'])),
                    departmentsTemplate: @json(route('admin.api.colleges.departments', ['id' => '__ID__'])),
                    universities: (id) => cfg.routes.universitiesTemplate.replace('__ID__', encodeURIComponent(String(id))),
                    colleges: (id) => cfg.routes.collegesTemplate.replace('__ID__', encodeURIComponent(String(id))),
                    departments: (id) => cfg.routes.departmentsTemplate.replace('__ID__', encodeURIComponent(String(id))),
                }
            };

            const el = {
                breadcrumb: document.getElementById('breadcrumb-list'),
                title: document.getElementById('list-title'),
                count: document.getElementById('result-count'),
                search: document.getElementById('search-input'),
                list: document.getElementById('items-list'),
                loading: document.getElementById('loading-indicator'),
                error: document.getElementById('list-error'),
                empty: document.getElementById('list-empty'),
                fit: document.getElementById('btn-fit'),
                locate: document.getElementById('btn-locate'),
                tUni: document.getElementById('toggle-universities'),
                tCol: document.getElementById('toggle-colleges'),
                tDep: document.getElementById('toggle-departments'),
            };

            const text = (v) => String(v ?? '').trim().toLowerCase();
            const esc = (v) => String(v ?? '').replace(/[&<>"']/g, (m) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));
            const meta = {
                province: ['پارێزگا', 'bi bi-map', 'bg-secondary'],
                university: ['زانکۆ', 'bi bi-building', 'bg-primary'],
                college: ['کۆلێژ', 'bi bi-bank2', 'bg-success'],
                department: ['بەش', 'bi bi-diagram-3', 'bg-warning text-dark'],
            };
            const markerIconByType = {
                university: 'bi bi-building',
                college: 'bi bi-bank2',
                department: 'bi bi-diagram-3',
            };
            const paletteByType = {
                university: ['#1d4ed8', '#2563eb', '#0ea5e9', '#0284c7', '#6366f1'],
                college: ['#047857', '#059669', '#10b981', '#0f766e', '#22c55e'],
                department: ['#b45309', '#d97706', '#ea580c', '#f59e0b', '#c2410c', '#fb923c'],
            };

            function hashKey(value) {
                const str = String(value ?? '');
                let hash = 0;
                for (let i = 0; i < str.length; i += 1) {
                    hash = ((hash << 5) - hash) + str.charCodeAt(i);
                    hash |= 0;
                }
                return Math.abs(hash);
            }

            function getMarkerColor(item, type, index = 0) {
                const palette = paletteByType[type] || ['#334155'];
                const key = `${item?.id ?? ''}|${item?.name ?? ''}|${type}|${index}`;
                const hashed = hashKey(key);
                return palette[hashed % palette.length];
            }

            function markerIcon(type, markerColor) {
                const iconClass = markerIconByType[type] || 'bi bi-geo-alt';
                return L.divIcon({
                    className: 'gis-marker',
                    html: `<span class="gis-marker-pin" style="--marker-color:${markerColor}"><i class="${iconClass}"></i></span>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
                    popupAnchor: [0, -14],
                    tooltipAnchor: [0, -14],
                });
            }

            function popupForItem(item, type, markerColor) {
                const dot = `<span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:${markerColor};margin-left:6px;vertical-align:middle;"></span>`;
                const nameRow = `<div class="fw-semibold mb-1">${dot}${esc(item?.name || '—')}</div>`;
                const enRow = item?.name_en ? `<div class="small text-muted mb-2">${esc(item.name_en)}</div>` : '';
                const hasCoords = item?.lat && item?.lng;
                const coordsRow = hasCoords
                    ? `<div class="small text-muted mt-2">${Number(item.lat).toFixed(5)}, ${Number(item.lng).toFixed(5)}</div>`
                    : '';

                if (type !== 'department') {
                    return `<div class="p-1">${nameRow}${enRow}${coordsRow}</div>`;
                }

                const departmentType = item?.department_type ?? item?.type ?? '—';
                const rows = [
                    ['سیستەم', item?.system_name ?? item?.system ?? '—'],
                    ['پارێزگا', item?.province_name ?? '—'],
                    ['زانکۆ', item?.university_name ?? '—'],
                    ['کۆلێژ', item?.college_name ?? '—'],
                    ['جۆر', departmentType || '—'],
                    ['ڕەگەز', item?.sex ?? '—'],
                    ['نمرەی ن.پارێزگا', item?.local_score ?? '—'],
                    ['نمرەی د.پارێزگا', item?.external_score ?? '—'],
                ];

                const tableRows = rows.map(([k, v]) => `
                    <tr>
                        <td class="dep-popup-key">${esc(k)}</td>
                        <td class="dep-popup-val">${esc(v)}</td>
                    </tr>
                `).join('');

                const description = item?.description
                    ? `<div class="small mt-2" style="line-height:1.5"><span class="text-muted">وەسف:</span> ${esc(item.description)}</div>`
                    : '';

                return `
                    <div class="p-1" style="min-width:280px;max-width:340px">
                        ${nameRow}
                        ${enRow}
                        <table class="dep-popup-table">${tableRows}</table>
                        ${description}
                        ${coordsRow}
                    </div>
                `;
            }

            const st = {
                map: null,
                layers: {
                    provinces: L.featureGroup(),
                    universities: L.featureGroup(),
                    colleges: L.featureGroup(),
                    departments: L.featureGroup(),
                    user: L.layerGroup(),
                },
                visible: { universities: true, colleges: true, departments: true },
                provinceLayers: new Map(),
                provinceItems: [],
                current: [],
                filtered: [],
                selected: { province: null, university: null, college: null },
            };

            const baseProvinceStyle = () => ({ color: '#64748b', weight: 1.8, fillOpacity: .14, fillColor: '#3b82f6' });
            const setLoading = (v) => el.loading.classList.toggle('d-none', !v);
            const setError = (msg) => {
                if (!el.error) return;
                if (!msg) {
                    el.error.classList.add('d-none');
                    el.error.textContent = '';
                    return;
                }
                el.error.textContent = msg;
                el.error.classList.remove('d-none');
            };

            function initMap() {
                st.map = L.map('map', { zoomControl: false, preferCanvas: true }).setView(cfg.init.center, cfg.init.zoom);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors', maxZoom: 19 }).addTo(st.map);
                L.control.zoom({ position: 'topright' }).addTo(st.map);
                Object.values(st.layers).forEach((l) => l.addTo(st.map));
            }

            function fit(layer) {
                if (!layer || layer.getLayers().length === 0) return;
                const b = layer.getBounds();
                if (b && b.isValid()) st.map.fitBounds(b.pad(.18), { animate: true, duration: .45 });
            }

            async function getJSON(url) {
                const r = await fetch(url, { headers: { Accept: 'application/json' } });
                if (!r.ok) throw new Error('bad response');
                return r.json();
            }

            function breadcrumb() {
                const b = [{ k: 'provinces', n: 'پارێزگاکان' }];
                if (st.selected.province) b.push({ k: 'province', n: st.selected.province.name });
                if (st.selected.university) b.push({ k: 'university', n: st.selected.university.name });
                if (st.selected.college) b.push({ k: 'college', n: st.selected.college.name });
                el.breadcrumb.innerHTML = b.map((x, i) => i === b.length - 1 ? `<li class="breadcrumb-item active">${esc(x.n)}</li>` : `<li class="breadcrumb-item"><a href="#" data-crumb="${x.k}">${esc(x.n)}</a></li>`).join('');
            }

            function setItems(items, title) { st.current = items; el.title.textContent = title; applySearch(); }

            function applySearch() {
                const q = text(el.search.value);
                st.filtered = st.current.filter((i) => !q || text(i.name).includes(q) || text(i.name_en).includes(q));
                el.count.textContent = `${st.filtered.length} دانە`;
                el.empty.classList.toggle('d-none', st.filtered.length !== 0);
                el.list.innerHTML = st.filtered.map((i, idx) => {
                    const [lbl, ic, badge] = meta[i.type] || meta.department;
                    const active = i.type === 'province' && st.selected.province && String(i.id) === String(st.selected.province.id);
                    const hint = i.type === 'department' ? 'بینین' : 'دواتر';
                    return `<button type="button" class="list-group-item list-group-item-action ${active ? 'active' : ''}" data-idx="${idx}">
                        <div class="d-flex justify-content-between gap-2">
                            <div class="text-end flex-grow-1">
                                <div class="fw-semibold"><i class="${ic} me-1"></i>${esc(i.name || '—')}</div>
                                ${i.name_en ? `<div class="small text-muted">${esc(i.name_en)}</div>` : ''}
                            </div>
                            <div class="text-start"><span class="badge ${badge} type-badge">${lbl}</span><div class="small text-muted mt-1">${hint}</div></div>
                        </div></button>`;
                }).join('');
            }

            function clearProvinceHighlight() {
                st.provinceLayers.forEach((l) => { l.setStyle(baseProvinceStyle()); l._highlighted = false; });
            }

            function selectProvinceVisual(layer) {
                clearProvinceHighlight();
                if (!layer) return;
                layer.setStyle({ color: '#1d4ed8', weight: 2.8, fillOpacity: .22, fillColor: '#2563eb' });
                layer._highlighted = true;
            }

            function renderMapItems(items, type) {
                const key = type === 'university' ? 'universities' : (type === 'college' ? 'colleges' : 'departments');
                const layer = st.layers[key];
                layer.clearLayers();
                items.forEach((x, index) => {
                    const markerColor = getMarkerColor(x, type, index);

                    if (x.lat && x.lng) {
                        L.marker([x.lat, x.lng], {
                                icon: markerIcon(type, markerColor)
                            })
                            .bindTooltip(x.name || '', { direction: 'top' })
                            .bindPopup(popupForItem(x, type, markerColor))
                            .addTo(layer);
                    }
                    if (x.geojson) {
                        L.geoJSON(x.geojson, { style: { color: markerColor, weight: 2, fillOpacity: .16, fillColor: markerColor } }).addTo(layer);
                    }
                });
                if (st.visible[key]) fit(layer);
            }

            async function loadProvinces() {
                setLoading(true);
                setError(null);
                try {
                    const gj = await getJSON(cfg.routes.provinces);
                    const all = Array.isArray(gj.features) ? gj.features : [];
                    let features = all;
                    if (cfg.scope && cfg.scope.is_restricted) {
                        const s = new Set((cfg.scope.allowed_province_names || []).map((n) => text(n)).filter(Boolean));
                        if (text(cfg.scope.primary_province_name || '')) s.add(text(cfg.scope.primary_province_name));
                        features = s.size ? features.filter((f) => s.has(text(f?.properties?.name))) : [];
                    }

                    st.layers.provinces.clearLayers();
                    st.provinceLayers.clear();
                    L.geoJSON({ type: 'FeatureCollection', features }, {
                        style: baseProvinceStyle,
                        onEachFeature: (f, l) => {
                            const id = String(f?.properties?.id ?? '');
                            if (id) st.provinceLayers.set(id, l);
                            l.bindTooltip(f?.properties?.name || 'پارێزگا', { sticky: true, direction: 'top' });
                            l.on('click', () => onProvince({ id: f?.properties?.id, name: f?.properties?.name, name_en: f?.properties?.name_en, type: 'province' }, l));
                            l.on('mouseover', () => { if (!l._highlighted) l.setStyle({ weight: 2.4, fillOpacity: .2 }); });
                            l.on('mouseout', () => { if (!l._highlighted) l.setStyle(baseProvinceStyle()); });
                        }
                    }).addTo(st.layers.provinces);

                    st.selected = { province: null, university: null, college: null };
                    st.layers.universities.clearLayers(); st.layers.colleges.clearLayers(); st.layers.departments.clearLayers();
                    st.provinceItems = features.map((f) => ({ id: f?.properties?.id, name: f?.properties?.name, name_en: f?.properties?.name_en, type: 'province' }));
                    breadcrumb(); setItems(st.provinceItems, 'پارێزگاکان'); fit(st.layers.provinces);

                    if (cfg.scope && cfg.scope.is_restricted && st.provinceItems.length) {
                        const p = st.provinceItems.find((x) => text(x.name) === text(cfg.scope.primary_province_name || '')) || st.provinceItems[0];
                        onProvince(p, st.provinceLayers.get(String(p.id)) || null);
                    }
                } catch (err) {
                    console.error(err);
                    st.provinceItems = [];
                    setItems([], 'پارێزگاکان');
                    setError('هەڵەیەک لە بارکردنی پارێزگاکان ڕوویدا.');
                } finally { setLoading(false); }
            }

            async function onProvince(item, layer) {
                if (!item || !item.id) return;
                st.selected.province = { id: item.id, name: item.name, name_en: item.name_en || null };
                st.selected.university = null; st.selected.college = null;
                selectProvinceVisual(layer || st.provinceLayers.get(String(item.id)) || null);
                st.layers.universities.clearLayers(); st.layers.colleges.clearLayers(); st.layers.departments.clearLayers();
                breadcrumb(); setLoading(true);
                setError(null);
                try {
                    const d = await getJSON(cfg.routes.universities(item.id));
                    const items = (d.items || []).map((x) => ({ ...x, type: 'university' }));
                    renderMapItems(items, 'university'); setItems(items, 'زانکۆکان');
                } catch (err) {
                    console.error(err);
                    setItems([], 'زانکۆکان');
                    setError('بارکردنی زانکۆکان سەرکەوتوو نەبوو.');
                } finally { setLoading(false); }
            }

            async function onUniversity(item) {
                st.selected.university = { id: item.id, name: item.name }; st.selected.college = null;
                st.layers.colleges.clearLayers(); st.layers.departments.clearLayers(); breadcrumb(); setLoading(true);
                setError(null);
                try {
                    const d = await getJSON(cfg.routes.colleges(item.id));
                    const items = (d.items || []).map((x) => ({ ...x, type: 'college' }));
                    renderMapItems(items, 'college'); setItems(items, 'کۆلێژەکان');
                } catch (err) {
                    console.error(err);
                    setItems([], 'کۆلێژەکان');
                    setError('بارکردنی کۆلێژەکان سەرکەوتوو نەبوو.');
                } finally { setLoading(false); }
            }

            async function onCollege(item) {
                st.selected.college = { id: item.id, name: item.name };
                st.layers.departments.clearLayers(); breadcrumb(); setLoading(true);
                setError(null);
                try {
                    const d = await getJSON(cfg.routes.departments(item.id));
                    const items = (d.items || []).map((x) => ({ ...x, department_type: x.type, type: 'department' }));
                    renderMapItems(items, 'department'); setItems(items, 'بەشەکان');
                } catch (err) {
                    console.error(err);
                    setItems([], 'بەشەکان');
                    setError('بارکردنی بەشەکان سەرکەوتوو نەبوو.');
                } finally { setLoading(false); }
            }

            function wire() {
                el.search.addEventListener('input', applySearch);
                el.list.addEventListener('click', (e) => {
                    const b = e.target.closest('[data-idx]'); if (!b) return;
                    const i = st.filtered[Number(b.dataset.idx)]; if (!i) return;
                    if (i.type === 'province') return onProvince(i, st.provinceLayers.get(String(i.id)) || null);
                    if (i.type === 'university') return onUniversity(i);
                    if (i.type === 'college') return onCollege(i);
                    if (i.type === 'department' && i.lat && i.lng) st.map.setView([i.lat, i.lng], 14, { animate: true });
                });
                el.breadcrumb.addEventListener('click', (e) => {
                    const a = e.target.closest('[data-crumb]'); if (!a) return; e.preventDefault();
                    const c = a.dataset.crumb;
                    if (c === 'provinces') return loadProvinces();
                    if (c === 'province' && st.selected.province) return onProvince({ ...st.selected.province, type: 'province' }, st.provinceLayers.get(String(st.selected.province.id)) || null);
                    if (c === 'university' && st.selected.university) return onUniversity({ ...st.selected.university, type: 'university' });
                    if (c === 'college' && st.selected.college) return onCollege({ ...st.selected.college, type: 'college' });
                });
                [['tUni', 'universities'], ['tCol', 'colleges'], ['tDep', 'departments']].forEach(([btn, key]) => {
                    el[btn].addEventListener('click', () => {
                        st.visible[key] = !st.visible[key];
                        el[btn].classList.toggle('active', st.visible[key]);
                        if (st.visible[key]) st.layers[key].addTo(st.map); else st.map.removeLayer(st.layers[key]);
                    });
                });
                el.fit.addEventListener('click', () => {
                    const g = L.featureGroup(); ['provinces', 'universities', 'colleges', 'departments', 'user'].forEach((k) => {
                        if (k === 'provinces' || k === 'user' || st.visible[k]) st.layers[k].eachLayer((l) => g.addLayer(l));
                    }); fit(g);
                });
                el.locate.addEventListener('click', () => {
                    if (!navigator.geolocation) return;
                    navigator.geolocation.getCurrentPosition((p) => {
                        st.layers.user.clearLayers();
                        const m = L.circleMarker([p.coords.latitude, p.coords.longitude], { radius: 7, color: '#dc2626', fillColor: '#ef4444', fillOpacity: .9, weight: 2 }).bindPopup('شوێنی ئێستای تۆ');
                        st.layers.user.addLayer(m); st.map.setView([p.coords.latitude, p.coords.longitude], 12, { animate: true }); m.openPopup();
                    });
                });
            }

            initMap();
            wire();
            loadProvinces();
        })();
    </script>
@endpush
