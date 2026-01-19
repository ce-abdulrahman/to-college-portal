@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.colleges.index') }}">کۆلێژکان</a></li>
                        <li class="breadcrumb-item active">تەواوی کۆلێژکان</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-building-columns me-1"></i>
                    تەواوی کۆلێژکان
                </h4>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.colleges.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> زیادکردنی کۆلێژ
        </a>
        <span class="badge bg-info">
            <i class="fa-solid fa-database me-1"></i> کۆی گشتی: {{ count($colleges) }}
        </span>
    </div>

    {{-- Filters --}}
    <div class="card glass mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
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
                    <label class="form-label"><i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ</label>
                    <select id="filter-status" class="form-select">
                        <option value="">هەموو</option>
                        <option value="1">چاڵاک</option>
                        <option value="0">ناچاڵاک</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">گەڕان</label>
                    <input id="filter-search" type="search" class="form-control" placeholder="گەڕان...">
                </div>

                <div class="col-md-3">
                    <label class="form-label">پیشاندانی</label>
                    <select id="filter-length" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button id="filter-reset" class="btn btn-outline-secondary w-100">
                        <i class="fa-solid fa-rotate-left me-1"></i> ڕێستکردنەوە
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-3"><i class="fa-solid fa-table-list me-2"></i> کۆلێژەکان</h4>

            <div class="table-responsive">
                <table id="collegesTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>وێنە</th>
                            <th>پارێزگا</th>
                            <th>زانکۆ</th>
                            <th>کۆلێژ</th>
                            <th width="100">دۆخ</th>
                            <th width="180" class="text-center">کردار</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($colleges as $college)
                            <tr data-province="{{ $college->university->province_id ?? '' }}"
                                data-university="{{ $college->university_id }}"
                                data-status="{{ $college->status }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $college->image }}" alt="{{ $college->name }}"
                                         class="rounded" style="width: 50px; height: 40px; object-fit: cover;">
                                </td>
                                <td>{{ $college->university->province->name ?? '—' }}</td>
                                <td>{{ $college->university->name ?? '—' }}</td>
                                <td>
                                    <i class="fa-solid fa-building-columns me-1 text-muted"></i>
                                    <strong>{{ $college->name }}</strong>
                                </td>
                                <td>
                                    @if ($college->status)
                                        <span class="badge bg-success">چاڵاک</span>
                                    @else
                                        <span class="badge bg-danger">ناچاڵاک</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.colleges.show', $college->id) }}"
                                           class="btn btn-outline-info" title="پیشاندان">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.colleges.edit', $college->id) }}"
                                           class="btn btn-outline-primary" title="دەستکاری">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.colleges.destroy', $college->id) }}"
                                              method="POST" class="d-inline" onsubmit="return confirm('دڵنیایت؟');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="سڕینەوە">
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let table = $('#collegesTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "هەموو"]],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ku.json'
            },
            initComplete: function() {
                $('#collegesTable_wrapper').find('input[type="search"]').addClass('form-control');
            }
        });

        const provinceFilter = $('#filter-province');
        const universityFilter = $('#filter-university');
        const statusFilter = $('#filter-status');
        const searchFilter = $('#filter-search');
        const lengthFilter = $('#filter-length');
        const resetBtn = $('#filter-reset');

        function filterTable() {
            const provinceVal = provinceFilter.val();
            const universityVal = universityFilter.val();
            const statusVal = statusFilter.val();
            const searchVal = searchFilter.val();

            table.column(2).search(provinceVal || '', false, false);
            table.column(3).search(universityVal || '', false, false);
            
            table.draw();

            table.rows().every(function() {
                const row = $(this.node());
                const rowStatus = row.data('status');
                const rowProvince = row.data('province');
                const rowUniversity = row.data('university');
                
                let show = true;
                
                if (statusVal && rowStatus != statusVal) show = false;
                if (provinceVal && rowProvince != provinceVal) show = false;
                if (universityVal && rowUniversity != universityVal) show = false;
                if (searchVal && !row.text().toLowerCase().includes(searchVal.toLowerCase())) show = false;
                
                if (show) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        provinceFilter.on('change', function() {
            const provinceId = $(this).val();
            universityFilter.find('option').show();
            
            if (provinceId) {
                universityFilter.find('option:not([data-province="' + provinceId + '"]):not(:first)').hide();
            }
            universityFilter.val('');
            filterTable();
        });

        universityFilter.on('change', filterTable);
        statusFilter.on('change', filterTable);
        searchFilter.on('keyup', filterTable);
        lengthFilter.on('change', function() {
            table.page.len($(this).val()).draw();
        });

        resetBtn.on('click', function() {
            provinceFilter.val('');
            universityFilter.val('');
            statusFilter.val('');
            searchFilter.val('');
            lengthFilter.val('10');
            universityFilter.find('option').show();
            table.search('').columns().search('').draw();
            table.page.len(10).draw();
        });
    });
</script>
@endpush