@extends('website.web.admin.layouts.app')

@section('title','Geo Education Dashboard')

@section('content')
<div class="container-fluid py-4">
  <!-- Page Title & Breadcrumb -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="page-title-box d-flex align-items-center justify-content-between">
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
            <li class="breadcrumb-item active">نەخشە (GIS)</li>
          </ol>
        </div>
        <h4 class="page-title">
          <i class="fas fa-map-marked-alt me-2"></i>
          نەخشەی قوتابی
        </h4>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card glass border-0 shadow-sm fade-in">
        <div class="card-body p-0">
          <div id="map" style="height: 78vh; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb"></div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card glass border-0 shadow-sm fade-in">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
              <div class="text-muted small">Current Selection</div>
              <h5 id="current-title" class="mb-0">—</h5>
            </div>
            <div class="btn-group btn-group-sm">
              <button id="btn-home" class="btn btn-outline-secondary" title="Home"><i class="bi bi-house"></i></button>
              <button id="btn-fit" class="btn btn-outline-secondary" title="Fit visible"><i class="bi bi-aspect-ratio"></i></button>
              <button id="btn-locate" class="btn btn-outline-secondary" title="My location"><i class="bi bi-geo-alt"></i></button>
              <a href="{{ route('student.departments.selection') }}" class="btn btn-outline-primary" title="Department Selection">
                <i class="bi bi-diagram-3"></i>
              </a>
            </div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-4">
              <button id="btn-toggle-uni" class="btn btn-outline-primary w-100 btn-sm active"><i class="bi bi-building"></i> Uni</button>
            </div>
            <div class="col-4">
              <button id="btn-toggle-col" class="btn btn-outline-success w-100 btn-sm"><i class="bi bi-house"></i> Col</button>
            </div>
            <div class="col-4">
              <button id="btn-toggle-dep" class="btn btn-outline-warning w-100 btn-sm"><i class="bi bi-diagram-3"></i> Dep</button>
            </div>
          </div>

          <nav aria-label="breadcrumb">
            <ol id="breadcrumb-list" class="breadcrumb mb-2">
              <li class="breadcrumb-item active">Provinces</li>
            </ol>
          </nav>

          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 id="list-title" class="mb-0">Select a Province</h6>
            <span id="result-count" class="text-muted small">0 found</span>
          </div>

          <div id="loading-indicator" class="text-center py-4" style="display:none">
            <div class="spinner-border text-primary" role="status" style="width:2rem;height:2rem"></div>
            <div class="mt-2 text-muted small">Loading...</div>
          </div>

          <div class="table-responsive">
            <table id="inst-table" class="table table-sm table-hover" style="width:100%">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Type</th>
                  <th style="width:70px">Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
// Define MAP_CONFIG in the Blade template
const MAP_CONFIG = {
    initialView: {
        center: [33.9391, 67.7100], // Afghanistan center
        zoom: 6
    },
    tileLayer: {
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    },
    provincesUrl: '{{ route("admin.api.provinces.geojson") }}',
    apiRoutes: {
        universitiesByProvince: (id) => `{{ url('api/v1/provinces') }}/${id}/universities`,
        collegesByUniversity: (id) => `{{ url('api/v1/universities') }}/${id}/colleges`,
        departmentsByCollege: (id) => `{{ url('api/v1/colleges') }}/${id}/departments`
    }
};

class MapDashboard {
    constructor() {
        this.map = null;
        this.provincesLayer = null;
        this.dataTable = null;
        this.layers = {
            provinces: L.featureGroup(),
            universities: L.featureGroup(),
            colleges: L.featureGroup(),
            departments: L.featureGroup(),
            user: L.layerGroup()
        };
        this.currentSelection = {
            type: 'provinces',
            id: null,
            name: null,
            parent: null
        };
        this.visibleLayers = {
            universities: true,
            colleges: true,
            departments: true
        };
        this.init();
    }

    init() {
        this.initMap();
        this.initDataTable();
        this.initEventListeners();
        this.loadProvinces();
    }

    initMap() {
        this.map = L.map('map', {
            zoomControl: false,
            fadeAnimation: true
        }).setView(
            MAP_CONFIG.initialView.center,
            MAP_CONFIG.initialView.zoom
        );

        L.tileLayer(MAP_CONFIG.tileLayer.url, {
            attribution: MAP_CONFIG.tileLayer.attribution,
            maxZoom: 19
        }).addTo(this.map);

        L.control.zoom({ position: 'topright' }).addTo(this.map);

        // Add all layers to map
        Object.values(this.layers).forEach(layer => layer.addTo(this.map));
    }

