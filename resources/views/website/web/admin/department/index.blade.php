@extends('website.web.admin.layouts.app')

@section('page_name', 'department')
@section('view_name', 'index')

@section('content')
    <div class="container-fluid py-4">
        {{-- Actions bar --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">بەشەکان</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-building-columns me-1"></i>
                        ناوی بەشەکەکان
                    </h4>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="d-flex gap-2">
                {{--  <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-gear me-1"></i> بەڕێوەبەرایەتی
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('admin.systems.create') }}" class="dropdown-item">
                        <i class="fa-solid fa-cube me-2"></i> سیستەمەکان
                    </a>
                    <a href="{{ route('admin.provinces.create') }}" class="dropdown-item">
                        <i class="fa-solid fa-map-location-dot me-2"></i> پارێزگاکان
                    </a>
                    <a href="{{ route('admin.universities.create') }}" class="dropdown-item">
                        <i class="fa-solid fa-building-columns me-2"></i> زانکۆکان
                    </a>
                </div>
            </div>  --}}

                <div class="dropdown">
                    <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-file-import me-1"></i> Import/Export
                    </button>
                    <div class="dropdown-menu">
                        <a href="#importModal" class="dropdown-item" data-bs-toggle="modal">
                            <i class="fa-solid fa-file-import me-2"></i> Import بەشەکان
                        </a>
                        <a href="{{ route('admin.departments.export') }}" class="dropdown-item">
                            <i class="fa-solid fa-file-export me-2"></i> Export بەشەکان
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.departments.download-template') }}" class="dropdown-item">
                            <i class="fa-solid fa-file-excel me-2"></i> داگرتنی نموونە
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> زیادکردنی بەش
                </a>
                <a href="{{ route('admin.departments.compare-descriptions') }}" class="btn btn-outline-info">
                    <i class="fa-solid fa-code-compare me-1"></i> بەراوردکردنی وەسف
                </a>
            </div>

            <div></div>
        </div>

        {{-- Filters Section --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="searchInput" class="form-label">ناوی بەش</label>
                <input type="text" id="searchInput" class="form-control" placeholder="ناوی بەش بنوسە...">
            </div>
            <div class="col-md-2">
                <label for="systemFilter" class="form-label">سیستەم</label>
                <select id="systemFilter" class="form-select">
                    <option value="">هەموو</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="provinceFilter" class="form-label">پارێزگا</label>
                <select id="provinceFilter" class="form-select">
                    <option value="">هەموو</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="universityFilter" class="form-label">زانکۆ</label>
                <select id="universityFilter" class="form-select">
                    <option value="">هەموو</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="collegeFilter" class="form-label">پۆل</label>
                <select id="collegeFilter" class="form-select">
                    <option value="">هەموو</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button id="resetFilters" class="btn btn-outline-secondary w-100">
                    <i class="fa-solid fa-redo me-1"></i> پاکردنەوە
                </button>
            </div>
        </div>
    </div>

    {{-- Departments Table --}}
    <div class="mt-4">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="departmentsTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="30%">ناوی بەش</th>
                            <th width="10%">لق</th>
                            <th width="10%">نمرە</th>
                            <th width="10%">بارودۆخ</th>
                            <th width="10%">کردارەکان</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody">
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">بارکردن...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="card-footer bg-light d-flex justify-content-center">
                <nav aria-label="Page navigation" class="m-0">
                    <ul class="pagination justify-content-center" id="pagination">
                        <!-- Pagination will be rendered here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    </div>
@endsection

{{-- Modal بۆ Import --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.departments.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-file-import me-2"></i> Import بەشەکان
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        فایلەکە دەبێت Excel (xlsx/xls) بێت.
                        <a href="{{ route('admin.departments.download-template') }}" class="alert-link">
                            نموونەیەک داگرە
                        </a>
                    </div>

                    <div class="mb-3">
                        <label for="importFile" class="form-label">فایلی Excel</label>
                        <input type="file" class="form-control" id="importFile" name="file"
                            accept=".xlsx,.xls" required>
                        <small class="text-muted">تەنها فایلەکانی Excel پشتگیری دەکرێن</small>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="update_existing" id="updateExisting">
                        <label class="form-check-label" for="updateExisting">
                            نوێکردنەوەی تۆمارە هەبووەکان (بەپێی ID)
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-1"></i> هەڵوەشاندنەوە
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-file-import me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let paginationData = {};
            let filters = {
                search: '',
                system_id: '',
                province_id: '',
                university_id: '',
                college_id: ''
            };
            let allUniversities = [];
            let allColleges = [];

            // تێپێدانی دۆخەکان
            $('[data-bs-toggle="tooltip"]').tooltip();

            function setSelectOptions($select, items, placeholderText) {
                $select.empty();
                $select.append(`<option value="">${placeholderText}</option>`);
                $.each(items, function(key, item) {
                    $select.append(`<option value="${item.id}">${item.name}</option>`);
                });
            }

            function resetUniversityOptions() {
                setSelectOptions($('#universityFilter'), allUniversities, 'هەموو');
                $('#universityFilter').prop('disabled', false);
            }

            function resetCollegeOptions() {
                setSelectOptions($('#collegeFilter'), allColleges, 'هەموو');
                $('#collegeFilter').prop('disabled', false);
            }

            function loadUniversitiesByProvince(provinceId) {
                $('#universityFilter').prop('disabled', true);
                setSelectOptions($('#universityFilter'), [], 'بارکردن...');

                $.ajax({
                    url: '{{ route('admin.api.universities') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        province_id: provinceId
                    },
                    success: function(response) {
                        setSelectOptions($('#universityFilter'), response, 'هەموو');
                    },
                    error: function(error) {
                        console.error('هیچ داتا نەتوانی بێنە:', error);
                        setSelectOptions($('#universityFilter'), [], 'هەموو');
                    },
                    complete: function() {
                        $('#universityFilter').prop('disabled', false);
                    }
                });
            }

            function loadCollegesByUniversity(universityId) {
                $('#collegeFilter').prop('disabled', true);
                setSelectOptions($('#collegeFilter'), [], 'بارکردن...');

                $.ajax({
                    url: '{{ route('admin.api.colleges') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        university_id: universityId
                    },
                    success: function(response) {
                        setSelectOptions($('#collegeFilter'), response, 'هەموو');
                    },
                    error: function(error) {
                        console.error('هیچ داتا نەتوانی بێنە:', error);
                        setSelectOptions($('#collegeFilter'), [], 'هەموو');
                    },
                    complete: function() {
                        $('#collegeFilter').prop('disabled', false);
                    }
                });
            }

            // بارکردنی فلتەرەکانی dropdown
            function loadFilterOptions() {
                $.ajax({
                    url: '{{ route('admin.departments.index') }}?ajax=1&get_filters=1',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // بارکردنی سیستەمەکان
                        setSelectOptions($('#systemFilter'), response.systems, 'هەموو');

                        // بارکردنی پارێزگاکان
                        setSelectOptions($('#provinceFilter'), response.provinces, 'هەموو');

                        // بارکردنی زانکۆکان
                        allUniversities = response.universities || [];
                        resetUniversityOptions();

                        // بارکردنی پۆلەکان
                        allColleges = response.colleges || [];
                        resetCollegeOptions();
                    }
                });
            }

            // بارکردنی دانگا بە فلتەر
            function loadDepartments(page = 1) {
                let params = new URLSearchParams(filters);
                params.append('ajax', 1);
                params.append('page', page);

                $.ajax({
                    url: '{{ route('admin.departments.index') }}?' + params.toString(),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        renderTable(response.data);
                        renderPagination(response);
                        paginationData = response;
                    },
                    error: function(error) {
                        console.error('هیچ داتا نەتوانی بێنە:', error);
                        $('#tableBody').html(`
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="alert alert-danger mb-0">
                                        <i class="fa-solid fa-exclamation-circle me-2"></i> هیچ داتا نەدۆزرایەوە
                                    </div>
                                </td>
                            </tr>
                        `);
                    }
                });
            }

            // نیشاندانی جدول
            function renderTable(data) {
                if (data.length === 0) {
                    $('#tableBody').html(`
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="alert alert-info mb-0">
                                    <i class="fa-solid fa-info-circle me-2"></i> هیچ بەشەک نەدۆزرایەوە
                                </div>
                            </td>
                        </tr>
                    `);
                    return;
                }

                let html = '';
                $.each(data, function(index, dept) {
                    const statusBadge = dept.status ?
                        '<span class="badge bg-success">چالاک</span>' :
                        '<span class="badge bg-danger">ناچالاک</span>';

                    // دیاریکردنی رەنگی badge بەپێی سیستەم
                    const systemName = dept.system?.name ?? '-';
                    let systemBadge = 'bg-secondary';
                    if (systemName === 'زانکۆلاین') {
                        systemBadge = 'bg-primary';
                    } else if (systemName === 'پاراڵیل') {
                        systemBadge = 'bg-success';
                    } else if (systemName === 'ئێواران') {
                        systemBadge = 'bg-danger';
                    }

                    html += `
                        <tr>
                            <td>${dept.id}</td>
                            <td>
                                <div class="fw-semibold">${dept.name}</div>
                                <div class="text-muted small mt-1">
                                    ${dept.province?.name ?? '-'} /
                                    ${dept.university?.name ?? '-'} /
                                    ${dept.college?.name ?? '-'}
                                </div>
                                <span class="badge ${systemBadge} mt-1">
                                    <i class="fa-solid fa-cube me-1"></i> ${systemName}
                                </span>
                            </td>
                            <td>${dept.type || '-'}</td>
                            <td>
                                <div class="fw-semibold text-black"><span class="badge bg-success">${dept.local_score || '-'}</span></div>
                                <div class="text-muted small mt-1">
                                    <span class="badge bg-danger text-white font-weight-bold">${dept.external_score || '-'}</span>
                                </div>
                            </td>

                            <td>${statusBadge}</td>
                            <td>
                                <div class="btn-group dropend">
                                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-gear"></i>
                                    </button>
                                    <ul class="dropdown-menu text-center">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.departments.show', '') }}/${dept.id}">
                                                <i class="fa-solid fa-eye me-2"></i> نیشاندان
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.departments.edit', '') }}/${dept.id}">
                                                <i class="fa-solid fa-edit me-2"></i> دەستکاری
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button type="button" class="dropdown-item text-danger delete-btn" data-id="${dept.id}">
                                                <i class="fa-solid fa-trash me-2"></i> سڕینەوە
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                $('#tableBody').html(html);

                // بەستانی رووداوی سڕینەوە
                $('.delete-btn').on('click', function() {
                    const id = $(this).data('id');
                    deleteDepartment(id);
                });
            }

            // نیشاندانی pagination
            function renderPagination(response) {
                let html = '';
                const lastPage = response.last_page;
                const currentPage = response.current_page;

                // دوگمەی پێشتر
                if (currentPage > 1) {
                    html +=
                        `<li class="page-item"><button class="page-link pagination-btn" data-page="${currentPage - 1}"><i class="fa-solid fa-chevron-right me-1"></i> پێشتر</button></li>`;
                } else {
                    html +=
                        `<li class="page-item disabled"><span class="page-link"><i class="fa-solid fa-chevron-right me-1"></i> پێشتر</span></li>`;
                }

                // پەڕەکان
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(lastPage, currentPage + 2);

                if (startPage > 1) {
                    html +=
                        `<li class="page-item"><button class="page-link pagination-btn" data-page="1">1</button></li>`;
                    if (startPage > 2) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    if (i === currentPage) {
                        html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                    } else {
                        html +=
                            `<li class="page-item"><button class="page-link pagination-btn" data-page="${i}">${i}</button></li>`;
                    }
                }

                if (endPage < lastPage) {
                    if (endPage < lastPage - 1) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                    html +=
                        `<li class="page-item"><button class="page-link pagination-btn" data-page="${lastPage}">${lastPage}</button></li>`;
                }

                // دوگمەی داهاتوو
                if (currentPage < lastPage) {
                    html +=
                        `<li class="page-item"><button class="page-link pagination-btn" data-page="${currentPage + 1}">داهاتوو <i class="fa-solid fa-chevron-left ms-1"></i></button></li>`;
                } else {
                    html +=
                        `<li class="page-item disabled"><span class="page-link">داهاتوو <i class="fa-solid fa-chevron-left ms-1"></i></span></li>`;
                }

                $('#pagination').html(html);

                // بەستانی دوگمەکانی pagination
                $('.pagination-btn').on('click', function() {
                    const page = $(this).data('page');
                    loadDepartments(page);
                    window.scrollTo(0, 0);
                });
            }

            // جستجۆ (debounce)
            let searchTimer = null;
            $('#searchInput').on('input', function() {
                const val = $(this).val();
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    filters.search = val;
                    loadDepartments(1);
                }, 300);
            });

            // فلتەرکردن بەپێی سیستەم
            $('#systemFilter').on('change', function() {
                filters.system_id = $(this).val();
                loadDepartments(1);
            });

            // فلتەرکردن بەپێی پارێزگا
            $('#provinceFilter').on('change', function() {
                filters.province_id = $(this).val();
                filters.university_id = '';
                filters.college_id = '';
                $('#universityFilter').val('');
                $('#collegeFilter').val('');

                if (filters.province_id) {
                    loadUniversitiesByProvince(filters.province_id);
                    setSelectOptions($('#collegeFilter'), [], 'هەموو');
                    $('#collegeFilter').prop('disabled', true);
                } else {
                    resetUniversityOptions();
                    resetCollegeOptions();
                }
                loadDepartments(1);
            });

            // فلتەرکردن بەپێی زانکۆ
            $('#universityFilter').on('change', function() {
                filters.university_id = $(this).val();
                filters.college_id = '';
                $('#collegeFilter').val('');

                if (filters.university_id) {
                    loadCollegesByUniversity(filters.university_id);
                } else if (filters.province_id) {
                    setSelectOptions($('#collegeFilter'), [], 'هەموو');
                    $('#collegeFilter').prop('disabled', true);
                } else {
                    resetCollegeOptions();
                }
                loadDepartments(1);
            });

            // فلتەرکردن بەپێی پۆل
            $('#collegeFilter').on('change', function() {
                filters.college_id = $(this).val();
                loadDepartments(1);
            });

            // ڕیسیت فلتەرەکان
            $('#resetFilters').on('click', function() {
                filters = {
                    search: '',
                    system_id: '',
                    province_id: '',
                    university_id: '',
                    college_id: ''
                };
                $('#searchInput').val('');
                $('#systemFilter').val('');
                $('#provinceFilter').val('');
                $('#universityFilter').val('');
                $('#collegeFilter').val('');
                resetUniversityOptions();
                resetCollegeOptions();
                loadDepartments(1);
            });

            // سڕینەوەی بەش
            function deleteDepartment(id) {
                if (confirm('ئایا تێدەگەیت بەم بەشە سڕیبکەیتەوە؟')) {
                    $.ajax({
                        url: '{{ route('admin.departments.destroy', '') }}/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('بەشەک سڕیبدرایتەوە');
                                loadDepartments();
                            }
                        },
                        error: function(error) {
                            alert('هیچی لە سڕینەوەیدا هەڵ چوو');
                            console.error(error);
                        }
                    });
                }
            }

            // بارکردنی فلتەرەکان و داتاکان لە سەرەتای کار
            loadFilterOptions();
            loadDepartments();
        });
    </script>
@endpush
