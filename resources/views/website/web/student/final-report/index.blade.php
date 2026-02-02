@extends('website.web.admin.layouts.app')

@section('title', 'لیستی کۆتایی و ڕیزبەندی AI')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Title & Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">لیستی کۆتایی</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-file-invoice me-1"></i>
                        ڕاپۆرتی کۆتایی و ڕیزبەندییەکان
                    </h4>
                </div>
            </div>
        </div>

        <!-- Student Info Header -->
        <div class="card glass border-0 shadow-sm mb-4 fade-in">
            <div class="card-header bg-gradient-primary text-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1"><i class="fas fa-user-graduate me-2"></i> پوختەی زانیارییەکان</h4>
                        <p class="mb-0 opacity-75 small"> قوتابی: {{ $student->user->name }} | نمرە: {{ $student->mark }} |
                            لق: {{ $student->type }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button onclick="window.print()" class="btn btn-light btn-sm fw-bold">
                            <i class="fas fa-print me-1"></i> چاپکردن
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Chosen Departments (ResultDep) -->
            <div class="col-lg-6">
                <div class="card glass border-0 shadow-sm h-100 overflow-hidden">
                    <div
                        class="card-header bg-soft-primary border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-list-check me-2"></i> لیستی بەشە
                            هەڵبژێردراوەکان</h6>
                        <span class="badge bg-primary px-3 py-2">{{ $chosenDepartments->count() }} بەش</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted smaller">
                                    <tr>
                                        <th class="ps-3" style="width: 50px;">ڕیز</th>
                                        <th>زانیاری بەش</th>
                                        <th class="text-center">سیستەم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($chosenDepartments as $item)
                                        <tr>
                                            <td class="ps-3 fw-bold text-muted">{{ $item->rank }}</td>
                                            <td>
                                                <div class="fw-bold text-dark small">{{ $item->department->name }}</div>
                                                <div class="text-muted smaller">
                                                    {{ $item->department->university->name }} /
                                                    {{ $item->department->college->name }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge {{ $item->department->system->name == 'زانکۆلاین' ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }} px-2 py-1 smaller">
                                                    {{ $item->department->system->name }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="p-5 text-center">
                                                <i class="fas fa-folder-open fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                                <p class="text-muted">هێشتا هیچ بەشێکت هەڵنەبژاردووە.</p>
                                                <a href="{{ route('student.departments.selection') }}"
                                                    class="btn btn-primary btn-sm mt-2">چوون بۆ هەڵبژاردن</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Ranking Results -->
            <div class="col-lg-6">
                <div class="card glass border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-header bg-soft-info border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-info"><i class="fa-solid fa-robot me-2"></i> پێشنیارەکانی ژیری دەستکرد
                            (AI)</h6>
                        @if ($student->ai_rank)
                            <span class="badge bg-info px-3 py-2">{{ $aiRankings->count() }} پێشنیار</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @if ($student->ai_rank)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted smaller">
                                        <tr>
                                            <th class="ps-3" style="width: 50px;">ڕیز</th>
                                            <th>بەشی پێشنیارکراو</th>
                                            <th class="text-center">نمرەی AI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($aiRankings as $item)
                                            <tr>
                                                <td class="ps-3 fw-bold text-muted">{{ $item->rank }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark small">{{ $item->department->name }}
                                                    </div>
                                                    <div class="text-muted smaller">
                                                        {{ $item->department->university->name }} /
                                                        {{ $item->department->college->name }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="circular-progress-sm"
                                                        style="--percent: {{ $item->score }}">
                                                        <span class="smaller fw-bold">{{ round($item->score) }}%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="p-5 text-center">
                                                    <i class="fas fa-brain fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                                    <p class="text-muted">هێشتا تاقیکردنەوەی AIت ئەنجام نەداوە.</p>
                                                    <a href="{{ route('student.ai-ranking.questionnaire') }}"
                                                        class="btn btn-info btn-sm text-white mt-2">دەستپێکردن</a>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-5 text-center">
                                <div class="mb-4">
                                    <div class="icon-box-lg bg-soft-warning rounded-circle mx-auto mb-3">
                                        <i class="fas fa-lock text-warning fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold">تایبەتمەندی AI چالاک نییە</h5>
                                    <p class="text-muted px-4">بۆ سوودوەرگرتن لە ژیری دەستکرد بۆ دیاریکردنی باشترین بەشەکان،
                                        پێویستە داواکاری بنێریت.</p>
                                </div>
                                <a href="{{ route('student.departments.request-more') }}"
                                    class="btn btn-warning fw-bold px-4 rounded-pill shadow-sm">
                                    <i class="fas fa-paper-plane me-1"></i> ناردنی داواکاری بۆ چالاککردن
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #6C63FF 0%, #3F3D56 100%);
        }

        .bg-soft-primary {
            background: rgba(108, 99, 255, 0.1);
        }

        .bg-soft-info {
            background: rgba(13, 202, 240, 0.1);
        }

        .bg-soft-warning {
            background: rgba(255, 193, 7, 0.1);
        }

        .smaller {
            font-size: 0.75rem;
        }

        .icon-box-lg {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .circular-progress-sm {
            width: 45px;
            height: 45px;
            background: radial-gradient(closest-side, white 79%, transparent 80% 100%),
                conic-gradient(#0dcaf0 calc(var(--percent) * 1%), #e9ecef 0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media print {

            .btn,
            .breadcrumb,
            .page-title-right {
                display: none !important;
            }

            .card {
                border: 1px solid #ddd !important;
                shadow: none !important;
            }

            .col-lg-6 {
                width: 50% !important;
                float: left !important;
            }
        }
    </style>
@endpush
