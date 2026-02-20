@extends('website.web.admin.layouts.app')

@section('page_name', 'users')
@section('view_name', 'show')

@section('content')
    <div class="container-fluid py-4">
        {{-- Top Bar --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right d-flex align-items-center gap-2">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">بەکارهێنەران</a></li>
                            <li class="breadcrumb-item active">{{ $user->name }}</li>
                        </ol>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary ms-2 shadow-sm">
                            <i class="fa-solid fa-pen-to-square me-1"></i> دەستکاری
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                            گەڕانەوە
                        </a>
                    </div>
                    <h4 class="page-title">
                        <i class="fa-solid fa-address-card me-2 text-primary"></i>
                        زانیاری بەکارهێنەر
                    </h4>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Basic Information Column --}}
            <div class="col-lg-6">
                <div class="card glass fade-in shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-2">
                        <h5 class="mb-0 card-title">
                            <i class="fa-solid fa-user-circle me-2 text-info"></i>
                            زانیاری سەرەکی
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-4 text-muted" style="width: 140px;">
                                            <i class="fa-solid fa-hashtag me-2"></i> ID
                                        </th>
                                        <td class="fw-semibold">{{ $user->id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-4 text-muted">
                                            <i class="fa-solid fa-user me-2"></i> ناو
                                        </th>
                                        <td class="fw-bold text-dark">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-4 text-muted">
                                            <i class="fa-solid fa-barcode me-2"></i> کۆد
                                        </th>
                                        <td class="font-monospace">{{ $user->code }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-4 text-muted">
                                            <i class="fa-solid fa-key me-2"></i> Rand Code
                                        </th>
                                        <td class="font-monospace">{{ $user->rand_code }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-4 text-muted">
                                            <i class="fa-solid fa-phone me-2"></i> تەلەفۆن
                                        </th>
                                        <td>{{ $user->phone ?: '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-4 text-muted">
                                            <i class="fa-solid fa-user-tag me-2"></i> Role
                                        </th>
                                        <td><span
                                                class="badge bg-soft-primary text-primary">{{ strtoupper($user->role) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-4 text-muted">
                                            <i class="fa-solid fa-toggle-on me-2"></i> Status
                                        </th>
                                        <td>
                                            @if ($user->status)
                                                <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>
                                                    چاڵاک</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="fa-solid fa-xmark me-1"></i>
                                                    ناچاڵاک</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-4 text-muted border-bottom-0 pb-4">
                                            <i class="fa-regular fa-calendar me-2"></i> Created
                                        </th>
                                        <td class="border-bottom-0 pb-4">
                                            {{ optional($user->created_at)->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Role Specific Information Column --}}
            <div class="col-lg-6">
                <div class="card glass fade-in shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-2">
                        <h5 class="mb-0 card-title">
                            <i class="fa-solid fa-users-gear me-2 text-warning"></i>
                            زانیاری role-specific
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if ($user->role === 'center')
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-4 text-muted" style="width: 160px;"><i
                                                    class="fa-solid fa-map-marker-alt me-2"></i> Province</th>
                                            <td>{{ data_get($user, 'center.province') ?: '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-users me-2"></i> Referral</th>
                                            <td class="font-monospace">{{ data_get($user, 'center.referral_code') ?: '—' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-chalkboard-user me-2"></i>
                                                Limit Teacher</th>
                                            <td>{{ data_get($user, 'center.limit_teacher', 0) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-user-graduate me-2"></i> Limit
                                                Student</th>
                                            <td>{{ data_get($user, 'center.limit_student', 0) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-robot me-2"></i> AI Rank</th>
                                            <td>
                                                <span
                                                    class="badge {{ data_get($user, 'center.ai_rank') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'center.ai_rank') ? 'ON' : 'OFF' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-map me-2"></i> GIS</th>
                                            <td>
                                                <span
                                                    class="badge {{ data_get($user, 'center.gis') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'center.gis') ? 'ON' : 'OFF' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-building-columns me-2"></i>
                                                All Depts</th>
                                            <td>
                                                <span
                                                    class="badge {{ data_get($user, 'center.all_departments') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'center.all_departments') ? 'ON' : 'OFF' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted border-bottom-0 pb-4"><i
                                                    class="fa-solid fa-hand-holding-hand me-2"></i> Queue Hand</th>
                                            <td class="border-bottom-0 pb-4">
                                                <span
                                                    class="badge {{ data_get($user, 'center.queue_hand_department') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'center.queue_hand_department') ? 'ON' : 'OFF' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @elseif ($user->role === 'teacher')
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-4 text-muted" style="width: 160px;"><i
                                                    class="fa-solid fa-map-marker-alt me-2"></i> Province</th>
                                            <td>{{ data_get($user, 'teacher.province') ?: '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-users me-2"></i> Referral</th>
                                            <td class="font-monospace">
                                                {{ data_get($user, 'teacher.referral_code') ?: '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-user-graduate me-2"></i> Limit
                                                Student</th>
                                            <td>{{ data_get($user, 'teacher.limit_student', 0) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-robot me-2"></i> AI Rank</th>
                                            <td>
                                                <span
                                                    class="badge {{ data_get($user, 'teacher.ai_rank') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'teacher.ai_rank') ? 'ON' : 'OFF' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-map me-2"></i> GIS</th>
                                            <td>
                                                <span
                                                    class="badge {{ data_get($user, 'teacher.gis') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'teacher.gis') ? 'ON' : 'OFF' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-building-columns me-2"></i>
                                                All Depts</th>
                                            <td>
                                                <span
                                                    class="badge {{ data_get($user, 'teacher.all_departments') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'teacher.all_departments') ? 'ON' : 'OFF' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted border-bottom-0 pb-4"><i
                                                    class="fa-solid fa-hand-holding-hand me-2"></i> Queue Hand</th>
                                            <td class="border-bottom-0 pb-4">
                                                <span
                                                    class="badge {{ data_get($user, 'teacher.queue_hand_department') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'teacher.queue_hand_department') ? 'ON' : 'OFF' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @elseif ($user->role === 'student')
                            @php
                                $studentYearRaw = (int) data_get($user, 'student.year', 0);
                                $studentYearDisplay =
                                    $studentYearRaw === 1 ? '1' : ($studentYearRaw > 1 ? 'زیاتر لە ٢' : '—');
                                $isStudentYearOne = $studentYearRaw === 1;
                            @endphp
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-4 text-muted" style="width: 160px;"><i
                                                    class="fa-solid fa-percent me-2"></i> Mark</th>
                                            <td class="fw-bold text-success">{{ data_get($user, 'student.mark') ?? '—' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-map-marker-alt me-2"></i>
                                                Province</th>
                                            <td>{{ data_get($user, 'student.province') ?: '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-layer-group me-2"></i> Type
                                            </th>
                                            <td><span
                                                    class="badge bg-soft-info text-info">{{ data_get($user, 'student.type') ?: '—' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-venus-mars me-2"></i> Gender
                                            </th>
                                            <td>{{ data_get($user, 'student.gender') ?: '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-regular fa-calendar me-2"></i>
                                                پڕکردنەوەی فۆرم
                                            </th>
                                            <td>{{ $studentYearDisplay }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-circle-info me-2"></i>
                                                سیستەمی هەڵبژاردن
                                            </th>
                                            <td>
                                                @if ($studentYearDisplay !== '—')
                                                    @if ($isStudentYearOne)
                                                        دەتوانی سیستەمی <span class="badge bg-success">زانکۆلاین</span>
                                                        و <span class="badge bg-danger">پاڕالێل</span> و
                                                        <span class="badge bg-dark">ئێواران</span> هەڵبژێری
                                                    @else
                                                        بەس سیستەمی <span class="badge bg-danger">پاڕالێل</span> و
                                                        <span class="badge bg-dark">ئێواران</span> هەڵبژێری
                                                    @endif
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-users me-2"></i> Referral
                                            </th>
                                            <td class="font-monospace">
                                                {{ data_get($user, 'student.referral_code') ?: '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-toggle-on me-2"></i> Status
                                            </th>
                                            <td>
                                                <span
                                                    class="badge {{ data_get($user, 'student.status') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'student.status') ? 'چاڵاک' : 'ناچاڵاک' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-brain me-2"></i> MBTI</th>
                                            <td><span
                                                    class="badge bg-soft-warning text-warning">{{ data_get($user, 'student.mbti_type') ?: '—' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i
                                                    class="fa-solid fa-location-crosshairs me-2"></i> Lat / Lng</th>
                                            <td class="small font-monospace">
                                                {{ data_get($user, 'student.lat') ?: '—' }} /
                                                {{ data_get($user, 'student.lng') ?: '—' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-robot me-2"></i> AI Rank
                                            </th>
                                            <td>
                                                <span
                                                    class="badge {{ data_get($user, 'student.ai_rank') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'student.ai_rank') ? 'ON' : 'OFF' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted"><i class="fa-solid fa-map me-2"></i> GIS</th>
                                            <td>
                                                <span
                                                    class="badge {{ data_get($user, 'student.gis') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'student.gis') ? 'ON' : 'OFF' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 text-muted border-bottom-0 pb-4"><i
                                                    class="fa-solid fa-building-columns me-2"></i> All Depts</th>
                                            <td class="border-bottom-0 pb-4">
                                                <span
                                                    class="badge {{ data_get($user, 'student.all_departments') ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ data_get($user, 'student.all_departments') ? 'ON' : 'OFF' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="row align-items-center justify-content-center h-100 p-5 text-center">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <i class="fa-solid fa-user-shield fa-3x text-muted opacity-50"></i>
                                    </div>
                                    <h6 class="text-muted">بەکارهێنەری Admin کۆلۆمی Role-specific نییە</h6>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
