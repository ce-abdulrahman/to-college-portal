@extends('website.web.admin.layouts.app')

@section('content')

<div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.results.index') }}">لیستی هەڵبژاردراوەکانی قوتابیان</a></li>
                        <li class="breadcrumb-item active">زانیاریەکانی هەڵبژاردراوەکانی قوتابیان</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-bar me-1"></i>
                    زانیاریەکانی هەڵبژاردراوەکانی قوتابیان
                </h4>
            </div>
        </div>
    </div> 

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">زانیاری بەش</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-primary">
                <i class="fa-solid fa-pen-to-square me-1"></i>
            </a>
            <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST"
                onsubmit="return confirm('دڵنیایت؟');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-trash-can me-1"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-table-list me-2"></i> زانیاری تەواوی بەش
                    </h4>

                    <div class="table-wrap">
                        <div class="table-responsive table-scroll-x">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                        <td>{{ $department->id }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-diagram-project me-1 text-muted"></i> سیستەم</th>
                                        <td>{{ $department->system->name ?? '—' }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-location-dot me-1 text-muted"></i> پارێزگا</th>
                                        <td>{{ $department->province->name ?? '—' }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-school me-1 text-muted"></i> زانکۆ</th>
                                        <td>{{ $department->university->name ?? '—' }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-building-columns me-1 text-muted"></i> کۆلێژ/پەیمانگا</th>
                                        <td>{{ $department->college->name ?? '—' }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-tag me-1 text-muted"></i> ناوی بەش</th>
                                        <td class="fw-bold">{{ $department->name }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-percent me-1 text-muted"></i> نمرەی ناو پاریزگا</th>
                                        <td>{{ $department->local_score ?? '—' }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-percent me-1 text-muted"></i> نمرەی ناوەندی</th>
                                        <td>{{ $department->internal_score ?? '—' }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-layer-group me-1 text-muted"></i> لَق</th>
                                        <td><span class="chip"><i class="fa-solid fa-layer-group"></i>
                                                {{ $department->type }}</span></td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-regular fa-calendar-plus me-1 text-muted"></i> دروستکراوە</th>
                                        <td>{{ optional($department->created_at)->format('Y-m-d H:i') }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-regular fa-clock me-1 text-muted"></i> نوێکرایەوە</th>
                                        <td>{{ optional($department->updated_at)->format('Y-m-d H:i') }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ</th>
                                        <td>
                                            @if ($department->status)
                                                <span class="badge bg-success">کارایە</span>
                                            @else
                                                <span class="badge bg-danger">نەکارایە</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-regular fa-note-sticky me-1 text-muted"></i> وەسف</th>
                                        <td class="text-muted">{!! $department->description ?: '—' !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Optional: Quick actions under the table --}}
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-primary">
                            <i class="fa-solid fa-pen-to-square me-1"></i> گۆڕین
                        </a>
                        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline">
                            <i class="fa-solid fa-list me-1"></i> لیستەکە
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
