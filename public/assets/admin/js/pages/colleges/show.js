(function() {
    const MAP_ID = 'map-college';

    // Function to reset leaflet container
    function resetLeafletContainer(id) {
        const el = document.getElementById(id);
        if (el && el._leaflet_id) {
            // Destroy existing map
            if (window.L && el._leaflet_id in L.Map._instances) {
                var map = L.Map._instances[el._leaflet_id];
                if (map) {
                    map.remove();
                }
            }
            el._leaflet_id = null;
            el.innerHTML = '';
        }
    }

    // Normalize GeoJSON
    function normalizeGeoJSON(input) {
        try {
            if (typeof input === 'string') {
                input = JSON.parse(input);
            }
        } catch (_) {
            return null;
        }
        if (!input) return null;
        
        if (Array.isArray(input)) {
            return {
                type: 'FeatureCollection',
                features: input
            };
        }
        
        if (input.type === 'Feature' || input.type === 'FeatureCollection') {
            return input;
        }
        
        if (input.type && input.coordinates) {
            return {
                type: 'Feature',
                geometry: input,
                properties: {}
            };
        }
        
        return null;
    }

    // Main function
    function initMap() {
        const el = document.getElementById(MAP_ID);
        if (!el) return;

        // Reset container
        resetLeafletContainer(MAP_ID);

        // Get data from data attributes
        const collegeGeoJSON = el.dataset.geojson ? JSON.parse(el.dataset.geojson) : null;
        const collegeLat = parseFloat(el.dataset.lat) || null;
        const collegeLng = parseFloat(el.dataset.lng) || null;
        const collegeName = el.dataset.name || '';
        const departments = el.dataset.departments ? JSON.parse(el.dataset.departments) : [];

        // Initialize map
        const map = L.map(MAP_ID).setView([36.2, 44.0], 7);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const area = L.geoJSON(null, {
            style: {
                color: '#2563eb',
                weight: 2,
                fillColor: '#3b82f6',
                fillOpacity: 0.15
            }
        }).addTo(map);
        
        const markers = L.layerGroup().addTo(map);
        let anything = false;

        // College GeoJSON
        if (collegeGeoJSON) {
            try {
                const gj = normalizeGeoJSON(collegeGeoJSON);
                if (gj) {
                    area.addData(gj);
                    anything = true;
                }
            } catch (e) {
                console.error('Error loading college GeoJSON:', e);
            }
        }

        // College point
        if (collegeLat && collegeLng) {
            L.marker([collegeLat, collegeLng])
                .addTo(markers)
                .bindPopup('<strong>' + collegeName + '</strong>');
            anything = true;
        }

        // Departments
        if (departments && departments.length > 0) {
            departments.forEach(function(department) {
                // Department marker
                if (department.lat && department.lng) {
                    L.marker([parseFloat(department.lat), parseFloat(department.lng)])
                        .addTo(markers)
                        .bindPopup('<strong>' + department.name + '</strong>');
                    anything = true;
                }
                
                // Department GeoJSON
                if (department.geojson) {
                    try {
                        const dgj = normalizeGeoJSON(department.geojson);
                        if (dgj) {
                            L.geoJSON(dgj, {
                                style: {
                                    color: '#16a34a',
                                    weight: 2,
                                    fillColor: '#22c55e',
                                    fillOpacity: 0.12
                                }
                            }).addTo(map);
                            anything = true;
                        }
                    } catch (e) {
                        console.error('Error loading department GeoJSON:', e);
                    }
                }
            });
        }

        // Fit bounds or set default view
        if (anything) {
            const layersForBounds = [];
            area.eachLayer(function(l) {
                layersForBounds.push(l);
            });
            markers.eachLayer(function(m) {
                layersForBounds.push(m);
            });
            
            const boundsGroup = L.featureGroup(layersForBounds);
            const bounds = boundsGroup.getBounds();
            
            if (bounds.isValid()) {
                map.fitBounds(bounds, {
                    padding: [20, 20]
                });
            }
        } else {
            map.setView([36.2, 44.0], 8);
        }

        // Resize map
        setTimeout(function() {
            map.invalidateSize();
        }, 300);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMap);
    } else {
        initMap();
    }
})();