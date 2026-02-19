@extends('website.web.admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- Actions bar --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('center.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">بەشەکان</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-building-columns me-1"></i>
                        ناوی بەشەکەکان
                    </h4>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="d-flex gap-2">
                <a href="{{ route('center.departments.compare-descriptions') }}" class="btn btn-outline-info">
                    <i class="fa-solid fa-code-compare me-1"></i> بەراوردکردنی وەسف
                </a>
            </div>
            <div></div>
        </div>

        {{-- Filters Section --}}
        <form id="filtersForm" method="GET" action="{{ route('center.departments.index') }}">
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="searchInput" class="form-label">ناوی بەش</label>
                    <input type="text" id="searchInput" name="search" class="form-control"
                        value="{{ request('search') }}" placeholder="ناوی بەش بنوسە...">
                </div>
                <div class="col-md-2">
                    <label for="system_id" class="form-label">سیستەم</label>
                    <select id="system_id" name="system_id" class="form-select">
                        <option value="">هەموو</option>
                        @foreach ($systems as $sys)
                            <option value="{{ $sys->id }}" @selected(request('system_id') == $sys->id)>{{ $sys->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="province_id" class="form-label">پارێزگا</label>
                    <select id="province_id" name="province_id" class="form-select">
                        <option value="">هەموو</option>
                        @foreach ($provinces as $prov)
                            <option value="{{ $prov->id }}" @selected(request('province_id') == $prov->id)>{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="university_id" class="form-label">زانکۆ</label>
                    <select id="university_id" name="university_id" class="form-select">
                        <option value="">هەموو</option>
                        @foreach ($universities as $uni)
                            <option value="{{ $uni->id }}" @selected(request('university_id') == $uni->id)>{{ $uni->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="college_id" class="form-label">پۆل</label>
                    <select id="college_id" name="college_id" class="form-select">
                        <option value="">هەموو</option>
                        @foreach ($colleges as $coll)
                            <option value="{{ $coll->id }}" @selected(request('college_id') == $coll->id)>{{ $coll->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label for="limit" class="form-label">ژمارەی ڕیز</label>
                    <select id="limit" name="limit" class="form-select">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" @selected((int) request('limit', 25) === $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button id="resetFilters" type="button" class="btn btn-outline-secondary w-100">
                        <i class="fa-solid fa-redo me-1"></i> پاکردنەوە
                    </button>
                </div>
            </div>
        </form>

        {{-- Departments Table --}}
        <div class="mt-4">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="departmentsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="30%">ناوی بەش</th>
                                <th width="10%">لق</th>
                                <th width="10%">نمرە</th>
                                <th width="10%">بارودۆخ</th>
                                <th width="10%">کردارەکان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($departments as $department)
                                @php
                                    $systemName = $department->system->name ?? '-';
                                    $systemBadge = 'bg-secondary';
                                    if ($systemName === 'زانکۆلاین') {
                                        $systemBadge = 'bg-primary';
                                    } elseif ($systemName === 'پاراڵیل') {
                                        $systemBadge = 'bg-success';
                                    } elseif ($systemName === 'ئێواران') {
                                        $systemBadge = 'bg-danger';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $department->id }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $department->name }}</div>
                                        <div class="text-muted small mt-1">
                                            {{ $department->province->name ?? '-' }} /
                                            {{ $department->university->name ?? '-' }} /
                                            {{ $department->college->name ?? '-' }}
                                        </div>
                                        <span class="badge {{ $systemBadge }} mt-1">
                                            <i class="fa-solid fa-cube me-1"></i> {{ $systemName }}
                                        </span>
                                    </td>
                                    <td>{{ $department->type ?? '-' }}</td>
                                    <td>
                                        <div class="fw-semibold text-black">
                                            <span class="badge bg-success">
                                                {{ $department->local_score !== null ? number_format($department->local_score, 3) : '-' }}
                                            </span>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            <span class="badge bg-danger text-white">
                                                {{ $department->external_score !== null ? number_format($department->external_score, 3) : '-' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($department->status)
                                            <span class="badge bg-success">چالاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچالاک</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group dropend">
                                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu text-center">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('center.departments.show', $department->id) }}">
                                                        <i class="fa-solid fa-eye me-2"></i> نیشاندان
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="alert alert-info mb-0">
                                            <i class="fa-solid fa-info-circle me-2"></i> هیچ بەشەک نەدۆزرایەوە
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($departments->hasPages())
                    <div class="card-footer bg-light d-flex justify-content-center">
                        {{ $departments->links('vendor.pagination.admin-departments') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const form = document.getElementById('filtersForm');
            if (!form) return;

            ['system_id', 'province_id', 'university_id', 'college_id', 'limit'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                el.addEventListener('change', () => form.submit());
            });

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let t = null;
                searchInput.addEventListener('input', function() {
                    clearTimeout(t);
                    t = setTimeout(() => form.submit(), 400);
                });
            }

            const resetBtn = document.getElementById('resetFilters');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    window.location = "{{ route('center.departments.index') }}";
                });
            }
        })();
    </script>
@endpush
