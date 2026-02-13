document.addEventListener('DOMContentLoaded', function() {
    const currentPage = document.body.getAttribute('data-page');
    const currentView = document.body.getAttribute('data-view');

    if (currentView === 'create') {
        initFormValidation();
        initPageSpecificFeatures(currentPage);
        initImagePreview();
    }
});

function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);

        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                validateField(this);
            });

            input.addEventListener('change', function() {
                validateField(this);
            });

            if (input.type === 'file') {
                input.addEventListener('change', function() {
                    validateFile(this);
                });
            }

            if (input.type === 'email') {
                input.addEventListener('blur', function() {
                    validateEmail(this);
                });
            }

            if (input.type === 'number') {
                input.addEventListener('blur', function() {
                    validateNumber(this);
                });
            }
        });
    });
}

function validateField(field) {
    const isValid = field.checkValidity();
    const feedback = field.parentNode.querySelector('.invalid-feedback') || createFeedbackElement(field);

    if (!isValid) {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
        feedback.style.display = 'block';
    } else {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        feedback.style.display = 'none';
    }
}

function validateFile(fileInput) {
    const feedback = fileInput.parentNode.querySelector('.invalid-feedback') || createFeedbackElement(fileInput);

    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        const maxSize = 5 * 1024 * 1024;

        if (!allowedTypes.includes(file.type)) {
            fileInput.classList.add('is-invalid');
            fileInput.classList.remove('is-valid');
            feedback.textContent = 'جۆری فایل نادروستە (پێویستە jpg, png, gif, webp بێت)';
            feedback.style.display = 'block';
            return false;
        }

        if (file.size > maxSize) {
            fileInput.classList.add('is-invalid');
            fileInput.classList.remove('is-valid');
            feedback.textContent = 'قەبارەی فایل زۆر گەورەیە (کەمتر لە 5MB)';
            feedback.style.display = 'block';
            return false;
        }

        fileInput.classList.remove('is-invalid');
        fileInput.classList.add('is-valid');
        feedback.style.display = 'none';
        return true;
    }

    if (fileInput.required) {
        fileInput.classList.add('is-invalid');
        fileInput.classList.remove('is-valid');
        feedback.textContent = 'پێویستە فایل هەڵبژێریت';
        feedback.style.display = 'block';
        return false;
    }

    return true;
}

function validateEmail(emailInput) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const feedback = emailInput.parentNode.querySelector('.invalid-feedback') || createFeedbackElement(emailInput);

    if (emailInput.value && !emailRegex.test(emailInput.value)) {
        emailInput.classList.add('is-invalid');
        emailInput.classList.remove('is-valid');
        feedback.textContent = 'فۆرماتی ئیمەیڵ نادروستە';
        feedback.style.display = 'block';
        return false;
    }

    return true;
}

function validateNumber(numberInput) {
    const feedback = numberInput.parentNode.querySelector('.invalid-feedback') || createFeedbackElement(numberInput);
    const min = parseFloat(numberInput.min);
    const max = parseFloat(numberInput.max);
    const value = parseFloat(numberInput.value);

    if (numberInput.value) {
        if (!isNaN(min) && value < min) {
            numberInput.classList.add('is-invalid');
            numberInput.classList.remove('is-valid');
            feedback.textContent = `نمرە پێویستە لە ${min} بەرزتر بێت`;
            feedback.style.display = 'block';
            return false;
        }

        if (!isNaN(max) && value > max) {
            numberInput.classList.add('is-invalid');
            numberInput.classList.remove('is-valid');
            feedback.textContent = `نمرە پێویستە لە ${max} کەمتر بێت`;
            feedback.style.display = 'block';
            return false;
        }

        numberInput.classList.remove('is-invalid');
        numberInput.classList.add('is-valid');
        feedback.style.display = 'none';
        return true;
    }

    return true;
}

function createFeedbackElement(field) {
    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    field.parentNode.appendChild(feedback);
    return feedback;
}

