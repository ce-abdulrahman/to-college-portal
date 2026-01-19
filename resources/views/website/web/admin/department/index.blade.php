@extends('website.web.admin.layouts.app')

@section('page_name', 'department')
@section('view_name', 'index')

@section('content')
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
            <div class="dropdown">
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
            </div>
            
            <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> زیادکردنی بەش
            </a>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info">
                <i class="fa-solid fa-database me-1"></i> کۆی گشتی: {{ count($departments) }}
            </span>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card glass mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label"><i class="fa-solid fa-cube me-1 text-muted"></i> سیستەم</label>
                    <select id="filter-system" class="form-select">
                        <option value="">هەموو سیستەمەکان</option>
                        @foreach ($systems as $sys)
                            <option value="{{ $sys->name }}">{{ $sys->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label"><i class="fa-solid fa-map-pin me-1 text-muted"></i> پارێزگا</label>
                    <select id="filter-province" class="form-select">
                        <option value="">هەموو پارێزگاكان</option>
                        @foreach ($provinces as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label"><i class="fa-solid fa-school me-1 text-muted"></i> زانکۆ</label>
                    <select id="filter-university" class="form-select">
                        <option value="">هەموو زانکۆكان</option>
                        @foreach ($universities as $uni)
                            <option value="{{ $uni->id }}" data-province="{{ $uni->province_id }}">{{ $uni->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label"><i class="fa-solid fa-building-columns me-1 text-muted"></i> کۆلێژ</label>
                    <select id="filter-college" class="form-select">
                        <option value="">هەموو کۆلێژەکان</option>
                        @foreach ($colleges as $coll)
                            <option value="{{ $coll->id }}" data-university="{{ $coll->university_id }}">{{ $coll->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="fa-solid fa-magnifying-glass me-1 text-muted"></i> گەڕان</label>
                    <input id="filter-search" type="text" class="form-control" placeholder="گەڕان لە ناو و زانیارییەکاندا...">
                </div>

                <div class="col-md-3">
                    <label class="form-label">ژمارەی ڕیز</label>
                    <select id="filter-length" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button id="filter-reset" class="btn btn-outline-secondary w-100">
                        <i class="fa-solid fa-rotate-left me-1"></i> پاککردنەوە
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-4"><i class="fa-solid fa-table-list me-2"></i> بەشەکان</h4>

            <div class="table-responsive">
                <table id="departmentsTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>وێنە</th>
                            <th>ناو</th>
                            <th width="100">ن. ناوەندی</th>
                            <th width="100">ن. دەرەوە</th>
                            <th width="80">دۆخ</th>
                            <th width="150" class="text-center">کردار</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                            @php
                                $systemName = $department->system->name;
                                $badge = match ($systemName) {
                                    'زانکۆلاین' => 'bg-primary',
                                    'پاراڵیل' => 'bg-success',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <tr data-system="{{ $department->system->name }}"
                                data-province="{{ $department->province_id }}"
                                data-university="{{ $department->university_id }}"
                                data-college="{{ $department->college_id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $department->image }}" alt="{{ $department->name }}"
                                         class="rounded" style="width: 50px; height: 40px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $department->name }}</div>
                                    <div class="text-muted small mt-1">
                                        {{ $department->system->name }} /
                                        {{ $department->province->name }} /
                                        {{ $department->university->name }} /
                                        {{ $department->college->name }}
                                    </div>
                                    <span class="badge {{ $badge }} mt-1">
                                        <i class="fa-solid fa-cube me-1"></i>{{ $systemName }}
                                    </span>
                                </td>
                                <td>
                                    @if($department->local_score)
                                        <span class="badge bg-info">{{ $department->local_score }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($department->external_score)
                                        <span class="badge bg-warning text-dark">{{ $department->external_score }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($department->status)
                                        <span class="badge bg-success">چاڵاک</span>
                                    @else
                                        <span class="badge bg-danger">ناچاڵاک</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.departments.show', $department->id) }}"
                                           class="btn btn-outline-info" title="پیشاندان">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.departments.edit', $department->id) }}"
                                           class="btn btn-outline-primary" title="دەستکاری">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.departments.destroy', $department->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    onclick="return confirm('ئایە دڵنیایت لە سڕینەوەی ئەم بەشە؟');"
                                                    title="سڕینەوە">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let table = $('#departmentsTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "هەموو"]],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ku.json'
            },
            initComplete: function() {
                $('#departmentsTable_wrapper').find('input[type="search"]').addClass('form-control');
            }
        });

        const systemFilter = $('#filter-system');
        const provinceFilter = $('#filter-province');
        const universityFilter = $('#filter-university');
        const collegeFilter = $('#filter-college');
        const searchFilter = $('#filter-search');
        const lengthFilter = $('#filter-length');
        const resetBtn = $('#filter-reset');

        function filterTable() {
            const systemVal = systemFilter.val();
            const provinceVal = provinceFilter.val();
            const universityVal = universityFilter.val();
            const collegeVal = collegeFilter.val();
            const searchVal = searchFilter.val();

            table.search(searchVal || '').draw();

            table.rows().every(function() {
                const row = $(this.node());
                const rowSystem = row.data('system');
                const rowProvince = row.data('province');
                const rowUniversity = row.data('university');
                const rowCollege = row.data('college');
                
                let show = true;
                
                if (systemVal && rowSystem != systemVal) show = false;
                if (provinceVal && rowProvince != provinceVal) show = false;
                if (universityVal && rowUniversity != universityVal) show = false;
                if (collegeVal && rowCollege != collegeVal) show = false;
                
                if (show) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        function updateDropdowns() {
            const provinceId = provinceFilter.val();
            const universityId = universityFilter.val();
            
            // Update university filter
            if (provinceId) {
                universityFilter.find('option:not(:first)').hide();
                universityFilter.find(`option[data-province="${provinceId}"]`).show();
            } else {
                universityFilter.find('option').show();
            }
            
            // Update college filter
            if (universityId) {
                collegeFilter.find('option:not(:first)').hide();
                collegeFilter.find(`option[data-university="${universityId}"]`).show();
            } else {
                collegeFilter.find('option').show();
            }
        }

        provinceFilter.on('change', function() {
            updateDropdowns();
            universityFilter.val('');
            collegeFilter.val('');
            filterTable();
        });

        universityFilter.on('change', function() {
            updateDropdowns();
            collegeFilter.val('');
            filterTable();
        });

        systemFilter.on('change', filterTable);
        collegeFilter.on('change', filterTable);
        searchFilter.on('keyup', filterTable);
        
        lengthFilter.on('change', function() {
            table.page.len($(this).val()).draw();
        });

        resetBtn.on('click', function() {
            systemFilter.val('');
            provinceFilter.val('');
            universityFilter.val('');
            collegeFilter.val('');
            searchFilter.val('');
            lengthFilter.val('10');
            
            universityFilter.find('option').show();
            collegeFilter.find('option').show();
            
            table.search('').draw();
            table.page.len(10).draw();
        });

        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush