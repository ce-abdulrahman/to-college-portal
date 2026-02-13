@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('center.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">مامۆستایەکان</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-users me-1"></i>
                        لیستی مامۆستایەکان
                    </h4>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <a href="{{ route('center.teachers.create') }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-user-plus me-1"></i> زیادکردنی مامۆستا نوێ
            </a>
            {{--  <span class="chip"><i class="fa-solid fa-database"></i> کۆی گشتی: {{ count($users) }}</span>  --}}
        </div>

        <div class="card glass mb-3">
            <div class="card-body">
                <div class="row g-2 align-items-end">

                    {{-- Search --}}
                    <div class="col-12 col-md-4 mt-2">
                        <label class="form-label"><i class="fa-solid fa-magnifying-glass me-1 text-muted"></i> گەڕانی
                            گشتی</label>
                        <input id="filter-search" type="text" class="form-control"
                            placeholder="ناوی بەش/سیستەم/پارێزگا/زانکۆ/کۆلێژ ...">
                    </div>

                    <div class="col-12 col-md-3 mt-2">
                        <label class="form-label">نیشاندانی</label>
                        <select id="page-length" class="form-select form-select-sm" style="width:auto">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    {{-- Reset --}}
                    <div class="col-12 col-md-3 mt-2">
                        <button id="filter-reset" type="button" class="btn btn-outline w-100">
                            <i class="fa-solid fa-rotate-left me-1"></i> ڕێستکردنەوەی فلتەرەکان
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card glass fade-in">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="fa-solid fa-users me-2"></i> بەکارهێنەران
                </h4>

                <div class="table-wrap">
                    <div class="table-responsive table-scroll-x">
                        <table id="datatable" class="table align-middle nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:60px">#</th>
                                    <th>ناو</th>
                                    <th>کۆد چوونەژوورەوە</th>
                                    <th>کۆد بانگێشت</th>
                                    <th>ژمارە</th>
                                    <th>پیشە</th>
                                    <th style="width:120px">دۆخ</th>
                                    <th style="width:160px">کردار</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($teachers as $index => $teacher)
                                    @if (auth()->user()->id != $teacher->user_id)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td class="fw-semibold">
                                                <i class="fa-regular fa-user me-1 text-muted"></i>
                                                {{ $teacher->user->name }}
                                            </td>
                                            <td>{{ $teacher->user->code }}</td>
                                            <td>{{ $teacher->user->rand_code }}</td>
                                            <td>{{ $teacher->user->phone }}</td>
                                            <td>
                                                @if ($teacher->user->role === 'teacher')
                                                    <span class="badge bg-dark">مامۆستا</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($teacher->user->status)
                                                    <span class="badge bg-success">چاڵاک</span>
                                                @else
                                                    <span class="badge bg-danger">ناچاڵاک</span>
                                                @endif
                                            </td>
                                            <td class="actions">
                                                <a href="{{ route('center.teachers.show', $teacher->id) }}"
                                                    class="text-decoration-none btn-outline-light">
                                                    <i class="fa fa-eye me-1"></i>
                                                </a>
                                                <a href="{{ route('center.teachers.edit', $teacher->id) }}"
                                                    class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                    data-bs-title="نوێ کردنەوە">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('center.teachers.destroy', $teacher->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('دڵنیایت لە سڕینەوەی ئەم بەکارهێنەرە؟');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="tooltip" data-bs-title="سڕینەوە">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Bottom info + pager --}}
                        <div class="d-flex flex-wrap justify-content-between align-items-center mt-2">
                            <div id="dt-info" class="small text-muted"></div>
                            <div id="dt-pager"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            const $table = $('#datatable');
            const $searchInput = $('#filter-search');
            const $lenSel = $('#page-length');
            const $resetBtn = $('#filter-reset');
            const $infoBox = $('#dt-info');
            const $pagerBox = $('#dt-pager');

            // اگر پێشتر ئینیت بووە، سڕەوە
            if ($.fn.dataTable.isDataTable($table)) {
                $table.DataTable().destroy();
            }

            const dt = $table.DataTable({
                responsive: true,
                paging: true,
                pageLength: parseInt($lenSel.val(), 10) || 10,
                lengthChange: false,
                ordering: true,
                order: [
                    [0, 'asc']
                ],
                info: false, // info لە بیرون دەنووسین
                searching: true,
                dom: "<'table-top'>'t'",
                language: {
                    emptyTable: "هیچ داتایەک نییە",
                    zeroRecords: "هیچ داتایەک نەدۆزرایەوە",
                    loadingRecords: "بارکردن...",
                    processing: "کارکردن...",
                    paginate: {
                        first: "یەکەم",
                        previous: "پێشوو",
                        next: "دواتر",
                        last: "کۆتا"
                    }
                },
                drawCallback: function() {
                    const api = this.api();
                    const info = api.page.info();
                    const start = info.recordsDisplay ? info.start + 1 : 0;
                    const end = info.end;
                    const total = info.recordsDisplay;
                    $infoBox.text(`نیشاندان: ${start} – ${end} لە ${total}`);

                    // pagerی ناوخۆی DataTables بگەڕێنە ناو #dt-pager
                    const $paginate = $(api.table().container()).find('.dataTables_paginate');
                    if ($paginate.length) {
                        $pagerBox.html($paginate);
                    }
                }
            });

            // گەڕانی گشتی
            $searchInput.on('keyup change', function() {
                dt.search(this.value).draw();
            });

            // هەڵبژاردنی درێژی لاپەڕە
            $lenSel.on('change', function() {
                dt.page.len(parseInt(this.value, 10)).draw();
            });

            // ڕیسێت
            $resetBtn.on('click', function() {
                $searchInput.val('');
                $lenSel.val('10');
                dt.search('').page.len(10).draw();
            });

            // Tooltipەکانی Bootstrap (اختیاری)
            if (window.bootstrap) {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
            }
        });
    </script>
@endpush
