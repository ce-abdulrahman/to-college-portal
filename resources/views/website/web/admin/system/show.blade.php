@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.systems.index') }}">سیستەمەکانی خوێندن</a></li>
                        <li class="breadcrumb-item active">تەواوی زانیاری سیستەم</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-bar me-1"></i>
                    تەواوی زانیاری سیستەم
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            {{-- System Information --}}
            <div class="card glass fade-in mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-table-list me-2"></i> زانیاری سیستەم
                    </h4>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fa-solid fa-hashtag fa-2x text-muted mb-2"></i>
                                    <h5 class="mb-1">#</h5>
                                    <p class="fs-4 mb-0">{{ $system->id }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                            <i class="fa-solid fa-diagram-project fa-2x text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="mb-1">{{ $system->name }}</h4>
                                            <p class="text-muted mb-0">سیستەمی خوێندن</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title text-muted"><i class="fa-solid fa-toggle-on me-2"></i> دۆخ</h6>
                                    <p class="card-text">
                                        @if ($system->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچاڵاک</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title text-muted"><i class="fa-solid fa-calendar-check me-2"></i> دروستکراوە</h6>
                                    <p class="card-text">{{ $system->created_at?->format('Y-m-d H:i') ?? '—' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title text-muted"><i class="fa-solid fa-calendar-pen me-2"></i> گۆڕدراوە</h6>
                                    <p class="card-text">{{ $system->updated_at?->format('Y-m-d H:i') ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.systems.edit', $system->id) }}" class="btn btn-primary">
                            <i class="fa-solid fa-pen-to-square me-1"></i> دەستکاری
                        </a>
                        <a href="{{ route('admin.systems.index') }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-list me-1"></i> لیستەکە
                        </a>
                    </div>
                </div>
            </div>

            {{-- Departments in this system --}}
            <div class="card glass fade-in">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">
                            <i class="fa-solid fa-graduation-cap me-2"></i> بەشەکانی ئەم سیستەمە
                        </h4>
                        <div class="d-flex gap-2">
                            <span class="badge bg-info">
                                <i class="fa-solid fa-database me-1"></i> کۆی گشتی: {{ count($departments ?? []) }}
                            </span>
                            <a href="{{ route('admin.departments.create') }}?system_id={{ $system->id }}" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-plus me-1"></i> زیادکردنی بەش
                            </a>
                        </div>
                    </div>

                    @if(isset($departments) && $departments->count() > 0)
                        <div class="table-responsive">
                            <table id="departments-table" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ناوی بەش</th>
                                        <th>سیستەم</th>
                                        <th>دۆخ</th>
                                        <th>کردار</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($departments as $index => $department)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $department->name }}</td>
                                            <td>{{ $department->system->name ?? '—' }}</td>
                                            <td>
                                                @if ($department->status)
                                                    <span class="badge bg-success">چاڵاک</span>
                                                @else
                                                    <span class="badge bg-danger">ناچاڵاک</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.departments.show', $department->id) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('ئیشکراوی ئەم بەشە دڵنیایت؟');">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            هیچ بەشێک لەم سیستەمەدا نیە.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    // Initialize DataTable for departments
    document.addEventListener('DOMContentLoaded', function() {
        const departmentsTable = $('#departments-table');
        
        if (departmentsTable.length) {
            departmentsTable.DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ku.json'
                },
                order: [],
                pageLength: 10,
                responsive: true
            });
        }
    });
</script>
@endpush