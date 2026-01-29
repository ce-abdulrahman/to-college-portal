@extends('website.web.admin.layouts.app')

@section('page_name', 'department')
@section('view_name', 'index')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
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
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-gear me-1"></i> بەڕێوەبەرایەتی
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('admin.systems.create') }}" class="dropdown-item">
                        <i class="fa-solid fa-cube me-2"></i> سیستەمەکان
                    </a>
                    <a href="{{ route('admin.provinces.create') }}" class="dropdown-item">
                        <i class="fa-solid fa-map-location-dot me-2"></i> پارێزگاکان
                    </a>
                    <a href="{{ route('admin.universities.create') }}" class="dropdown-item">
                        <i class="fa-solid fa-building-columns me-2"></i> زانکۆکان
                    </a>
                </div>
            </div>

            <div class="dropdown">
                <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-file-import me-1"></i> Import/Export
                </button>
                <div class="dropdown-menu">
                    <a href="#importModal" class="dropdown-item" data-bs-toggle="modal">
                        <i class="fa-solid fa-file-import me-2"></i> Import بەشەکان
                    </a>
                    <a href="{{ route('admin.departments.export') }}" class="dropdown-item">
                        <i class="fa-solid fa-file-export me-2"></i> Export بەشەکان
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.departments.download-template') }}" class="dropdown-item">
                        <i class="fa-solid fa-file-excel me-2"></i> داگرتنی نموونە
                    </a>
                </div>
            </div>


            <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> زیادکردنی بەش
            </a>
        </div>

        <div class="d-flex align-items-center gap-2">
            {{-- Total count is now in Livewire, but we can't easily access $departments count here without querying. 
                 It's minor UI, so we can remove it or keep it static? 
                 Better to remove or move it into Livewire component if we want it dynamic.
                 I'll remove it here to avoid error since $departments variable is gone from Controller. --}}
        </div>
    </div>

    {{-- Livewire Table Component --}}
    <div class="mt-4">
        <livewire:admin.department-table />
    </div>

@endsection

{{-- Modal بۆ Import --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.departments.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-file-import me-2"></i> Import بەشەکان
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        فایلەکە دەبێت Excel (xlsx/xls) بێت.
                        <a href="{{ route('admin.departments.download-template') }}" class="alert-link">
                            نموونەیەک داگرە
                        </a>
                    </div>

                    <div class="mb-3">
                        <label for="importFile" class="form-label">فایلی Excel</label>
                        <input type="file" class="form-control" id="importFile" name="file" accept=".xlsx,.xls"
                            required>
                        <small class="text-muted">تەنها فایلەکانی Excel پشتگیری دەکرێن</small>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="update_existing" id="updateExisting">
                        <label class="form-check-label" for="updateExisting">
                            نوێکردنەوەی تۆمارە هەبووەکان (بەپێی ID)
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-1"></i> هەڵوەشاندنەوە
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-file-import me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    {{-- Client-side logic removed for server-side performance optimization --}}
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
