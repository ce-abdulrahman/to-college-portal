$(document).ready(function() {
    // Check if map container exists (for create and edit pages)
    if (document.getElementById('map')) {
        // Initialize Leaflet map
        var map = L.map('map').setView([36.2, 44.0], 8);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        
        // Marker for university location
        var marker = null;
        
        // GeoJSON layer for university area
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
        
        // Handle map click to set marker
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            
            // Update form fields
            $('#lat').val(lat.toFixed(6));
            $('#lng').val(lng.toFixed(6));
            
            // Add or update marker
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup('زانکۆ: ' + lat.toFixed(6) + ', ' + lng.toFixed(6))
                    .openPopup();
            }
        });
        
        // Initialize with existing GeoJSON if any (for edit page)
        var initialGeoJSON = $('textarea[name="geojson"]').val();
        if (initialGeoJSON) {
            updateMapWithGeoJSON(initialGeoJSON);
        }
        
        // Initialize with existing lat/lng if any (for edit page)
        var initialLat = $('#lat').val();
        var initialLng = $('#lng').val();
        if (initialLat && initialLng) {
            marker = L.marker([parseFloat(initialLat), parseFloat(initialLng)]).addTo(map)
                .bindPopup('زانکۆ: ' + initialLat + ', ' + initialLng);
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
        
        // Invalidate map size after tab/window changes
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    }
    
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
    
    // Image preview (optional)
    $('input[name="image"]').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            // Validate image type
            var validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showToast('هەڵە', 'تەنها وێنە (JPEG, PNG, GIF, WebP) قبوڵە', 'error');
                $(this).val('');
                return;
            }
            
            // Validate image size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                showToast('هەڵە', 'قەبارەی وێنە نابێت لە 5MB زیاتر بێت', 'error');
                $(this).val('');
                return;
            }
            
            // Show preview if needed
            var reader = new FileReader();
            reader.onload = function(e) {
                // You can add image preview here
                console.log('Image selected:', file.name);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Helper function for toast notifications
    function showToast(title, message, type) {
        if (typeof Swal !== 'undefined') {
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
        } else {
            console.log(title + ': ' + message);
        }
    }
});