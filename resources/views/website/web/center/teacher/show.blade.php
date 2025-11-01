@extends('website.web.admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-table-list me-2"></i> زانیاری تەواوی مامۆستا
                    </h4>

                    <table class="table table-bordered align-middle">
                        <tbody>
                            <tr>
                                <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                <td>1</td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-cube me-1 text-muted"></i> ناوی قوتابی</th>
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
                                <th><i class="fa-solid fa-school me-1 text-muted"></i> پیشە </th>
                                <td>{{ $userTeacher->role ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-school me-1 text-muted"></i> کۆدی بانگێشتی مامۆستا </th>
                                <td>{{ $userTeacher->rand_code ?? '—' }}</td>
                            </tr>


                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="card glass fade-in mt-3">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-table-list me-2"></i> ناو قوتابیەکانی لەسەر ناوی ئەم مامۆستایە داخیل بوونە
                    </h4>
                    <div class="table-wrap">
                        <div class="table-responsive">
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
                                        <th>بانگێشت</th>
                                        <th>بینین</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($students as $index => $student)
                                        @php
                                            $systemName = $student->department->system->name ?? '';
                                            $badge = match ($systemName) {
                                                'زانکۆلاین' => 'bg-primary',
                                                'پاراڵیل' => 'bg-success',
                                                default => 'bg-danger',
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>
                                                {{ $student->user->name }}
                                            </td>
                                            <td>
                                                {{ $student->user->phone }}
                                            </td>
                                            <td>
                                                {{ $student->mark }}
                                            </td>
                                            <td>
                                                {{ $student->province }}
                                            </td>
                                            <td>
                                                {{ $student->type }}
                                            </td>
                                            <td>
                                                {{ $student->gender }}
                                            </td>
                                            <td>
                                                {{ $student->referral_code }}
                                            </td>
                                            <td>
                                                <button type="button" class="btn" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop-{{ $student->id }}">
                                                    <i class="fa fa-eye me-1"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal -->
                                        @if ($student->id)
                                            <div class="modal fade" id="staticBackdrop-{{ $student->id }}"
                                                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div
                                                    class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                                {{ $student->user->name }}</h1>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card glass fade-in">
                                                                <div class="card-body">
                                                                    <h4 class="card-title mb-3">
                                                                        <i class="fa-solid fa-users me-2"></i> هەلبژاردن
                                                                        بەشەکان
                                                                    </h4>

                                                                    <div class="table-wrap">
                                                                        <div class="table-responsive">
                                                                            <table
                                                                                class="table table-bordered align-middle">
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

                                                                                    @php

                                                                                        $result_deps = App\Models\ResultDep::with(
                                                                                            'student',
                                                                                        )
                                                                                            ->where(
                                                                                                'student_id',
                                                                                                $student->id,
                                                                                            )
                                                                                            ->get();

                                                                                        $NameDep = App\Models\Department::whereIn(
                                                                                            'id',
                                                                                            $result_deps->pluck(
                                                                                                'department_id',
                                                                                            ),
                                                                                        )->get();

                                                                                    @endphp

                                                                                    @forelse ($result_deps as $index => $result_dep)
                                                                                        @php
                                                                                            $systemName =
                                                                                                $result_dep->department
                                                                                                    ->system->name ??
                                                                                                '';
                                                                                            $badge = match (
                                                                                                $systemName
                                                                                            ) {
                                                                                                'زانکۆلاین'
                                                                                                    => 'bg-primary',
                                                                                                'پاراڵیل'
                                                                                                    => 'bg-success',
                                                                                                default => 'bg-danger',
                                                                                            };
                                                                                            $lat =
                                                                                                $result_dep->department
                                                                                                    ->lat ?? null;
                                                                                            $lng =
                                                                                                $result_dep->department
                                                                                                    ->lng ?? null;
                                                                                        @endphp
                                                                                        <tr data-lat="{{ $lat ?? '' }}"
                                                                                            data-lng="{{ $lng ?? '' }}">
                                                                                            <td>{{ ++$index }}</td>
                                                                                            <td class="fw-semibold">
                                                                                                <div class="fw-semibold">
                                                                                                    {{ $result_dep->department->name }}
                                                                                                </div>
                                                                                                <div
                                                                                                    class="text-muted small">
                                                                                                    <span
                                                                                                        class="badge {{ $badge }}"><i
                                                                                                            class="fa-solid fa-cube me-1"></i>{{ $result_dep->department->system->name }}</span>
                                                                                                    /
                                                                                                    {{ $result_dep->department->province->name ?? '' }}
                                                                                                    /
                                                                                                    {{ $result_dep->department->university->name ?? '' }}
                                                                                                    /
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
                                                                                                <span
                                                                                                    class="badge bg-success"
                                                                                                    data-bs-toggle="tooltip"
                                                                                                    data-bs-placement="top"
                                                                                                    title="{!! $result_dep->department->description !!}">
                                                                                                    {{ \Illuminate\Support\Str::limit($result_dep->department->description ?? '', 10) }}
                                                                                                </span>
                                                                                            </td>
                                                                                            <td>
                                                                                                @if ($lat && $lng)
                                                                                                    <a class="btn btn-sm btn-outline-primary"
                                                                                                        target="_blank"
                                                                                                        rel="noopener"
                                                                                                        href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}">
                                                                                                        <i
                                                                                                            class="fa-solid fa-map-pin me-1"></i>
                                                                                                        نیشان
                                                                                                    </a>
                                                                                                    <a class="btn btn-sm btn-outline-secondary ms-2"
                                                                                                        target="_blank"
                                                                                                        rel="noopener"
                                                                                                        href="https://www.openstreetmap.org/?mlat={{ $lat }}&mlon={{ $lng }}#map=16/{{ $lat }}/{{ $lng }}">
                                                                                                    </a>
                                                                                                @else
                                                                                                    <span
                                                                                                        class="text-muted small">coords
                                                                                                        نەهێنراون</span>
                                                                                                @endif
                                                                                            </td>
                                                                                        </tr>
                                                                                    @empty
                                                                                        <tr>
                                                                                            <td colspan="6"
                                                                                                class="text-center text-muted">
                                                                                                <i
                                                                                                    class="fa-solid fa-circle-info me-1"></i>
                                                                                                هیچ کۆلێژ/پەیمانگایەک بۆ ئەم
                                                                                                زانکۆیە نەدۆزرایەوە
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
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">داخستن</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

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
@endsection
