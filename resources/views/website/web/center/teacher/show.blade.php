@extends('website.web.admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('center.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('center.teachers.index') }}">مامۆستایەکان</a></li>
                            <li class="breadcrumb-item active">بینینی زانیاری</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-user me-1"></i>
                        زانیاری تەواوی مامۆستا
                    </h4>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <a href="{{ route('center.teachers.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
            </a>
            <a href="{{ route('center.teachers.edit', $teacher->id) }}" class="btn btn-primary ms-2">
                <i class="fa-solid fa-pen-to-square me-1"></i> دەستکاری
            </a>
        </div>

        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <div class="small text-muted">مامۆستا</div>
                            <div class="h5 mb-1">{{ $userTeacher->name }}</div>
                            <div class="small text-muted">کۆد: {{ $userTeacher->code }}</div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark border">پارێزگا: {{ $teacher->province ?? '—' }}</span>
                            <span class="badge {{ $userTeacher->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $userTeacher->status ? 'چاڵاک' : 'ناچاڵاک' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card glass fade-in">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-table-list me-2"></i> زانیاری تەواوی مامۆستا
                        </h4>

                        <div class="table-wrap">
                            <div class="table-responsive table-scroll-x">
                                <table class="table table-bordered align-middle">
                                    <tbody>
                                        <tr>
                                            <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-cube me-1 text-muted"></i> ناوی مامۆستا</th>
                                            <td>{{ $userTeacher->name }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> کۆد چوونەژوورەوە</th>
                                            <td>{{ $userTeacher->code }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-school me-1 text-muted"></i> ژمارەی مۆبایل</th>
                                            <td>{{ $userTeacher->phone ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-map-location-dot me-1 text-muted"></i> پارێزگا</th>
                                            <td>{{ $teacher->province ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-school me-1 text-muted"></i> پیشە </th>
                                            <td>{{ $userTeacher->role === 'teacher' ? 'مامۆستا' : '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-school me-1 text-muted"></i> کۆدی بانگێشتی مامۆستا </th>
                                            <td>{{ $userTeacher->rand_code ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ </th>
                                            <td>
                                                @if ($userTeacher->status)
                                                    <span class="badge bg-success">چاڵاک</span>
                                                @else
                                                    <span class="badge bg-danger">ناچاڵاک</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card glass fade-in mt-3">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-table-list me-2"></i> ناو قوتابیەکانی لەسەر ناوی ئەم مامۆستایە داخیل بوونە
                        </h4>
                        <div class="table-wrap">
                            <div class="table-responsive table-scroll-x">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width:60px">#</th>
                                            <th>ناو</th>
                                            <th>ژمارە</th>
                                            <th>نمرە</th>
                                            <th>پارێزگا</th>
                                            <th>لق</th>
                                            <th>ڕەگەز</th>
                                            <th colspan="2" class="text-center">بینین</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($students as $index => $student)
                                            @php
                                                $manualModalId = 'manualResultDepsModal-s' . $student->id . '-u' . $student->user->id;
                                                $aiModalId = 'aiRankModal-s' . $student->id . '-u' . $student->user->id;
                                            @endphp
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $student->user->name }}</td>
                                                <td>{{ $student->user->phone }}</td>
                                                <td>{{ $student->mark }}</td>
                                                <td>{{ $student->province }}</td>
                                                <td>{{ $student->type }}</td>
                                                <td>{{ $student->gender }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal" data-bs-target="#{{ $manualModalId }}">
                                                        <i class="fa-solid fa-list-ol me-1"></i> ڕێزبەندی دەستی
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-sm {{ (int) ($student->ai_rank ?? 0) === 1 ? 'btn-outline-success' : 'btn-outline-secondary' }}"
                                                        data-bs-toggle="modal" data-bs-target="#{{ $aiModalId }}">
                                                        <i class="fa-solid fa-robot me-1"></i> ڕێزبەندی AI
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">
                                                    <i class="fa-solid fa-circle-info me-1"></i>
                                                    هیچ قوتابیەک بۆ ئەم مامۆستایە نەدۆزرایەوە
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
    </div>

    @foreach ($students as $student)
        @if ($student->id && $student->user?->id)
            @php
                $manualModalId = 'manualResultDepsModal-s' . $student->id . '-u' . $student->user->id;
                $manualModalLabelId = $manualModalId . '-label';
                $aiModalId = 'aiRankModal-s' . $student->id . '-u' . $student->user->id;
                $aiModalLabelId = $aiModalId . '-label';
                $studentProvinceId = (int) ($student->province_id ?? 0);
            @endphp

            <div class="modal fade" id="{{ $manualModalId }}" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="{{ $manualModalLabelId }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="{{ $manualModalLabelId }}">
                                {{ $student->user->name }} - ڕێزبەندی دەستی
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-wrap">
                                <div class="table-responsive table-scroll-x">
                                    <table class="table table-hover table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:60px">#</th>
                                                <th>ناو</th>
                                                <th>نمرەی ن. پارێزگا</th>
                                                <th>نمرەی د. پارێزگا</th>
                                                <th style="width:120px">وەسف</th>
                                                <th style="width:120px">نەخشە</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($student->resultDeps as $index => $result_dep)
                                                @php
                                                    $department = $result_dep->department;
                                                @endphp
                                                @continue(!$department)
                                                @php
                                                    $systemName = $department->system->name ?? '';
                                                    $badge = match ($systemName) {
                                                        'زانکۆلاین' => 'bg-primary',
                                                        'پاراڵیل' => 'bg-success',
                                                        default => 'bg-danger',
                                                    };
                                                    $lat = $department->lat ?? null;
                                                    $lng = $department->lng ?? null;
                                                @endphp
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td class="fw-semibold">
                                                        <div class="fw-semibold">{{ $department->name }}</div>
                                                        <div class="text-muted small">
                                                            <span class="badge {{ $badge }}"><i
                                                                    class="fa-solid fa-cube me-1"></i>{{ $department->system->name ?? '' }}</span>
                                                            /
                                                            {{ $department->province->name ?? '' }}
                                                            /
                                                            {{ $department->university->name ?? '' }}
                                                            /
                                                            {{ $department->college->name ?? '' }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $department->local_score ?? '—' }}</td>
                                                    <td>{{ $department->external_score ?? '—' }}</td>
                                                    <td>
                                                        <span class="badge bg-success" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="{{ strip_tags($department->description ?? '') }}">
                                                            {{ \Illuminate\Support\Str::limit($department->description ?? '', 10) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if ($lat && $lng)
                                                            <a class="btn btn-sm btn-outline-primary" target="_blank"
                                                                rel="noopener"
                                                                href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}">
                                                                <i class="fa-solid fa-map-pin me-1"></i> نیشان
                                                            </a>
                                                        @else
                                                            <span class="text-muted small">coords نەهێنراون</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        <i class="fa-solid fa-circle-info me-1"></i>
                                                        هیچ کۆلێژ/پەیمانگایەک بۆ ئەم قوتابییە نەدۆزرایەوە
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">داخستن</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="{{ $aiModalId }}" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="{{ $aiModalLabelId }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="{{ $aiModalLabelId }}">
                                {{ $student->user->name }} - ڕێزبەندی AI
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3"> 
                                سیستەمی ژێری دەستکردنی: 
                                <span class="badge {{ (int) ($student->ai_rank ?? 0) === 1 ? 'bg-success' : 'bg-danger' }}">
                                {{ (int) ($student->ai_rank ?? 0) === 1 ? 'چاڵاکە' : 'ناچاڵاکە' }}
                                </span>
                            </div>

                            @if ((int) ($student->ai_rank ?? 0) !== 1)
                                <div class="alert alert-danger mb-0 text-center">
                                    <i class="fa-solid fa-circle-info me-1"></i>
                                    ئەم قوتابییە سیستەمی ژێری دەستکردنی چالاک نییە.
                                </div>
                            @else
                                <div class="table-wrap">
                                    <div class="table-responsive table-scroll-x">
                                        <table class="table table-hover table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:70px">#</th>
                                                    <th>ناو</th>
                                                    <th>سیستەم</th>
                                                    <th>جۆری نمرە</th>
                                                    <th>نمرەی پێویست</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($student->aiRankings as $index => $aiRanking)
                                                    @php
                                                        $department = $aiRanking->department;
                                                    @endphp
                                                    @continue(!$department)
                                                    @php
                                                        $isLocal = (int) ($department->province_id ?? 0) === $studentProvinceId;
                                                        $requiredScore = $isLocal
                                                            ? (float) ($department->local_score ?? 0)
                                                            : (float) ($department->external_score ?? 0);
                                                        $systemName = $department->system->name ?? '—';
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $aiRanking->rank ?? ++$index }}</td>
                                                        <td>
                                                            <div class="fw-semibold">{{ $department->name ?? '—' }}</div>
                                                            <div class="text-muted small">
                                                                {{ $department->province->name ?? '—' }}
                                                                /
                                                                {{ $department->university->name ?? '—' }}
                                                                /
                                                                {{ $department->college->name ?? '—' }}
                                                            </div>
                                                        </td>
                                                        <td><span class="badge bg-info text-dark">{{ $systemName }}</span></td>
                                                        <td>
                                                            <span class="badge {{ $isLocal ? 'bg-success' : 'bg-warning text-dark' }}">
                                                                {{ $isLocal ? 'local_score' : 'external_score' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ rtrim(rtrim(number_format($requiredScore, 3, '.', ''), '0'), '.') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">
                                                            <i class="fa-solid fa-circle-info me-1"></i>
                                                            هیچ AI ڕێزبەندییەک نەدۆزرایەوە
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">داخستن</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
