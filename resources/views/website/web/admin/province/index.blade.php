@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.provinces.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> زیادکردنی پارێزگا
        </a>
        <span class="chip"><i class="fa-solid fa-database"></i> کۆی گشتی: {{ count($provinces) }}</span>
    </div>

    {{-- Filters --}}
    <div class="card glass mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label"><i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ</label>
                    <select id="filter-status" class="form-select">
                        <option value="">هەموو</option>
                        <option value="1">چاڵاک</option>
                        <option value="0">ناچاڵاک</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <button id="filter-reset" type="button" class="btn btn-outline w-100">
                        <i class="fa-solid fa-rotate-left me-1"></i> ڕێستکردنەوە
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- External search & page length --}}
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
        <div class="d-flex align-items-center gap-2">
            <label class="small text-muted mb-0">پیشاندانی</label>
            <select id="page-length" class="form-select form-select-sm" style="width:auto">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <label class="small text-muted mb-0">تۆمار لە هەردەم</label>
        </div>

        <div class="ms-auto" style="min-width:260px">
            <input id="custom-search" type="search" class="form-control" placeholder="گەڕان... (ناو)">
        </div>
    </div>

    {{-- Table --}}
    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-3"><i class="fa-solid fa-table-list me-2"></i> پارێزگا</h4>

            <div class="table-wrap">
                <div class="table-responsive">
                    <table id="datatable" class="table align-middle nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>ناو</th>
                                <th style="width:120px">دۆخ</th>
                                <th style="width:160px">کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($provinces as $index => $province)
                                <tr data-status="{{ (int) $province->status }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">
                                        <i class="fa-solid fa-map-location-dot me-1 text-muted"></i> {{ $province->name }}
                                    </td>
                                    <td>
                                        @if ($province->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچاڵاک</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('admin.provinces.show', $province->id) }}"
                                            class="btn btn-sm btn-outline" data-bs-toggle="tooltip"
                                            data-bs-title="پیشاندان">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.provinces.edit', $province->id) }}"
                                            class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                            data-bs-title="دەستکاری">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.provinces.destroy', $province->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('دڵنیایت؟');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                                data-bs-title="سڕینەوە">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-2">
                        <div id="dt-info" class="small text-muted"></div>
                        <div id="dt-pager"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/pages/provinces/index.js') }}" defer></script>
@endpush
