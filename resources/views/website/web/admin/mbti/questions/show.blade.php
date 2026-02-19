@extends('website.web.admin.layouts.app')

@section('title', 'بینینی پرسیار - MBTI')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.mbti.questions.index') }}">پرسیارەکانی
                                    MBTI</a></li>
                            <li class="breadcrumb-item active">پرسیار #{{ $question->id }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-chart-bar me-1"></i>پرسیارەکانی MBTI
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-question-circle me-2"></i>زانیاریەکانی پرسیار
                            </h5>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.mbti.questions.edit', $question) }}">
                                            <i class="fas fa-edit me-2"></i>دەستکاری
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.mbti.questions.destroy', $question) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="dropdown-item text-danger delete-btn">
                                                <i class="fas fa-trash me-2"></i>سڕینەوە
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge bg-primary me-2">ID</span>
                                    <h6 class="mb-0">{{ $question->id }}</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-{{ $question->is_active ? 'success' : 'danger' }} me-2">
                                        {{ $question->is_active ? 'چالاک' : 'ناچالاک' }}
                                    </span>
                                    <h6 class="mb-0">ستاتەس</h6>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="mb-2">
                                    <small class="text-muted">دروستکراوە:</small>
                                    <div class="fw-bold">{{ $question->created_at->format('Y/m/d - H:i') }}</div>
                                </div>
                                <div>
                                    <small class="text-muted">دوایین نوێکردنەوە:</small>
                                    <div class="fw-bold">{{ $question->updated_at->format('Y/m/d - H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card border-info h-100">
                                    <div class="card-header bg-info text-white py-2">
                                        <h6 class="mb-0"><i class="fas fa-cubes me-1"></i>بەش و لا</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="text-muted">بەش:</label>
                                            <div class="fw-bold">
                                                @switch($question->dimension)
                                                    @case('EI')
                                                        Extraversion (E) - Introversion (I)
                                                    @break

                                                    @case('SN')
                                                        Sensing (S) - Intuition (N)
                                                    @break

                                                    @case('TF')
                                                        Thinking (T) - Feeling (F)
                                                    @break

                                                    @case('JP')
                                                        Judging (J) - Perceiving (P)
                                                    @break
                                                @endswitch
                                                <span class="badge bg-secondary ms-2">{{ $question->dimension }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-muted">لا:</label>
                                            <div class="fw-bold">
                                                @switch($question->side)
                                                    @case('E')
                                                        Extraversion
                                                    @break

                                                    @case('I')
                                                        Introversion
                                                    @break

                                                    @case('S')
                                                        Sensing
                                                    @break

                                                    @case('N')
                                                        Intuition
                                                    @break

                                                    @case('T')
                                                        Thinking
                                                    @break

                                                    @case('F')
                                                        Feeling
                                                    @break

                                                    @case('J')
                                                        Judging
                                                    @break

                                                    @case('P')
                                                        Perceiving
                                                    @break
                                                @endswitch
                                                <span class="badge bg-secondary ms-2">{{ $question->side }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card border-success h-100">
                                    <div class="card-header bg-success text-white py-2">
                                        <h6 class="mb-0"><i class="fas fa-sort-amount-down me-1"></i>ڕێکخستن</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="text-muted">ڕیز:</label>
                                            <div class="fw-bold display-6">{{ $question->order }}</div>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success" style="width: {{ $question->order }}%">
                                            </div>
                                        </div>
                                        <small class="text-muted">لە ١٠٠</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-white py-2">
                                        <h6 class="mb-0"><i class="fas fa-language me-1"></i>پرسیارەکان</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label class="text-muted d-flex justify-content-between">
                                                <span>کوردی:</span>
                                                <span class="badge bg-primary">{{ mb_strlen($question->question_ku) }}
                                                    پیت</span>
                                            </label>
                                            <div class="p-3 bg-light rounded border">
                                                <p class="mb-0 fs-5">{{ $question->question_ku }}</p>
                                            </div>
                                        </div>

                                        @if ($question->question_en)
                                            <div>
                                                <label class="text-muted d-flex justify-content-between">
                                                    <span>ئینگلیزی:</span>
                                                    <span class="badge bg-primary">{{ mb_strlen($question->question_en) }}
                                                        پیت</span>
                                                </label>
                                                <div class="p-3 bg-light rounded border">
                                                    <p class="mb-0">{{ $question->question_en }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>پرسیاری ئینگلیزی بوونی نییە
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.mbti.questions.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-right me-1"></i>گەڕانەوە بۆ لیست
                            </a>
                            <div class="btn-group">
                                <a href="{{ route('admin.mbti.questions.edit', $question) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i>دەستکاری
                                </a>
                                <button type="button" class="btn btn-primary" onclick="printCard()">
                                    <i class="fas fa-print me-1"></i>چاپکردن
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($question->answers()->count() > 0)
            <div class="row mt-4">
                <div class="col-xl-8 col-lg-10 mx-auto">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>ئاماری وەڵامەکان
                                </h5>
                                <span class="badge bg-light text-dark">{{ $question->answers()->count() }} وەڵام</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <h6 class="border-bottom pb-2">ئاماری گشتی</h6>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded">
                                                <div class="display-6 fw-bold text-primary">
                                                    {{ $question->answers()->count() }}</div>
                                                <small class="text-muted">کۆی وەڵامەکان</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded">
                                                <div class="display-6 fw-bold text-success">
                                                    {{ round($question->answers()->avg('score') ?? 0, 1) }}</div>
                                                <small class="text-muted">تێکڕای نمرە</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <h6 class="border-bottom pb-2">دابەشبوونی نمرەکان</h6>
                                    <canvas id="scoreDistributionChart" height="150"></canvas>
                                </div>
                            </div>

                            @if ($question->answers()->with('user')->latest()->take(5)->get()->count() > 0)
                                <div class="mt-4">
                                    <h6 class="border-bottom pb-2">دوایین وەڵامەکان</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover" id="answersTable">
                                            <thead>
                                                <tr>
                                                    <th>بەکارهێنەر</th>
                                                    <th>نمرە</th>
                                                    <th>کات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($question->answers()->with('user')->latest()->take(5)->get() as $answer)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm me-2">
                                                                    <span class="avatar-title bg-primary rounded-circle">
                                                                        {{ strtoupper(substr($answer->user->name, 0, 1)) }}
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-bold">{{ $answer->user->name }}</div>
                                                                    <small
                                                                        class="text-muted">{{ $answer->user->code }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $answer->score >= 7 ? 'success' : ($answer->score >= 4 ? 'warning' : 'danger') }}">
                                                                {{ $answer->score }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <small>{{ $answer->created_at->format('Y/m/d H:i') }}</small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-end">
                                        <a href="#" class="btn btn-sm btn-outline-primary">بینینی هەموو
                                            وەڵامەکان</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row mt-4">
                <div class="col-xl-8 col-lg-10 mx-auto">
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading">هیچ وەڵامێک بوونی نییە!</h5>
                                <p class="mb-0">هێشتا هیچ بەکارهێنەرێک وەڵامی ئەم پرسیارەی نەداوە.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
    @if ($question->answers()->count() > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#answersTable').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ku.json'
                    },
                    searching: false,
                    paging: false,
                    info: false
                });

                const ctx = document.getElementById('scoreDistributionChart').getContext('2d');
                const scores = @json($question->answers()->pluck('score'));
                const distribution = Array(10).fill(0);

                scores.forEach(score => {
                    if (score >= 1 && score <= 10) {
                        distribution[score - 1]++;
                    }
                });

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
                        datasets: [{
                            label: 'ژمارەی وەڵامەکان',
                            data: distribution,
                            backgroundColor: [
                                '#dc3545', '#dc3545', '#dc3545',
                                '#ffc107', '#ffc107', '#ffc107',
                                '#20c997', '#20c997', '#20c997', '#20c997'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                $('.delete-btn').on('click', function() {
                    if (confirm('دڵنیایت لە سڕینەوەی ئەم پرسیارە؟')) {
                        $(this).closest('.delete-form').submit();
                    }
                });
            });

            function printCard() {
                const printContent = document.querySelector('.card').outerHTML;
                const originalContent = document.body.innerHTML;

                document.body.innerHTML = `
        <!DOCTYPE html>
        <html dir="rtl">
        <head>
            <title>پرسیاری MBTI - {{ $question->id }}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @media print {
                    body { padding: 20px; }
                    .btn, .dropdown { display: none !important; }
                    .badge { border: 1px solid #000; }
                }
            </style>
        </head>
        <body>${printContent}</body>
        </html>
    `;

                window.print();
                document.body.innerHTML = originalContent;
                window.location.reload();
            }
        </script>
    @endif
@endpush
