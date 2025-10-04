@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.systems.create') }}" class="btn btn-outline" data-bs-toggle="tooltip"
                data-bs-placement="bottom" data-bs-html="true"
                data-bs-title="<div class='text-start'>
            @foreach ($systems as $system)
<div><i class='fa-solid fa-cube me-1 text-muted'></i>{{ $system->name }}</div>
@endforeach
         </div>">
                <i class="fa-solid fa-diagram-project me-1"></i> سیستەم
            </a>

            <a href="{{ route('admin.provinces.create') }}" class="btn btn-outline" data-bs-toggle="tooltip"
                data-bs-placement="bottom" data-bs-html="true"
                data-bs-title="<div class='text-start'>
            @foreach ($provinces as $province)
<div><i class='fa-solid fa-cube me-1 text-muted'></i>{{ $province->name }}</div>
@endforeach
         </div>">
                <i class="fa-solid fa-map-location-dot me-1"></i> پارێزگا
            </a>

            <a href="{{ route('admin.universities.create') }}" class="btn btn-outline" data-bs-toggle="tooltip"
                data-bs-placement="bottom" data-bs-html="true"
                data-bs-title="<div class='text-start'>
            @foreach ($universities as $university)
<div><i class='fa-solid fa-cube me-1 text-muted'></i>{{ $university->name }}</div>
@endforeach
         </div>">
                <i class="fa-solid fa-building-columns me-1"></i> زانکۆ
            </a>

            <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> بەش
            </a>
        </div>

        {{-- Toolbar: counters + filters --}}
        <div class="d-flex align-items-center gap-2">
            <span class="chip">
                <i class="fa-solid fa-database"></i> کۆی گشتی: {{ count($departments) }}
            </span>
        </div>
    </div>

    {{-- Filters Toolbar --}}
    <div class="card glass mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                {{-- System --}}
                <div class="col-12 col-md-3">
                    <label class="form-label"><i class="fa-solid fa-cube me-1 text-muted"></i> سیستەم</label>
                    <select id="filter-system" class="form-select">
                        <option value="">هەموو سیستەمەکان</option>
                        @foreach ($systems as $sys)
                            <option value="{{ $sys->name }}">{{ $sys->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Province --}}
                <div class="col-12 col-md-3">
                    <label class="form-label"><i class="fa-solid fa-map-pin me-1 text-muted"></i> پارێزگا</label>
                    <select id="filter-province" class="form-select">
                        <option value="">هەموو پارێزگاكان</option>
                        @foreach ($provinces as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- University (depends on province) --}}
                <div class="col-12 col-md-3">
                    <label class="form-label"><i class="fa-solid fa-school me-1 text-muted"></i> زانکۆ</label>
                    <select id="filter-university" class="form-select" disabled>
                        <option value="">هەموو زانکۆكان</option>
                    </select>
                </div>

                {{-- College (depends on university) --}}
                <div class="col-12 col-md-3">
                    <label class="form-label"><i class="fa-solid fa-building-columns me-1 text-muted"></i> کۆلێژ</label>
                    <select id="filter-college" class="form-select" disabled>
                        <option value="">هەموو کۆلێژەکان</option>
                    </select>
                </div>

                {{-- Search --}}
                <div class="col-12 col-md-6 mt-2">
                    <label class="form-label"><i class="fa-solid fa-magnifying-glass me-1 text-muted"></i> گەڕانی
                        گشتی</label>
                    <input id="filter-search" type="text" class="form-control"
                        placeholder="ناوی بەش/سیستەم/پارێزگا/زانکۆ/کۆلێژ ...">
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
            <input id="custom-search" type="search" class="form-control" placeholder="گەڕان... (ناو/سیستەم/پارێزگا/...)">
        </div>
    </div>


    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-3"><i class="fa-solid fa-table-list me-2"></i> بەشەکان</h4>

            <div class="table-wrap">
                <div class="table-responsive">
                    <table id="datatable" class="table align-middle nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ناو</th>
                                <th>نمرەی ناوەندی</th>
                                <th>نمرەی ناوخۆی</th>
                                {{--  <th>جۆر</th>
                                <th>ڕەگەز</th>  --}}
                                <th>دۆخ</th>
                                <th>کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $i => $department)
                                @php
                                    $systemName = $department->system->name;
                                    $systemBadgeClass = match ($systemName) {
                                        'زانکۆلاین' => 'bg-primary',
                                        'پاراڵیل' => 'bg-success',
                                        default => 'bg-danger',
                                    };
                                @endphp

                                <tr data-systemColor="{{ $systemName }}" data-system="{{ $department->system->name }}"
                                    data-province="{{ $department->province->name }}"
                                    data-university="{{ $department->university->name }}"
                                    data-college="{{ $department->college->name }}">
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="fw-semibold">{{ $department->name }}</div>
                                            <div class="text-muted small">
                                                {{ $department->system->name }} /
                                                {{ $department->province->name }} /
                                                {{ $department->university->name }} /
                                                {{ $department->college->name }}
                                            </div>
                                            <div class="mt-1">
                                                <span class="badge {{ $systemBadgeClass }}">
                                                    <i class="fa-solid fa-cube me-1"></i>{{ $systemName }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $department->local_score ?? '—' }}</td>
                                    <td>{{ $department->internal_score ?? '—' }}</td>
                                    {{--  <td><span class="chip"><i class="fa-solid fa-layer-group"></i>
                                            {{ $department->type }}</span></td>
                                    <td>{{ $department->sex ?? '—' }}</td>  --}}
                                    <td>
                                        @if ($department->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچاڵاک</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('admin.departments.show', $department->id) }}"
                                            class="btn btn-sm btn-outline" data-bs-toggle="tooltip"
                                            data-bs-title="پیشاندان">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.departments.edit', $department->id) }}"
                                            class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                            data-bs-title="دەستکاری">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.departments.destroy', $department->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('ئایە دڵنیایت لە سڕینەوەی ئەم بەشە؟');">
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ====== DataTable init (گلوباڵ) ======
            window.dt = new DataTable('#datatable', {
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

            // ====== External Search & PageLength ======
            const customSearch = document.getElementById('custom-search');
            customSearch.addEventListener('input', () => window.dt.search(customSearch.value).draw());

            const pageLen = document.getElementById('page-length');
            pageLen.addEventListener('change', () => window.dt.page.len(Number(pageLen.value)).draw());

            // ====== Custom Info & Pager ======
            const infoBox = document.getElementById('dt-info');
            const pager = document.getElementById('dt-pager');

            function renderInfo() {
                const i = window.dt.page.info();
                infoBox.textContent = i.recordsDisplay ? `پیشاندانی ${i.start} تا ${i.end} لە ${i.recordsDisplay}` :
                    'هیچ تۆمار نییە';
            }

            function renderPager() {
                const i = window.dt.page.info();
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
                pager.querySelectorAll('[data-page]').forEach(el => {
                    el.addEventListener('click', (e) => {
                        e.preventDefault();
                        const to = Number(el.getAttribute('data-page'));
                        if (!Number.isNaN(to) && to >= 0 && to < total) window.dt.page(to).draw(
                            'page');
                    });
                });
            }

            window.dt.on('draw', () => {
                renderInfo();
                renderPager();
            });
            renderInfo();
            renderPager();

            // ====== Filters (System / Province / University / College) ======
            const q = id => document.getElementById(id);
            const selSystem = q('filter-system');
            const selProvince = q('filter-province');
            const selUniversity = q('filter-university');
            const selCollege = q('filter-college');
            const inputFilter = q('filter-search');
            const btnReset = q('filter-reset');

            const enable = (el, on = true) => el.disabled = !on;
            const fillSelect = (el, items, placeholder) => {
                el.innerHTML = `<option value="">${placeholder}</option>`;
                items.forEach(it => {
                    const opt = document.createElement('option');
                    opt.value = it.id; // ✅ value=id
                    opt.textContent = it.name; // نمایش=name
                    el.appendChild(opt);
                });
            };
            const selectedText = (el) => (el && el.value) ? el.options[el.selectedIndex].text : '';

            // Province -> Universities (by ID)
            selProvince.addEventListener('change', () => {
                const pid = selProvince.value;
                fillSelect(selUniversity, [], 'هەموو زانکۆكان');
                enable(selUniversity, false);
                fillSelect(selCollege, [], 'هەموو کۆلێژەکان');
                enable(selCollege, false);
                if (!pid) {
                    applyFilters();
                    return;
                }

                fetch(`/admin/api/universities?province_id=${encodeURIComponent(pid)}`)
                    .then(r => r.json())
                    .then(data => {
                        fillSelect(selUniversity, data, 'هەموو زانکۆكان');
                        enable(selUniversity, true);
                    })
                    .catch(() => {
                        fillSelect(selUniversity, [], 'هەڵە ڕوویدا');
                    });

                applyFilters();
            });

            // University -> Colleges (by ID)
            selUniversity.addEventListener('change', () => {
                const uid = selUniversity.value;
                fillSelect(selCollege, [], 'هەموو کۆلێژەکان');
                enable(selCollege, false);
                if (!uid) {
                    applyFilters();
                    return;
                }

                fetch(`/admin/api/colleges?university_id=${encodeURIComponent(uid)}`)
                    .then(r => r.json())
                    .then(data => {
                        fillSelect(selCollege, data, 'هەموو کۆلێژەکان');
                        enable(selCollege, true);
                    })
                    .catch(() => {
                        fillSelect(selCollege, [], 'هەڵە ڕوویدا');
                    });

                applyFilters();
            });

            // Column filtering (on Name column index=1) with AND lookaheads
            const NAME_COL_INDEX = 1;
            const escapeRx = (s) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

            function buildRegex() {
                const parts = [];
                if (selSystem.value) parts.push(escapeRx(selSystem.value)); // system (name)
                if (selProvince.value) parts.push(escapeRx(selectedText(selProvince))); // province name
                if (selUniversity.value) parts.push(escapeRx(selectedText(selUniversity))); // university name
                if (selCollege.value) parts.push(escapeRx(selectedText(selCollege))); // college name
                if (!parts.length) return '';
                return parts.map(p => `(?=.*${p})`).join('') + '.*';
            }

            function applyFilters() {
                const rx = buildRegex();
                // v2 API:
                window.dt.column(NAME_COL_INDEX).search(rx, {
                    regex: true,
                    smart: false
                }).draw();
            }

            [selSystem, selProvince, selUniversity, selCollege].forEach(el => el.addEventListener('change',
                applyFilters));
            inputFilter.addEventListener('input', () => window.dt.search(inputFilter.value).draw());

            // Reset all
            btnReset.addEventListener('click', () => {
                selSystem.value = '';
                selProvince.value = '';
                fillSelect(selUniversity, [], 'هەموو زانکۆكان');
                enable(selUniversity, false);
                fillSelect(selCollege, [], 'هەموو کۆلێژەکان');
                enable(selCollege, false);
                inputFilter.value = '';
                customSearch.value = '';

                window.dt.search('').columns().search('').draw();
            });
        });
    </script>
@endpush
