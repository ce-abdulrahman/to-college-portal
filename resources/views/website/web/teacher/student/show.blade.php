@extends('website.web.admin.layouts.app')

@section('content')
    @php
        $studentYearRaw = (int) data_get($user, 'student.year', 0);
        $studentYearDisplay = $studentYearRaw === 1 ? '1' : ($studentYearRaw > 1 ? 'زیاتر لە ٢' : '—');
        $isYearOne = $studentYearRaw === 1;
        $manualModalId = 'manualResultDepsModal-student-' . $student->id;
        $manualModalLabelId = $manualModalId . '-label';
        $aiModalId = 'aiRankModal-student-' . $student->id;
        $aiModalLabelId = $aiModalId . '-label';
        $studentProvinceId = (int) ($student->province_id ?? 0);
    @endphp
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('teacher.students.index') }}">قوتابیەکان</a></li>
                            <li class="breadcrumb-item active">زانیاری</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-user-circle me-1"></i>
                        زانیاری تەواوی قوتابی
                    </h4>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
            </a>
            <a href="{{ route('teacher.students.edit', $user->student->id) }}" class="btn btn-primary ms-2">
                <i class="fa-solid fa-pen-to-square me-1"></i> دەستکاری
            </a>
        </div>

        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <div class="small text-muted">قوتابی</div>
                            <div class="h5 mb-1">{{ $user->name }}</div>
                            <div class="small text-muted">کۆد: {{ $user->code }}</div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark border">پارێزگا: {{ $user->student->province ?? '—' }}</span>
                            <span class="badge {{ $user->student->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->student->status ? 'چاڵاک' : 'ناچاڵاک' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card glass fade-in">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fa-solid fa-table-list me-2"></i> زانیاری قوتابی
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
                                            <th><i class="fa-solid fa-cube me-1 text-muted"></i> ناوی قوتابی</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> کۆد چوونەژوورەوە</th>
                                            <td>{{ $user->code }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-school me-1 text-muted"></i> ژمارەی مۆبایل</th>
                                            <td>{{ $user->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-tag me-1 text-muted"></i> نمرەی قوتابی</th>
                                            <td>{{ $user->student->mark }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-building-columns me-1 text-muted"></i> پارێزگا</th>
                                            <td class="fw-semibold">{{ $user->student->province }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-percent me-1 text-muted"></i>لق</th>
                                            <td>{{ $user->student->type ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-venus-mars me-1 text-muted"></i> ڕەگەز </th>
                                            <td>{{ $user->student->gender ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-layer-group me-1 text-muted"></i> پڕکردنەوەی فۆرم</th>
                                            <td>{{ $studentYearDisplay }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-circle-info me-1 text-muted"></i> سیستەمی هەڵبژاردن</th>
                                            <td>
                                                @if ($studentYearDisplay !== '—')
                                                    @if ($isYearOne)
                                                        دەتوانی سیستەمی <span class="badge bg-success">زانکۆلاین</span> و
                                                        <span class="badge bg-danger">پاڕالێل</span> و
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
                                            <th><i class="fa-solid fa-percent me-1 text-muted"></i> کۆدی بانگێشت لای مامۆستا</th>
                                            <td>{{ $user->student->referral_code ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ</th>
                                            <td>
                                                @if ($user->student->status)
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
                            <i class="fa-solid fa-table-list me-2"></i> بەش و ڕێزبەندییەکان
                        </h4>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#{{ $manualModalId }}">
                                <i class="fa-solid fa-list-ol me-1"></i> ڕێزبەندی دەستی
                            </button>
                            <button type="button"
                                class="btn btn-sm {{ (int) ($student->ai_rank ?? 0) === 1 ? 'btn-outline-success' : 'btn-outline-secondary' }}"
                                data-bs-toggle="modal" data-bs-target="#{{ $aiModalId }}">
                                <i class="fa-solid fa-robot me-1"></i> ڕێزبەندی AI
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="{{ $manualModalId }}" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="{{ $manualModalLabelId }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="{{ $manualModalLabelId }}">{{ $user->name }} - ڕێزبەندی دەستی</h1>
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
                                    @forelse ($result_deps as $index => $result_dep)
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
                                                هیچ کۆلێژ/پەیمانگایەک بۆ ئەم زانکۆیە نەدۆزرایەوە
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

    <div class="modal fade" id="{{ $aiModalId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="{{ $aiModalLabelId }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="{{ $aiModalLabelId }}">{{ $user->name }} - ڕێزبەندی AI</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="badge {{ (int) ($student->ai_rank ?? 0) === 1 ? 'bg-success' : 'bg-secondary' }}">
                            AI Rank: {{ (int) ($student->ai_rank ?? 0) === 1 ? 'ON' : 'OFF' }}
                        </span>
                    </div>

                    @if ((int) ($student->ai_rank ?? 0) !== 1)
                        <div class="alert alert-secondary mb-0">
                            ئەم قوتابییە سیستەمی AI Rank چالاک نییە.
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
                                        @forelse ($ai_rankings as $index => $aiRanking)
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
@endsection