    initDataTable() {
        this.dataTable = $('#inst-table').DataTable({
            paging: true,
            pageLength: 10,
            searching: true,
            info: true,
            ordering: true,
            language: {
                emptyTable: 'No data available',
                zeroRecords: 'No matching records found'
            },
            columns: [
                {
                    data: 'name',
                    render: function(data, type, row) {
                        const icon = row.type === 'university' ? 'bi-building' :
                                   row.type === 'college' ? 'bi-house' :
                                   'bi-diagram-3';
                        return `<i class="bi ${icon} me-2"></i>${data || '—'}`;
                    }
                },
                {
                    data: 'type',
                    render: function(data) {
                        return data ? data.charAt(0).toUpperCase() + data.slice(1) : '';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: (data, type, row) => {
                        if (row.type === 'department') return '';

                        const nextType = row.type === 'university' ? 'colleges' : 'departments';
                        const buttonText = row.type === 'university' ? 'View Colleges' : 'View Departments';

                        return `<button class="btn btn-sm btn-outline-primary btn-view"
                                data-id="${row.id}" data-type="${row.type}">
                                ${buttonText}
                                </button>`;
                    }
                }
            ]
        });

        $('#inst-table tbody').on('click', '.btn-view', (e) => {
            const btn = $(e.currentTarget);
            const id = btn.data('id');
            const type = btn.data('type');
            const rowData = this.dataTable.row(btn.closest('tr')).data();
            this.handleViewClick(rowData, type);
        });
    }

    async loadProvinces() {
        try {
            const response = await fetch(MAP_CONFIG.provincesUrl);
            if (!response.ok) throw new Error('Network response was not ok');

            const geojson = await response.json();
            this.renderProvinces(geojson);
        } catch (error) {
            console.error('Failed to load provinces:', error);
            this.showError('Failed to load provinces');
        }
    }

    renderProvinces(geojson) {
        this.layers.provinces.clearLayers();

        L.geoJSON(geojson, {
            style: {
                color: '#666',
                weight: 2,
                fillOpacity: 0.1,
                fillColor: '#3b82f6'
            },
            onEachFeature: (feature, layer) => {
                const props = feature.properties || {};
                layer.bindTooltip(props.name || 'Province', {
                    sticky: true,
                    direction: 'top'
                });

                layer.on({
                    click: () => this.handleProvinceClick(feature, layer),
                    mouseover: () => layer.setStyle({
                        weight: 3,
                        fillOpacity: 0.2,
                        color: '#2563eb'
                    }),
                    mouseout: () => {
                        if (!layer._highlighted) {
                            layer.setStyle({
                                weight: 2,
                                fillOpacity: 0.1,
                                color: '#666'
                            });
                        }
                    }
                });
            }
        }).addTo(this.layers.provinces);

        this.fitLayerBounds(this.layers.provinces);
    }

    async handleProvinceClick(feature, layer) {
        this.clearHighlights();
        layer.setStyle({
            weight: 4,
            color: '#3b82f6',
            fillOpacity: 0.3,
            fillColor: '#3b82f6'
        });
        layer._highlighted = true;

        this.currentSelection = {
            type: 'province',
            id: feature.properties.id,
            name: feature.properties.name,
            parent: null
        };

        this.updateUI();
        await this.loadUniversities(feature.properties.id);
    }

    async loadUniversities(provinceId) {
        this.showLoading();
        try {
            const url = MAP_CONFIG.apiRoutes.universitiesByProvince(provinceId);
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();

            this.renderInstitutions(data.items || [], 'university');
            this.renderMapMarkers(data.items || [], 'university');

            this.updateBreadcrumb([
                { name: 'Provinces', type: 'provinces' },
                { name: this.currentSelection.name, type: 'province' }
            ]);
        } catch (error) {
            console.error('Failed to load universities:', error);
            this.showError('Failed to load universities');
        } finally {
            this.hideLoading();
        }
    }

    async loadColleges(universityId) {
        this.showLoading();
        try {
            const url = MAP_CONFIG.apiRoutes.collegesByUniversity(universityId);
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();

            this.renderInstitutions(data.items || [], 'college');
            this.renderMapMarkers(data.items || [], 'college');

            this.updateBreadcrumb([
                { name: 'Provinces', type: 'provinces' },
                { name: this.currentSelection.parent.name, type: 'province' },
                { name: this.currentSelection.name, type: 'university' }
            ]);
        } catch (error) {
            console.error('Failed to load colleges:', error);
            this.showError('Failed to load colleges');
        } finally {
            this.hideLoading();
        }
    }

    async loadDepartments(collegeId) {
        this.showLoading();
        try {
            const url = MAP_CONFIG.apiRoutes.departmentsByCollege(collegeId);
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();

            this.renderInstitutions(data.items || [], 'department');
            this.renderMapMarkers(data.items || [], 'department');

            this.updateBreadcrumb([
                { name: 'Provinces', type: 'provinces' },
                { name: this.currentSelection.parent.parent.name, type: 'province' },
                { name: this.currentSelection.parent.name, type: 'university' },
                { name: this.currentSelection.name, type: 'college' }
            ]);
        } catch (error) {
            console.error('Failed to load departments:', error);
            this.showError('Failed to load departments');
        } finally {
            this.hideLoading();
        }
    }

    renderInstitutions(data, type) {
        const formattedData = data.map(item => ({
            ...item,
            type: type
        }));

        this.dataTable.clear().rows.add(formattedData).draw();
        $('#result-count').text(`${data.length} ${type.charAt(0).toUpperCase() + type.slice(1)}s found`);
        $('#list-title').text(`${type.charAt(0).toUpperCase() + type.slice(1)}s`);
    }

    renderMapMarkers(items, type) {
        const layerKey = type === 'university' ? 'universities' : type === 'college' ? 'colleges' : 'departments';
        const layer = this.layers[layerKey];
        layer.clearLayers();

        items.forEach(item => {
            if (item.lat && item.lng) {
                const color = this.getColorForType(type);
                const marker = L.circleMarker([item.lat, item.lng], {
                    radius: 8,
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.85,
                    weight: 2
                }).bindTooltip(item.name || '', { direction: 'top' })
                  .bindPopup(this.createPopup(item, type));

                marker.on('click', () => {
                    this.map.setView([item.lat, item.lng], 15);
                    marker.openPopup();
                });

                layer.addLayer(marker);
            }

            if (item.geojson) {
                const polygon = L.geoJSON(item.geojson, {
                    style: {
                        color: this.getColorForType(type),
                        weight: 2,
                        fillOpacity: 0.2,
                        fillColor: this.getColorForType(type)
                    }
                }).bindPopup(this.createPopup(item, type));

                layer.addLayer(polygon);
            }
        });

        if (this.visibleLayers[layerKey] && layer.getLayers().length > 0) {
            this.fitLayerBounds(layer);
        }
    }

    createPopup(item, type) {
        const typeName = type === 'university' ? 'University' :
                        type === 'college' ? 'College' : 'Department';

        return `<div class="p-2">
            <h6 class="mb-1">${item.name || typeName}</h6>
            ${item.name_en ? `<p class="text-muted small mb-1">${item.name_en}</p>` : ''}
            ${item.lat && item.lng ?
              `<p class="text-muted small mb-0"><i class="bi bi-geo-alt"></i> ${item.lat.toFixed(4)}, ${item.lng.toFixed(4)}</p>` : ''}
        </div>`;
    }

    getColorForType(type) {
        const colors = {
            university: '#2563eb',
            college: '#10b981',
            department: '#f59e0b'
        };
        return colors[type] || '#666';
    }

    handleViewClick(rowData, type) {
        if (type === 'university') {
            this.currentSelection = {
                type: 'university',
                id: rowData.id,
                name: rowData.name,
                parent: this.currentSelection
            };
            this.loadColleges(rowData.id);
        } else if (type === 'college') {
            this.currentSelection = {
                type: 'college',
                id: rowData.id,
                name: rowData.name,
                parent: this.currentSelection
            };
            this.loadDepartments(rowData.id);
        }
    }

    updateUI() {
        $('#current-title').text(this.currentSelection.name || '—');
    }

    updateBreadcrumb(items) {
        const $bc = $('#breadcrumb-list').empty();

        items.forEach((item, index) => {
            const isLast = index === items.length - 1;
            const $li = $(`<li class="breadcrumb-item ${isLast ? 'active' : ''}"></li>`);

            if (isLast) {
                $li.text(item.name);
            } else {
                const $a = $(`<a href="#" class="text-decoration-none" data-type="${item.type}">${item.name}</a>`);
                $a.on('click', (e) => {
                    e.preventDefault();
                    this.handleBreadcrumbClick(item.type);
                });
                $li.append($a);
            }

            $bc.append($li);
        });
    }

    handleBreadcrumbClick(type) {
        if (type === 'provinces') {
            this.resetToProvinces();
        } else if (type === 'province' && this.currentSelection.parent) {
            this.currentSelection = this.currentSelection.parent;
            this.loadUniversities(this.currentSelection.id);
        }
    }

    resetToProvinces() {
        this.currentSelection = { type: 'provinces', id: null, name: null, parent: null };
        this.updateUI();
        this.dataTable.clear().draw();
        $('#result-count').text('0 found');
        $('#list-title').text('Select a Province');

        this.clearHighlights();
        this.clearAllMarkers();
        this.updateBreadcrumb([{ name: 'Provinces', type: 'provinces' }]);
        this.fitLayerBounds(this.layers.provinces);
    }

    clearHighlights() {
        this.layers.provinces.eachLayer(layer => {
            layer.setStyle({
                weight: 2,
                fillOpacity: 0.1,
                color: '#666'
            });
            layer._highlighted = false;
        });
    }

    clearAllMarkers() {
        this.layers.universities.clearLayers();
        this.layers.colleges.clearLayers();
        this.layers.departments.clearLayers();
    }

    initEventListeners() {
        $('#btn-home').click(() => {
            this.map.setView(MAP_CONFIG.initialView.center, MAP_CONFIG.initialView.zoom);
        });

        $('#btn-fit').click(() => {
            const visibleLayers = [];

            if (this.visibleLayers.universities) visibleLayers.push(this.layers.universities);
            if (this.visibleLayers.colleges) visibleLayers.push(this.layers.colleges);
            if (this.visibleLayers.departments) visibleLayers.push(this.layers.departments);

            const bounds = L.featureGroup(visibleLayers).getBounds();
            if (bounds.isValid()) {
                this.map.fitBounds(bounds.pad(0.1));
            } else {
                // If no visible layers, fit to provinces
                this.fitLayerBounds(this.layers.provinces);
            }
        });

        $('#btn-locate').click(() => {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const { latitude, longitude, accuracy } = pos.coords;
                    this.layers.user.clearLayers();

                    L.circle([latitude, longitude], {
                        radius: accuracy,
                        color: '#ef4444',
                        fillColor: '#ef4444',
                        fillOpacity: 0.1,
                        weight: 1
                    }).addTo(this.layers.user);

                    L.marker([latitude, longitude], {
                        icon: L.divIcon({
                            className: 'user-location-marker',
                            html: '<i class="bi bi-person-fill text-danger fs-5"></i>',
                            iconSize: [24, 24]
                        })
                    }).addTo(this.layers.user)
                      .bindTooltip('Your location', { direction: 'top' });

                    this.map.setView([latitude, longitude], 14);
                },
                (err) => {
                    console.error('Geolocation error:', err);
                    alert('Unable to retrieve your location');
                }
            );
        });

        $('#btn-toggle-uni').click(() => this.toggleLayer('universities'));
        $('#btn-toggle-col').click(() => this.toggleLayer('colleges'));
        $('#btn-toggle-dep').click(() => this.toggleLayer('departments'));
    }

    toggleLayer(layerType) {
        const btn = $(`#btn-toggle-${layerType.slice(0, 3)}`);
        const isActive = !btn.hasClass('active');

        btn.toggleClass('active', isActive);
        this.visibleLayers[layerType] = isActive;

        if (isActive) {
            this.map.addLayer(this.layers[layerType]);
        } else {
            this.map.removeLayer(this.layers[layerType]);
        }
    }

    fitLayerBounds(layer) {
        if (layer.getLayers().length > 0) {
            try {
                this.map.fitBounds(layer.getBounds().pad(0.1));
            } catch (e) {
                console.warn('Could not fit bounds:', e);
            }
        }
    }

    showLoading() {
        $('#loading-indicator').show();
    }

    hideLoading() {
        $('#loading-indicator').hide();
    }

    showError(message) {
        // You can replace this with a toast notification
        console.error(message);
    }
}

// Initialize when document is ready
$(document).ready(function() {
    new MapDashboard();
});
</script>
@endpush
