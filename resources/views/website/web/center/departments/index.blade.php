@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-outline" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true"
                data-bs-title="<div class='text-start'>
            @foreach ($systems as $system)
<div><i class='fa-solid fa-cube me-1 text-muted'></i>{{ $system->name }}</div>
@endforeach
         </div>">
                <i class="fa-solid fa-diagram-project me-1"></i> سیستەم
            </a>

            <a href="#" class="btn btn-outline" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true"
                data-bs-title="<div class='text-start'>
            @foreach ($provinces as $province)
<div><i class='fa-solid fa-cube me-1 text-muted'></i>{{ $province->name }}</div>
@endforeach
         </div>">
                <i class="fa-solid fa-map-location-dot me-1"></i> پارێزگا
            </a>

            <a href="#" class="btn btn-outline" data-bs-toggle="tooltip" data-bs-placement="bottom"
                data-bs-html="true"
                data-bs-title="<div class='text-start'>
            @foreach ($universities as $university)
<div><i class='fa-solid fa-cube me-1 text-muted'></i>{{ $university->name }}</div>
@endforeach
         </div>">
                <i class="fa-solid fa-building-columns me-1"></i> زانکۆ
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
                <div class="table-responsive table-scroll-x">
                    <table id="datatable" class="table align-middle nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>وێنە</th>
                                <th>ناو</th>
                                <th>ن. نمرەی</th>
                                <th>د. نمرەی</th>
                                <th>کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $i => $department)
                                @php
                                    $systemName = $department->system->name;
                                    $badge = match ($systemName) {
                                        'زانکۆلاین' => 'bg-primary',
                                        'پاراڵیل' => 'bg-success',
                                        default => 'bg-danger',
                                    };
                                @endphp
                                <tr data-system="{{ $department->system->name }}"
                                    data-province-id="{{ $department->province_id }}"
                                    data-university-id="{{ $department->university_id }}"
                                    data-college-id="{{ $department->college_id }}">
                                    <td>{{ $i + 1 }}</td>

                                    <td class="fw-semibold">
                                        <img src="{{ $department->image }}" alt="{{ $department->name }}"
                                            style="height:40px;max-width:100%;border-radius:6px;object-fit:cover">
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $department->name }}</div>
                                        <div class="text-muted small">
                                            <span class="badge {{ $badge }}"><i class="fa-solid fa-cube me-1"></i>
                                                {{ $systemName }}</span> /
                                            {{ $department->province->name }} /
                                            {{ $department->university->name }} /
                                            {{ $department->college->name }}
                                        </div>

                                    </td>
                                    <td>{{ $department->local_score ?? '—' }}</td>
                                    <td>{{ $department->external_score ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('center.departments.show', $department->id) }}"
                                            class="text-decoration-none btn-outline-light">
                                            <i class="fa fa-eye me-1"></i>
                                        </a>
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
        (() => {
            "use strict";
            document.addEventListener("DOMContentLoaded", () => {
                const dt = window.initDataTable('#datatable');

                // External search + length
                const customSearch = document.getElementById('custom-search');
                const lengthSel = document.getElementById('page-length');
                customSearch?.addEventListener('input', () => dt.search(customSearch.value).draw());
                lengthSel?.addEventListener('change', () => dt.page.len(Number(lengthSel.value)).draw());

                // Info + Pager
                const infoBox = document.getElementById('dt-info');
                const pager = document.getElementById('dt-pager');
                const rerender = () => {
                    infoBox && window.renderDtInfo(dt, infoBox);
                    pager && window.renderDtPager(dt, pager);
                };
                dt.on('draw', rerender);
                rerender();

                // Filters
                const $ = id => document.getElementById(id);
                const selSystem = $('filter-system'),
                    selProv = $('filter-province'),
                    selUni = $('filter-university'),
                    selCol = $('filter-college'),
                    txtFilter = $('filter-search'),
                    btnReset = $('filter-reset');

                const enable = (el, on = true) => el && (el.disabled = !on);
                const fill = (el, list, ph) => {
                    if (!el) return;
                    el.innerHTML = `<option value="">${ph}</option>`;
                    list.forEach(({
                        id,
                        name
                    }) => {
                        const o = document.createElement('option');
                        o.value = id;
                        o.textContent = name;
                        el.appendChild(o);
                    });
                };

                // Province -> Universities
                selProv?.addEventListener('change', () => {
                    const pid = selProv.value;
                    fill(selUni, [], 'هەموو زانکۆكان');
                    enable(selUni, false);
                    fill(selCol, [], 'هەموو کۆلێژەکان');
                    enable(selCol, false);
                    if (!pid) {
                        applyFilters();
                        return;
                    }

                    fetch(`/api/v1/lookups/universities?province_id=${encodeURIComponent(pid)}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(list => {
                            fill(selUni, list, 'هەموو زانکۆكان');
                            enable(selUni, true);
                        })
                        .catch(() => fill(selUni, [], 'هەڵە ڕوویدا'));

                    applyFilters();
                });

                // University -> Colleges
                selUni?.addEventListener('change', () => {
                    const uid = selUni.value;
                    fill(selCol, [], 'هەموو کۆلێژەکان');
                    enable(selCol, false);
                    if (!uid) {
                        applyFilters();
                        return;
                    }

                    fetch(`/api/v1/lookups/colleges?university_id=${encodeURIComponent(uid)}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(list => {
                            fill(selCol, list, 'هەموو کۆلێژەکان');
                            enable(selCol, true);
                        })
                        .catch(() => fill(selCol, [], 'هەڵە ڕوویدا'));

                    applyFilters();
                });

                // Apply filters by row dataset
                function applyFilters() {
                    const sys = selSystem?.value?.trim() || '';
                    const pid = selProv?.value || '';
                    const uid = selUni?.value || '';
                    const cid = selCol?.value || '';

                    dt.rows().every(function() {
                        const n = this.node();
                        const okSys = !sys || n.dataset.system === sys;
                        const okProv = !pid || n.dataset.provinceId === pid;
                        const okUni = !uid || n.dataset.universityId === uid;
                        const okCol = !cid || n.dataset.collegeId === cid;
                        (okSys && okProv && okUni && okCol) ? n.classList.remove('d-none'): n.classList
                            .add('d-none');
                    });
                    dt.draw(false);
                }

                [selSystem, selProv, selUni, selCol].forEach(el => el?.addEventListener('change',
                applyFilters));
                txtFilter?.addEventListener('input', () => dt.search(txtFilter.value).draw());

                // Reset
                btnReset?.addEventListener('click', () => {
                    selSystem && (selSystem.value = '');
                    selProv && (selProv.value = '');
                    fill(selUni, [], 'هەموو زانکۆكان');
                    enable(selUni, false);
                    fill(selCol, [], 'هەموو کۆلێژەکان');
                    enable(selCol, false);
                    txtFilter && (txtFilter.value = '');
                    customSearch && (customSearch.value = '');

                    dt.rows().every(function() {
                        this.node().classList.remove('d-none');
                    });
                    dt.search('').columns().search('').draw();
                });

            });
        })();
    </script>
@endpush
