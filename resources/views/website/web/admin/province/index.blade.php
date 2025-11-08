@extends('website.web.admin.layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title" style="font-size: 32px">
                <i class="fa-solid fa-map-pin me-1 text-muted"></i> تەواوی پارێزگاکان
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('admin.provinces.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> پارێزگای نوێ
            </a>
        @endif

        <span class="chip"><i class="fa-solid fa-database"></i> کۆی گشتی: {{ count($provinces) }}</span>
    </div>

    <div class="card glass fade-in">
        <div class="card-body">
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
                <div class="ms-auto" style="min-width:260px">
                    <input id="custom-search" type="search" class="form-control" placeholder="گەڕان... (ناو)">
                </div>
            </div>

            <div class="table-wrap">
                <div class="table-responsive table-scroll-x">
                    <table id="datatable" class="table align-middle nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>وێنە</th>
                                <th>ناو</th>
                                <th>ناو (ئینگلیزی)</th>
                                <th style="width:120px">دۆخ</th>
                                <th style="width:220px">کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($provinces as $index => $province)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">
                                        <img src="{{ $province->image }}" alt="{{ $province->name }}"
                                            style="height:40px;max-width:100%;border-radius:6px;object-fit:cover">
                                    </td>
                                    <td class="fw-semibold">
                                        <i class="fa-solid fa-map-pin me-1 text-muted"></i> {{ $province->name }}
                                    </td>
                                    <td class="fw-semibold">
                                        <i class="fa-solid fa-map-pin me-1 text-muted"></i> {{ $province->name_en }}
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
                                            class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                            data-bs-title="پیشاندان">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        @if (auth()->user()->role === 'admin')
                                            <a href="{{ route('admin.provinces.edit', $province->id) }}"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                data-bs-title="دەستکاری">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('admin.provinces.destroy', $province->id) }}"
                                                method="POST" class="d-inline" onsubmit="return confirm('دڵنیایت؟');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip" data-bs-title="سڕینەوە">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // DataTable (vanilla v2) – app already loads the library
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
                    }
                }
            });

            // external search + page length
            const s = document.getElementById('custom-search');
            const l = document.getElementById('page-length');
            s.addEventListener('input', () => dt.search(s.value).draw());
            l.addEventListener('change', () => dt.page.len(Number(l.value)).draw());

            // custom info/pager
            const info = document.getElementById('dt-info');
            const pager = document.getElementById('dt-pager');

            function renderInfo() {
                const i = dt.page.info();
                info.textContent = i.recordsDisplay ? `پیشاندانی ${i.start} تا ${i.end} لە ${i.recordsDisplay}` :
                    'هیچ تۆمار نییە';
            }

            function renderPager() {
                const i = dt.page.info();
                const cur = i.page,
                    total = i.pages;
                let html = `<nav aria-label="Pagination"><ul class="pagination pagination-sm mb-0">`;
                html +=
                    `<li class="page-item ${cur===0?'disabled':''}"><a class="page-link" href="#" data-page="${cur-1}">پێشتر</a></li>`;
                const max = 7;
                let start = Math.max(0, cur - Math.floor(max / 2));
                let end = Math.min(total - 1, start + max - 1);
                if (end - start + 1 < max) start = Math.max(0, end - max + 1);
                for (let p = start; p <= end; p++) {
                    html +=
                        `<li class="page-item ${p===cur?'active':''}"><a class="page-link" href="#" data-page="${p}">${p+1}</a></li>`;
                }
                html +=
                    `<li class="page-item ${cur===total-1?'disabled':''}"><a class="page-link" href="#" data-page="${cur+1}">دواتر</a></li>`;
                html += `</ul></nav>`;
                pager.innerHTML = html;
                pager.querySelectorAll('[data-page]').forEach(a => {
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        const to = Number(a.dataset.page);
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
@endpush
