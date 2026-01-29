<!DOCTYPE html>
<html lang="ku" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستەمی نەخشەی خوێندنی باڵا</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/nav.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<div id="dashboard-map" data-page="admin.dashboard">

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>🗺️ سیستەمی نەخشەی خوێندنی باڵا</h1>
                <p>گەڕان بە ناو زانکۆ، کۆلێژ و بەشەکان</p>
            </div>

            {{--  <div class="search-container">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="🔍 گەڕان بە ناو، شوێن...">
                    <button class="search-clear" id="searchClear">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>  --}}

            <div class="search-container">
                <div class="search-box">
                    <div class="row">
                        <a href="{{ route('center.students.index') }}">

                            <button class="button">
                                <div class="bg"></div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 342 208" height="208"
                                    width="342" class="splash">
                                    <path stroke-linecap="round" stroke-width="3"
                                        d="M54.1054 99.7837C54.1054 99.7837 40.0984 90.7874 26.6893 97.6362C13.2802 104.485 1.5 97.6362 1.5 97.6362">
                                    </path>
                                    <path stroke-linecap="round" stroke-width="3"
                                        d="M285.273 99.7841C285.273 99.7841 299.28 90.7879 312.689 97.6367C326.098 104.486 340.105 95.4893 340.105 95.4893">
                                    </path>
                                    <path stroke-linecap="round" stroke-width="3" stroke-opacity="0.3"
                                        d="M281.133 64.9917C281.133 64.9917 287.96 49.8089 302.934 48.2295C317.908 46.6501 319.712 36.5272 319.712 36.5272">
                                    </path>
                                    <path stroke-linecap="round" stroke-width="3" stroke-opacity="0.3"
                                        d="M281.133 138.984C281.133 138.984 287.96 154.167 302.934 155.746C317.908 157.326 319.712 167.449 319.712 167.449">
                                    </path>
                                    <path stroke-linecap="round" stroke-width="3"
                                        d="M230.578 57.4476C230.578 57.4476 225.785 41.5051 236.061 30.4998C246.337 19.4945 244.686 12.9998 244.686 12.9998">
                                    </path>
                                    <path stroke-linecap="round" stroke-width="3"
                                        d="M230.578 150.528C230.578 150.528 225.785 166.471 236.061 177.476C246.337 188.481 244.686 194.976 244.686 194.976">
                                    </path>
                                    <path stroke-linecap="round" stroke-width="3" stroke-opacity="0.3"
                                        d="M170.392 57.0278C170.392 57.0278 173.89 42.1322 169.571 29.54C165.252 16.9478 168.751 2.05227 168.751 2.05227">
                                    </path>
                                    <path stroke-linecap="round" stroke-width="3" stroke-opacity="0.3"
                                        d="M170.392 150.948C170.392 150.948 173.89 165.844 169.571 178.436C165.252 191.028 168.751 205.924 168.751 205.924">
                                    </path>
                                    <path stroke-linecap="round" stroke-width="3"
                                        d="M112.609 57.4476C112.609 57.4476 117.401 41.5051 107.125 30.4998C96.8492 19.4945 98.5 12.9998 98.5 12.9998">
                                    </path>
                                    <path stroke-linecap="round" stroke-width="3"
                                        d="M112.609 150.528C112.609 150.528 117.401 166.471 107.125 177.476C96.8492 188.481 98.5 194.976 98.5 194.976">
                                    </path>
                                    <path stroke-linecap="round" stroke-width="3" stroke-opacity="0.3"
                                        d="M62.2941 64.9917C62.2941 64.9917 55.4671 49.8089 40.4932 48.2295C25.5194 46.6501 23.7159 36.5272 23.7159 36.5272">
                                    </path>
                                    <path stroke-linecap="round" stroke-width="3" stroke-opacity="0.3"
                                        d="M62.2941 145.984C62.2941 145.984 55.4671 161.167 40.4932 162.746C25.5194 164.326 23.7159 174.449 23.7159 174.449">
                                    </path>
                                </svg>

                                <div class="wrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 221 42" height="42"
                                        width="221" class="path">
                                        <path stroke-linecap="round" stroke-width="3"
                                            d="M182.674 2H203C211.837 2 219 9.16344 219 18V24C219 32.8366 211.837 40 203 40H18C9.16345 40 2 32.8366 2 24V18C2 9.16344 9.16344 2 18 2H47.8855">
                                        </path>
                                    </svg>

                                    <div class="outline"></div>
                                    <div class="content">
                                        <span class="char state-1">
                                            <span data-label="t" style="--i: 10">t</span>
                                            <span data-label="n" style="--i: 9">n</span>
                                            <span data-label="e" style="--i: 8">e</span>
                                            <span data-label="m" style="--i: 7">m</span>
                                            <span data-label="t" style="--i: 6">t</span>
                                            <span data-label="r" style="--i: 5">r</span>
                                            <span data-label="a" style="--i: 4">a</span>
                                            <span data-label="p" style="--i: 3">p</span>
                                            <span data-label="e" style="--i: 2">e</span>
                                            <span data-label="D" style="--i: 1">D</span>
                                        </span>

                                        <div class="icon">
                                            <div></div>
                                        </div>

                                        <span class="char state-2">
                                            <span data-label="w" style="--i: 7">w</span>
                                            <span data-label="o" style="--i: 6">o</span>
                                            <span data-label="N" style="--i: 5">N</span>
                                            <span data-label="n" style="--i: 4">n</span>
                                            <span data-label="i" style="--i: 3">i</span>
                                            <span data-label="o" style="--i: 2">o</span>
                                            <span data-label="J" style="--i: 1">J</span>
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" id="uniCount">0</div>
                    <div class="stat-label">زانکۆ</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="colCount">0</div>
                    <div class="stat-label">کۆلێژ</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="depCount">0</div>
                    <div class="stat-label">بەش</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="totalCount">0</div>
                    <div class="stat-label">کۆی گشتی</div>
                </div>
            </div>

            {{--  <div class="action-buttons">
                <button class="action-btn" data-layer="uni" title="نیشان دانی زانکۆکان">
                    <i class="bi bi-building"></i>
                    <span>زانکۆ</span>
                </button>
                <button class="action-btn" data-layer="col" title="نیشان دانی کۆلێژەکان">
                    <i class="bi bi-house"></i>
                    <span>کۆلێژ</span>
                </button>
                <button class="action-btn" data-layer="dep" title="نیشان دانی بەشەکان">
                    <i class="bi bi-diagram-3"></i>
                    <span>بەش</span>
                </button>
                <button class="action-btn" id="locateUser" title="نیشان دانی شوێنی ئێستا">
                    <i class="bi bi-geo-alt"></i>
                    <span>شوێنم</span>
                </button>
                <button class="action-btn" id="toggle3D" title="3D کردن">
                    <i class="bi bi-cube"></i>
                    <span>3D</span>
                </button>
                <button class="action-btn" id="drawRoute" title="ڕێگا نیشان بدە">
                    <i class="bi bi-signpost"></i>
                    <span>ڕێگا</span>
                </button>
                <button class="action-btn" id="exportData" title="هەناردە کردن">
                    <i class="bi bi-download"></i>
                    <span>هەناردە</span>
                </button>
                <button class="action-btn" id="showHelp" title="ڕێنمایی">
                    <i class="bi bi-question-circle"></i>
                    <span>یارمەتی</span>
                </button>
            </div>  --}}

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
        <div class="map-container">
            <div id="map"></div>

            <div class="map-overlay">
                <div class="map-control-card">
                    <div class="gps-indicator" id="gpsIndicator">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>GPS چالاک نیە</span>
                    </div>
                </div>
                <div class="map-control-card">
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

            <div class="map-legend">
                <div class="legend-title">🎨 ڕێنوێنی نیشانەکان</div>
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
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
</body>

</html>
