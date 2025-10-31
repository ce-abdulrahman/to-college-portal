@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title" style="font-size: 32px">
                <i class="fa-solid fa-users me-2"></i> لیستی مامۆستایان
            </div>
        </div>
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
                                <th>ژمارە</th>
                                <th>دەسەڵات</th>
                                <th>کۆدی بانگکردن</th>
                                <th>دۆخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td class="fw-semibold">
                                            <i class="fa-regular fa-user me-1 text-muted"></i>
                                            {{ $user->name }}
                                        </td>
                                        <td>{{ $user->code }}</td>
                                        <td>
                                            {{ $user->phone }}
                                        </td>
                                        <td>
                                            @if ($user->role === 'center')
                                                <span class="badge bg-info">سەنتەر</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $user->rand_code }}
                                        </td>
                                        <td>
                                            @if ($user->status)
                                                <span class="badge bg-success">چاڵاک</span>
                                            @else
                                                <span class="badge bg-danger">ناچاڵاک</span>
                                            @endif
                                        </td>
                                    </tr>
                            @endforeach
                            <tr>
                                <td colspan="6" class="text-center">هیچ زانیاریەکی پەیوەندیدار نییە</td>
                            </tr>
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
