@extends('website.web.admin.layouts.app')

@section('page_name', 'users')
@section('view_name', 'index')

@section('content')
    {{-- Actions bar --}}

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title" style="font-size: 32px">
                <i class="fa-solid fa-users me-2"></i> لیستی بەکارهێنەران
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">
            <i class="fa-solid fa-user-plus me-1"></i> زیادکردنی بەکارهێنەری نوێ
        </a>
        <span class="chip"><i class="fa-solid fa-database"></i> کۆی گشتی: {{ count($users) }}</span>
    </div>

    <div class="card glass mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                {{-- System --}}
                <div class="col-12 col-md-2">
                    <label class="form-label"><i class="fa-solid fa-cube me-1 text-muted"></i> بەکارهێنەر</label>
                    <select id="filter-user" class="form-select">
                        <option value="">هەموو</option>
                        <option value="admin">ئەدمینەکان</option>
                        <option value="center">سەنتەرەکان</option>
                        <option value="teacher">مامۆستاکان</option>
                        <option value="student">قوتابیەکان</option>
                    </select>
                </div>
                {{-- Search --}}
                <div class="col-12 col-md-4 mt-2">
                    <label class="form-label"><i class="fa-solid fa-magnifying-glass me-1 text-muted"></i> گەڕانی
                        گشتی</label>
                    <input id="filter-search" type="text" class="form-control"
                        placeholder="ناوی بەش/سیستەم/پارێزگا/زانکۆ/کۆلێژ ...">
                </div>

                <div class="col-12 col-md-3 mt-2">
                    <label class="form-label">پیشاندانی</label>
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
                                <th>کۆد</th>
                                <th>پیشە</th>
                                <th style="width:120px">دۆخ</th>
                                <th style="width:160px">کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                @if (auth()->user()->id != $user->id)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td class="fw-semibold">
                                            <i class="fa-regular fa-user me-1 text-muted"></i>
                                            {{ $user->name }}
                                        </td>
                                        <td>{{ $user->code }}</td>

                                        <td>
                                            @if ($user->role === 'admin')
                                                <span class="badge bg-info">ئەدمین</span>
                                            @elseif ($user->role === 'center')
                                                <span class="badge bg-danger">سەنتەر</span>
                                            @elseif ($user->role === 'teacher')
                                                <span class="badge bg-warning text-dark">مامۆستا</span>
                                            @else
                                                <span class="badge bg-secondary">قوتابی</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->status)
                                                <span class="badge bg-success">چاڵاک</span>
                                            @else
                                                <span class="badge bg-danger">ناچاڵاک</span>
                                            @endif
                                        </td>

                                        <td class="actions">

                                            @if ($user->role === 'center')
                                                <a href="{{ route('admin.center.show', $user->id) }}"
                                                    class="text-decoration-none" title="{{ $user->role  }} || {{ $user->name }}">
                                                    <i class="fa fa-eye me-1"></i>
                                                </a>
                                            @elseif ($user->role === 'teacher')
                                                <a href="{{ route('admin.teacher.show', $user->id) }}"
                                                    class="text-decoration-none" title="{{ $user->role  }} || {{ $user->name }}">
                                                    <i class="fa fa-eye me-1"></i>
                                                </a>
                                            @elseif ($user->role === 'student')
                                                <a href="{{ route('admin.student.show', $user->id) }}"
                                                    class="text-decoration-none" title="{{ $user->role  }} || {{ $user->name }}">
                                                    <i class="fa fa-eye me-1"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">نییە</span>
                                            @endif



                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                data-bs-title="دەستکاری">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                class="d-inline"
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            TableKit.initDataTable({
                table: '#datatable',
                externalSearch: '#custom-search', // ئەگەر هەیە
                pageLengthSel: '#page-length', // ئەگەر هەیە
                infoBox: '#dt-info', // ئەگەر هەیە
                pagerBox: '#dt-pager' // ئەگەر هەیە
            });
        });
        $(function() {
            // 0) ئاماژەکان
            const $table = $('#datatable');
            const $searchInput = $('#filter-search');
            const $userSel = $('#filter-user'); // ناو — بەکارهاتووە وەک فلتەری ستوونی "ناو"
            const $lenSel = $('#page-length');
            const $resetBtn = $('#filter-reset');
            const $infoBox = $('#dt-info');
            const $pagerBox = $('#dt-pager');

            // 1) DataTable
            const dt = $table.DataTable({
                // ئەوەی خۆت پێشتر لە table ـەکەت هەیە: class="nowrap"
                responsive: true,
                processing: false,
                serverSide: false,
                paging: true,
                pageLength: parseInt($lenSel.val(), 10) || 10,
                lengthChange: false, // بەجایەوە لە بیرون هەڵدەبژێرین
                ordering: true,
                order: [
                    [0, 'asc']
                ], // بە پێی ستوونی یەکەم (#)
                info: false, // info لە بیرون دەنووسین
                searching: true,
                // DOM بکە کەم، چونکە pager/info لە بیرون دەخەینەوە
                dom: "<'table-top'>'t'",
                language: {
                    emptyTable: "هیچ داتایەک نییە",
                    zeroRecords: "هیچ داتایەک نەدۆزراوە",
                    loadingRecords: "بارکردن...",
                    processing: "کار کردن...",
                    paginate: {
                        first: "یەکەم",
                        previous: "پێشوو",
                        next: "دواتر",
                        last: "کۆتا"
                    }
                },
                drawCallback: function(settings) {
                    // 2) زانیاری ژێر خشتە (X–Y / N)
                    const api = this.api();
                    const info = api.page.info();
                    const start = info.recordsDisplay ? info.start + 1 : 0;
                    const end = info.end;
                    const total = info.recordsDisplay;
                    $infoBox.text(`پیشاندان: ${start} – ${end} لە ${total}`);

                    // 3) Pagerی ناوخۆیانە بکوژێنە دەرەوە
                    const $paginate = $(api.table().container()).find('.dataTables_paginate');
                    if ($paginate.length && !$pagerBox.find('.dataTables_paginate').length) {
                        $pagerBox.append($paginate);
                    }
                }
            });

            // 4) گەڕانی گشتی
            $searchInput.on('keyup change', function() {
                dt.search(this.value).draw();
            });

            // 5) فلتەری "سیستەم" (لە راستی ستوونی ناوە — index=1)
            //    ئەگەر دەتەوێت بە "contains" بێت: search(value)
            //    ئەگەر دەتەوێت بە تەواوی یەکسانی بێت: '^' + $.fn.dataTable.util.escapeRegex(value) + '$' , true, false
            $userSel.on('change', function() {
                const v = this.value || '';
                dt.column(1).search(v, false, false).draw(); // ستوونی 1=ناو
            });

            // 6) هەڵبژاردنی درێژی لاپەڕە
            $lenSel.on('change', function() {
                dt.page.len(parseInt(this.value, 10)).draw();
            });

            // 7) ڕیسێتکردنەوەی هەموو فلتەرەکان
            $resetBtn.on('click', function() {
                $searchInput.val('');
                $userSel.val('');
                $lenSel.val('10');
                dt.search('').columns().search('').page.len(10).draw();
            });

            // 8) Tooltipەکان (ئەگەر Bootstrap JS هەیە)
            if (window.bootstrap) {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
            }
        });
    </script>
@endpush
