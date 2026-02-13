document.addEventListener('DOMContentLoaded', function() {
    initBootstrapFeatures();

    const currentPage = document.body.getAttribute('data-page');
    const currentView = document.body.getAttribute('data-view');

    initDataTables(currentPage, currentView);
    initFilters(currentPage, currentView);
    initSelect2(currentPage, currentView);
});

function initBootstrapFeatures() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const toastElList = [].slice.call(document.querySelectorAll('.toast'));
    toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl).show();
    });
}

function initDataTables(page, view) {
    const tables = {
        'departments': initDepartmentsTable,
        'provinces': initProvincesTable,
        'universities': initUniversitiesTable,
        'colleges': initCollegesTable,
        'students': initStudentsTable,
        'teachers': initTeachersTable,
        'results': initResultsTable
    };

    if (tables[page]) {
        tables[page](view);
    } else {
        initDefaultTable();
    }
}

function initDepartmentsTable(view) {
    if (view !== 'index') return;

    const dt = initDataTable('#datatable');
    setupTableControls(dt);

    if (window.DeptFilters) {
        window.DeptFilters.init(dt, {
            nameColIndex: 2
        });
    }
}

function initProvincesTable(view) {
    if (view !== 'index') return;

    const dt = initDataTable('#datatable');
    setupTableControls(dt);
}

function initUniversitiesTable(view) {
    if (view !== 'index') return;

    const dt = initDataTable('#datatable', {
        columnDefs: [
            { targets: [6], orderable: false }
        ]
    });

    setupTableControls(dt);

    const statusFilter = document.getElementById('filter-status');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const value = this.value;
            if (value === '') {
                dt.columns().search('').draw();
            } else {
                dt.column(5).search(value).draw();
            }
        });
    }

    const resetBtn = document.getElementById('filter-reset');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (statusFilter) statusFilter.value = '';
            dt.columns().search('').draw();
        });
    }
}

function initCollegesTable(view) {
    if (view !== 'index') return;

    const dt = initDataTable('#collegesTable', {
        columnDefs: [
            { targets: [6], orderable: false }
        ]
    });

    setupTableControls(dt);
    initCollegeFilters(dt);
}

function initStudentsTable(view) {
    if (view !== 'index') return;

    const dt = initDataTable('#datatable');
    setupTableControls(dt);
}

function initTeachersTable(view) {
    if (view !== 'index') return;

    const dt = initDataTable('#datatable');
    setupTableControls(dt);
}

function initResultsTable(view) {
    if (view !== 'index') return;

    const dt = initDataTable('#datatable');
    setupTableControls(dt);
}

function initDefaultTable() {
    const table = document.querySelector('#datatable');
    if (table) {
        const dt = initDataTable('#datatable');
        setupTableControls(dt);
    }
}

function setupTableControls(dt) {
    const searchInput = document.getElementById('custom-search');
    const pageLengthSelect = document.getElementById('page-length');
    const infoBox = document.getElementById('dt-info');
    const pagerBox = document.getElementById('dt-pager');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            dt.search(this.value).draw();
        });
    }

    if (pageLengthSelect) {
        pageLengthSelect.addEventListener('change', function() {
            dt.page.len(Number(this.value)).draw();
        });
    }

    if (infoBox && pagerBox) {
        dt.on('draw', function() {
            renderDtInfo(dt, infoBox);
            renderDtPager(dt, pagerBox);
        });

        renderDtInfo(dt, infoBox);
        renderDtPager(dt, pagerBox);
    }
}

function initFilters(page, view) {
    const filters = {
        'colleges': initCollegeFilters
    };

    if (filters[page]) {
        filters[page]();
    }
}

function initCollegeFilters(dt) {
    const provinceFilter = document.getElementById('filter-province');
    const universityFilter = document.getElementById('filter-university');
    const statusFilter = document.getElementById('filter-status');
    const resetBtn = document.getElementById('filter-reset');

    if (provinceFilter && universityFilter) {
        provinceFilter.addEventListener('change', function() {
            const provinceId = this.value;
            universityFilter.innerHTML = '<option value="">هەموو زانکۆكان</option>';
            universityFilter.disabled = !provinceId;

            if (provinceId) {
                fetch(`/sadm/api/universities?province_id=${provinceId}`)
                    .then(response => response.json())
                    .then(universities => {
                        universities.forEach(uni => {
                            const option = document.createElement('option');
                            option.value = uni.id;
                            option.textContent = uni.name;
                            universityFilter.appendChild(option);
                        });
                    });
            }

            applyCollegeFilters(dt);
        });

        universityFilter.addEventListener('change', applyCollegeFilters.bind(null, dt));
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', applyCollegeFilters.bind(null, dt));
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (provinceFilter) provinceFilter.value = '';
            if (universityFilter) {
                universityFilter.innerHTML = '<option value="">هەموو زانکۆكان</option>';
                universityFilter.disabled = true;
            }
            if (statusFilter) statusFilter.value = '';

            dt.columns().search('').draw();
        });
    }
}

function applyCollegeFilters(dt) {
    const provinceFilter = document.getElementById('filter-province');
    const universityFilter = document.getElementById('filter-university');
    const statusFilter = document.getElementById('filter-status');

    dt.column(2).search(provinceFilter ? provinceFilter.value : '');
    dt.column(3).search(universityFilter ? universityFilter.value : '');
    dt.column(5).search(statusFilter ? statusFilter.value : '');

    dt.draw();
}

function initSelect2(page, view) {
    if (page === 'students' && view === 'create') {
        initReferralCodeSelect2();
    }
}

function initReferralCodeSelect2() {
    const referralSelect = document.getElementById('referral_code');
    if (referralSelect) {
        $(referralSelect).select2({
            placeholder: 'کۆدی بانگێشت هەلبژێرە...',
            allowClear: true,
            ajax: {
                url: '/sadm/api/users/search-by-code',
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
