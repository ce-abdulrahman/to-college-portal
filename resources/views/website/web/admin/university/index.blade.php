@extends('website.web.admin.layouts.app')

@section('page_name', 'universities')
@section('view_name', 'index')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title" style="font-size: 32px">
                <i class="fa-solid fa-building-columns me-2"></i> تەواوی زانکۆکان
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.universities.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> زیادکردنی زانکۆی نوێ
        </a>
        <span class="chip"><i class="fa-solid fa-database"></i> کۆی گشتی: {{ count($universities) }}</span>
    </div>

    {{-- Controls for index.js --}}
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
        <div class="d-flex align-items-center gap-2">
            <label class="small text-muted mb-0">پیشاندانی</label>
            <select id="page-length" class="form-select form-select-sm" style="width:auto">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <label class="small text-muted mb-0">تۆمار</label>
        </div>

        <div class="d-flex align-items-center gap-2 ms-auto">
            <select id="filter-status" class="form-select form-select-sm" style="width:auto">
                <option value="">— هەمووی —</option>
                <option value="1">چاڵاک</option>
                <option value="0">ناچاڵاک</option>
            </select>
            <button id="filter-reset" class="btn btn-sm btn-outline-secondary">Reset</button>

            <div style="min-width:260px">
                <input id="custom-search" type="search" class="form-control form-control-sm" placeholder="گەڕان...">
            </div>
        </div>
    </div>

    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-3">
                <i class="fa-solid fa-building-columns me-2"></i> زانکۆکان
            </h4>

            <div class="table-wrap">
                <div class="table-responsive">
                    <table id="datatable" class="table align-middle nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>وێنە</th>
                                <th>پارێزگا</th>
                                <th>ناوی زانکۆ</th>
                                <th>ناوی زانکۆ (ئینگلیزی)</th>
                                <th style="width:120px">دۆخ</th>
                                <th style="width:180px" data-orderable="false">کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($universities as $index => $university)
                                <tr data-status="{{ (int) $university->status }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">
                                        <img src="{{ $university->image }}" alt="{{ e($university->name) }}"
                                            style="height:40px;max-width:100%;border-radius:6px;object-fit:cover">
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-map-pin me-1 text-muted"></i>
                                        {{ $university->province->name ?? '—' }}
                                    </td>
                                    <td class="fw-semibold">
                                        <i class="fa-solid fa-school me-1 text-muted"></i> {{ $university->name }}
                                    </td>
                                    <td class="fw-semibold">
                                        <i class="fa-solid fa-school me-1 text-muted"></i> {{ $university->name_en }}
                                    </td>
                                    <td>
                                        @if ($university->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچاڵاک</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('admin.universities.show', $university->id) }}"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="fa-solid fa-eye me-1"></i>
                                        </a>
                                        <a href="{{ route('admin.universities.edit', $university->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>
                                        </a>
                                        <form action="{{ route('admin.universities.destroy', $university->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('دڵنیایت دەتەوێت زانکۆ بسڕیتەوە؟');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash-can me-1"></i>
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
 