@extends('website.web.admin.layouts.app')

@section('page_name', 'queue-hand-department')
@section('view_name', 'index')
@section('main_container_class', 'container-fluid')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ $dashboardRoute }}">{{ $dashboardLabel }}</a></li>
                            <li class="breadcrumb-item active">ڕێزبەندی دەستی بەشەکان</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fa-solid fa-list-check me-1"></i>
                        ڕێزبەندی دەستی بەشەکان
                    </h4>
                </div>
            </div>
        </div>

        @if (!($hasAccess ?? true))
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto">
                    <div class="card border-warning shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fa-solid fa-triangle-exclamation text-warning" style="font-size: 2.5rem;"></i>
                            </div>
                            <h5 class="mb-2">ئەم تایبەتمەندییە چالاک نییە</h5>
                            <p class="text-muted mb-3">
                                ئێستا دەتوانیت داواکاری پێشکەش بکەیت بۆ زیادکردنی تایبەتمەندی
                                <strong>ڕێزبەندی دەستی بەشەکان</strong>.
                            </p>
                            @if (!empty($requestFeatureRoute))
                                <a href="{{ $requestFeatureRoute }}" class="btn btn-warning">
                                    <i class="fa-solid fa-paper-plane me-1"></i>
                                    ناردنی داواکاری
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label for="searchInput" class="form-label">گەڕان</label>
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="ناسنامە / ناو / وەسف ...">
                        </div>
                        <div class="col-md-2">
                            <label for="systemFilter" class="form-label">سیستەم</label>
                            <select id="systemFilter" class="form-select">
                                <option value="">هەموو</option>
                                @foreach ($systems as $system)
                                    <option value="{{ $system->id }}">{{ $system->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="provinceFilter" class="form-label">پارێزگا</label>
                            <select id="provinceFilter" class="form-select">
                                <option value="">هەموو</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="universityFilter" class="form-label">زانکۆ</label>
                            <select id="universityFilter" class="form-select">
                                <option value="">هەموو</option>
                                @foreach ($universities as $university)
                                    <option value="{{ $university->id }}" data-province-id="{{ $university->province_id }}">
                                        {{ $university->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="collegeFilter" class="form-label">کۆلێژ</label>
                            <select id="collegeFilter" class="form-select">
                                <option value="">هەموو</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}"
                                        data-university-id="{{ $college->university_id }}">
                                        {{ $college->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label for="statusFilter" class="form-label">دۆخ</label>
                            <select id="statusFilter" class="form-select">
                                <option value="">هەموو</option>
                                <option value="1">چالاک</option>
                                <option value="0">ناچالاک</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-md-2">
                            <label for="typeFilter" class="form-label">لق</label>
                            <select id="typeFilter" class="form-select">
                                <option value="">هەموو</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sexFilter" class="form-label">ڕەگەز</label>
                            <select id="sexFilter" class="form-select">
                                <option value="">هەموو</option>
                                @foreach ($sexes as $sex)
                                    <option value="{{ $sex }}">{{ $sex }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="localMinFilter" class="form-label">کەمترین نمرەی ناوخۆ</label>
                            <input type="number" step="0.001" min="0" id="localMinFilter" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="localMaxFilter" class="form-label">زۆرترین نمرەی ناوخۆ</label>
                            <input type="number" step="0.001" min="0" id="localMaxFilter" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="externalMinFilter" class="form-label">کەمترین نمرەی دەرەوە</label>
                            <input type="number" step="0.001" min="0" id="externalMinFilter"
                                class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="externalMaxFilter" class="form-label">زۆرترین نمرەی دەرەوە</label>
                            <input type="number" step="0.001" min="0" id="externalMaxFilter"
                                class="form-control">
                        </div>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-md-2">
                            <label for="perPageFilter" class="form-label">ژمارەی ڕیز</label>
                            <select id="perPageFilter" class="form-select">
                                <option value="10">10</option>
                                <option value="25" selected>25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sortByFilter" class="form-label">ڕیزبەندی بەپێی</label>
                            <select id="sortByFilter" class="form-select">
                                <option value="id">ناسنامە</option>
                                <option value="name">ناو</option>
                                <option value="local_score">نمرەی ناوخۆ</option>
                                <option value="external_score">نمرەی دەرەوە</option>
                                <option value="created_at">بەرواری دروستکردن</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sortDirectionFilter" class="form-label">ئاراستە</label>
                            <select id="sortDirectionFilter" class="form-select">
                                <option value="desc" selected>دابەزین</option>
                                <option value="asc">بەرزبوونەوە</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end justify-content-end gap-2">
                            <button id="resetFiltersBtn" type="button"
                                class="btn btn-outline-secondary btn-sm">پاککردنەوە</button>
                            <button id="reloadDataBtn" type="button" class="btn btn-primary btn-sm">نوێکردنەوە</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mb-0">خشتەی بەشەکان (هەموو کۆلۆمەکان)</h5>
                            <span id="tableMeta" class="text-muted small">0</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0 queue-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>ناسنامە</th>
                                        <th>سیستەم</th>
                                        <th>پارێزگا</th>
                                        <th>زانکۆ</th>
                                        <th>کۆلێژ</th>
                                        <th>ناو</th>
                                        <th>ناوخۆ</th>
                                        <th>دەرەوە</th>
                                        <th>لق</th>
                                        <th>ڕەگەز</th>
                                        <th>وەسف</th>
                                        <th>دۆخ</th>
                                        <th>کردار</th>
                                    </tr>
                                </thead>
                                <tbody id="departmentsTableBody">
                                    <tr>
                                        <td colspan="13" class="text-center py-4">
                                            <div class="spinner-border" role="status"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-light">
                            <ul class="pagination justify-content-center mb-0" id="pagination"></ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card sticky-top" style="top: 1rem;">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="mb-0"><i class="fa-solid fa-list-ol me-1"></i>لیستی هەڵبژاردن</h6>
                            <span class="badge bg-primary" id="selectionCountBadge">
                                0{{ !empty($maxSelection) ? '/' . (int) $maxSelection : '' }}
                            </span>
                        </div>
                        <div class="card-body border-bottom">
                            <label for="studentSelector" class="form-label fw-semibold mb-1">قوتابی</label>
                            <select id="studentSelector" class="form-select form-select-sm">
                                <option value="">قوتابی هەڵبژێرە</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}" data-name="{{ $student->user->name ?? '' }}"
                                        data-code="{{ $student->user->code ?? '' }}"
                                        data-mark="{{ $student->mark ?? '' }}"
                                        data-province="{{ $student->province ?? '' }}"
                                        data-type="{{ $student->type ?? '' }}"
                                        data-gender="{{ $student->gender ?? '' }}">
                                        {{ $student->user->name ?? '—' }}
                                        @if (!empty($student->user->code))
                                            ({{ $student->user->code }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <button id="openAddStudentModalBtn" type="button"
                                class="btn btn-outline-primary btn-sm w-100 mt-2">
                                <i class="fa-solid fa-user-plus me-1"></i> زیادکردنی قوتابی
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div id="selectedList" class="list-group list-group-flush selected-list-wrap">
                                <div class="p-3 text-muted text-center">هێشتا هیچ بەشێک زیاد نەکراوە.</div>
                            </div>
                        </div>
                        <div class="card-footer d-grid gap-2">
                            <button id="resetSelectionBtn" type="button"
                                class="btn btn-outline-danger btn-sm">پاککردنەوەی لیست</button>
                            <button id="printSelectionBtn" type="button" class="btn btn-success btn-sm">چاپی
                                هەڵبژاردن</button>
                            <button id="saveResultDepsBtn" type="button" class="btn btn-primary btn-sm d-none">
                                <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوتی ڕێزبەندیی قوتابی
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    @endif

    @if ($hasAccess ?? true)
        <div class="modal fade" id="descriptionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">وەسف</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0 white-space-preline" id="descriptionModalBody">—</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-solid fa-user-plus me-1"></i> زیادکردنی قوتابی</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="quickAddStudentForm">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="quickStudentName" class="form-label">ناوی قوتابی</label>
                                    <input id="quickStudentName" name="name" type="text" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="quickStudentCode" class="form-label">کۆدی چوونەژوورەوە</label>
                                    <input id="quickStudentCode" name="code" type="text" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="quickStudentPhone" class="form-label">ژمارەی مۆبایل</label>
                                    <input id="quickStudentPhone" name="phone" type="text" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="quickStudentPassword" class="form-label">وشەی نهێنی</label>
                                    <input id="quickStudentPassword" name="password" type="text" class="form-control"
                                        value="12345678">
                                </div>
                                <div class="col-md-4">
                                    <label for="quickStudentMark" class="form-label">نمرە</label>
                                    <input id="quickStudentMark" name="mark" type="number" step="0.001"
                                        min="0" max="100" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="quickStudentProvince" class="form-label">پارێزگا</label>
                                    <select id="quickStudentProvince" name="province" class="form-select" required>
                                        <option value="">هەڵبژێرە</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->name }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="quickStudentYear" class="form-label">پڕکردنەوەی فۆرم</label>
                                    <select id="quickStudentYear" name="year" class="form-select" required>
                                        <option value="1" selected>1</option>
                                        <option value="2">زیاتر لە 2</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="quickStudentType" class="form-label">لق</label>
                                    <select id="quickStudentType" name="type" class="form-select" required>
                                        <option value="زانستی" selected>زانستی</option>
                                        <option value="وێژەیی">وێژەیی</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="quickStudentGender" class="form-label">ڕەگەز</label>
                                    <select id="quickStudentGender" name="gender" class="form-select" required>
                                        <option value="نێر" selected>نێر</option>
                                        <option value="مێ">مێ</option>
                                    </select>
                                </div>
                                <input id="quickStudentLat" name="lat" type="hidden">
                                <input id="quickStudentLng" name="lng" type="hidden">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">هەڵوەشاندنەوە</button>
                        <button id="submitQuickStudentBtn" type="button" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوتکردن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div id="printFooterContent" class="d-none">
        @include('website.web.admin.layouts.footer')
    </div>
@endsection

@push('styles')
    <style>
        .queue-table th,
        .queue-table td {
            white-space: nowrap;
            font-size: 0.83rem;
        }

        .queue-table td.description-cell {
            max-width: 170px;
            white-space: normal;
        }

        .selected-list-wrap {
            max-height: 68vh;
            overflow-y: auto;
        }

        .selected-row .drag-handle {
            cursor: grab;
        }

        .white-space-preline {
            white-space: pre-line;
        }
    </style>
@endpush

@if ($hasAccess ?? true)
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            $(function() {
                const dataUrl = @json($dataRoute);
                const storeStudentUrl = @json($storeStudentRoute);
                const saveResultDepsUrl = @json($saveResultDepsRoute);
                const studentSelectionUrl = @json($studentSelectionRoute);
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                const maxSelection = @json($maxSelection ?? null);
                const printConfig = {
                    logo: "{{ asset($appSettings['site_logo'] ?? 'images/logo.png') }}",
                    siteName: "{{ $appSettings['site_name'] ?? 'ToCollegePortal' }}",
                    author: "ئەندازیار عبدالرحمن",
                    authorPhone: "075043424",
                    currentUser: "{{ auth()->user()->name }}",
                    currentRole: "{{ auth()->user()->role === 'admin' ? 'بەرێوبەر' : (auth()->user()->role === 'center' ? 'سەنتەر' : (auth()->user()->role === 'teacher' ? 'مامۆستا' : '')) }}",
                    fontKu: "{{ isset($appSettings['font_ku']) ? asset($appSettings['font_ku']) : '' }}",
                    fontAr: "{{ isset($appSettings['font_ar']) ? asset($appSettings['font_ar']) : '' }}",
                    fontEn: "{{ isset($appSettings['font_en']) ? asset($appSettings['font_en']) : '' }}"
                };
                const modalElement = document.getElementById('descriptionModal');
                const descModal = modalElement ? new bootstrap.Modal(modalElement) : null;
                const addStudentModalElement = document.getElementById('addStudentModal');
                const addStudentModal = addStudentModalElement ? new bootstrap.Modal(addStudentModalElement) :
                    null;
                const $studentSelector = $('#studentSelector');
                const selected = [];
                let itemsMap = {};
                let pager = {
                    current_page: 1,
                    last_page: 1,
                    total: 0
                };
                let studentSelectionRequestNonce = 0;
                let timer = null;
                const $provinceFilter = $('#provinceFilter');
                const $universityFilter = $('#universityFilter');
                const $collegeFilter = $('#collegeFilter');
                @php
                    $requiresStudentLocation = false;
                    if (in_array(auth()->user()->role, ['center', 'teacher'], true)) {
                        $ownerAiRank = auth()->user()->role === 'center' ? (int) (auth()->user()?->center?->ai_rank ?? 0) : (int) (auth()->user()?->teacher?->ai_rank ?? 0);
                        $requiresStudentLocation = $ownerAiRank === 1;
                    }
                @endphp
                const requiresStudentLocation = @json($requiresStudentLocation);
                const $quickLatInput = $('#quickStudentLat');
                const $quickLngInput = $('#quickStudentLng');
                let quickLocatingInProgress = false;

                const quickHasLocation = () => {
                    const lat = String($quickLatInput.val() ?? '').trim();
                    const lng = String($quickLngInput.val() ?? '').trim();
                    return lat !== '' && lng !== '';
                };

                const fillQuickLocationFromBrowser = () => new Promise((resolve) => {
                    if (!requiresStudentLocation || !$quickLatInput.length || !$quickLngInput.length || !
                        navigator.geolocation || quickLocatingInProgress) {
                        resolve(false);
                        return;
                    }

                    quickLocatingInProgress = true;
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            $quickLatInput.val(Number(position.coords.latitude).toFixed(7));
                            $quickLngInput.val(Number(position.coords.longitude).toFixed(7));
                            quickLocatingInProgress = false;
                            resolve(true);
                        },
                        () => {
                            quickLocatingInProgress = false;
                            resolve(false);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0,
                        }
                    );
                });

                if ($studentSelector.length) {
                    $studentSelector.select2({
                        width: '100%',
                        placeholder: 'قوتابی هەڵبژێرە',
                        allowClear: true
                    });
                }

                function collectCascadeOptions($select, relationAttr) {
                    return $select.find('option').map(function() {
                        const value = String($(this).val() ?? '');
                        if (!value) return null;
                        return {
                            value,
                            text: $(this).text(),
                            parentId: String($(this).attr(relationAttr) ?? '')
                        };
                    }).get().filter(Boolean);
                }

                const allUniversities = collectCascadeOptions($universityFilter, 'data-province-id');
                const allColleges = collectCascadeOptions($collegeFilter, 'data-university-id');
                const provinceByUniversityId = {};
                allUniversities.forEach((item) => {
                    provinceByUniversityId[item.value] = item.parentId;
                });

                function setCascadeOptions($select, options, relationAttr, preferredValue = '') {
                    const targetValue = String(preferredValue ?? '');
                    $select.empty().append($('<option>', {
                        value: '',
                        text: 'هەموو'
                    }));

                    options.forEach((item) => {
                        const $option = $('<option>', {
                            value: item.value,
                            text: item.text
                        });
                        if (relationAttr) {
                            $option.attr(relationAttr, item.parentId || '');
                        }
                        $select.append($option);
                    });

                    const canKeep = options.some((item) => item.value === targetValue);
                    $select.val(canKeep ? targetValue : '');
                }

                function syncUniversityFilter() {
                    const provinceId = String($provinceFilter.val() ?? '');
                    const currentUniversityId = String($universityFilter.val() ?? '');
                    const options = provinceId ?
                        allUniversities.filter((item) => item.parentId === provinceId) :
                        allUniversities;

                    setCascadeOptions($universityFilter, options, 'data-province-id', currentUniversityId);
                }

                function syncCollegeFilter() {
                    const provinceId = String($provinceFilter.val() ?? '');
                    const universityId = String($universityFilter.val() ?? '');
                    const currentCollegeId = String($collegeFilter.val() ?? '');

                    let options = allColleges;
                    if (universityId) {
                        options = allColleges.filter((item) => item.parentId === universityId);
                    } else if (provinceId) {
                        options = allColleges.filter((item) => provinceByUniversityId[item.parentId] === provinceId);
                    }

                    setCascadeOptions($collegeFilter, options, 'data-university-id', currentCollegeId);
                }

                function syncCascadeFilters() {
                    syncUniversityFilter();
                    syncCollegeFilter();
                }

                function filters() {
                    return {
                        search: $('#searchInput').val().trim(),
                        system_id: $('#systemFilter').val(),
                        province_id: $('#provinceFilter').val(),
                        university_id: $('#universityFilter').val(),
                        college_id: $('#collegeFilter').val(),
                        type: $('#typeFilter').val(),
                        sex: $('#sexFilter').val(),
                        status: $('#statusFilter').val(),
                        local_score_min: $('#localMinFilter').val(),
                        local_score_max: $('#localMaxFilter').val(),
                        external_score_min: $('#externalMinFilter').val(),
                        external_score_max: $('#externalMaxFilter').val(),
                        per_page: $('#perPageFilter').val(),
                        sort_by: $('#sortByFilter').val(),
                        sort_direction: $('#sortDirectionFilter').val()
                    };
                }

                function load(page = 1) {
                    const params = new URLSearchParams(filters());
                    params.set('page', page);
                    $.getJSON(`${dataUrl}?${params.toString()}`, function(resp) {
                        pager = resp;
                        renderTable(resp.data || []);
                        renderPager();
                        $('#tableMeta').text(`${resp.total || 0} تۆمار`);
                    }).fail(function() {
                        $('#departmentsTableBody').html(
                            '<tr><td colspan="13" class="text-center text-danger py-4">هەڵەی بارکردن</td></tr>'
                        );
                    });
                }

                function selectedIds() {
                    return selected.map((s) => Number(s.id));
                }

                function systemBadgeClass(systemId) {
                    const id = Number(systemId || 0);
                    if (id === 1) return 'bg-primary';
                    if (id === 2) return 'bg-danger';
                    return 'bg-dark';
                }

                function renderTable(rows) {
                    itemsMap = {};
                    if (!rows.length) {
                        $('#departmentsTableBody').html(
                            '<tr><td colspan="13" class="text-center py-4">هیچ تۆمارێک نەدۆزرایەوە.</td></tr>');
                        return;
                    }
                    const ids = selectedIds();
                    let html = '';
                    rows.forEach((d) => {
                        itemsMap[String(d.id)] = d;
                        const added = ids.includes(Number(d.id));
                        const desc = (d.description || '').toString();
                        const safeDesc = $('<div>').text(desc).html();
                        const shortDesc = desc.length > 32 ? `${desc.substring(0, 32)}...` : (desc || '—');
                        const status = Number(d.status) === 1 ? '<span class="badge bg-success">چالاک</span>' :
                            '<span class="badge bg-danger">ناچالاک</span>';
                        const systemClass = systemBadgeClass(d.system?.id);
                        const systemBadge =
                            `<span class="badge ${systemClass}">${d.system?.name ?? '-'}</span>`;
                        const btn = added ?
                            '<button class="btn btn-sm btn-outline-secondary w-100" disabled>زیادکراو</button>' :
                            `<button class="btn btn-sm btn-primary w-100 add-btn" data-id="${d.id}">زیادکردن</button>`;

                        html += `
                        <tr>
                            <td>${d.id ?? '-'}</td>
                            <td>${systemBadge}</td>
                            <td>${d.province?.name ?? '-'}</td>
                            <td>${d.university?.name ?? '-'}</td>
                            <td>${d.college?.name ?? '-'}</td>
                            <td>${d.name ?? '-'}</td>
                            <td>${d.local_score ?? '-'}</td>
                            <td>${d.external_score ?? '-'}</td>
                            <td>${d.type ?? '-'}</td>
                            <td>${d.sex ?? '-'}</td>
                            <td class="description-cell"><button class="btn btn-link btn-sm p-0 show-desc-btn" data-description="${safeDesc}">${shortDesc}</button></td>
                            <td>${status}</td>
                            <td>${btn}</td>
                        </tr>
                    `;
                    });
                    $('#departmentsTableBody').html(html);
                }

                function renderPager() {
                    const current = Number(pager.current_page || 1);
                    const last = Number(pager.last_page || 1);
                    if (last <= 1) {
                        $('#pagination').empty();
                        return;
                    }
                    const start = Math.max(1, current - 2);
                    const end = Math.min(last, current + 2);
                    let html =
                        `<li class="page-item ${current <= 1 ? 'disabled' : ''}"><button class="page-link go-page" data-page="${current - 1}">پێشوو</button></li>`;
                    for (let i = start; i <= end; i++) {
                        html +=
                            `<li class="page-item ${i === current ? 'active' : ''}"><button class="page-link go-page" data-page="${i}">${i}</button></li>`;
                    }
                    html +=
                        `<li class="page-item ${current >= last ? 'disabled' : ''}"><button class="page-link go-page" data-page="${current + 1}">داهاتوو</button></li>`;
                    $('#pagination').html(html);
                }

                function renderSelected() {
                    if (!selected.length) {
                        $('#selectedList').html(
                            '<div class="p-3 text-muted text-center">هێشتا هیچ بەشێک زیاد نەکراوە.</div>');
                        $('#selectionCountBadge').text(maxSelection ? `0/${maxSelection}` : '0');
                        updateSaveResultDepsButtonState();
                        return;
                    }
                    let html = '';
                    selected.forEach((item, idx) => {
                        const systemClass = systemBadgeClass(item.system_id);
                        html += `
                        <div class="list-group-item selected-row" data-id="${item.id}">
                            <div class="d-flex align-items-center gap-2">
                                <span class="drag-handle text-muted"><i class="fa-solid fa-grip-vertical"></i></span>
                                <span class="badge bg-light text-dark">${idx + 1}</span>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">
                                        ${item.name ?? '-'}
                                        <span class="badge ${systemClass} ms-1">${item.system ?? '-'}</span>
                                    </div>
                                    <div class="text-muted" style="font-size:12px">${item.university ?? '-'} / ${item.college ?? '-'}</div>
                                </div>
                                <button class="btn btn-sm btn-outline-danger rm-btn" data-id="${item.id}"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                    `;
                    });
                    $('#selectedList').html(html);
                    $('#selectionCountBadge').text(maxSelection ? `${selected.length}/${maxSelection}` : String(selected
                        .length));
                    updateSaveResultDepsButtonState();
                }

                function add(id) {
                    if (selected.some((x) => Number(x.id) === Number(id))) return;
                    if (maxSelection && selected.length >= Number(maxSelection)) {
                        alert(`ناتوانیت زیاتر لە ${maxSelection} بەش زیاد بکەیت.`);
                        return;
                    }
                    const d = itemsMap[String(id)];
                    if (!d) return;
                    selected.push({
                        id: d.id,
                        name: d.name,
                        system_id: d.system?.id ?? null,
                        system: d.system?.name ?? '-',
                        province: d.province?.name ?? '-',
                        university: d.university?.name ?? '-',
                        college: d.college?.name ?? '-',
                        local: d.local_score,
                        external: d.external_score
                    });
                    renderSelected();
                    renderTable(Object.values(itemsMap));
                }

                function remove(id) {
                    const idx = selected.findIndex((x) => Number(x.id) === Number(id));
                    if (idx >= 0) selected.splice(idx, 1);
                    renderSelected();
                    renderTable(Object.values(itemsMap));
                }

                function resetFilters() {
                    $('#searchInput,#systemFilter,#provinceFilter,#universityFilter,#collegeFilter,#typeFilter,#sexFilter,#statusFilter,#localMinFilter,#localMaxFilter,#externalMinFilter,#externalMaxFilter')
                        .val('');
                    $('#perPageFilter').val('25');
                    $('#sortByFilter').val('id');
                    $('#sortDirectionFilter').val('desc');
                    syncCascadeFilters();
                    load(1);
                }

                function getSelectedStudentId() {
                    const value = Number($studentSelector.val() || 0);
                    return value > 0 ? value : null;
                }

                function escapeHtml(value) {
                    return $('<div>').text(String(value ?? '')).html();
                }

                function getSelectedStudentInfo() {
                    const studentId = getSelectedStudentId();
                    if (!studentId) {
                        return null;
                    }

                    const $option = $studentSelector.find('option:selected');
                    if (!$option.length || !String($option.val() || '').trim()) {
                        return null;
                    }

                    const name = String($option.attr('data-name') || '').trim();
                    const code = String($option.attr('data-code') || '').trim();
                    const mark = String($option.attr('data-mark') || '').trim();
                    const province = String($option.attr('data-province') || '').trim();
                    const type = String($option.attr('data-type') || '').trim();
                    const gender = String($option.attr('data-gender') || '').trim();

                    return {
                        name: name || '—',
                        code: code || '—',
                        mark: mark || '—',
                        province: province || '—',
                        type: type || '—',
                        gender: gender || '—',
                    };
                }

                function applyStudentSelectionFromServer(rows) {
                    const next = [];
                    const seen = new Set();

                    (Array.isArray(rows) ? rows : []).forEach((item) => {
                        const departmentId = Number(item?.id || 0);
                        if (departmentId <= 0 || seen.has(departmentId)) {
                            return;
                        }

                        seen.add(departmentId);
                        next.push({
                            id: departmentId,
                            name: item?.name ?? '-',
                            system_id: item?.system_id ?? null,
                            system: item?.system ?? '-',
                            province: item?.province ?? '-',
                            university: item?.university ?? '-',
                            college: item?.college ?? '-',
                            local: item?.local ?? null,
                            external: item?.external ?? null,
                        });
                    });

                    selected.splice(0, selected.length, ...next);
                    renderSelected();
                    renderTable(Object.values(itemsMap));
                }

                function loadSavedSelectionForStudent(studentId) {
                    const numericStudentId = Number(studentId || 0);
                    const currentRequestNonce = ++studentSelectionRequestNonce;

                    if (numericStudentId <= 0) {
                        applyStudentSelectionFromServer([]);
                        return;
                    }

                    applyStudentSelectionFromServer([]);

                    if (!studentSelectionUrl) {
                        return;
                    }

                    const params = new URLSearchParams({
                        student_id: String(numericStudentId)
                    });

                    $.getJSON(`${studentSelectionUrl}?${params.toString()}`, function(resp) {
                        if (currentRequestNonce !== studentSelectionRequestNonce) {
                            return;
                        }
                        applyStudentSelectionFromServer(resp?.data || []);
                    }).fail(function(xhr) {
                        if (currentRequestNonce !== studentSelectionRequestNonce) {
                            return;
                        }
                        applyStudentSelectionFromServer([]);
                        alert(firstErrorMessage(xhr));
                    });
                }

                function updateSaveResultDepsButtonState() {
                    const $btn = $('#saveResultDepsBtn');
                    if (!$btn.length) {
                        return;
                    }

                    const hasStudent = !!getSelectedStudentId();
                    const hasSelection = selected.length > 0;

                    $btn.toggleClass('d-none', !hasStudent);
                    $btn.prop('disabled', !hasStudent || !hasSelection);
                }

                function firstErrorMessage(xhr) {
                    const errors = xhr?.responseJSON?.errors || null;
                    if (errors && typeof errors === 'object') {
                        const firstKey = Object.keys(errors)[0];
                        if (firstKey && Array.isArray(errors[firstKey]) && errors[firstKey].length) {
                            return String(errors[firstKey][0]);
                        }
                    }
                    return xhr?.responseJSON?.message || 'هەڵەیەک ڕوویدا.';
                }

                function buildSelectionRowsHtml() {
                    return selected.map((x, i) => `
                        <tr>
                            <td class="text-center">${i + 1}</td>
                            <td>
                                <span class="sys-badge">${x.system ?? '-'}</span>
                                <span class="sep"><i class="fa-solid fa-angle-left"></i></span>
                                <span class="uni-col">${x.province ?? '-'}</span>
                                <span class="sep"><i class="fa-solid fa-angle-left"></i></span>
                                <span class="uni-col fw-bold">${x.university ?? '-'}</span>
                                <span class="sep"><i class="fa-solid fa-angle-left"></i></span>
                                <span class="uni-col">${x.college ?? '-'}</span>
                                <span class="sep"><i class="fa-solid fa-angle-left"></i></span>
                                <span class="dep-name test-primary">${x.name ?? '-'}</span>
                            </td>
                            <td class="text-center fw-bold text-success" style="font-size: 13px">${x.local ?? '-'}</td>
                        </tr>
                    `).join('');
                }

                function buildPrintStyles() {
                    return `
                        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;700&display=swap');
                        ${printConfig.fontKu ? `@font-face { font-family: 'CustomKu'; src: url('${printConfig.fontKu}'); font-display: swap; }` : ''}
                        ${printConfig.fontAr ? `@font-face { font-family: 'CustomAr'; src: url('${printConfig.fontAr}'); font-display: swap; }` : ''}
                        body {
                            font-family: ${printConfig.fontKu ? "'CustomKu'," : ''} ${printConfig.fontAr ? "'CustomAr'," : ''} "Noto Sans Arabic", "Tahoma", sans-serif;
                            background: #fff;
                            color: #000 !important;
                            padding: 20px;
                            min-height: 100vh;
                            position: relative;
                        }
                        .watermark {
                            position: fixed;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%) rotate(-45deg);
                            text-align: center;
                            font-weight: 900;
                            color: rgba(0, 0, 0, 0.04);
                            z-index: -1;
                            pointer-events: none;
                            white-space: nowrap;
                        }
                        .watermark-main { font-size: 90px; }
                        .watermark-sub { font-size: 70px; display: block; margin-top: 10px; }
                        .watermark-sub-phone { font-size: 70px; display: block; margin-top: 10px; }
                        .print-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            border-bottom: 2px solid #000;
                            padding-bottom: 15px;
                            margin-bottom: 20px;
                        }
                        .header-logo img { height: 60px; width: auto; }
                        .header-info { text-align: right; }
                        .header-name { font-size: 20px; font-weight: 800; color: #000; }
                        .header-date { font-size: 12px; color: #666; margin-top: 5px; }
                        .student-meta {
                            margin-bottom: 12px;
                            font-size: 10px;
                            color: #1f2937;
                            border: 1px solid #d1d5db;
                            border-radius: 6px;
                            padding: 6px 8px;
                            background: #f9fafb;
                        }
                        .student-meta-row {
                            display: flex;
                            flex-wrap: wrap;
                            align-items: center;
                            gap: 4px 14px;
                        }
                        .student-meta-item {
                            white-space: nowrap;
                        }
                        .student-meta-label {
                            font-weight: 700;
                            color: #111827;
                            margin-left: 4px;
                        }
                        table { width: 100%; margin-bottom: 20px; font-size: 12px; }
                        th, td { border: 1px solid #dee2e6; padding: 6px 8px; vertical-align: middle; }
                        th { background-color: #f8f9fa !important; color: #000; font-weight: 800; border-bottom: 2px solid #000; }
                        tr:nth-child(even) { background-color: #fcfcfc; }
                        .sys-badge { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-weight: 600; font-size: 11px; border: 1px solid #e5e7eb; }
                        .dep-name { font-weight: 800; font-size: 13px; color: #000; }
                        .uni-col { color: #4b5563; }
                        .sep { color: #9ca3af; margin: 0 4px; font-size: 10px; }
                        #print-footer { margin-top: auto; padding-top: 15px; border-top: 1px solid #eee; font-size: 10px; }
                        #print-footer .bg-dark { background: none !important; }
                        #print-footer .text-light { color: #000 !important; }
                        #print-footer .footer { margin-top: 0 !important; padding-top: 5px !important; }
                        .footer-logo i { color: #000 !important; }
                    `;
                }

                function buildPrintableBodyHtml(now) {
                    const rows = buildSelectionRowsHtml();
                    const footerHtml = $('#printFooterContent').html();
                    const studentInfo = getSelectedStudentInfo();
                    const studentInfoHtml = studentInfo ? `
                        <div class="student-meta">
                            <div class="student-meta-row justify-content-between align-items-center m-auto">
                                <span class="student-meta-item"><span class="student-meta-label">ناوی قوتابی:</span>${escapeHtml(studentInfo.name)}</span>
                                <span class="student-meta-item"><span class="student-meta-label">کۆدی داخیل بوون:</span>${escapeHtml(studentInfo.code)}</span>
                                <span class="student-meta-item"><span class="student-meta-label">نمرە:</span>${escapeHtml(studentInfo.mark)}</span>
                                <span class="student-meta-item"><span class="student-meta-label">پارێزگا:</span>${escapeHtml(studentInfo.province)}</span>
                                <span class="student-meta-item"><span class="student-meta-label">لق:</span>${escapeHtml(studentInfo.type)}</span>
                                <span class="student-meta-item"><span class="student-meta-label">ڕەگەز:</span>${escapeHtml(studentInfo.gender)}</span>
                            </div>
                        </div>
                        <hr>
                    ` : '';

                    return `
                        <div class="watermark">
                            <div class="watermark-main">${printConfig.siteName}</div>
                            <div class="watermark-sub">${printConfig.author}</div>
                            <div class="watermark-sub-phone">${printConfig.authorPhone}</div>
                        </div>
                        <div class="print-header">
                            <div class="header-info">
                                <div class="header-name">${printConfig.author}</div>
                                <div class="header-date">بەروار: ${now}</div>
                                <div class="mt-1 small">کۆی هەڵبژاردن: ${selected.length}</div>
                            </div>
                            <div class="header-logo">
                                <img src="${printConfig.logo}" alt="Logo">
                            </div>
                        </div>
                        ${studentInfoHtml}
                        <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                            <div class="fw-bold fs-6">ناو : ${printConfig.currentUser}</div>
                            <h5 class="fw-bold m-0">لیستی هەڵبژاردنی بەشەکان</h5>
                            <div class="fw-bold fs-6">پیشە : ${printConfig.currentRole}</div>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 50px" class="text-center">ڕیز</th>
                                    <th>زانیاری بەش (سیستەم / پارێزگا / زانکۆ / کۆلێژ / بەش)</th>
                                    <th style="width: 100px" class="text-center">نمرەی ناوخۆ</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                        <div id="print-footer">${footerHtml}</div>
                    `;
                }

                function printSelection() {
                    if (!selected.length) {
                        alert('هیچ بەشێک بۆ چاپ هەڵنەبژێردراوە.');
                        return;
                    }

                    const now = new Date().toLocaleString('en-GB');
                    const w = window.open('', '_blank');
                    if (!w) return;

                    w.document.write(`
                        <html lang="ku" dir="rtl">
                            <head>
                                <title>چاپی لیستی هەڵبژاردن</title>
                                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
                                <style>${buildPrintStyles()}</style>
                            </head>
                            <body>${buildPrintableBodyHtml(now)}</body>
                        </html>
                    `);
                    w.document.close();
                    w.focus();
                    setTimeout(() => w.print(), 500);
                }

                function saveSelectionToResultDeps() {
                    if (!selected.length) {
                        alert('هیچ بەشێک بۆ پاشەکەوتکردن هەڵنەبژێردراوە.');
                        return;
                    }

                    const studentId = getSelectedStudentId();
                    if (!studentId) {
                        alert('تکایە سەرەتا قوتابییەک هەڵبژێرە.');
                        return;
                    }

                    if (!saveResultDepsUrl) {
                        alert('ڕێگای پاشەکەوتکردنی ڕێزبەندی بەردەست نییە.');
                        return;
                    }

                    const $saveBtn = $('#saveResultDepsBtn');
                    const originalBtnHtml = $saveBtn.html();
                    $saveBtn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-1"></span> چاوەڕوانبە...');

                    $.ajax({
                        url: saveResultDepsUrl,
                        method: 'POST',
                        contentType: 'application/json; charset=utf-8',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: JSON.stringify({
                            student_id: studentId,
                            selection_payload: selected,
                        }),
                    }).done(function(response) {
                        alert(response?.message || 'ڕێزبەندی بە سەرکەوتوویی پاشەکەوتکرا.');
                    }).fail(function(xhr) {
                        alert(firstErrorMessage(xhr));
                    }).always(function() {
                        $saveBtn.html(originalBtnHtml);
                        updateSaveResultDepsButtonState();
                    });
                }

                function appendStudentOption(student) {
                    if (!student || !student.id) {
                        return;
                    }

                    const studentId = String(student.id);
                    const label = student.code ? `${student.name} (${student.code})` : (student.name || studentId);
                    let $option = $studentSelector.find(`option[value="${studentId}"]`);
                    const attrs = {
                        'data-name': student.name || '',
                        'data-code': student.code || '',
                        'data-mark': student.mark ?? '',
                        'data-province': student.province || '',
                        'data-type': student.type || '',
                        'data-gender': student.gender || '',
                    };

                    if (!$option.length) {
                        $option = $('<option>', {
                            value: studentId,
                            text: label
                        }).attr(attrs);
                        $studentSelector.append($option);
                    } else {
                        $option.text(label).attr(attrs);
                    }

                    $studentSelector.val(studentId).trigger('change');
                }

                async function submitQuickStudent() {
                    if (!storeStudentUrl) {
                        alert('ڕێگای زیادکردنی قوتابی بەردەست نییە.');
                        return;
                    }

                    if (requiresStudentLocation && !quickHasLocation()) {
                        const locationOk = await fillQuickLocationFromBrowser();
                        if (!locationOk) {
                            alert('نەتوانرا شوێنی قوتابی وەربگیرێت. تکایە ڕێگەبدە بە Location.');
                            return;
                        }
                    }

                    const $submitBtn = $('#submitQuickStudentBtn');
                    const originalBtnHtml = $submitBtn.html();
                    $submitBtn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-1"></span> چاوەڕوانبە...');

                    $.ajax({
                        url: storeStudentUrl,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: $('#quickAddStudentForm').serialize(),
                    }).done(function(response) {
                        appendStudentOption(response?.student || null);
                        $('#quickAddStudentForm')[0].reset();
                        $('#quickStudentPassword').val('12345678');
                        if (addStudentModal) {
                            addStudentModal.hide();
                        }
                        alert(response?.message || 'قوتابی بە سەرکەوتوویی زیادکرا.');
                    }).fail(function(xhr) {
                        alert(firstErrorMessage(xhr));
                    }).always(function() {
                        $submitBtn.prop('disabled', false).html(originalBtnHtml);
                    });
                }

                $('#departmentsTableBody').on('click', '.add-btn', function() {
                    add($(this).data('id'));
                });
                $('#departmentsTableBody').on('click', '.show-desc-btn', function() {
                    const decoded = $('<div>').html($(this).data('description') || '—').text();
                    $('#descriptionModalBody').text(decoded || '—');
                    if (descModal) descModal.show();
                });
                $('#selectedList').on('click', '.rm-btn', function() {
                    remove($(this).data('id'));
                });
                $('#pagination').on('click', '.go-page', function() {
                    const p = Number($(this).data('page'));
                    const last = Number(pager.last_page || 1);
                    if (p >= 1 && p <= last) load(p);
                });

                $('#searchInput').on('input', function() {
                    clearTimeout(timer);
                    timer = setTimeout(() => load(1), 250);
                });
                $('#localMinFilter,#localMaxFilter,#externalMinFilter,#externalMaxFilter').on('input', function() {
                    clearTimeout(timer);
                    timer = setTimeout(() => load(1), 250);
                });
                $('#provinceFilter').on('change', function() {
                    syncUniversityFilter();
                    syncCollegeFilter();
                    load(1);
                });
                $('#universityFilter').on('change', function() {
                    syncCollegeFilter();
                    load(1);
                });
                $('#systemFilter,#collegeFilter,#typeFilter,#sexFilter,#statusFilter,#perPageFilter,#sortByFilter,#sortDirectionFilter')
                    .on('change', function() {
                        load(1);
                    });
                $('#resetFiltersBtn').on('click', resetFilters);
                $('#reloadDataBtn').on('click', function() {
                    load(1);
                });
                $('#resetSelectionBtn').on('click', function() {
                    selected.splice(0, selected.length);
                    renderSelected();
                    renderTable(Object.values(itemsMap));
                });
                $('#printSelectionBtn').on('click', printSelection);
                $('#saveResultDepsBtn').on('click', saveSelectionToResultDeps);
                $studentSelector.on('change', function() {
                    loadSavedSelectionForStudent($(this).val());
                    updateSaveResultDepsButtonState();
                });
                $('#openAddStudentModalBtn').on('click', function() {
                    if (addStudentModal) {
                        addStudentModal.show();
                    }
                    if (requiresStudentLocation && !quickHasLocation()) {
                        void fillQuickLocationFromBrowser();
                    }
                });
                $('#submitQuickStudentBtn').on('click', submitQuickStudent);
                $('#quickAddStudentForm').on('submit', function(e) {
                    e.preventDefault();
                    void submitQuickStudent();
                });

                const listEl = document.getElementById('selectedList');
                if (listEl && typeof Sortable !== 'undefined') {
                    Sortable.create(listEl, {
                        handle: '.drag-handle',
                        animation: 150,
                        onEnd: function() {
                            const next = [];
                            $('#selectedList .selected-row').each(function() {
                                const id = Number($(this).data('id'));
                                const found = selected.find((x) => Number(x.id) === id);
                                if (found) next.push(found);
                            });
                            selected.splice(0, selected.length, ...next);
                            renderSelected();
                        }
                    });
                }

                syncCascadeFilters();
                loadSavedSelectionForStudent(getSelectedStudentId());
                updateSaveResultDepsButtonState();
                load(1);
            });
        </script>
    @endpush
@endif
