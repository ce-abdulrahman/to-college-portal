@extends('website.web.admin.layouts.app')

@section('title', 'بینینی ئەنجامی MBTI - ' . $student->user->name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.mbti.results.index') }}">ئەنجامەکانی MBTI</a></li>
                        <li class="breadcrumb-item active">{{ $student->user->name }}</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-user-chart me-1"></i>بینینی ئەنجامی MBTI
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-start">
                                <div class="avatar-xl me-4">
                                    <span class="avatar-title bg-primary rounded-circle display-4">
                                        {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h2 class="mb-1">{{ $student->user->name }}</h2>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-envelope me-1"></i> {{ $student->user->code }}
                                    </p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-user me-1"></i> ID : {{ $student->user->id }}
                                        </span>
                                        <span class="badge bg-info">
                                            <i class="fas fa-calendar me-1"></i>
                                            بەروار : {{ $student->user->created_at->format('Y/m/d') }}
                                        </span>
                                        @if($student->province)
                                        <span class="badge bg-success">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $student->province }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-4 text-md-end">
                            <div class="dropdown mt-3 mt-md-0">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog me-1"></i>کردارەکان
                                </button>
                                <ul class="dropdown-menu" style="right: 0; left: auto; z-index: 9999 !important;">
                                    <li>
                                        <a class="dropdown-item" href="mailto:{{ $student->user->email }}?subject=ئەنجامی تاقیکردنەوەی MBTI">
                                            <i class="fas fa-envelope me-2"></i>ناردنی ئیمەیڵ
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="printResult()">
                                            <i class="fas fa-print me-2"></i>چاپکردنی ئەنجام
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button type="button" class="dropdown-item text-danger delete-result-btn"
                                                data-id="{{ $student->id }}">
                                            <i class="fas fa-trash me-2"></i>سڕینەوەی ئەنجام
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-trophy me-2"></i> ئەنجامی MBTI</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="mbti-badge display-1 fw-bold text-white mb-3">
                            {{ $student->mbti_type ?? 'N/A' }}
                        </div>
                        <h4 class="mb-2">{{ $student->mbti_full_name ?? 'دیاری نەکراوە' }}</h4>
                        @if($student->mbti_kurdish_description)
                        <p class="text-muted">{{ $student->mbti_kurdish_description }}</p>
                        @endif
                    </div>

                    @if($answers->count() > 0)
                    <div class="alert alert-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">کاتی تاقیکردنەوە:</small>
                                <div class="fw-bold">{{ $answers->first()->created_at->format('Y/m/d - H:i') }}</div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">ژمارەی وەڵامەکان:</small>
                                <div class="fw-bold">{{ $answers->count() }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-radar me-2"></i>چارتی ئاستەکان</h5>
                </div>
                <div class="card-body">
                    <canvas id="dimensionChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-pie me-2"></i>دابەشبوونی نمرەکان</h5>
                </div>
                <div class="card-body">
                    @foreach(['EI' => ['E' => 'Extraversion', 'I' => 'Introversion'],
                            'SN' => ['S' => 'Sensing', 'N' => 'Intuition'],
                            'TF' => ['T' => 'Thinking', 'F' => 'Feeling'],
                            'JP' => ['J' => 'Judging', 'P' => 'Perceiving']] as $dim => $sides)
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">
                            @switch($dim)
                                @case('EI')<i class="fas fa-users me-1"></i>@break
                                @case('SN')<i class="fas fa-eye me-1"></i>@break
                                @case('TF')<i class="fas fa-brain me-1"></i>@break
                                @case('JP')<i class="fas fa-gavel me-1"></i>@break
                            @endswitch
                            @foreach($sides as $key => $label)
                                {{ $label }}@if(!$loop->last) - @endif
                            @endforeach
                        </h6>

                        @php
                            $total = 0;
                            foreach($sides as $key => $label) {
                                $total += $scores[$key] ?? 0;
                            }
                        @endphp

                        <div class="row align-items-center">
                            @foreach($sides as $key => $label)
                            <div class="col-2 text-center">
                                <span class="badge bg-primary fs-6">{{ $key }}</span>
                            </div>
                            @if($loop->first)
                            <div class="col-8">
                                <div class="progress" style="height: 25px;">
                                    @php
                                        $percentage = $total > 0 ? (($scores[$key] ?? 0) / $total) * 100 : 0;
                                        $color = match($dim) {
                                            'EI' => 'bg-success',
                                            'SN' => 'bg-info',
                                            'TF' => 'bg-warning',
                                            'JP' => 'bg-danger',
                                            default => 'bg-primary'
                                        };
                                    @endphp
                                    <div class="progress-bar {{ $color }}" style="width: {{ $percentage }}%">
                                        <span class="fw-bold">{{ $scores[$key] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        <div class="row mt-1">
                            @foreach($sides as $key => $label)
                            <div class="{{ $loop->first ? 'col-6 text-start' : 'col-6 text-end' }}">
                                <small class="text-muted">{{ $label }}: {{ $scores[$key] ?? 0 }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="fas fa-list-check me-2"></i>زانیاریەکانی وەڵامەکان</h5>
                        <span class="badge bg-light text-dark">{{ $answers->count() }} وەڵام</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="answersTable" class="table table-hover table-centered mb-0">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th width="80">بەش</th>
                                    <th width="60">لا</th>
                                    <th>پرسیار</th>
                                    <th width="80">نمرە</th>
                                    <th width="100">کات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($answers as $answer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @php
                                            $dimensionColor = match($answer->question->dimension) {
                                                'EI' => 'success',
                                                'SN' => 'info',
                                                'TF' => 'warning',
                                                'JP' => 'primary',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $dimensionColor }}">
                                            {{ $answer->question->dimension }}
                                        </span>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $answer->question->side }}</span></td>
                                    <td>
                                        <span class="d-block text-truncate" style="max-width: 300px;">
                                            {{ $answer->question->question_ku }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $scoreColor = match(true) {
                                                $answer->score >= 7 => 'success',
                                                $answer->score >= 4 => 'warning',
                                                default => 'danger'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $scoreColor }}">
                                            {{ $answer->score }}
                                        </span>
                                    </td>
                                    <td><small>{{ $answer->created_at->format('H:i') }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($student->mbti_type)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>وەسفی جۆری {{ $student->mbti_type }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(!empty($strengths))
                        <div class="col-md-6 mb-4">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success text-white py-2">
                                    <h6 class="mb-0"><i class="fas fa-star me-2"></i>تواناکانی سەرەکی</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($strengths as $strength)
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i> {{ $strength }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!empty($weaknesses))
                        <div class="col-md-6 mb-4">
                            <div class="card border-danger h-100">
                                <div class="card-header bg-danger text-white py-2">
                                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>لەتەکان</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($weaknesses as $weakness)
                                        <li class="mb-2">
                                            <i class="fas fa-times-circle text-danger me-2"></i> {{ $weakness }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(!empty($careers))
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white py-2">
                                    <h6 class="mb-0"><i class="fas fa-briefcase me-2"></i>کارە گونجاوەکان</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($careers as $career)
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-user-tie me-1"></i> {{ $career }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mt-4">
        <div class="col-12">
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.mbti.results.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-1"></i>گەڕانەوە بۆ لیستی ئەنجامەکان
                    </a>

                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" onclick="printResult()">
                            <i class="fas fa-print me-1"></i>چاپکردن
                        </button>
                        <button type="button" class="btn btn-success" onclick="downloadAsPDF()">
                            <i class="fas fa-download me-1"></i>داگرتن PDF
                        </button>
                        <a href="mailto:{{ $student->user->email }}?subject=ئەنجامی تاقیکردنەوەی MBTI" class="btn btn-info">
                            <i class="fas fa-paper-plane me-1"></i>ناردن بۆ قوتابی
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteResultModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">سڕینەوەی ئەنجام</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>دڵنیایت لە سڕینەوەی ئەنجامەکانی <strong>{{ $student->user->name }}</strong>؟</p>
                <p class="text-danger"><small>ئەم کردارە گەڕانەوەی نییە!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">پاشگەزبوونەوە</button>
                <form action="{{ route('admin.mbti.results.delete', $student) }}" method="POST" id="resultDeleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">سڕینەوە</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
.mbti-badge {
    font-size: 60px;
    font-weight: bold;
    color: #0d6efd;
    border: 3px solid #0d6efd;
    display: inline-block;
    padding: 20px 40px;
    border-radius: 15px;
    margin: 10px 0;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable for answers
    $('#answersTable').DataTable({
        language: {
            "processing": "چالاکیەکە لە جێبەجێکردن دایە...",
            "lengthMenu": "نیشاندانی _MENU_ تۆمار",
            "zeroRecords": "هیچ تۆمارێک نەدۆزرایەوە",
            "info": "نیشاندانی _START_ بۆ _END_ لە _TOTAL_ تۆمار",
            "infoEmpty": "نیشاندانی 0 بۆ 0 لە 0 تۆمار",
            "infoFiltered": "(پاڵاوتە بۆ _MAX_ کۆی تۆمار)",
            "search": "گەڕان:",
            "paginate": {
                "first": "یەکەم",
                "previous": "پێشوو",
                "next": "داهاتوو",
                "last": "کۆتایی"
            }
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        order: [[0, 'asc']],
        responsive: true
    });

    // Delete result button handler
    $('.delete-result-btn').on('click', function() {
        $('#deleteResultModal').modal('show');
    });

    @if($answers->count() > 0)
    // Radar Chart for MBTI dimensions
    const ctx = document.getElementById('dimensionChart').getContext('2d');
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['E', 'I', 'S', 'N', 'T', 'F', 'J', 'P'],
            datasets: [{
                label: 'ئاستەکان',
                data: [
                    {{ $scores['E'] ?? 0 }},
                    {{ $scores['I'] ?? 0 }},
                    {{ $scores['S'] ?? 0 }},
                    {{ $scores['N'] ?? 0 }},
                    {{ $scores['T'] ?? 0 }},
                    {{ $scores['F'] ?? 0 }},
                    {{ $scores['J'] ?? 0 }},
                    {{ $scores['P'] ?? 0 }}
                ],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 3,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    angleLines: { display: true, color: 'rgba(0, 0, 0, 0.1)' },
                    suggestedMin: 0,
                    suggestedMax: 100,
                    ticks: { stepSize: 20 },
                    pointLabels: { font: { size: 14, weight: 'bold' } }
                }
            },
            plugins: {
                legend: { labels: { font: { size: 14 } } }
            }
        }
    });
    @endif
});

function printResult() {
    const printContent = document.querySelector('.container-fluid').innerHTML;
    const originalContent = document.body.innerHTML;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html dir="rtl">
        <head>
            <title>ئەنجامی MBTI - {{ $student->user->name }}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
            <style>
                @media print {
                    body { padding: 20px; font-size: 14px; }
                    .btn, .dropdown, .card-footer, .page-title-box, .breadcrumb { display: none !important; }
                    .card { border: 1px solid #ddd !important; margin-bottom: 20px; page-break-inside: avoid; }
                    .card-header { background-color: #f8f9fa !important; color: #000 !important; border-bottom: 2px solid #000 !important; }
                    .badge { border: 1px solid #000; }
                    .table th, .table td { padding: 8px; border: 1px solid #ddd; }
                }
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
                .mbti-badge { font-size: 60px; font-weight: bold; color: #0d6efd; border: 3px solid #0d6efd;
                    display: inline-block; padding: 20px 40px; border-radius: 15px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="text-center mb-4">
                    <h1 class="mb-0">ئەنجامی تاقیکردنەوەی MBTI</h1>
                    <h3 class="text-primary">{{ $student->user->name }}</h3>
                    <p class="text-muted">تاریخ: {{ now()->format('Y/m/d - H:i') }}</p>
                </div>
                ${printContent}
            </div>
        </body>
        </html>
    `);

    printWindow.document.close();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}

function downloadAsPDF() {
    alert('کاریگەری PDF داگرتن لێرە دەنووسرێت.\nپێشنیار: چاپکردن و هەڵبژاردنی "چاپکردن بۆ PDF" لە شاشەی چاپکردن.');
}
</script>
@endpush
