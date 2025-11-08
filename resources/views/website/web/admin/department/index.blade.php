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
                <div class="col-12 col-md-3 mt-2">
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
                                            {{ $department->system->name }} /
                                            {{ $department->province->name }} /
                                            {{ $department->university->name }} /
                                            {{ $department->college->name }}
                                        </div>
                                        <span class="badge {{ $badge }}"><i
                                                class="fa-solid fa-cube me-1"></i>{{ $systemName }}</span>
                                    </td>
                                    <td>{{ $department->local_score ?? '—' }}</td>
                                    <td>{{ $department->external_score ?? '—' }}</td>
                                    <td>
                                        @if ($department->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچاڵاک</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('admin.departments.show', $department->id) }}"
                                            class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                            data-bs-title="پیشاندان">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.departments.edit', $department->id) }}"
                                            class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                            data-bs-title="دەستکاری">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.departments.destroy', $department->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('ئایە دڵنیایت لە سڕینەوەی ئەم بەشە؟');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip" data-bs-title="سڕینەوە">
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
    <script src="{{ asset('assets/admin/js/pages/departments/index.js') }}" defer></script>
@endpush
