@extends('website.web.admin.layouts.app')

@section('title', 'نەخشەی بەشەکان')

@section('content')
    <div class="container-fluid py-4" style="min-height: calc(100vh - 76px);">
        <!-- Page Title & Breadcrumb -->
        <div class="row mb-4 px-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">نەخشەی بەشەکان</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-building-columns me-1"></i>
                        نەخشەی بەشەکان
                    </h4>
                </div>
            </div>
        </div>

        <div class="row g-0" style="height: calc(100vh - 130px);">
            <!-- سایدبار (٣٠٠px) -->
            <div class="col-md-4 col-lg-3 border-end" style="height: 100%; overflow-y: auto;">
                <!-- پارێزگاکان -->
                <div class="p-3 border-bottom">
                    <h6 class="mb-3">
                        <i class="fas fa-map-marker-alt me-2"></i>پارێزگاکان
                    </h6>
                    <div class="list-group list-group-flush" id="provincesList">
                        @foreach ($provinces as $province)
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action province-item"
                                data-id="{{ $province->id }}" data-name="{{ $province->name }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-city me-2"></i>
                                        {{ $province->name }}
                                    </div>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-university"></i>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- گەڕان -->
                <div class="p-3 border-bottom">
                    <h6 class="mb-3">
                        <i class="fas fa-search me-2"></i>گەڕان
                    </h6>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="searchInput"
                            placeholder="ناوی بەش، زانکۆ، پارێزگا...">
                        <button class="btn btn-primary" type="button" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- لیستی بەشە هەڵبژێردراوەکان -->
                <div class="p-3">
                    <h6 class="mb-3 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-check-circle me-2"></i>بەشە هەڵبژێردراوەکان</span>
                        <span class="badge bg-success" id="selectedBadge">{{ $currentCount }}</span>
                    </h6>

                    <div id="selectedDepartmentsList" style="max-height: 300px; overflow-y: auto;">
                        @if ($selectedDepartments->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($selectedDepartments as $item)
                                    <div class="list-group-item py-2 px-0 border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div style="flex: 1;">
                                                <div class="fw-bold small">{{ $item->department->name }}</div>
                                                <div class="text-muted xsmall">
                                                    {{ $item->department->university->name ?? '' }} |
                                                    {{ $item->department->province->name ?? '' }}
                                                </div>
                                            </div>
                                            <div>
                                                <span
                                                    class="badge bg-success me-2">{{ $item->department->local_score }}</span>
                                                <button class="btn btn-sm btn-outline-danger remove-department-btn"
                                                    data-id="{{ $item->department->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-3"></i>
                                <p class="mb-0">هیچ بەشێک هەڵنەبژاردووە.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ڕێنوێنی نیشانەکان -->
                <div class="p-3 border-top">
                    <h6 class="mb-3">
                        <i class="fas fa-palette me-2"></i>ڕێنوێنی نیشانەکان
                    </h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center">
                            <div class="legend-color"
                                style="background-color: #28a745; width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;">
                            </div>
                            <span class="small">بەشە هەڵبژێردراوەکان</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-color"
                                style="background-color: #007bff; width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;">
                            </div>
                            <span class="small">بەشە گونجاوەکان</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-color"
                                style="background-color: #dc3545; width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;">
                            </div>
                            <span class="small">بەشە ناگونجاوەکان</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-color"
                                style="background-color: #6f42c1; width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;">
                            </div>
                            <span class="small">زانکۆ</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- نەخشە (٧٠%) -->
            <div class="col-md-8 col-lg-9">
                <div id="map" style="width: 100%; height: 100%;"></div>

                <!-- کۆنترۆڵەکانی نەخشە -->
                <div class="map-controls">
                    <button class="btn btn-light btn-sm map-control-btn" id="zoomIn" title="نزیک کردنەوە">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="btn btn-light btn-sm map-control-btn" id="zoomOut" title="دوور کردنەوە">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button class="btn btn-light btn-sm map-control-btn" id="resetView" title="ڕیسێت کردن">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button class="btn btn-light btn-sm map-control-btn" id="locateMe" title="شوێنم">
                        <i class="fas fa-location-arrow"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal بۆ وردەکاری بەش -->
    <div class="modal fade" id="departmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDepartmentName"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">زانکۆ:</th>
                            <td id="modalUniversity"></td>
                        </tr>
                        <tr>
                            <th>کۆلێژ:</th>
                            <td id="modalCollege"></td>
                        </tr>
                        <tr>
                            <th>پارێزگا:</th>
                            <td id="modalProvince"></td>
                        </tr>
                        <tr>
                            <th>جۆر:</th>
                            <td><span class="badge bg-info" id="modalType"></span></td>
                        </tr>
                        <tr>
                            <th>نمرەی پێویست:</th>
                            <td>
                                <span class="badge" id="modalScoreBadge"></span>
                                <small class="text-muted ms-2">نمرەی تۆ: {{ $student->mark }}</small>
                            </td>
                        </tr>
                    </table>

                    <div id="modalActions" class="mt-3">
                        <!-- دینامیکی بار دەکرێت -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* ستایلی گشتی */
        #map {
            border-left: 1px solid #dee2e6;
        }

        /* کۆنترۆڵەکانی نەخشە */
        .map-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .map-control-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* پارێزگاکان */
        .province-item {
            border-radius: 8px !important;
            margin-bottom: 5px;
            border: 1px solid transparent !important;
            transition: all 0.3s;
        }

        .province-item:hover,
        .province-item.active {
            background-color: #e3f2fd !important;
            border-color: #2196f3 !important;
            transform: translateX(5px);
        }

        /* لیستی بەشەکان */
        .list-group-item {
            border-left: none;
            border-right: none;
            border-radius: 0 !important;
        }

        .list-group-item:first-child {
            border-top: none;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        /* مارکەرەکانی نەخشە */
        .leaflet-popup-content {
            min-width: 250px;
        }

        .department-popup {
            padding: 10px;
        }

        .department-popup h6 {
            color: #2c3e50;
            margin-bottom: 10px;
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 5px;
        }

        /* زنجیرەی گەڕان */
        .search-results {
            position: absolute;
            top: 60px;
            left: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .search-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        /* GPS Indicator */
        .gps-indicator {
            position: absolute;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            background: white;
            padding: 10px 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: none;
        }

        /* وێنەی مارکەر */
        .custom-marker {
            background: none !important;
            border: none !important;
        }

        .marker-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .marker-green {
            background: linear-gradient(135deg, #28a745, #218838);
        }

        .marker-blue {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }

        .marker-red {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .marker-purple {
            background: linear-gradient(135deg, #6f42c1, #5a2d9c);
        }

        .marker-gold {
            background: linear-gradient(135deg, #ffc107, #e0a800);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        $(document).ready(function() {
            let map;
            let currentMarkers = [];
            let currentProvince = null;
            let userLocation = null;
            let userMarker = null;
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let maxSelections = {{ $maxSelections }};
            let studentMark = {{ $student->mark }};

            // دەستپێکردنی نەخشە
            function initMap() {
                // ناوەندی کوردستان
                map = L.map('map').setView([36.1911, 44.0092], 7);

                // نەخشەی OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Event listeners
                setupEventListeners();
            }

            // Event listeners
            function setupEventListeners() {
                // کۆنترۆڵەکانی نەخشە
                $('#zoomIn').click(() => map.zoomIn());
                $('#zoomOut').click(() => map.zoomOut());
                $('#resetView').click(() => map.setView([36.1911, 44.0092], 7));
                $('#locateMe').click(locateUser);

                // کلیک لەسەر پارێزگا
                $('.province-item').click(function() {
                    const provinceId = $(this).data('id');
                    const provinceName = $(this).data('name');

                    // ڕەنگی دیاریکراو
                    $('.province-item').removeClass('active');
                    $(this).addClass('active');

                    // بارکردنی داتای پارێزگا
                    loadProvinceData(provinceId, provinceName);
                });

                // گەڕان
                $('#searchBtn').click(performSearch);
                $('#searchInput').on('keyup', function(e) {
                    if (e.key === 'Enter') performSearch();
                });

                // سڕینەوەی بەش
                $(document).on('click', '.remove-department-btn', function() {
                    const departmentId = $(this).data('id');
                    removeDepartment(departmentId);
                });
            }

            // بارکردنی داتای پارێزگا
            function loadProvinceData(provinceId, provinceName) {
                currentProvince = provinceId;

                $.ajax({
                    url: '{{ route('student.gis.province', ':id') }}'.replace(':id', provinceId),
                    method: 'GET',
                    beforeSend: function() {
                        // نیشاندانی loading
                        clearMapMarkers();
                    },
                    success: function(response) {
                        // زیادکردنی مارکەرەکان
                        addMarkersToMap(response);

                        // گەڕاندنەوەی نەخشە بۆ پارێزگا
                        if (response.departments.length > 0 || response.universities.length > 0) {
                            const bounds = new L.LatLngBounds();

                            response.departments.forEach(dept => {
                                bounds.extend([dept.lat, dept.lng]);
                            });

                            response.universities.forEach(uni => {
                                bounds.extend([uni.lat, uni.lng]);
                            });

                            map.fitBounds(bounds, {
                                padding: [50, 50]
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading province data:', xhr);
                        alert('هەڵە لە بارکردنی داتاکانی پارێزگا');
                    }
                });
            }

            // زیادکردنی مارکەرەکان بۆ نەخشە
            function addMarkersToMap(data) {
                clearMapMarkers();

                // مارکەری زانکۆکان
                data.universities.forEach(uni => {
                    const marker = L.marker([uni.lat, uni.lng], {
                        icon: createMarkerIcon('purple', 'university')
                    }).addTo(map);

                    marker.bindPopup(`
                <div class="department-popup">
                    <h6><i class="fas fa-university"></i> ${uni.name}</h6>
                    <p class="mb-0">ئەم زانکۆیە چەندین بەشی تێدایە.</p>
                </div>
            `);

                    currentMarkers.push(marker);
                });

                // مارکەری بەشەکان
                data.departments.forEach(dept => {
                    const marker = L.marker([dept.lat, dept.lng], {
                        icon: createMarkerIcon(dept.marker_color, 'book')
                    }).addTo(map);

                    // دروستکردنی popup
                    const popupContent = `
                <div class="department-popup">
                    <h6>${dept.name}</h6>
                    <table class="table table-sm mb-2">
                        <tr><td>زانکۆ:</td><td>${dept.university}</td></tr>
                        <tr><td>کۆلێژ:</td><td>${dept.college}</td></tr>
                        <tr><td>نمرە:</td><td><span class="badge bg-${studentMark >= dept.local_score ? 'success' : 'danger'}">${dept.local_score}</span></td></tr>
                    </table>
                    ${getActionButton(dept)}
                </div>
            `;

                    marker.bindPopup(popupContent);
                    marker.departmentData = dept;

                    // کلیک لەسەر مارکەر
                    marker.on('click', function() {
                        showDepartmentDetails(dept);
                    });

                    currentMarkers.push(marker);
                });
            }

            // دروستکردنی وێنەی مارکەر
            function createMarkerIcon(color, iconType) {
                const icons = {
                    'green': 'check-circle',
                    'blue': 'book',
                    'red': 'times-circle',
                    'purple': 'university',
                    'gold': 'user-graduate'
                };

                return L.divIcon({
                    html: `<div class="marker-icon marker-${color}">
                     <i class="fas fa-${icons[color] || 'map-marker-alt'}"></i>
                   </div>`,
                    className: 'custom-marker',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                });
            }

            // دەرکەوتنی دوگمەی کردار
            function getActionButton(dept) {
                if (dept.is_selected) {
                    return `
                <button class="btn btn-sm btn-success w-100" disabled>
                    <i class="fas fa-check me-1"></i>هەڵبژێردراوە
                </button>
                <button class="btn btn-sm btn-outline-danger w-100 mt-1 remove-from-map" 
                        onclick="removeDepartmentFromMap(${dept.id})">
                    <i class="fas fa-trash me-1"></i>سڕینەوە
                </button>
            `;
                } else if (dept.is_eligible) {
                    return `
                <button class="btn btn-sm btn-primary w-100 add-from-map" 
                        onclick="addDepartmentFromMap(${dept.id})">
                    <i class="fas fa-plus me-1"></i>زیادکردن
                </button>
            `;
                } else {
                    return `
                <button class="btn btn-sm btn-danger w-100" disabled>
                    <i class="fas fa-times me-1"></i>نمرە کەمە
                </button>
            `;
                }
            }

            // نیشاندانی وردەکاری بەش
            function showDepartmentDetails(dept) {
                $('#modalDepartmentName').text(dept.name);
                $('#modalUniversity').text(dept.university);
                $('#modalCollege').text(dept.college);
                $('#modalProvince').text(currentProvince ? $('.province-item.active').data('name') : 'نەناسراو');
                $('#modalType').text(dept.type);

                // نمرە
                const scoreBadge = $('#modalScoreBadge');
                scoreBadge.text(dept.local_score);
                scoreBadge.removeClass().addClass('badge');
                scoreBadge.addClass(studentMark >= dept.local_score ? 'bg-success' : 'bg-danger');

                // کردارەکان
                const actionsDiv = $('#modalActions');
                actionsDiv.html(getActionButton(dept));

                $('#departmentModal').modal('show');
            }

            // زیادکردنی بەش لە نەخشە
            window.addDepartmentFromMap = function(departmentId) {
                $.ajax({
                    url: '{{ route('student.gis.add') }}',
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        department_id: departmentId
                    },
                    success: function(response) {
                        if (response.success) {
                            // نوێکردنەوەی مارکەر
                            updateMarkerColor(departmentId, 'green');

                            // نوێکردنەوەی ژمارەکان
                            updateSelectionCounts(response.data.total_selected, response.data
                                .remaining);

                            // زیادکردن بە لیست
                            addToSelectedList(response.data.department);

                            // پەیامی سەرکەوتوو
                            showToast('سەرکەوتوو', response.message, 'success');
                            $('#departmentModal').modal('hide');
                        } else {
                            showToast('هەڵە', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        showToast('هەڵە', xhr.responseJSON?.message || 'هەڵەیەک ڕوویدا', 'error');
                    }
                });
            };

            // سڕینەوەی بەش لە نەخشە
            window.removeDepartmentFromMap = function(departmentId) {
                if (!confirm('دڵنیای لە سڕینەوەی ئەم بەشە؟')) return;

                $.ajax({
                    url: '{{ url('student/gis/remove') }}/' + departmentId,
                    method: 'DELETE',
                    data: {
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            // نوێکردنەوەی مارکەر
                            const isEligible = studentMark >= getDepartmentScore(departmentId);
                            updateMarkerColor(departmentId, isEligible ? 'blue' : 'red');

                            // نوێکردنەوەی ژمارەکان
                            updateSelectionCounts(response.data.total_selected, response.data
                                .remaining);

                            // سڕینەوە لە لیست
                            removeFromSelectedList(departmentId);

                            // پەیامی سەرکەوتوو
                            showToast('سەرکەوتوو', response.message, 'success');
                            $('#departmentModal').modal('hide');
                        }
                    },
                    error: function(xhr) {
                        showToast('هەڵە', xhr.responseJSON?.message || 'هەڵەیەک ڕوویدا', 'error');
                    }
                });
            };

            // سڕینەوەی بەش لە لیست
            function removeDepartment(departmentId) {
                if (!confirm('دڵنیای لە سڕینەوەی ئەم بەشە؟')) return;

                $.ajax({
                    url: '{{ url('student/gis/remove') }}/' + departmentId,
                    method: 'DELETE',
                    data: {
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            // نوێکردنەوەی مارکەر
                            const isEligible = studentMark >= getDepartmentScore(departmentId);
                            updateMarkerColor(departmentId, isEligible ? 'blue' : 'red');

                            // نوێکردنەوەی ژمارەکان
                            updateSelectionCounts(response.data.total_selected, response.data
                                .remaining);

                            // سڕینەوە لە لیست
                            removeFromSelectedList(departmentId);

                            // پەیامی سەرکەوتوو
                            showToast('سەرکەوتوو', response.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        showToast('هەڵە', xhr.responseJSON?.message || 'هەڵەیەک ڕوویدا', 'error');
                    }
                });
            }

            // نوێکردنەوەی ڕەنگی مارکەر
            function updateMarkerColor(departmentId, color) {
                currentMarkers.forEach(marker => {
                    if (marker.departmentData && marker.departmentData.id == departmentId) {
                        marker.departmentData.marker_color = color;
                        marker.departmentData.is_selected = (color === 'green');

                        // نوێکردنەوەی وێنە
                        marker.setIcon(createMarkerIcon(color, 'book'));

                        // نوێکردنەوەی popup
                        const popupContent = `
                    <div class="department-popup">
                        <h6>${marker.departmentData.name}</h6>
                        <table class="table table-sm mb-2">
                            <tr><td>زانکۆ:</td><td>${marker.departmentData.university}</td></tr>
                            <tr><td>کۆلێژ:</td><td>${marker.departmentData.college}</td></tr>
                            <tr><td>نمرە:</td><td><span class="badge bg-${studentMark >= marker.departmentData.local_score ? 'success' : 'danger'}">${marker.departmentData.local_score}</span></td></tr>
                        </table>
                        ${getActionButton(marker.departmentData)}
                    </div>
                `;
                        marker.bindPopup(popupContent);
                    }
                });
            }

            // وەرگرتنی نمرەی بەش
            function getDepartmentScore(departmentId) {
                for (const marker of currentMarkers) {
                    if (marker.departmentData && marker.departmentData.id == departmentId) {
                        return marker.departmentData.local_score;
                    }
                }
                return 0;
            }

            // نوێکردنەوەی ژمارەکان
            function updateSelectionCounts(total, remaining) {
                $('#selectedCount').text(total);
                $('#selectedBadge').text(total);

                // ئەگەر سنوور تێپەڕی
                if (remaining <= 0) {
                    $('.add-from-map').prop('disabled', true)
                        .removeClass('btn-primary').addClass('btn-secondary')
                        .html('<i class="fas fa-ban me-1"></i>سنوور تێپەڕی');
                }
            }

            // زیادکردن بە لیستی هەڵبژێردراوەکان
            function addToSelectedList(department) {
                const listDiv = $('#selectedDepartmentsList');
                const noItemsDiv = listDiv.find('.text-center');

                if (noItemsDiv.length > 0) {
                    noItemsDiv.remove();
                    listDiv.html('<div class="list-group list-group-flush"></div>');
                }

                const itemHtml = `
            <div class="list-group-item py-2 px-0 border-0 department-list-item" data-id="${department.id}">
                <div class="d-flex justify-content-between align-items-center">
                    <div style="flex: 1;">
                        <div class="fw-bold small">${department.name}</div>
                        <div class="text-muted xsmall">
                            ${department.university}
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-success me-2">${department.local_score}</span>
                        <button class="btn btn-sm btn-outline-danger remove-department-btn" 
                                data-id="${department.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

                listDiv.find('.list-group').prepend(itemHtml);
            }

            // سڕینەوە لە لیستی هەڵبژێردراوەکان
            function removeFromSelectedList(departmentId) {
                $(`.department-list-item[data-id="${departmentId}"]`).remove();

                // ئەگەر لیست بەتاڵ بوو
                if ($('#selectedDepartmentsList .list-group-item').length === 0) {
                    $('#selectedDepartmentsList').html(`
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-3"></i>
                    <p class="mb-0">هیچ بەشێک هەڵنەبژاردووە.</p>
                </div>
            `);
                }
            }

            // سڕینەوەی هەموو مارکەرەکان
            function clearMapMarkers() {
                currentMarkers.forEach(marker => marker.remove());
                currentMarkers = [];
            }

            // گەڕان
            function performSearch() {
                const query = $('#searchInput').val().trim();
                if (query.length < 2) return;

                $.ajax({
                    url: '{{ route('student.gis.search') }}',
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        query: query
                    },
                    success: function(response) {
                        if (response.success && response.results.length > 0) {
                            // نیشاندانی ئەنجامەکان
                            showSearchResults(response.results);
                        } else {
                            showToast('هیچ ئەنجامێک', 'هیچ بەشێک بەم ناوە نەدۆزرایەوە', 'info');
                        }
                    }
                });
            }

            // نیشاندانی ئەنجامەکانی گەڕان
            function showSearchResults(results) {
                let html = '<div class="search-results" id="searchResults">';

                results.forEach(result => {
                    html += `
                <div class="search-result-item" data-lat="${result.lat}" data-lng="${result.lng}" data-id="${result.id}">
                    <div class="fw-bold">${result.name}</div>
                    <div class="text-muted small">
                        ${result.university?.name || ''} | ${result.province?.name || ''}
                    </div>
                    <div class="text-muted xsmall">نمرە: ${result.local_score}</div>
                </div>
            `;
                });

                html += '</div>';

                // سڕینەوەی ئەنجامەکانی پێشوو
                $('#searchResults').remove();
                $('.map-container').append(html);
                $('#searchResults').slideDown();

                // کلیک لەسەر ئەنجام
                $('.search-result-item').click(function() {
                    const lat = $(this).data('lat');
                    const lng = $(this).data('lng');
                    const id = $(this).data('id');

                    // گەڕاندنەوەی نەخشە
                    map.setView([lat, lng], 15);

                    // نیشاندانی popup
                    const marker = currentMarkers.find(m => m.departmentData?.id == id);
                    if (marker) {
                        marker.openPopup();
                        showDepartmentDetails(marker.departmentData);
                    }

                    // لێکردنەوەی ئەنجامەکان
                    $('#searchResults').slideUp(function() {
                        $(this).remove();
                    });
                });

                // داخستنی ئەنجامەکان بە کلیک لە دەرەوە
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('#searchResults, #searchInput, #searchBtn').length) {
                        $('#searchResults').slideUp(function() {
                            $(this).remove();
                        });
                    }
                });
            }

            // شوێنی ئێستا
            function locateUser() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            userLocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };

                            // سڕینەوەی مارکەری پێشوو
                            if (userMarker) {
                                userMarker.remove();
                            }

                            // دروستکردنی مارکەری نوێ
                            userMarker = L.marker([userLocation.lat, userLocation.lng], {
                                icon: createMarkerIcon('gold', 'user-graduate')
                            }).addTo(map);

                            userMarker.bindPopup(`
                        <div class="department-popup">
                            <h6><i class="fas fa-user-graduate"></i> شوێنی ئێستا</h6>
                            <p class="mb-0">تۆ لێرەیت!</p>
                        </div>
                    `);

                            // گەڕاندنەوەی نەخشە
                            map.setView([userLocation.lat, userLocation.lng], 13);

                            // پەیامی سەرکەوتوو
                            showToast('سەرکەوتوو', 'شوێنەکەت نیشان درا', 'success');
                        },
                        function(error) {
                            showToast('هەڵە', 'نەتوانرا شوێنەکەت بدۆزرێتەوە', 'error');
                        }
                    );
                } else {
                    showToast('هەڵە', 'GPS پشتگیری ناکرێت', 'error');
                }
            }

            // پەیامەکان
            function showToast(title, message, type) {
                // ئەگەر Toast پێگەیەنت هەیە، بەکاری بێنە
                if (typeof Toast !== 'undefined') {
                    Toast.fire({
                        icon: type,
                        title: title,
                        text: message
                    });
                } else {
                    // پەیامی سادە
                    alert(title + ': ' + message);
                }
            }

            // دەستپێکردنی نەخشە
            initMap();
        });
    </script>
@endpush