function initPageSpecificFeatures(page) {
    const features = {
        'departments': initDepartmentsCreate,
        'students': initStudentsCreate,
        'teachers': initTeachersCreate,
        'universities': initUniversitiesCreate,
        'colleges': initCollegesCreate,
        'provinces': initProvincesCreate
    };

    if (features[page]) {
        features[page]();
    }
}

function initDepartmentsCreate() {
    initDependentSelects();
    initLeafletMap();
    initGeoJSONMap();
    initSummernote();
}

function initStudentsCreate() {
    initReferralCodeSelect2();
}

function initTeachersCreate() {
    initTeacherValidation();
}

function initUniversitiesCreate() {
    initLeafletMap();
    initGeoJSONMap();
    initSummernote();
}

function initCollegesCreate() {
    initDependentSelects();
    initGeoJSONMap();
    initSummernote();
}

function initProvincesCreate() {
    initGeoJSONMap();
    initSummernote();
}

function initDependentSelects() {
    const provinceSelect = document.getElementById('province_id');
    const universitySelect = document.getElementById('university_id');
    const collegeSelect = document.getElementById('college_id');

    if (provinceSelect && universitySelect) {
        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            universitySelect.innerHTML = '<option value="">هەموو زانکۆكان</option>';
            universitySelect.disabled = !provinceId;
            if (collegeSelect) {
                collegeSelect.innerHTML = '<option value="">هەموو کۆلێژەکان</option>';
                collegeSelect.disabled = true;
            }

            if (provinceId) {
                fetch(`${window.API_UNI}?province_id=${provinceId}`)
                    .then(response => response.json())
                    .then(universities => {
                        universities.forEach(uni => {
                            const option = document.createElement('option');
                            option.value = uni.id;
                            option.textContent = uni.name;
                            universitySelect.appendChild(option);
                        });
                        universitySelect.disabled = false;
                    })
                    .catch(() => {
                        universitySelect.innerHTML = '<option value="">هەڵە ڕوویدا</option>';
                    });
            }
        });
    }

    if (universitySelect && collegeSelect) {
        universitySelect.addEventListener('change', function() {
            const universityId = this.value;
            collegeSelect.innerHTML = '<option value="">هەموو کۆلێژەکان</option>';
            collegeSelect.disabled = !universityId;

            if (universityId) {
                fetch(`${window.API_COLLS}?university_id=${universityId}`)
                    .then(response => response.json())
                    .then(colleges => {
                        colleges.forEach(college => {
                            const option = document.createElement('option');
                            option.value = college.id;
                            option.textContent = college.name;
                            collegeSelect.appendChild(option);
                        });
                        collegeSelect.disabled = false;
                    })
                    .catch(() => {
                        collegeSelect.innerHTML = '<option value="">هەڵە ڕوویدا</option>';
                    });
            }
        });
    }
}

