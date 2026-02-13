@extends('website.web.admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('center.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('center.students.index') }}">قوتابیەکان</a></li>
                            <li class="breadcrumb-item active">بینینی زانیاری</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-user me-1"></i>
                        زانیاری تەواوی قوتابی
                    </h4>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <a href="{{ route('center.students.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
            </a>
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
                                            <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #
                                            </th>
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
                                            <th><i class="fa-solid fa-layer-group me-1 text-muted"></i> چەند ساڵ فۆرمی
                                                پڕکردۆتەوە</th>
                                            <td>{{ $user->student->year ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fa-solid fa-percent me-1 text-muted"></i> کۆدی بانگێشتی سەنتەر
                                            </th>
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
                            <i class="fa-solid fa-table-list me-2"></i> بەشە هەڵبژێردراوەکانی قوتابی
                        </h4>
                        <div class="table-wrap">
                            <div class="table-responsive table-scroll-x">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width:60px">#</th>
                                            <th>ناو</th>
                                            <th>نمرەی ن. پارێزگا</th>
                                            <th>نمرەی د. پارێزگا</th>
                                            <th style="width:120px">وەسف</th>
                                            <th style="width:120px">نەخشە</th>
                                            {{-- هەلبژاردن: ئەگەر خانەی تر هەیە وەکو جۆر/ژمارەی بەشەکان، لێرە زیاد بکە --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($result_deps as $index => $result_dep)
                                            @php
                                                $systemName = $result_dep->department->system->name ?? '';
                                                $badge = match ($systemName) {
                                                    'زانکۆلاین' => 'bg-primary',
                                                    'پاراڵیل' => 'bg-success',
                                                    default => 'bg-danger',
                                                };
                                                $lat = $result_dep->department->lat ?? null;
                                                $lng = $result_dep->department->lng ?? null;
                                            @endphp
                                            <tr data-lat="{{ $lat ?? '' }}" data-lng="{{ $lng ?? '' }}">
                                                <td>{{ ++$index }}</td>
                                                <td class="fw-semibold">
                                                    <div class="fw-semibold">{{ $result_dep->department->name }}</div>
                                                    <div class="text-muted small">
                                                        <span class="badge {{ $badge }}"><i
                                                                class="fa-solid fa-cube me-1"></i>{{ $result_dep->department->system->name }}</span>
                                                        /
                                                        {{ $result_dep->department->province->name ?? '' }} /
                                                        {{ $result_dep->department->university->name ?? '' }} /
                                                        {{ $result_dep->department->college->name ?? '' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $result_dep->department->local_score ?? '—' }}
                                                </td>
                                                <td>
                                                    {{ $result_dep->department->external_score ?? '—' }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-success" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="{!! $result_dep->department->description !!}">
                                                        {{ \Illuminate\Support\Str::limit($result_dep->department->description ?? '', 10) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($lat && $lng)
                                                        <a class="btn btn-sm btn-outline-primary" target="_blank"
                                                            rel="noopener"
                                                            href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}">
                                                            <i class="fa-solid fa-map-pin me-1"></i> نیشان
                                                        </a>
                                                        <a class="btn btn-sm btn-outline-secondary ms-2" target="_blank"
                                                            rel="noopener"
                                                            href="https://www.openstreetmap.org/?mlat={{ $lat }}&mlon={{ $lng }}#map=16/{{ $lat }}/{{ $lng }}">
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
                </div>
            </div>
        </div>
    </div>
@endsection
