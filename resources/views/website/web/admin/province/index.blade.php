@extends('website.web.admin.layouts.app')

@section('page_name', 'provinces')
@section('view_name', 'index')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.provinces.index') }}">پارێزگاکان</a></li>
                        <li class="breadcrumb-item active">تەواوی پارێزگاکان</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-map-pin me-1"></i>
                    تەواوی پارێزگاکان
                </h4>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('admin.provinces.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> پارێزگای نوێ
            </a>
        @endif

        <span class="badge bg-info">
            <i class="fa-solid fa-database me-1"></i> کۆی گشتی: {{ count($provinces) }}
        </span>
    </div>

    {{-- Filters --}}
    <div class="card glass mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label"><i class="fa-solid fa-magnifying-glass me-1 text-muted"></i> گەڕان</label>
                    <input id="filter-search" type="search" class="form-control" placeholder="گەڕان لە ناوی پارێزگادا...">
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
                    <label class="form-label">ژمارەی ڕیز</label>
                    <select id="filter-length" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-4"><i class="fa-solid fa-table-list me-2"></i> پارێزگاکان</h4>

            <div class="table-responsive">
                <table id="provincesTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>وێنە</th>
                            <th>ناو</th>
                            <th width="100">دۆخ</th>
                            <th width="180" class="text-center">کردار</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($provinces as $province)
                            <tr data-status="{{ $province->status }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $province->image }}" alt="{{ $province->name }}"
                                         class="rounded" style="width: 50px; height: 40px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $province->name }}</div>
                                    <div class="text-muted small">{{ $province->name_en }}</div>
                                </td>
                                <td>
                                    @if ($province->status)
                                        <span class="badge bg-success">چاڵاک</span>
                                    @else
                                        <span class="badge bg-danger">ناچاڵاک</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.provinces.show', $province->id) }}"
                                           class="btn btn-outline-info" title="پیشاندان">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        @if (auth()->user()->role === 'admin')
                                            <a href="{{ route('admin.provinces.edit', $province->id) }}"
                                               class="btn btn-outline-primary" title="دەستکاری">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('admin.provinces.destroy', $province->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"
                                                        onclick="return confirm('دڵنیایت؟');" title="سڕینەوە">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
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
        let table = $('#provincesTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "هەموو"]],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ku.json'
            },
            initComplete: function() {
                $('#provincesTable_wrapper').find('input[type="search"]').addClass('form-control');
            }
        });

        const searchFilter = $('#filter-search');
        const statusFilter = $('#filter-status');
        const lengthFilter = $('#filter-length');

        function filterTable() {
            const searchVal = searchFilter.val();
            const statusVal = statusFilter.val();

            table.search(searchVal || '').draw();

            if (statusVal) {
                table.rows().every(function() {
                    const row = $(this.node());
                    const rowStatus = row.data('status');
                    
                    if (statusVal && rowStatus != statusVal) {
                        row.hide();
                    } else {
                        row.show();
                    }
                });
            } else {
                table.rows().every(function() {
                    $(this.node()).show();
                });
            }
        }

        searchFilter.on('keyup', filterTable);
        statusFilter.on('change', filterTable);
        lengthFilter.on('change', function() {
            table.page.len($(this).val()).draw();
        });

        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush