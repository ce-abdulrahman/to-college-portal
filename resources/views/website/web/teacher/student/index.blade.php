@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title" style="font-size: 32px">
                <i class="fa-solid fa-users me-2"></i> لیستی قوتابییەکان
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('teacher.students.create') }}" class="btn btn-outline-primary">
            <i class="fa-solid fa-user-plus me-1"></i> زیادکردنی قوتابی نوێ
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
                <i class="fa-solid fa-users me-2"></i> قوتابییەکان
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

                            @foreach ($students as $index => $student)
                                @if (auth()->user()->id != $student->id)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td class="fw-semibold">
                                            <i class="fa-regular fa-user me-1 text-muted"></i>
                                            {{ $student->user->name }}
                                        </td>
                                        <td>{{ $student->user->code }}</td>
                                        <td>
                                            @if ($student->user->role === 'student')
                                                <span class="badge bg-secondary">قوتابی</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($student->status)
                                                <span class="badge bg-success">چاڵاک</span>
                                            @else
                                                <span class="badge bg-danger">ناچاڵاک</span>
                                            @endif
                                        </td>
                                        <td class="actions">
                                            <a href="{{ route('teacher.students.show', $student->user->id) }}"
                                                    class="text-decoration-none btn-outline-light">
                                                    <i class="fa fa-eye me-1"></i>
                                                </a>
                                            <a href="{{ route('teacher.students.edit', $student->id) }}"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                data-bs-title="دەستکاری">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('teacher.students.destroy', $student->user->id) }}" method="POST"
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
