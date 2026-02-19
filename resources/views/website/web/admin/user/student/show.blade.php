@extends('website.web.admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- Top Bar --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">لیستی بەکارهێنەران</a></li>
                            <li class="breadcrumb-item active">زانیاری قوتابی</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-chart-bar me-1"></i>
                        زانیاری قوتابی
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">

                {{-- User Info Card --}}
                <div class="card glass fade-in shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-circle-info me-2 text-primary"></i> زانیاری تەواوی قوتابی
                        </h4>

                        <div class="table-responsive table-scroll-x">
                            <table class="table table-striped table-hover align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i></th>
                                        <td>1</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-user me-1 text-muted"></i> ناو</th>
                                        <td>{{ $user->name ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-location-dot me-1 text-muted"></i> پارێزگا</th>
                                        <td>{{ $user->student->province ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-barcode me-1 text-muted"></i> کۆد</th>
                                        <td>{{ $user->code ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-percent me-1 text-muted"></i> نمرە</th>
                                        <td>{{ $user->student->mark ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-layer-group me-1 text-muted"></i> لق</th>
                                        <td><span class="chip chip-primary">{{ $user->student->type ?? '—' }}</span></td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-regular fa-calendar-plus me-1 text-muted"></i> دروستکراوە</th>
                                        <td>{{ optional($user->created_at)->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-regular fa-clock me-1 text-muted"></i> نوێکرایەوە</th>
                                        <td>{{ optional($user->updated_at)->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ</th>
                                        <td>
                                            @if ($user->status)
                                                <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>
                                                    چاڵاکە</span>
                                            @else
                                                <span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i>
                                                    ناچاڵاکە</span>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- Feature Flags Display --}}
                                    @if ($user->student)
                                        <tr>
                                            <th><i class="fa-solid fa-star me-1 text-muted"></i> تایبەتمەندییەکان</th>
                                            <td>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <span
                                                        class="badge {{ $user->student->ai_rank ? 'bg-success' : 'bg-secondary' }}">
                                                        <i
                                                            class="fa-solid {{ $user->student->ai_rank ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                        ڕیزبەندی کرد بە زیرەکی دەستکرد
                                                    </span>
                                                    <span
                                                        class="badge {{ $user->student->gis ? 'bg-success' : 'bg-secondary' }}">
                                                        <i
                                                            class="fa-solid {{ $user->student->gis ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                        GIS
                                                    </span>
                                                    <span
                                                        class="badge {{ $user->student->all_departments ? 'bg-success' : 'bg-secondary' }}">
                                                        <i
                                                            class="fa-solid {{ $user->student->all_departments ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                        All Departments (50)
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>

                {{-- Departments Table --}}
                <div class="card glass fade-in shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-building-columns me-2 text-success"></i> بەشە هەڵبژێدراوەکان لە کۆلێژ و
                            پەیمانگا
                        </h4>

                        <div class="table-responsive table-scroll-x">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>ناو</th>
                                        <th>ن. ناوەندی</th>
                                        <th>ن. دەرەوە</th>
                                        <th>جۆر</th>
                                        <th>ڕەگەز</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($result_deps as $index => $result_dep)
                                        @php
                                            $systemName = $result_dep->department->system->name;
                                            $systemBadgeClass = match ($systemName) {
                                                'زانکۆلاین' => 'bg-primary',
                                                'پاراڵیل' => 'bg-success',
                                                default => 'bg-danger',
                                            };
                                        @endphp
                                        <tr
                                            class="{{ $result_dep->department->system->name == 'زانکۆلاین' ? 'table-primary' : ($result_dep->department->system->name == 'پاراڵیل' ? 'table-success' : 'table-danger') }}">
                                            <td>{{ ++$index }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <div class="fw-semibold">{{ $result_dep->department->name }}</div>
                                                    <div class="text-muted small">
                                                        <span class="badge {{ $systemBadgeClass }}">
                                                            <i class="fa-solid fa-cube me-1"></i> {{ $systemName }}
                                                        </span> /
                                                        {{ $result_dep->department->province->name }} /
                                                        {{ $result_dep->department->university->name }} /
                                                        {{ $result_dep->department->college->name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $result_dep->department->local_score ?? '—' }}</td>
                                            <td>{{ $result_dep->department->external_score ?? '—' }}</td>
                                            <td><span class="chip"><i class="fa-solid fa-layer-group me-1"></i>
                                                    {{ $result_dep->department->type }}</span>
                                            </td>
                                            <td>{{ $result_dep->department->sex ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                <i class="fa-solid fa-circle-info me-1"></i> هیچ بەشێک نەدۆزرایەوە
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
