@extends('website.web.admin.layouts.app')

@section('title', 'ئەنجامی ڕیزبەندی AI')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Title & Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 text-muted">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">ئەنجامەکانی AI</li>
                        </ol>
                    </div>
                    <h4 class="page-title fw-bold text-dark font-primary">
                        <i class="fas fa-robot me-2 text-primary"></i>
                        ئەنجامەکانی زیرەکی دەستکرد
                    </h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- AI Results Header Card -->
                <div class="card glass border-0 shadow-lg mb-4 overflow-hidden fade-in-up">
                    <div class="card-header bg-gradient-ai p-4 position-relative">
                        <div class="z-index-1 position-relative d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="avatar-lg bg-white-transparent rounded-circle d-flex align-items-center justify-content-center me-4 shadow-sm pulse-ai">
                                    <i class="fas fa-chart-line fa-2x   "></i>
                                </div>
                                <div>
                                    <h3 class="mb-1  fw-bold">ڕاپۆرتی شیکاری زیرەکی دەستکرد</h3>
                                    <p class="mb-0  ">قوتابی: {{ $student->user->name }} | کۆد:
                                        {{ $student->user->code }}</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('student.ai-ranking.retake') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning-soft rounded-pill px-4">
                                        <i class="fas fa-redo me-1"></i>دووبارەکردنەوە
                                    </button>
                                </form>
                                <a href="{{ route('student.ai-ranking.compare') }}"
                                    class="btn btn-light-transparent rounded-pill px-4">
                                    <i class="fas fa-balance-scale me-1"></i>پێوانەکردن
                                </a>
                            </div>
                        </div>
                        <div class="ai-shapes">
                            <div class="ai-shape-1"></div>
                            <div class="ai-shape-2"></div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Stats Grid -->
                        <div class="row g-4 mb-5">
                            <div class="col-md-3">
                                <div class="stat-card glass shadow-sm p-4 text-center h-100">
                                    <div class="icon-circle bg-soft-primary text-primary mb-3 mx-auto">
                                        <i class="fas fa-list-ol fs-4"></i>
                                    </div>
                                    <h3 class="fw-bold mb-1">{{ $stats['total'] }}</h3>
                                    <p class="text-muted small mb-0">کۆی بەشەکان</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card glass shadow-sm p-4 text-center h-100">
                                    <div class="icon-circle bg-soft-success text-success mb-3 mx-auto">
                                        <i class="fas fa-percent fs-4"></i>
                                    </div>
                                    <h3 class="fw-bold mb-1">{{ $stats['average_score'] }}%</h3>
                                    <p class="text-muted small mb-0">نیشاندەری گوزارشت</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card glass shadow-sm p-4 text-center h-100">
                                    <div class="icon-circle bg-soft-info text-info mb-3 mx-auto">
                                        <i class="fas fa-star fs-4"></i>
                                    </div>
                                    <h3 class="fw-bold mb-1">{{ $stats['top_category'] }}</h3>
                                    <p class="text-muted small mb-0">خاڵی بەهێزت</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card glass shadow-sm p-4 text-center h-100">
                                    <div
                                        class="icon-circle bg-soft-{{ $stats['match_level'] == 'زۆر بەرز' ? 'success' : ($stats['match_level'] == 'بەرز' ? 'info' : 'warning') }} mb-3 mx-auto">
                                        <i
                                            class="fas fa-check-circle fs-4 text-{{ $stats['match_level'] == 'زۆر بەرز' ? 'success' : ($stats['match_level'] == 'بەرز' ? 'info' : 'warning') }}"></i>
                                    </div>
                                    <h3 class="fw-bold mb-1">{{ $stats['match_level'] }}</h3>
                                    <p class="text-muted small mb-0">ئاستی گونجاوی</p>
                                </div>
                            </div>
                        </div>

                        <!-- Match Breakdown Section -->
                        <div class="card glass border-soft mb-5">
                            <div class="card-header bg-transparent border-0 pt-4 px-4">
                                <h5 class="fw-bold"><i class="fas fa-project-diagram me-2 text-primary"></i>کایەکانی گونجاوی
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <div class="row g-4">
                                            @foreach ($stats['categories'] as $category => $score)
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span
                                                                class="small fw-bold text-muted">{{ $category }}</span>
                                                            <span
                                                                class="small fw-bold text-primary">{{ $score }}%</span>
                                                        </div>
                                                        <div class="progress rounded-pill" style="height: 10px;">
                                                            <div class="progress-bar bg-gradient-{{ $score >= 80 ? 'success' : ($score >= 60 ? 'primary' : 'warning') }}"
                                                                style="width: {{ $score }}%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-lg-6 text-center">
                                        <div class="p-4 bg-soft-ai rounded-4 border-dashed border-primary">
                                            <i class="fas fa-lightbulb fa-3x text-warning mb-3"></i>
                                            <h6 class="fw-bold">پێشنیاری زیرەک</h6>
                                            <p class="text-muted small mb-0 px-4">بەپێی زانیارییەکان، تۆ لە کایەی <span
                                                    class="text-primary fw-bold">{{ $stats['top_category'] }}</span>
                                                زۆرترین گونجاوییت هەیە. ئەمەش یارمەتیدەرە بۆ هەڵبژاردنی ئەو بەشانەی پشت بەم
                                                خاڵە دەبەستن.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rankings Table -->
                        <div class="card glass border-0 shadow-sm overflow-hidden animate-fade-in">
                            <div
                                class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                                <h5 class="mb-0 fw-bold"><i class="fas fa-trophy me-2 text-warning"></i>ڕیزبەندی پێشنیارکراو
                                </h5>
                                <div class="table-actions">
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="exportExcel">
                                        <i class="fas fa-file-excel me-1 text-success"></i> Excel
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="rankingTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4" width="8%">ڕیز</th>
                                                <th width="35%">زانیاری بەش</th>
                                                <th width="15%">نمرەی گونجاوی</th>
                                                <th width="25%">شیکاری گونجاوی</th>
                                                <th width="10%" class="pe-4">بینین</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rankings as $ranking)
                                                <tr>
                                                    <td class="ps-4">
                                                        <div
                                                            class="rank-badge {{ $ranking->rank == 1 ? 'rank-gold' : ($ranking->rank == 2 ? 'rank-silver' : ($ranking->rank == 3 ? 'rank-bronze' : 'rank-normal')) }}">
                                                            @if ($ranking->rank <= 3)
                                                                <i class="fas fa-crown me-1 small"></i>
                                                            @endif
                                                            {{ $ranking->rank }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="dept-icon me-3 bg-soft-primary rounded-3 text-primary d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-university"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold text-dark">
                                                                    {{ $ranking->department->name }}</div>
                                                                <div class="small text-muted mb-1">
                                                                    {{ $ranking->department->university->name ?? '' }} |
                                                                    {{ $ranking->department->college->name ?? '' }}
                                                                </div>
                                                                <div class="d-flex gap-1">
                                                                    <span
                                                                        class="badge badge-soft-info">{{ $ranking->department->province->name ?? '' }}</span>
                                                                    <span
                                                                        class="badge badge-soft-secondary">{{ $ranking->department->type }}</span>
                                                                    @if ($ranking->department->province_id == $studentProvinceId)
                                                                        <span class="badge badge-soft-success">ناو
                                                                            پارێزگا</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="compatibility-score">
                                                            <div class="d-flex align-items-center mb-1">
                                                                <span
                                                                    class="fw-bold text-{{ $ranking->score >= 80 ? 'success' : ($ranking->score >= 60 ? 'primary' : 'warning') }} me-2">
                                                                    {{ number_format($ranking->score, 1) }}%
                                                                </span>
                                                                <div class="progress flex-grow-1 rounded-pill"
                                                                    style="height: 6px;">
                                                                    <div class="progress-bar bg-{{ $ranking->score >= 80 ? 'success' : ($ranking->score >= 60 ? 'primary' : 'warning') }}"
                                                                        style="width: {{ $ranking->score }}%"></div>
                                                                </div>
                                                            </div>
                                                            <small class="text-muted x-small">ڕێژەی گونجاوی</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="reason-box glass p-2 rounded-3 border-soft small">
                                                            <i class="fas fa-robot text-primary me-1"></i>
                                                            {{ $ranking->reason }}
                                                            @php
                                                                $factors = is_array($ranking->match_factors)
                                                                    ? $ranking->match_factors
                                                                    : json_decode($ranking->match_factors, true) ?? [];
                                                            @endphp
                                                            @if (isset($factors['mark_difference']) && $factors['mark_difference'] > 0)
                                                                <div class="mt-1"><span
                                                                        class="badge bg-soft-success text-success">+{{ $factors['mark_difference'] }}
                                                                        نمرەی زیادە</span></div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="pe-4 text-center">
                                                        <button
                                                            class="btn btn-icon btn-soft-info rounded-circle view-details-btn"
                                                            data-id="{{ $ranking->department_id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Action Footer -->
                        <div class="text-center mt-5 mb-4 print-hide">
                            <p class="text-muted mb-4">ئایا دەتەوێت دووبارە ڕیزبەندییەکان ببینی؟</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('student.departments.selection') }}"
                                    class="btn btn-secondary rounded-pill px-5 py-2 shadow-sm">
                                    <i class="fas fa-list-check me-2"></i>لیستی هەڵبژێردراوەکان
                                </a>
                                <form method="POST" action="{{ route('student.ai-ranking.retake') }}" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-primary bg-gradient-ai border-0 rounded-pill px-5 py-2 shadow-lg">
                                        <i class="fas fa-sync-alt me-2"></i>دووبارەکردنەوەی پشکنین
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content glass border-0 shadow-2xl overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="modalBody">
                    <!-- Dynamic Content -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        :root {
            --ai-primary: #0984e3;
            --ai-secondary: #6c5ce7;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --bg-body: #f8faff;
        }

        .font-primary {
            font-family: 'NizarNastaliqKurdish', 'Wafeq', sans-serif;
        }

        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .bg-gradient-ai {
            background: linear-gradient(135deg, var(--ai-primary), var(--ai-secondary));
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #00b894, #00d2d3);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #0984e3, #4834d4);
        }

        .bg-white-transparent {
            background: rgba(255, 255, 255, 0.2);
        }

        .ai-shapes .ai-shape-1,
        .ai-shapes .ai-shape-2 {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .ai-shape-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -50px;
        }

        .ai-shape-2 {
            width: 150px;
            height: 150px;
            bottom: -50px;
            right: 5%;
        }

        .pulse-ai {
            animation: pulse 3s infinite ease-in-out;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        .icon-circle {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-soft-primary {
            background-color: rgba(9, 132, 227, 0.1);
        }

        .bg-soft-success {
            background-color: rgba(0, 184, 148, 0.1);
        }

        .bg-soft-info {
            background-color: rgba(0, 206, 201, 0.1);
        }

        .bg-soft-warning {
            background-color: rgba(253, 203, 110, 0.1);
        }

        .bg-soft-ai {
            background-color: rgba(108, 92, 231, 0.05);
        }

        .btn-warning-soft {
            background: rgba(253, 203, 110, 0.2);
            color: #d35400;
            border: none;
        }

        .btn-light-transparent {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-soft-info {
            background: rgba(0, 206, 201, 0.1);
            color: #00cec9;
            border: none;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        .border-soft {
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        .rank-badge {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .rank-gold {
            background: linear-gradient(135deg, #FFD700, #F39C12);
            color: white;
            box-shadow: 0 4px 10px rgba(243, 156, 18, 0.3);
        }

        .rank-silver {
            background: linear-gradient(135deg, #BDC3C7, #7F8C8D);
            color: white;
            box-shadow: 0 4px 10px rgba(127, 140, 141, 0.3);
        }

        .rank-bronze {
            background: linear-gradient(135deg, #E67E22, #D35400);
            color: white;
            box-shadow: 0 4px 10px rgba(211, 84, 0, 0.3);
        }

        .rank-normal {
            background: #f1f3f5;
            color: #6c757d;
        }

        .dept-icon {
            width: 45px;
            height: 45px;
            flex-shrink: 0;
            font-size: 1.2rem;
        }

        .badge-soft-info {
            background-color: rgba(9, 132, 227, 0.1);
            color: #0984e3;
        }

        .badge-soft-secondary {
            background-color: #f1f3f5;
            color: #6c757d;
        }

        .badge-soft-success {
            background-color: rgba(0, 184, 148, 0.1);
            color: #00b894;
        }

        .x-small {
            font-size: 0.75rem;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(9, 132, 227, 0.02) !important;
            transform: scale(1.002);
        }

        .modal-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        @media print {
            body {
                background: #fff !important;
            }

            .page-title-right,
            .breadcrumb,
            .table-actions,
            .btn,
            .modal,
            .print-hide,
            .dataTables_length,
            .dataTables_filter,
            .dataTables_info,
            .dataTables_paginate {
                display: none !important;
            }

            .card,
            .glass {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            .table {
                font-size: 12px;
            }

            .rank-badge {
                box-shadow: none !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // بینینی وردەکاری
            $(document).on('click', '.view-details-btn, #rankingTable tbody tr', function(e) {
                const departmentId = $(this).is('tr') ? $(this).find('.view-details-btn').data('id') : $(
                    this).data('id');
                if (!departmentId) return;

                $('#modalBody').html(
                    '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>'
                );
                const modalEl = document.getElementById('detailsModal');
                if (modalEl) {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                }

                $.ajax({
                    url: '/s/ai-ranking/department/' + departmentId + '/details',
                    method: 'GET',
                    success: function(response) {
                        $('#modalTitle').text(response.department.name);

                        let factorsHtml = '';
                        if (response.match_factors) {
                            factorsHtml = `
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-microchip me-2 text-primary"></i>شیکردنەوەی AI</h6>
                                    <div class="row g-2">
                                        <div class="col-6"><div class="p-2 bg-light rounded small">نمرە: <b>${response.match_factors.academic_match}%</b></div></div>
                                        <div class="col-6"><div class="p-2 bg-light rounded small">کەسایەتی: <b>${response.match_factors.personality_match}%</b></div></div>
                                        <div class="col-6"><div class="p-2 bg-light rounded small">حەز: <b>${response.match_factors.interest_match}%</b></div></div>
                                        <div class="col-6"><div class="p-2 bg-light rounded small">شوێن: <b>${response.match_factors.location_match}%</b></div></div>
                                    </div>
                                </div>
                            `;
                        }

                        $('#modalBody').html(`
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-soft-primary text-primary px-3 rounded-pill me-2">${response.department.university.name}</span>
                                            <span class="badge bg-soft-secondary text-secondary px-3 rounded-pill">${response.department.type}</span>
                                        </div>
                                        <h4 class="fw-bold mb-3">${response.department.name}</h4>
                                        <p class="text-muted small"><i class="fas fa-info-circle me-1"></i> ئەم بەشە یەکێکە لە پێشنیارە هەرە باشەکان بۆت بەپێی شیکردنەوەی داتاکان.</p>
                                    </div>
                                    
                                    <div class="list-group list-group-flush border-top border-bottom mb-4">
                                        <div class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                            <span>کۆلێژ</span><span class="fw-bold">${response.department.college.name}</span>
                                        </div>
                                        <div class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                            <span>پارێزگا</span><span class="fw-bold">${response.department.province.name}</span>
                                        </div>
                                        <div class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                            <span>سیستەم</span><span class="fw-bold">${response.department.system.name}</span>
                                        </div>
                                    </div>
                                    ${factorsHtml}
                                </div>
                                <div class="col-md-5 text-center d-none d-md-block">
                                    <div class="p-4 bg-soft-primary rounded-4 mb-4">
                                        <div class="compatibility-orb mx-auto mb-3">
                                            <div class="orb-content shadow-lg bg-white rounded-circle d-flex flex-column align-items-center justify-content-center">
                                                <h3 class="fw-bold mb-0 text-primary">${response.ranking ? response.ranking.score : '0'}%</h3>
                                                <small class="text-muted" style="font-size: 10px">گونجاوی</small>
                                            </div>
                                        </div>
                                        <div class="text-muted small">ئەوە تەنها پێشنیاری AI ـە</div>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                });
            });
        });
    </script>

    <style>
        .compatibility-orb {
            width: 120px;
            height: 120px;
            padding: 8px;
            background: linear-gradient(135deg, var(--ai-primary), var(--ai-secondary));
            border-radius: 50%;
            position: relative;
        }

        .orb-content {
            width: 100%;
            height: 100%;
        }
    </style>
@endpush
