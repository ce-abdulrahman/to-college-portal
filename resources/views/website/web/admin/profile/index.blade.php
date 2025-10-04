@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">{{ __('قائمة بەکارهێنەران') }}</div>
        </div>

        <a href="{{ route('admin.profile.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-user-plus me-1"></i> {{ __('زیادکردنی بەکارهێنەری نوێ') }}
        </a>
    </div>

    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-3">
                <i class="fa-solid fa-users me-2"></i> {{ __('بەکارهێنەران') }}
            </h4>

            {{-- Top toolbar (length + search) --}}
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted mb-0">{{ __('پیشاندانی') }}</label>
                    <select id="page-length" class="form-select form-select-sm" style="width:auto">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <label class="small text-muted mb-0">{{ __('تۆمار لە هەردەم') }}</label>
                </div>

                <div class="ms-auto" style="min-width:260px">
                    <input id="custom-search" type="search" class="form-control" placeholder="{{ __('گەڕان... (ناو)') }}">
                </div>
            </div>

            <div class="table-wrap">
                <div class="table-responsive">
                    <table id="datatable" class="table align-middle nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>ناو</th>
                                <th>کۆد</th>
                                <th>{{ __('پەیوەندیدانی قوتابی') }}</th>
                                <th>دەسەڵات</th>
                                <th style="width:120px">{{ __('دۆخ') }}</th>
                                <th style="width:160px">{{ __('کردار') }}</th>
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
                                            @if ($user->student)
                                            <a href="{{ route('admin.students.edit', $user->student->id) }}"
                                                class="text-decoration-none">
                                                    <i class="fa-solid fa-link me-1"></i>
                                                    {{ $user->student->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">{{ __('هیچ پەیوەندیدانێک نییە') }}</span>
                                            @endif
                                        </td>
                                                    <td>
                                                        @if ($user->role === 'admin')
                                                            <span class="badge bg-info">{{ __('ئەدمین') }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">قوتابی</span>
                                                        @endif
                                                    </td>
                                        <td>
                                            @if ($user->status)
                                                <span class="badge bg-success">{{ __('چاڵاک') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('ناچاڵاک') }}</span>
                                            @endif
                                        </td>
                                        <td class="actions">
                                            <a href="{{ route('admin.profile.edit', $user->id) }}"
                                                class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                                data-bs-title="{{ __('دەستکاری') }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('admin.profile.destroy', $user->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('{{ __('دڵنیایت لە سڕینەوەی ئەم بەکارهێنەرە؟') }}');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    data-bs-toggle="tooltip" data-bs-title="{{ __('سڕینەوە') }}">
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
    {{-- DataTables v2 (Vanilla JS) باید لە layout بارکرابێت:
      <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
      <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
  --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Enable tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

            // DataTable init (external UI)
            const dt = new DataTable('#datatable', {
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: null,
                    bottomEnd: null
                },
                paging: true,
                ordering: true,
                autoWidth: false,
                pageLength: 10,
                lengthChange: false,
                language: {
                    zeroRecords: 'هیچ داتا نییە',
                    info: 'پیشاندانی _START_ تا _END_ لە _TOTAL_',
                    infoEmpty: 'هیچ تۆمار نییە',
                    paginate: {
                        previous: 'پێشتر',
                        next: 'دواتر'
                    },
                }
            });

            // External search
            const input = document.getElementById('custom-search');
            input.addEventListener('input', () => dt.search(input.value).draw());

            // External page length
            const sel = document.getElementById('page-length');
            sel.addEventListener('change', () => dt.page.len(Number(sel.value)).draw());

            // Custom info
            const infoBox = document.getElementById('dt-info');

            function renderInfo() {
                const i = dt.page.info();
                infoBox.textContent = i.recordsDisplay ?
                    `پیشاندانی ${i.start} تا ${i.end} لە ${i.recordsDisplay}` :
                    'هیچ تۆمار نییە';
            }

            // Custom pager (Bootstrap-like)
            const pager = document.getElementById('dt-pager');

            function renderPager() {
                const i = dt.page.info();
                const cur = i.page,
                    total = i.pages;

                let html = `<nav aria-label="Pagination"><ul class="pagination pagination-sm mb-0">`;
                html += `<li class="page-item ${cur===0?'disabled':''}">
                   <a class="page-link" href="#" data-page="${cur-1}">پێشتر</a>
                 </li>`;

                const max = 7;
                let start = Math.max(0, cur - Math.floor(max / 2));
                let end = Math.min(total - 1, start + max - 1);
                if (end - start + 1 < max) start = Math.max(0, end - max + 1);

                for (let p = start; p <= end; p++) {
                    html += `<li class="page-item ${p===cur?'active':''}">
                     <a class="page-link" href="#" data-page="${p}">${p+1}</a>
                   </li>`;
                }

                html += `<li class="page-item ${cur===total-1?'disabled':''}">
                   <a class="page-link" href="#" data-page="${cur+1}">دواتر</a>
                 </li>`;
                html += `</ul></nav>`;

                pager.innerHTML = html;
                pager.querySelectorAll('[data-page]').forEach(el => {
                    el.addEventListener('click', (e) => {
                        e.preventDefault();
                        const to = Number(el.getAttribute('data-page'));
                        if (!Number.isNaN(to) && to >= 0 && to < total) dt.page(to).draw('page');
                    });
                });
            }

            dt.on('draw', () => {
                renderInfo();
                renderPager();
            });
            renderInfo();
            renderPager();
        });
    </script>

    <style>
        #dt-pager .pagination .page-link {
            min-width: 34px;
            text-align: center;
        }

        .dataTable.table {
            border-collapse: separate;
            border-spacing: 0 .25rem;
        }
    </style>
@endpush