function initLeafletMap() {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');

    const defaultLat = latInput?.value || 33.2232;
    const defaultLng = lngInput?.value || 43.6793;

    const map = L.map('map').setView([defaultLat, defaultLng], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;

    if (latInput?.value && lngInput?.value) {
        marker = L.marker([latInput.value, lngInput.value]).addTo(map);
    }

    map.on('click', function(e) {
        const { lat, lng } = e.latlng;

        if (marker) {
            map.removeLayer(marker);
        }

        marker = L.marker([lat, lng]).addTo(map);

        if (latInput) latInput.value = lat.toFixed(6);
        if (lngInput) lngInput.value = lng.toFixed(6);

        validateField(latInput);
        validateField(lngInput);
    });
}

// لە create.js زیادبکە بەم شێوەیە
function initGeoJSONMap() {
    const geojsonTextarea = document.querySelector('textarea[name="geojson"]');
    const mapElement = document.getElementById('map');

    if (!geojsonTextarea || !mapElement) return;

    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');

    const defaultLat = latInput?.value || 33.2232;
    const defaultLng = lngInput?.value || 43.6793;

    const map = L.map('map').setView([defaultLat, defaultLng], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    let drawControl = new L.Control.Draw({
        edit: {
            featureGroup: drawnItems
        },
        draw: {
            polygon: true,
            polyline: false,
            rectangle: false,
            circle: false,
            marker: true,
            circlemarker: false
        }
    });
    map.addControl(drawControl);

    map.on(L.Draw.Event.CREATED, function(event) {
        const layer = event.layer;
        drawnItems.addLayer(layer);

        const geojson = layer.toGeoJSON();
        geojsonTextarea.value = JSON.stringify(geojson.geometry);
        validateField(geojsonTextarea);
    });

    map.on(L.Draw.Event.EDITED, function(event) {
        const layers = event.layers;
        let newGeoJSON = {
            type: "FeatureCollection",
            features: []
        };

        layers.eachLayer(function(layer) {
            newGeoJSON.features.push(layer.toGeoJSON());
        });

        if (newGeoJSON.features.length === 1) {
            geojsonTextarea.value = JSON.stringify(newGeoJSON.features[0].geometry);
        } else {
            geojsonTextarea.value = JSON.stringify(newGeoJSON);
        }
        validateField(geojsonTextarea);
    });

    map.on(L.Draw.Event.DELETED, function(event) {
        geojsonTextarea.value = '';
        validateField(geojsonTextarea);
    });

    geojsonTextarea.addEventListener('input', function() {
        if (this.value) {
            try {
                const geojson = JSON.parse(this.value);
                drawnItems.clearLayers();
                L.geoJSON(geojson).addTo(drawnItems);
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } catch (e) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        }
    });
}

function initSummernote() {
    const summernoteElements = document.querySelectorAll('.summernote');
    if (summernoteElements.length > 0 && typeof $.fn.summernote !== 'undefined') {
        summernoteElements.forEach(element => {
            $(element).summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    }
}

function initReferralCodeSelect2() {
    const referralSelect = document.getElementById('referral_code');
    if (referralSelect && typeof $.fn.select2 !== 'undefined') {
        $(referralSelect).select2({
            placeholder: 'کۆدی بانگێشت هەلبژێرە...',
            allowClear: true,
            ajax: {
                url: '/admin/api/users/search-by-code',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term || '' };
                },
                processResults: function(response) {
                    const data = Array.isArray(response) ? response : (response.results || []);
                    return {
                        results: data.map(item => ({
                            id: item.rand_code || item.id || item.code || '',
                            text: item.text || (item.rand_code || item.id || item.code || '')
                        }))
                    };
                },
                cache: true
            },
            minimumInputLength: 1
        });
    }
}

function initTeacherValidation() {
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('blur', function() {
            validatePhone(this);
        });
    }
}

function validatePhone(phoneInput) {
    const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
    const feedback = phoneInput.parentNode.querySelector('.invalid-feedback') || createFeedbackElement(phoneInput);

    if (phoneInput.value && !phoneRegex.test(phoneInput.value.replace(/\s/g, ''))) {
        phoneInput.classList.add('is-invalid');
        phoneInput.classList.remove('is-valid');
        feedback.textContent = 'ژمارەی تەلەفۆن نادروستە';
        feedback.style.display = 'block';
        return false;
    }

    phoneInput.classList.remove('is-invalid');
    phoneInput.classList.add('is-valid');
    feedback.style.display = 'none';
    return true;
}

function initImagePreview() {
    const imageInput = document.querySelector('input[type="file"][accept="image/*"]');
    const previewContainer = document.getElementById('image-preview-container');

    if (imageInput && !previewContainer) {
        const container = document.createElement('div');
        container.id = 'image-preview-container';
        container.className = 'mt-2';
        container.innerHTML = `
            <img id="image-preview" class="img-thumbnail d-none" style="max-height: 200px;">
        `;
        imageInput.parentNode.appendChild(container);
    }

    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const preview = document.getElementById('image-preview');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.classList.add('d-none');
            }
        });
    }
}
