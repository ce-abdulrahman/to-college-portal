/* ============================
 * Dashboard Map V2 - Fixed Version
 * Compatible with Laravel Backend
 * ============================ */
// ✅ Guard: Run this script only on dashboard page
(function() {
  const pageAttr = document.body?.dataset?.page;
  const mapEl = document.getElementById('dashboard-map');

  // If not the dashboard page, stop here
  if (pageAttr !== 'sadm.dshbd' && !mapEl) {
    return;
  }

    class DashboardMapV2 {
        constructor() {
            // Get API base URL from Laravel
            const API_BASE = window.location.origin + '/api/v1';

            this.config = {
                initialView: {
                    center: [36.1911, 44.0091], // Kurdistan coordinates
                    zoom: 8
                },
                tileLayer: {
                    url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    attribution: '© OpenStreetMap contributors'
                },
                apiRoutes: {
                    provincesGeoJSON: `${API_BASE}/provinces/geojson`,
                    universitiesByProvince: (id) => `${API_BASE}/provinces/${id}/universities`,
                    collegesByUniversity: (id) => `${API_BASE}/universities/${id}/colleges`,
                    departmentsByCollege: (id) => `${API_BASE}/colleges/${id}/departments`
                }
            };

            this.currentLevel = 'provinces';
            this.currentData = {
                province: null,
                university: null,
                college: null
            };
            this.layers = {};
            this.markers = {};
            this.is3DMode = false;
            this.userLocation = null;

            this.init();
        }

        init() {
            this.initMap();
            this.initLayers();
            this.initControls();
            this.loadProvinces();
            this.setupEventListeners();
            this.startGPSMonitoring();
        }

        initMap() {
            this.map = L.map('map', {
                zoomControl: false,
                fadeAnimation: true,
                markerZoomAnimation: true
            }).setView(this.config.initialView.center, this.config.initialView.zoom);

            L.tileLayer(this.config.tileLayer.url, {
                maxZoom: 19,
                attribution: this.config.tileLayer.attribution
            }).addTo(this.map);

            L.control.zoom({
                position: 'topright'
            }).addTo(this.map);
        }

        initLayers() {
            this.layers = {
                provinces: L.featureGroup().addTo(this.map),   // هەمانەوە
                universities: L.featureGroup().addTo(this.map), // ⟵ layerGroup بوو
                colleges: L.featureGroup().addTo(this.map),     // ⟵ layerGroup بوو
                departments: L.featureGroup().addTo(this.map),  // ⟵ layerGroup بوو
                user: L.layerGroup().addTo(this.map),
                highlight: L.featureGroup().addTo(this.map)
            };
        }

        initControls() {
            this.add3DControl();
        }

        add3DControl() {
            const Control3D = L.Control.extend({
                onAdd: (map) => {
                    const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                    container.innerHTML = `
                        <a href="#" title="3D کردن/لابردن" style="
                            display: block;
                            width: 30px;
                            height: 30px;
                            line-height: 30px;
                            text-align: center;
                            text-decoration: none;
                            background: white;
                            border-radius: 4px;
                            font-weight: bold;
                        ">3D</a>
                    `;

                    L.DomEvent.on(container, 'click', (e) => {
                        L.DomEvent.stopPropagation(e);
                        L.DomEvent.preventDefault(e);
                        this.toggle3DEffect();
                    });

                    return container;
                }
            });

            this.map.addControl(new Control3D({ position: 'topright' }));
        }

        async loadProvinces() {
            try {
                this.showLoading('بارکردنی پارێزگاکان...');

                const response = await $.ajax({
                    url: this.config.apiRoutes.provincesGeoJSON,
                    method: 'GET',
                    dataType: 'json'
                });

                this.renderProvinces(response);
                this.hideLoading();
            } catch (error) {
                console.error('Error loading provinces:', error);
                this.showError('هەڵە له بارکردنی پارێزگاکان: ' + (error.responseJSON?.message || error.message));
            }
        }

        renderProvinces(geojson) {
            this.layers.provinces.clearLayers();

            L.geoJSON(geojson, {
                style: this.getProvinceStyle(),
                onEachFeature: (feature, layer) => {
                    const province = feature.properties || {};

                    // Enhanced tooltip with image if available
                    let tooltipContent = `
                        <div class="text-center" style="min-width: 150px;">
                            ${province.image ? `<img src="${province.image}" style="width: 100%; max-width: 120px; border-radius: 4px; margin-bottom: 8px;" alt="${province.name}">` : ''}
                            <strong style="font-size: 14px;">🏛️ ${province.name || 'پارێزگا'}</strong>
                            ${province.name_en ? `<br><small style="color: #64748b;">${province.name_en}</small>` : ''}
                        </div>
                    `;

                    layer.bindTooltip(tooltipContent, {
                        sticky: true,
                        className: 'polygon-tooltip'
                    });

                    layer.on('mouseover', (e) => {
                        this.onProvinceHover(province, layer, e);
                    });

                    layer.on('mouseout', (e) => {
                        this.onProvinceHoverOut(province, layer, e);
                    });

                    layer.on('click', (e) => {
                        this.onProvinceClick(province, layer, e);
                    });
                }
            }).addTo(this.layers.provinces);

            if (this.layers.provinces.getBounds().isValid()) {
                this.map.fitBounds(this.layers.provinces.getBounds().pad(0.1));
            }
        }

        getProvinceStyle(isHover = false, isSelected = false) {
            return {
                color: '#666',
                weight: isSelected ? 4 : (isHover ? 3 : 2),
                fillOpacity: isSelected ? 0.3 : (isHover ? 0.2 : 0.1),
                opacity: isSelected ? 0.8 : (isHover ? 0.6 : 0.4),
                fillColor: isSelected ? '#3b82f6' : (isHover ? '#60a5fa' : '#9ca3af')
            };
        }

        onProvinceHover(province, layer, event) {
            layer.setStyle(this.getProvinceStyle(true, false));
            layer.openTooltip();
        }

        onProvinceHoverOut(province, layer, event) {
            if (!layer._selected) {
                layer.setStyle(this.getProvinceStyle(false, false));
            }
            layer.closeTooltip();
        }

        async onProvinceClick(province, layer, event) {
            this.layers.provinces.eachLayer(l => {
                l.setStyle(this.getProvinceStyle(false, false));
                l._selected = false;
            });

            layer.setStyle(this.getProvinceStyle(false, true));
            layer._selected = true;

            this.currentData.province = province;
            this.currentLevel = 'universities';

            this.updateBreadcrumb([
                { name: '🏠 سەرەتا', level: 'provinces' },
                { name: province.name, level: 'universities' }
            ]);

            this.updateSidebarHeader(province);
            await this.loadUniversities(province.id);
        }

        async loadUniversities(provinceId) {
            try {
                this.showListLoading();

                const response = await $.ajax({
                    url: this.config.apiRoutes.universitiesByProvince(provinceId),
                    method: 'GET',
                    dataType: 'json'
                });

                const universities = response.items || [];

                this.renderUniversitiesList(universities);
                this.renderUniversitiesOnMap(universities);

                // Update stats with counts from response
                if (response.counts) {
                    this.updateAllStats(response.counts);
                } else {
                    this.updateStats('universities', universities.length);
                }

                this.hideListLoading();
            } catch (error) {
                console.error('Error loading universities:', error);
                this.showError('هەڵە له بارکردنی زانکۆکان: ' + (error.responseJSON?.message || error.message));
            }
        }

        renderUniversitiesList(universities) {
            const listElement = $('#institutionsList');
            const resultCount = $('#result-count');

            if (resultCount.length) {
                resultCount.text(`${universities.length} دۆزرایەوە`);
            }

            if (universities.length === 0) {
                listElement.html(`
                    <div class="empty-state" style="text-align: center; padding: 40px 20px;">
                        <i class="bi bi-building" style="font-size: 48px; color: #cbd5e1;"></i>
                        <p style="margin-top: 16px; color: #64748b;">هیچ زانکۆیەک نەدۆزرایەوە</p>
                    </div>
                `);
                return;
            }

            let html = `<h3 style="margin-bottom: 16px; font-size: 16px; font-weight: 600;">🎓 زانکۆکانی ${this.currentData.province.name}</h3>`;

            universities.forEach(uni => {
                html += `
                    <div class="institution-item" data-id="${uni.id}" data-type="uni" style="margin-bottom: 12px; padding: 12px; background: white; border-radius: 8px; border: 1px solid #e2e8f0; cursor: pointer;">
                        <div class="institution-header" style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                ${uni.image || uni.image_url ? `
                                    <img src="${uni.image_url || uni.image}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; margin-bottom: 8px;" alt="${uni.name}">
                                ` : ''}
                                <div class="institution-title" style="font-weight: 600; color: #0f172a; margin-bottom: 4px;">${uni.name}</div>
                                <div class="institution-subtitle" style="font-size: 12px; color: #64748b;">${uni.name_en || 'زانکۆ'}</div>
                            </div>
                            <div class="institution-actions" style="display: flex; gap: 4px;">
                                ${uni.lat && uni.lng ? `
                                    <button class="icon-btn" onclick="window.mapV2.focusOnItem('uni', ${uni.id}, ${uni.lat}, ${uni.lng})" title="نیشان بدە" style="width: 32px; height: 32px; border: none; background: #000; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-geo-alt"></i>
                                    </button>
                                ` : ''}
                                <button class="icon-btn" onclick="window.mapV2.loadColleges(${uni.id})" title="کۆلێژەکان" style="width: 32px; height: 32px; border: none; background: #3b82f6; color: white; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            listElement.html(html);
        }

        renderUniversitiesOnMap(unis) {
    this.layers.universities.clearLayers();
    let any = false;
    unis.forEach(u => {
        if (u.lat && u.lng) {
        const m = this.createMarker(u, 'uni').addTo(this.layers.universities);
        m.on('click', () => this.onMarkerClick(u, 'uni')); // کلیکی marker
        any = true;
        }
    });
    if (any) this.map.fitBounds(this.layers.universities.getBounds().pad(0.1));
    }


        createMarker(item, type) {
            const colors = {
                uni: ['#2563eb', '#1d4ed8'],
                col: ['#10b981', '#059669'],
                dep: ['#f59e0b', '#d97706']
            };

            const icons = {
                uni: '🎓',
                col: '🏢',
                dep: '📚'
            };

            const [color1, color2] = colors[type] || ['#6b7280', '#4b5563'];

            const icon = L.divIcon({
                className: `custom-marker ${type}-marker`,
                html: `
                    <div style="
                        background: linear-gradient(135deg, ${color1}, ${color2});
                        width: 32px;
                        height: 32px;
                        border-radius: 50% 50% 50% 0;
                        transform: rotate(-45deg);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                        position: relative;
                    ">
                        <div style="
                            transform: rotate(45deg);
                            color: white;
                            font-size: 14px;
                            font-weight: bold;
                        ">
                            ${icons[type]}
                        </div>
                    </div>
                `,
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            });

            const marker = L.marker([item.lat, item.lng], { icon });

            marker.bindPopup(this.createPopup(item, type));

            marker.on('click', () => {
                this.onMarkerClick(item, type);
            });

            return marker;
        }

        createPopup(item, type) {
            const titles = {
                uni: '🎓 زانکۆ',
                col: '🏢 کۆلێژ',
                dep: '📚 بەش'
            };

            return `
                <div class="popup-content" style="min-width: 250px; text-align: right !important">
                    ${item.image || item.image_url ? `
                        <img src="${item.image_url || item.image}" style="width: 100%; max-height: 120px; object-fit: cover; border-radius: 6px; margin-bottom: 12px;" alt="${item.name}">
                    ` : ''}
                    <h3 style="margin: 0 0 8px 0; color: #fff; font-size: 14px;">${titles[type]} ${item.name} | ${item.name_en} </h3>

                    ${type === 'dep' && item.province_name ? `
                        <div style="font-size: 12px; color: #ffff; margin: 4px 0;">
                            <i class="bi bi-pin-map"></i> ${item.province_name}
                        </div>
                    ` : ''}
                    ${type === 'dep' && item.university_name ? `
                        <div style="font-size: 12px; color: #fff; margin: 4px 0;">
                            <i class="bi bi-building"></i> ${item.university_name}
                        </div>
                    ` : ''}
                    ${type === 'dep' && item.college_name ? `
                        <div style="font-size: 12px; color: #fff; margin: 4px 0;">
                            <i class="bi bi-house"></i> ${item.college_name}
                        </div>
                    ` : ''}

                    <div style="font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 4px;">
                        <i class="bi bi-geo-alt"></i> ${item.name}
                    </div>

                    ${type === 'dep' && item.description ? `
                        <div style="font-size: 12px; color: #fff; margin: 4px 0;">
                            <i class="bi bi-info-circle"></i> ${item.description}
                        </div>
                    ` : ''}

                    ${type === 'dep' && (item.local_score || item.external_score) ? `
                        <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e2e8f0;">
                            ${item.local_score ? `<div style="font-size: 12px;"><strong>نمرەی ناوخۆیی:</strong> ${item.local_score}</div>` : ''}
                            ${item.external_score ? `<div style="font-size: 12px;"><strong>نمرەی دەرەکی:</strong> ${item.external_score}</div>` : ''}
                        </div>
                    ` : ''}
                    <div style="display: flex; gap: 8px; margin-top: 12px;">
                        <button class="popup-btn focus-btn" onclick="window.mapV2.focusOnItem('${type}', ${item.id}, ${item.lat}, ${item.lng})" style="flex: 1; padding: 8px 12px; border: none; background: #000; border-radius: 6px; cursor: pointer; font-size: 12px;">
                            <i class="bi bi-crosshair"></i> نیشان بدە
                        </button>
                        ${type === 'uni' ? `
                            <button class="popup-btn action-btn" onclick="window.mapV2.loadColleges(${item.id})" style="flex: 1; padding: 8px 12px; border: none; background: #3b82f6; color: white; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="bi bi-arrow-right"></i> کۆلێژەکان
                            </button>
                        ` : type === 'col' ? `
                            <button class="popup-btn action-btn" onclick="window.mapV2.loadDepartments(${item.id})" style="flex: 1; padding: 8px 12px; border: none; background: #10b981; color: white; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="bi bi-arrow-right"></i> بەشەکان
                            </button>
                        ` : ''}
                    </div>
                </div>
            `;
        }

        onMarkerClick(item, type) {
            this.showSelectedOnMap(item, type);
            // هەروەھا اگر پێویستە لە لیستدا colleges/… بۆ uni یەک داگرتن
            if (type === 'uni') this.loadColleges(item.id);
        }

        async loadColleges(universityId) {
            try {
                this.showListLoading();

                const response = await $.ajax({
                    url: this.config.apiRoutes.collegesByUniversity(universityId),
                    method: 'GET',
                    dataType: 'json'
                });

                this.currentData.university = response.university;
                this.currentLevel = 'colleges';

                this.updateBreadcrumb([
                    { name: '🏠 سەرەتا', level: 'provinces' },
                    { name: this.currentData.province.name, level: 'universities' },
                    { name: response.university.name, level: 'colleges' }
                ]);

                const colleges = response.items || [];
                this.renderCollegesList(colleges);
                this.renderCollegesOnMap(colleges);
                this.updateStats('colleges', colleges.length);
                this.hideListLoading();
            } catch (error) {
                console.error('Error loading colleges:', error);
                this.showError('هەڵە له بارکردنی کۆلێژەکان: ' + (error.responseJSON?.message || error.message));
            }
        }

        renderCollegesList(colleges) {
            const listElement = $('#institutionsList');

            if (colleges.length === 0) {
                listElement.html(`
                    <div class="empty-state" style="text-align: center; padding: 40px 20px;">
                        <i class="bi bi-house" style="font-size: 48px; color: #cbd5e1;"></i>
                        <p style="margin-top: 16px; color: #64748b;">هیچ کۆلێژێک نەدۆزرایەوە</p>
                    </div>
                `);
                return;
            }

            let html = `<h3 style="margin-bottom: 16px; font-size: 16px; font-weight: 600;">🏢 کۆلێژەکانی ${this.currentData.university.name}</h3>`;

            colleges.forEach(college => {
                html += `
                    <div class="institution-item" data-id="${college.id}" data-type="col" style="margin-bottom: 12px; padding: 12px; background: white; border-radius: 8px; border: 1px solid #e2e8f0; cursor: pointer;">
                        <div class="institution-header" style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                ${college.image || college.image_url ? `
                                    <img src="${college.image_url || college.image}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; margin-bottom: 8px;" alt="${college.name}">
                                ` : ''}
                                <div class="institution-title" style="font-weight: 600; color: #0f172a; margin-bottom: 4px;">${college.name}</div>
                                <div class="institution-subtitle" style="font-size: 12px; color: #64748b;">${college.name_en || 'کۆلێژ'}</div>
                            </div>
                            <div class="institution-actions" style="display: flex; gap: 4px;">
                                ${college.lat && college.lng ? `
                                    <button class="icon-btn" onclick="window.mapV2.focusOnItem('col', ${college.id}, ${college.lat}, ${college.lng})" title="نیشان بدە" style="width: 32px; height: 32px; border: none; background: #000; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-geo-alt"></i>
                                    </button>
                                ` : ''}
                                <button class="icon-btn" onclick="window.mapV2.loadDepartments(${college.id})" title="بەشەکان" style="width: 32px; height: 32px; border: none; background: #10b981; color: white; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            listElement.html(html);
        }

        renderCollegesOnMap(colleges) {
            this.layers.colleges.clearLayers();
            this.layers.departments.clearLayers();

            let any = false;
            colleges.forEach(col => {
                any = this.addItemToLayer(col, 'col', this.layers.colleges) || any;
            });

            if (any) {
                this.map.fitBounds(this.layers.colleges.getBounds().pad(0.1));
            }
        }

        addItemToLayer(item, type, layer) {
    const colorMap = {
        uni: { color: '#2563eb', fillColor: '#3b82f6' },
        col: { color: '#10b981', fillColor: '#34d399' },
        dep: { color: '#f59e0b', fillColor: '#fbbf24' }
    };
    const style = (colorMap[type] || { color: '#6b7280', fillColor: '#9ca3af' });

    // اگر geojson هەیە
    if (item.geojson) {
        let gj;
        try {
        gj = (typeof item.geojson === 'string') ? JSON.parse(item.geojson) : item.geojson;
        } catch (e) {
        console.warn('Invalid geojson for', item, e);
        }
        if (gj) {
        const gjLayer = L.geoJSON(gj, {
            style: {
            color: style.color,
            weight: 2,
            fillColor: style.fillColor,
            fillOpacity: 0.15
            },
            pointToLayer: (_feature, latlng) => {
            // ئەگەر geojson = Point بوو، marker دروست بکە
            const markerItem = { ...item, lat: latlng.lat, lng: latlng.lng };
            return this.createMarker(markerItem, type);
            },
            onEachFeature: (_feature, l) => {
            // بۆ Polygon/MultiPolygon popup و کلیک
            if (!l.getPopup || !l.getPopup()) {
                l.bindPopup(this.createPopup(item, type));
            }
            l.on('click', () => this.onMarkerClick(item, type));
            }
        }).addTo(layer);
        this.markers[`${type}_${item.id}`] = gjLayer;
        return true;
        }
    }

    // fallback بۆ lat/lng
    if (item.lat && item.lng) {
        const marker = this.createMarker(item, type);
        marker.addTo(layer);
        this.markers[`${type}_${item.id}`] = marker;
        return true;
    }

    return false;
    }


        async loadDepartments(collegeId) {
            try {
                this.showListLoading();

                const response = await $.ajax({
                    url: this.config.apiRoutes.departmentsByCollege(collegeId),
                    method: 'GET',
                    dataType: 'json'
                });

                this.currentData.college = response.college;
                this.currentLevel = 'departments';

                this.updateBreadcrumb([
                    { name: '🏠 سەرەتا', level: 'provinces' },
                    { name: this.currentData.province.name, level: 'universities' },
                    { name: this.currentData.university.name, level: 'colleges' },
                    { name: response.college.name, level: 'departments' }
                ]);

                const departments = response.items || [];
                this.renderDepartmentsList(departments);
                this.renderDepartmentsOnMap(departments);
                this.updateStats('departments', departments.length);
                this.hideListLoading();
            } catch (error) {
                console.error('Error loading departments:', error);
                this.showError('هەڵە له بارکردنی بەشەکان: ' + (error.responseJSON?.message || error.message));
            }
        }

        renderDepartmentsList(departments) {
            const listElement = $('#institutionsList');

            if (departments.length === 0) {
                listElement.html(`
                    <div class="empty-state" style="text-align: center; padding: 40px 20px;">
                        <i class="bi bi-diagram-3" style="font-size: 48px; color: #cbd5e1;"></i>
                        <p style="margin-top: 16px; color: #64748b;">هیچ بەشێک نەدۆزرایەوە</p>
                    </div>
                `);
                return;
            }

            let html = `<h3 style="margin-bottom: 16px; font-size: 16px; font-weight: 600;">📚 بەشەکانی ${this.currentData.college.name}</h3>`;

            departments.forEach(dept => {
                html += `
                    <div class="institution-item" data-id="${dept.id}" data-type="dep" style="margin-bottom: 12px; padding: 12px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div class="institution-header" style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">

                                <div class="institution-title" style="font-weight: 600; color: #000; margin-bottom: 4px;">${dept.name}</div>

                            </div>
                            <div class="institution-actions" style="display: flex; gap: 4px;">
                                ${dept.lat && dept.lng ? `
                                    <button class="icon-btn" onclick="window.mapV2.focusOnItem('dep', ${dept.id}, ${dept.lat}, ${dept.lng})" title="نیشان بدە" style="width: 32px; height: 32px; border: none; background: #000; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-geo-alt"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });

            listElement.html(html);
        }

        renderDepartmentsOnMap(departments) {
            this.layers.departments.clearLayers();

            departments.forEach(dept => {
                if (dept.lat && dept.lng) {
                    const marker = this.createMarker(dept, 'dep');
                    marker.addTo(this.layers.departments);
                    this.markers[`dep_${dept.id}`] = marker;
                }
            });

            if (this.layers.departments.getLayers().length > 0) {
                this.map.fitBounds(this.layers.departments.getBounds().pad(0.1));
            }
        }

        focusOnItem(type, id, lat, lng) {
            const marker = this.markers[`${type}_${id}`];
            if (marker) {
                this.map.setView([lat, lng], 16, {
                    animate: true,
                    duration: 1
                });
                setTimeout(() => {
                    marker.openPopup();
                }, 500);
            } else {
                this.map.setView([lat, lng], 16);
            }
        }

        updateBreadcrumb(items) {
            const breadcrumb = $('#breadcrumb');
            if (!breadcrumb.length) return;

            let html = '';

            items.forEach((item, index) => {
                const isLast = index === items.length - 1;
                html += `
                    <div class="breadcrumb-item ${isLast ? 'active' : ''}" style="display: inline-flex; align-items: center;">
                        ${isLast ?
                            `<span style="color: #0f172a; font-weight: 600;">${item.name}</span>` :
                            `<a href="#" onclick="window.mapV2.navigateToLevel('${item.level}')" style="color: #3b82f6; text-decoration: none;">${item.name}</a>`
                        }
                    </div>
                    ${!isLast ? '<i class="bi bi-chevron-left" style="font-size: 12px; color: #64748b; margin: 0 8px;"></i>' : ''}
                `;
            });

            breadcrumb.html(html);
        }

        navigateToLevel(level) {
            switch (level) {
                case 'provinces':
                    this.currentLevel = 'provinces';
                    this.currentData = { province: null, university: null, college: null };
                    this.updateBreadcrumb([{ name: '🏠 سەرەتا', level: 'provinces' }]);
                    this.clearAllLayers();
                    this.loadProvinces();
                    $('#institutionsList').html('');
                    break;

                case 'universities':
                    if (this.currentData.province) {
                        this.currentLevel = 'universities';
                        this.currentData.university = null;
                        this.currentData.college = null;
                        this.updateBreadcrumb([
                            { name: '🏠 سەرەتا', level: 'provinces' },
                            { name: this.currentData.province.name, level: 'universities' }
                        ]);
                        this.layers.colleges.clearLayers();
                        this.layers.departments.clearLayers();
                        this.loadUniversities(this.currentData.province.id);
                    }
                    break;

                case 'colleges':
                    if (this.currentData.university) {
                        this.currentLevel = 'colleges';
                        this.currentData.college = null;
                        this.updateBreadcrumb([
                            { name: '🏠 سەرەتا', level: 'provinces' },
                            { name: this.currentData.province.name, level: 'universities' },
                            { name: this.currentData.university.name, level: 'colleges' }
                        ]);
                        this.layers.departments.clearLayers();
                        this.loadColleges(this.currentData.university.id);
                    }
                    break;
            }
        }

        clearAllLayers() {
            Object.values(this.layers).forEach(layer => {
                if (layer.clearLayers) layer.clearLayers();
            });
            this.markers = {};
        }

        updateSidebarHeader(province) {
            const header = $('.sidebar-header h1');
            const subtitle = $('.sidebar-header p');

            if (header.length) {
                header.text(`🗺️ ${province.name}`);
            }

            if (subtitle.length) {
                subtitle.text(`گەڕان بە ناو زانکۆ، کۆلێژ و بەشەکانی ${province.name}`);
            }
        }

        updateStats(type, count) {
            const counters = {
                universities: '#uniCount',
                colleges: '#colCount',
                departments: '#depCount'
            };

            const counterElement = $(counters[type]);
            if (counterElement.length) {
                this.animateCounter(counterElement, count);
            }
        }

        updateAllStats(counts) {
            if (counts.universities !== undefined) {
                this.animateCounter($('#uniCount'), counts.universities);
            }
            if (counts.colleges !== undefined) {
                this.animateCounter($('#colCount'), counts.colleges);
            }
            if (counts.departments !== undefined) {
                this.animateCounter($('#depCount'), counts.departments);
            }
        }

        showSelectedOnMap(item, type, styleOverride = null) {
    // پاککردنی highlight
    this.layers.highlight.clearLayers();

    // ڕەنگ/ستایل
    const baseColors = {
        uni: { color: '#2563eb', fillColor: '#3b82f6' },
        col: { color: '#10b981', fillColor: '#34d399' },
        dep: { color: '#f59e0b', fillColor: '#fbbf24' }
    };
    const style = styleOverride || baseColors[type] || { color: '#6b7280', fillColor: '#9ca3af' };

    // GeoJSON اگر بوو —> لە highlight بنووسە
    if (item.geojson) {
        let gj = item.geojson;
        if (typeof gj === 'string') {
        try { gj = JSON.parse(gj); } catch (e) { gj = null; }
        }
        if (gj) {
        const layer = L.geoJSON(gj, {
            style: { color: style.color, weight: 3, fillColor: style.fillColor, fillOpacity: 0.2 },
            pointToLayer: (_f, latlng) => {
            const markerItem = { ...item, lat: latlng.lat, lng: latlng.lng };
            return this.createMarker(markerItem, type);
            },
            onEachFeature: (_f, l) => {
            if (!l.getPopup || !l.getPopup()) {
                l.bindPopup(this.createPopup(item, type));
            }
            }
        }).addTo(this.layers.highlight);

        // فیت بۆ ئەو یەکە
        const b = layer.getBounds ? layer.getBounds() : null;
        if (b && b.isValid && b.isValid()) {
            this.map.fitBounds(b.pad(0.1));
        }
        return;
        }
    }

    // fallback بۆ lat/lng
    if (item.lat && item.lng) {
        const marker = this.createMarker(item, type).addTo(this.layers.highlight);
        if (marker.getLatLng) {
        this.map.setView(marker.getLatLng(), Math.max(this.map.getZoom(), 12));
        }
    }
    }


        animateCounter(element, target) {
            if (!element.length) return;

            const current = parseInt(element.text()) || 0;
            const duration = 1000;
            const startTime = performance.now();

            const updateCounter = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);

                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const currentValue = Math.floor(current + (target - current) * easeOutQuart);

                element.text(currentValue);

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    element.text(target);
                }
            };

            requestAnimationFrame(updateCounter);
        }

        toggle3DEffect() {
            this.is3DMode = !this.is3DMode;

            if (this.is3DMode) {
                $('body').addClass('perspective-3d');
                $('#map').addClass('map-3d');
                this.showNotification('3D چالاک کرا', 'success');
            } else {
                $('body').removeClass('perspective-3d');
                $('#map').removeClass('map-3d');
                this.showNotification('3D ناچالاک کرا', 'info');
            }
        }

        startGPSMonitoring() {
            if ('geolocation' in navigator) {
                const indicator = $('#gpsIndicator');

                navigator.geolocation.watchPosition(
                    (position) => {
                        this.userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        if (indicator.length) {
                            indicator.addClass('active').find('span').text('GPS چالاکە');
                        }

                        this.updateUserLocationMarker();
                    },
                    (error) => {
                        if (indicator.length) {
                            indicator.removeClass('active').find('span').text('GPS چالاک نییە');
                        }
                        this.userLocation = null;
                        this.layers.user.clearLayers();
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            }
        }

        updateUserLocationMarker() {
            if (!this.userLocation) return;

            this.layers.user.clearLayers();

            const userIcon = L.divIcon({
                className: 'user-location-marker',
                html: `
                    <div style="
                        background: linear-gradient(135deg, #ef4444, #dc2626);
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        border: 3px solid white;
                        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
                        animation: pulse 2s infinite;
                    "></div>
                `,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            L.marker([this.userLocation.lat, this.userLocation.lng], { icon: userIcon })
                .addTo(this.layers.user)
                .bindPopup('<strong>📍 شوێنی ئێستا</strong><br>ئەرەیە!');
        }

        setupEventListeners() {
            // Locate user button
            $('#locateUser').on('click', () => {
                this.locateUser();
            });

            // Reset view button
            $('#resetView').on('click', () => {
                this.resetView();
            });

            // Zoom controls
            $('#zoomIn').on('click', () => {
                this.map.zoomIn();
            });

            $('#zoomOut').on('click', () => {
                this.map.zoomOut();
            });

            // Keyboard shortcuts
            $(document).on('keydown', (e) => {
                if (e.ctrlKey || e.metaKey) {
                    switch (e.key) {
                        case 'r':
                            e.preventDefault();
                            this.resetView();
                            break;
                        case '3':
                            e.preventDefault();
                            this.toggle3DEffect();
                            break;
                    }
                }

                // ESC key to go back
                if (e.key === 'Escape') {
                    if (this.currentLevel === 'departments' && this.currentData.college) {
                        this.navigateToLevel('colleges');
                    } else if (this.currentLevel === 'colleges' && this.currentData.university) {
                        this.navigateToLevel('universities');
                    } else if (this.currentLevel === 'universities' && this.currentData.province) {
                        this.navigateToLevel('provinces');
                    }
                }
            });
        }

        locateUser() {
            if (this.userLocation) {
                this.map.setView([this.userLocation.lat, this.userLocation.lng], 15);
                this.showNotification('ڕووکاری شوێنی ئێستا', 'success');
            } else {
                this.showNotification('شوێنی ئێستا بەردەست نییە', 'error');
            }
        }

        resetView() {
            this.map.setView(this.config.initialView.center, this.config.initialView.zoom);
            this.showNotification('ڕووکار ڕیسێت کرا', 'info');
        }

        showLoading(message = 'بارکردن...') {
            const loadingOverlay = $('#loadingOverlay');
            if (loadingOverlay.length) {
                loadingOverlay.find('.loading-text').text(message);
                loadingOverlay.fadeIn(200);
            }
        }

        hideLoading() {
            const loadingOverlay = $('#loadingOverlay');
            if (loadingOverlay.length) {
                loadingOverlay.fadeOut(200);
            }
        }

        showListLoading() {
            $('#institutionsList').html(`
                <div class="empty-state" style="text-align: center; padding: 40px 20px;">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">بارکردن...</span>
                    </div>
                    <p style="margin-top: 12px; color: #64748b;">بارکردن...</p>
                </div>
            `);
        }

        hideListLoading() {
            // Loading state is cleared when content is rendered
        }

        showNotification(message, type = 'info') {
            // Simple console notification - you can enhance this with toast notifications
            const icons = {
                success: '✅',
                error: '❌',
                info: 'ℹ️',
                warning: '⚠️'
            };

            console.log(`${icons[type] || 'ℹ️'} ${message}`);

            // Optional: Add toast notification if you have a toast library
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            }
        }

        showError(message) {
            this.showNotification(message, 'error');

            // Show error in the list area too
            $('#institutionsList').html(`
                <div class="empty-state" style="text-align: center; padding: 40px 20px;">
                    <i class="bi bi-exclamation-triangle" style="font-size: 48px; color: #ef4444;"></i>
                    <p style="margin-top: 16px; color: #ef4444; font-weight: 600;">هەڵە ڕوویدا</p>
                    <p style="margin-top: 8px; color: #64748b; font-size: 14px;">${message}</p>
                    <button onclick="location.reload()" style="margin-top: 16px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer;">
                        <i class="bi bi-arrow-clockwise"></i> هەوڵبدەرەوە
                    </button>
                </div>
            `);
        }
    }

    // Initialize the dashboard when document is ready
    $(document).ready(function () {
        // Make sure Leaflet is loaded
        if (typeof L === 'undefined') {
            console.error('Leaflet library is not loaded!');
            return;
        }

        // Make sure jQuery is loaded
        if (typeof $ === 'undefined') {
            console.error('jQuery library is not loaded!');
            return;
        }

        // Initialize the map
        window.mapV2 = new DashboardMapV2();

    });

})(); // ✅ End of dashboard page guard
