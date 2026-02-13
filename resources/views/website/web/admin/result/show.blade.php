@extends('website.web.admin.layouts.app')

@section('content')
    @php
        $student = $result->student;
        $user = $student?->user;
        $department = $result->department;
    @endphp

    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.results.index') }}">لیستی هەڵبژاردراوەکانی
                                قوتابیان</a></li>
                        <li class="breadcrumb-item active">وردەکاری هەڵبژاردن</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-bar me-1"></i>
                    وردەکاری هەڵبژاردن
                </h4>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.results.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
        </a>
        <div class="d-flex gap-2">
            @if ($student)
                <a href="{{ route('admin.student.show', $student->id) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-user me-1"></i> زانیاری قوتابی
                </a>
            @endif
            @if ($department)
                <a href="{{ route('admin.departments.show', $department->id) }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-building-columns me-1"></i> زانیاری بەش
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-user-graduate me-2"></i> زانیاری قوتابی
                    </h4>

                    <div class="table-responsive table-scroll-x">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <th style="width:260px">ناو</th>
                                    <td>{{ $user?->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>کۆد</th>
                                    <td>{{ $user?->code ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>نمرە</th>
                                    <td>{{ $student?->mark ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>پارێزگا</th>
                                    <td>{{ $student?->province ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>لق</th>
                                    <td>{{ $student?->type ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>ڕەگەز</th>
                                    <td>{{ $student?->gender ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>ساڵ</th>
                                    <td>{{ $student?->year ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>کۆدی بانگێشت</th>
                                    <td>{{ $student?->referral_code ?? '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card glass fade-in mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-building-columns me-2"></i> زانیاری بەش
                    </h4>

                    <div class="table-responsive table-scroll-x">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <th style="width:260px">ناوی بەش</th>
                                    <td>{{ $department?->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>سیستەم</th>
                                    <td>{{ $department?->system?->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>پارێزگا</th>
                                    <td>{{ $department?->province?->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>زانکۆ</th>
                                    <td>{{ $department?->university?->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>کۆلێژ</th>
                                    <td>{{ $department?->college?->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>نمرەی ناوخۆی پارێزگا</th>
                                    <td>{{ $department?->local_score ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>نمرەی دەرەوەی پارێزگا</th>
                                    <td>{{ $department?->external_score ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>جۆر</th>
                                    <td>{{ $department?->type ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>ڕەگەز</th>
                                    <td>{{ $department?->sex ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>دۆخ</th>
                                    <td>
                                        @if ($department?->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچاڵاک</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>وەسف</th>
                                    <td class="text-muted">{!! $department?->description ?: '—' !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-list-ol me-2"></i> زانیاری هەڵبژاردن
                    </h4>

                    <div class="table-responsive table-scroll-x">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <th style="width:260px">ڕیز (Rank)</th>
                                    <td>{{ $result->rank ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>هەڵبژاردنی کۆتایی</th>
                                    <td>
                                        @if ($result->result_rank)
                                            <span class="badge bg-success">هەڵبژێردراوە</span>
                                        @else
                                            <span class="badge bg-secondary">نەهەڵبژێردراوە</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>کات</th>
                                    <td>{{ optional($result->created_at)->format('Y-m-d H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
