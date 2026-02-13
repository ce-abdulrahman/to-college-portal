@extends('website.web.admin.layouts.app')

@section('page_name', 'students')
@section('view_name', 'index')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item active">لیستی قوتابیایان</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-bar me-1"></i>
                    لیستی قوتابیایان
                </h4>
            </div>
        </div>
    </div>

    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-3">
                <i class="fa-solid fa-users me-2"></i> قوتابیایان
            </h4>

            {{-- Top toolbar (length + search) --}}
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted mb-0">نیشاندانی</label>
                    <select id="page-length" class="form-select form-select-sm" style="width:auto">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
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
                                <th>ناو</th>
                                <th>کۆد</th>
                                <th>پیشە</th>
                                <th style="width:140px">ژمارەی بەشەکان</th>
                                <th style="width:120px">دۆخ</th>
                                <th style="width:120px">بینین</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                @if (auth()->user()->id != $student->user_id)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td class="fw-semibold">
                                            <i class="fa-regular fa-user me-1 text-muted"></i>
                                            {{ $student->user->name ?? '—' }}
                                        </td>
                                        <td>{{ $student->user->code ?? '—' }}</td>
                                        <td>
                                            @if (($student->user->role ?? null) === 'student')
                                                <span class="badge bg-secondary">قوتابی</span>
                                            @else
                                                <span class="badge bg-info">{{ $student->user->role ?? '—' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $student->result_deps_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($student->user->status ?? false)
                                                <span class="badge bg-success">چاڵاک</span>
                                            @else
                                                <span class="badge bg-danger">ناچاڵاک</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($student)
                                                <a href="{{ route('admin.student.show', $student->id) }}"
                                                    class="text-decoration-none">
                                                    <i class="fa fa-eye me-1"></i>
                                                </a>
                                            @else
                                                <span class="text-muted"> نییە</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            @if (count($students) == 0)
                                <tr>
                                    <td colspan="7" class="text-center">هیچ زانیاریەکی پەیوەندیدار نییە</td>
                                </tr>
                            @endif
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
    </script>
@endpush
