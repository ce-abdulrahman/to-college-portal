@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">لیستی بەکارهێنەران</a></li>
                        <li class="breadcrumb-item active">زانیاری سەنتەر</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-bar me-1"></i>
                    زانیاری سەنتەر
                </h4>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.centers.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title fw-bold">زانیاری سەنتەر</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip"
                title="دەستکاری">
                <i class="fa-solid fa-pen-to-square me-1"></i>
            </a>
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                onsubmit="return confirm('دڵنیایت دەتەوێت بسڕیتەوە؟');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="سڕینەوە">
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
                        <i class="fa-solid fa-table-list me-2"></i> زانیاری تەواوی سەنتەر
                    </h4>

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
                                <td>{{ $user->phone ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-school me-1 text-muted"></i> پیشە </th>
                                <td>{{ $user->role ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-school me-1 text-muted"></i> کۆدی بانگێشتی مامۆستا </th>
                                <td>{{ $user->rand_code ?? '—' }}</td>
                            </tr>

                            {{-- Feature Flags Display --}}
                            @if ($user->center)
                                <tr>
                                    <th><i class="fa-solid fa-star me-1 text-muted"></i> تایبەتمەندییەکان</th>
                                    <td>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <span
                                                class="badge {{ $user->center->ai_rank ? 'bg-success' : 'bg-secondary' }}">
                                                <i
                                                    class="fa-solid {{ $user->center->ai_rank ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                AI Rank
                                            </span>
                                            <span class="badge {{ $user->center->gis ? 'bg-success' : 'bg-secondary' }}">
                                                <i
                                                    class="fa-solid {{ $user->center->gis ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                GIS
                                            </span>
                                            <span
                                                class="badge {{ $user->center->all_departments ? 'bg-success' : 'bg-secondary' }}">
                                                <i
                                                    class="fa-solid {{ $user->center->all_departments ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                All Departments (50)
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="card glass fade-in mt-3">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-table-list me-2"></i> ناو مامۆستایە لەسەر ناوی ئەم سەنتەرە داخیل بوونە
                    </h4>
                    <div class="table-wrap">
                        <div class="table-responsive table-scroll-x">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>ناو</th>
                                        <th>کۆدی داخیل</th>
                                        <th>ژمارە</th>
                                        <th>پیشە</th>
                                        <th>بانگێشت</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teachers as $index => $teacher)
                                        @php
                                            $systemName = $teacher->department->system->name ?? '';
                                            $badge = match ($systemName) {
                                                'زانکۆلاین' => 'bg-primary',
                                                'پاراڵیل' => 'bg-success',
                                                default => 'bg-danger',
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>
                                                {{ $teacher->user->name }}
                                            </td>
                                            <td>
                                                {{ $teacher->user->code }}
                                            </td>
                                            <td>
                                                {{ $teacher->user->phone }}
                                            </td>
                                            <td>
                                                @if ($teacher->user->role == 'teacher')
                                                    {{ 'مامۆستا' }}
                                                @endif
                                                @if ($teacher->user->role == 'student')
                                                    {{ 'قوتابی' }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $teacher->user->rand_code }}
                                            </td>

                                        </tr>


                                        @if (count($teachers) == 0)
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">
                                                    <i class="fa-solid fa-circle-info me-1"></i>
                                                    هیچ قوتابیەک بۆ ئەم مامۆستایە نەدۆزرایەوە
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
