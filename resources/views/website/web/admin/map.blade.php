<!DOCTYPE html>
<html lang="ku" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستەمی نەخشەی خوێندنی باڵا</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body id="dashboard-map" data-page="admin.dashboard">

    <!-- Education-themed decorative elements -->
    <div class="education-decoration decoration-1">🎓</div>
    <div class="education-decoration decoration-2">📚</div>
    <div class="education-decoration decoration-3">🏫</div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h1><i class="bi bi-geo-alt-fill"></i> نەخشەی کوردستان</h1>
                <p> زانکۆ، کۆلێژ و بەشەکانی کوردستان</p>
            </div>



            <div class="action-buttons ">
                <button class="action-btn" id="locateUser" title="نیشان دانی شوێنی ئێستا">
                    <i class="bi bi-geo-alt"></i>
                    <span>شوێنم</span>
                </button>
                <div class="gps-indicator" id="gpsIndicator">

                    <i class="bi bi-geo-alt-fill"></i>
                    <span>GPS چالاک نیە</span>
                </div>
                <a href="{{ route('admin.departments.index') }}" class="action-btn" title="بەڕێوەبردنی بەشەکان">
                    <i class="bi bi-gear"></i>
                    <span>بەڕێوەبردن</span>
                </a>
            </div>

            <div class="breadcrumb-nav">
                <div class="breadcrumb" id="breadcrumb">
                    <div class="breadcrumb-item active">
                        <i class="bi bi-house-door"></i> سەرەتا
                    </div>
                </div>
            </div>

            <div class="institutions-container" id="institutionsList">
                <div class="empty-state">
                    <i class="bi bi-map"></i>
                    <p>پارێزگایەک هەڵبژێرە لە نەخشە...</p>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div class="map-container d-none d-lg-block">
            <div id="map"></div>

            <div class="map-overlay">

                <div class="map-control-card ">
                    <button class="control-btn" id="resetView" title="ڕیسێت کردن">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    <button class="control-btn" id="zoomIn" title="نزیک کردنەوە">
                        <i class="bi bi-plus"></i>
                    </button>
                    <button class="control-btn" id="zoomOut" title="دوور کردنەوە">
                        <i class="bi bi-dash"></i>
                    </button>
                </div>
            </div>

            <div class="map-legend d-none d-lg-block">
                <div class="legend-title"><i class="bi bi-palette"></i> ڕێنوێنی نیشانەکان</div>
                <div class="legend-item">
                    <div class="legend-color" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);"></div>
                    <span>🏫 زانکۆ</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: linear-gradient(135deg, #10b981, #059669);"></div>
                    <span>🏢 کۆلێژ</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: linear-gradient(135deg, #f59e0b, #d97706);"></div>
                    <span>📚 بەش</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: linear-gradient(135deg, #ef4444, #dc2626);"></div>
                    <span>📍 شوێنی ئێستا</span>
                </div>
            </div>
        </div>
    </div>


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
</body>

</html>
