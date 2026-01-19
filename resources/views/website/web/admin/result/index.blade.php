@extends('website.web.admin.layouts.app')

@section('page_name', 'result')
@section('view_name', 'index')

@section('content')
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item active">لیستی هەڵبژاردراوەکانی قوتابیان</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-bar me-1"></i>
                    لیستی هەڵبژاردراوەکانی قوتابیان
                </h4>
            </div>
        </div>
    </div>    

    {{-- Filters Toolbar --}}
    <div class="card glass mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <form action="{{ route('admin.results.index') }}" method="post">
                    @csrf
                    {{-- Student --}}
                    <div class="col-12 col-md-3">
                        <label class="form-label"><i class="fa-solid fa-cube me-1 text-muted"></i> قوتابیەکان</label>
                        <select name="search" class="form-select">
                            <option value="">ناوی هەموو قوتابیەکان</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Search --}}
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label"><i class="fa-solid fa-magnifying-glass me-1 text-muted"></i> گەڕانی
                            گشتی</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="ناوی بەش/سیستەم/پارێزگا/زانکۆ/کۆلێژ ...">
                    </div>
                    <button class="btn btn-dark">گەڕانە</button>
                </form>

            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

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
                <input id="custom-search" type="search" class="form-control"
                    placeholder="گەڕان... (ناو/سیستەم/پارێزگا/...)">
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
                                <th>ناوی قوتابی</th>
                                <th>ناوی بەش</th>
                                <th>نمرەی ناوەندی</th>
                                <th>نمرەی ناوخۆی</th>
                                {{--  <th>جۆر</th>
                                <th>ڕەگەز</th>  --}}
                                <th>دۆخ</th>
                                <th>کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $i => $result)
                                @php
                                    $systemName = $result->department->system->name;
                                    $systemBadgeClass = match ($systemName) {
                                        'زانکۆلاین' => 'bg-primary',
                                        'پاراڵیل' => 'bg-success',
                                        default => 'bg-danger',
                                    };
                                @endphp

                                <tr data-systemColor="{{ $systemName }}"
                                    data-system="{{ $result->department->system->name }}"
                                    data-province="{{ $result->department->province->name }}"
                                    data-university="{{ $result->department->university->name }}"
                                    data-college="{{ $result->department->college->name }}">
                                    <td>{{ $i + 1 }}</td>
                                    <td data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true"
                                        data-bs-title="{{ $result->user->mark }}">{{ $result->user->name }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="fw-semibold">{{ $result->department->name }}</div>
                                            <div class="text-muted small">
                                                <span class="badge {{ $systemBadgeClass }}">
                                                    <i class="fa-solid fa-cube me-1"></i> {{ $systemName }}
                                                </span> /
                                                {{ $result->department->province->name }} /
                                                {{ $result->department->university->name }} /
                                                {{ $result->department->college->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $result->department->local_score ?? '—' }}</td>
                                    <td>{{ $result->department->internal_score ?? '—' }}</td>
                                    {{--  <td><span class="chip"><i class="fa-solid fa-layer-group"></i>
                                            {{ $department->type }}</span></td>
                                    <td>{{ $department->sex ?? '—' }}</td>  --}}
                                    <td>
                                        @if ($result->department->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچاڵاک</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('admin.departments.show', $result->department->id) }}"
                                            class="btn btn-sm btn-outline" data-bs-toggle="tooltip"
                                            data-bs-title="پیشاندان">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.departments.edit', $result->department->id) }}"
                                            class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                            data-bs-title="دەستکاری">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.departments.destroy', $result->department->id) }}"
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
 