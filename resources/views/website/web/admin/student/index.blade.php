@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">{{ __('لیستی قوتابیان') }}</div>
        </div>

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
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
                                                <a href="{{ route('admin.students.show', $user->student->id) }}"
                                                    class="text-decoration-none">
                                                    <i class="fa-solid fa-link me-1"></i>
                                                    {{ $user->name }}
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
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                                data-bs-title="{{ __('دەستکاری') }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
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
