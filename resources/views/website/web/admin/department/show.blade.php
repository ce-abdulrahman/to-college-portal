@extends('website.web.admin.layouts.app')

@section('page_name', 'department')
@section('view_name', 'show')

@section('content')
    <div class="container-fluid py-4">
        {{-- Actions bar --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}">بەشەکان</a></li>
                            <li class="breadcrumb-item active">زانیاری بەش</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-building-columns me-1"></i>
                        بەشەکان
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                {{-- Department Information --}}
                <div class="card glass fade-in mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-table-list me-2"></i> زانیاری تەواوی بەش
                        </h4>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fa-solid fa-hashtag fa-2x text-muted mb-2"></i>
                                        <h5 class="mb-1">#</h5>
                                        <p class="fs-4 mb-0">{{ $department->id }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-9 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $department->image }}" alt="{{ $department->name }}"
                                                class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                            <div>
                                                <h4 class="mb-1">{{ $department->name }}</h4>
                                                <p class="text-muted mb-0">{{ $department->name_en }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-cube me-2"></i> سیستەم</h6>
                                        <p class="card-text fs-5">{{ $department->system->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-map-pin me-2"></i> پارێزگا
                                        </h6>
                                        <p class="card-text fs-5">{{ $department->province->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-school me-2"></i> زانکۆ</h6>
                                        <p class="card-text fs-5">{{ $department->university->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-building-columns me-2"></i>
                                            کۆلێژ</h6>
                                        <p class="card-text fs-5">{{ $department->college->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-percent me-2"></i> ن.
                                            ناوەندی</h6>
                                        <p class="card-text fs-5">{{ $department->local_score ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-percent me-2"></i> ن. دەرەوە
                                        </h6>
                                        <p class="card-text fs-5">{{ $department->external_score ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-layer-group me-2"></i> جۆر
                                        </h6>
                                        <p class="card-text">{{ $department->type }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-venus-mars me-2"></i> ڕەگەز
                                        </h6>
                                        <p class="card-text">{{ $department->sex ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-toggle-on me-2"></i> دۆخ
                                        </h6>
                                        <p class="card-text">
                                            @if ($department->status)
                                                <span class="badge bg-success">چاڵاک</span>
                                            @else
                                                <span class="badge bg-danger">ناچاڵاک</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted"><i class="fa-solid fa-calendar me-2"></i>
                                            دروستکراوە</h6>
                                        <p class="card-text small">
                                            {{ $department->created_at?->format('Y-m-d H:i') ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            @if ($department->description)
                                <div class="col-12 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title text-muted"><i class="fa-solid fa-align-left me-2"></i>
                                                وەسف</h6>
                                            <p class="card-text">{!! $department->description !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-primary">
                                <i class="fa-solid fa-pen-to-square me-1"></i> دەستکاری
                            </a>
                            <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-list me-1"></i> لیستەکە
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Map Section --}}
                @if ($department->lat && $department->lng)
                    <div class="card glass fade-in">
                        <div class="card-body">
                            <h4 class="card-title mb-4">
                                <i class="fa-solid fa-map me-2"></i> شوێنی بەش
                            </h4>
                            <div id="department-map" style="height: 400px; border-radius: 10px;"
                                data-lat="{{ $department->lat }}" data-lng="{{ $department->lng }}"
                                data-name="{{ $department->name }}">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapEl = document.getElementById('department-map');
            if (!mapEl) return;

            const lat = parseFloat(mapEl.dataset.lat);
            const lng = parseFloat(mapEl.dataset.lng);
            const name = mapEl.dataset.name || 'بەش';

            const map = L.map('department-map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            const departmentIcon = L.icon({
                iconUrl: '{{ asset('assets/admin/images/map-marker-department.png') }}',
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40]
            });

            L.marker([lat, lng], {
                    icon: departmentIcon
                })
                .addTo(map)
                .bindPopup(`<strong>${name}</strong><br>بەشی زانستی`);
        });
    </script>
@endpush
