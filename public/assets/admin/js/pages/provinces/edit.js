$(document).ready(function() {
    // Initialize Leaflet map
    var map = L.map('map').setView([36.2, 44.0], 7);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    
    // GeoJSON layer for province boundaries
    var geoJsonLayer = L.geoJSON(null, {
        style: {
            color: '#4f46e5',
            weight: 2,
            fillColor: '#6366f1',
            fillOpacity: 0.15
        },
        onEachFeature: function(feature, layer) {
            if (feature.properties && feature.properties.name) {
                layer.bindPopup('<b>' + feature.properties.name + '</b>');
            }
        }
    }).addTo(map);
    
    // Function to update map with GeoJSON
    function updateMapWithGeoJSON(geojsonData) {
        geoJsonLayer.clearLayers();
        
        if (geojsonData && geojsonData.trim() !== '') {
            try {
                var parsed = JSON.parse(geojsonData);
                geoJsonLayer.addData(parsed);
                
                // Fit bounds to GeoJSON
                var bounds = geoJsonLayer.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            } catch (e) {
                console.error('Invalid GeoJSON:', e);
                showToast('هەڵە', 'فۆرماتی GeoJSON نادروستە', 'error');
            }
        }
    }
    
    // Initialize with existing GeoJSON if any
    var initialGeoJSON = $('textarea[name="geojson"]').val();
    if (initialGeoJSON) {
        updateMapWithGeoJSON(initialGeoJSON);
    }
    
    // Handle GeoJSON textarea changes
    $('textarea[name="geojson"]').on('input', function() {
        updateMapWithGeoJSON($(this).val());
    });
    
    // Handle GeoJSON file upload
    $('input[name="geojson_file"]').on('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;
        
        var reader = new FileReader();
        reader.onload = function(e) {
            var content = e.target.result;
            $('textarea[name="geojson"]').val(content);
            updateMapWithGeoJSON(content);
            showToast('سەرکەوتوو', 'فایلی GeoJSON بارکرا', 'success');
        };
        reader.readAsText(file);
    });
    
    // Form validation
    var forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
    
    // Image preview
    $('input[name="image"]').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // You can add image preview here if needed
                console.log('Image selected:', file.name);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Helper function for toast notifications
    function showToast(title, message, type) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        
        Toast.fire({
            icon: type,
            title: title,
            text: message
        });
    }
    
    // Handle map click to add coordinates (optional feature)
    map.on('click', function(e) {
        // Uncomment if you want to allow clicking on map to set coordinates
        /*
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        
        // You can add markers or update hidden fields
        L.marker([lat, lng]).addTo(map)
            .bindPopup('ناوچە: ' + lat.toFixed(6) + ', ' + lng.toFixed(6))
            .openPopup();
        */
    });
    
    // Invalidate map size after tab/window changes
    setTimeout(function() {
        map.invalidateSize();
    }, 100);
});