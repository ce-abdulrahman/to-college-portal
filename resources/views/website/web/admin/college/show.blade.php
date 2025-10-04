@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.colleges.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">{{ __('زانیاری کۆلێژ') }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.colleges.edit', $college->id) }}" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form action="{{ route('admin.colleges.destroy', $college->id) }}" method="POST"
                onsubmit="return confirm('دڵنیایت؟');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            {{-- College basic info --}}
            <div class="card glass fade-in mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-building me-2"></i> زانیاری بنەڕەتی کۆلێژ / پەیمانگا
                    </h4>

                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                        <td>{{ $college->id }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i>پارێزگا</th>
                                        <td>{{ $college->university->province->name ?? ($university->province->name ?? '—') }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-school me-1 text-muted"></i>زانکۆ</th>
                                        <td>{{ $college->university->name ?? ($university->name ?? '—') }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-building-columns me-1 text-muted"></i>
                                            ناوی کۆلێژ / پەیمانگا</th>
                                        <td class="fw-semibold">{{ $college->name }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i>دۆخ</th>
                                        <td>
                                            @if ($college->status)
                                                <span class="badge bg-success">چاڵاک</span>
                                            @else
                                                <span class="badge bg-danger">ناچاڵاک</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-regular fa-calendar-plus me-1 text-muted"></i>
                                            دروستکراوە لە</th>
                                        <td>{{ $college->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-regular fa-clock me-1 text-muted"></i> گۆڕدراوە لە</th>
                                        <td>{{ $college->updated_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Departments of this College --}}
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-table-list me-2"></i> بەشەکان بە پێی ئەم کۆلێژە
                    </h4>

                    {{-- Toolbar: count + (optional filters) --}}
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <div class="table-toolbar">
                            <span class="chip">
                                <i class="fa-solid fa-database text-primary"></i> کۆی گشتی بەشی زانکۆلاین:
                                {{ count($departments->where('system_id', 1)) }}
                            </span>
                            <span class="chip">
                                <i class="fa-solid fa-database text-success"></i> کۆی گشتی بەشی پاراڵیل:
                                {{ count($departments->where('system_id', 2)) }}
                            </span>
                            <span class="chip">
                                <i class="fa-solid fa-database text-danger"></i> کۆی گشتی بەشەکانی ئێواران:
                                {{ count($departments->where('system_id', 3)) ?? 'نیە' }}
                            </span>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table id="deptTable" class="table align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>ناو</th>
                                        <th style="width:120px">ن. ناوەندی</th>
                                        <th style="width:120px">ن. ناوخۆی</th>
                                        <th style="width:120px">جۆر</th>
                                        <th style="width:100px">ڕەگەز</th>
                                        <th style="width:120px">دۆخ</th>
                                        <th style="width:200px">کردار</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($departments as $index => $department)
                                        <tr
                                            class="{{ $department->system->name == 'زانکۆلاین' ? 'table-primary' : ($department->system->name == 'پاراڵیل' ? 'table-success' : 'table-danger') }}">
                                            <td>{{ ++$index }}</td>
                                            <td class="fw-semibold">
                                                <i class="fa-solid fa-tag me-1 text-muted"></i>
                                                {{ $department->name }}
                                            </td>
                                            <td>{{ $department->local_score ?? '—' }}</td>
                                            <td>{{ $department->internal_score ?? '—' }}</td>
                                            <td>
                                                <span class="chip"><i class="fa-solid fa-layer-group"></i>
                                                    {{ $department->type }}</span>
                                            </td>
                                            <td>{{ $department->sex ?? '—' }}</td>
                                            <td>
                                                @if ($department->status)
                                                    <span class="badge bg-success">چاڵاک</span>
                                                @else
                                                    <span class="badge bg-danger">ناچالاک</span>
                                                @endif
                                            </td>
                                            <td class="actions">
                                                <a href="{{ route('admin.departments.show', $department->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fa-solid fa-eye me-1"></i>
                                                </a>
                                                <a href="{{ route('admin.departments.edit', $department->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-pen-to-square me-1"></i>
                                                </a>
                                                <form action="{{ route('admin.departments.destroy', $department->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('دڵنیایت دەتەوێت ئەم بەشە بسڕیتەوە؟');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa-solid fa-trash-can me-1"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                <i class="fa-solid fa-circle-info me-1"></i>
                                                هیچ بەشێک بۆ ئەم کۆلێژە نەدۆزرایەوە
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // هەڵبژاردن: ئەگەر DataTablesت ئامادەیە، لەخوارەوە چالاکی بکە
            if (window.DataTable) {
                new DataTable('#deptTable', {
                    autoWidth: false,
                    ordering: true,
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        search: 'گەڕان:',
                        lengthMenu: 'پیشاندانی _MENU_',
                        info: 'پیشاندانی _START_ تا _END_ لە _TOTAL_',
                        paginate: {
                            previous: 'پێشتر',
                            next: 'دواتر'
                        },
                        zeroRecords: 'هیچ داتا نییە',
                        infoEmpty: 'هیچ تۆمار نییە',
                    }
                });
            }
        });
    </script>
@endpush
