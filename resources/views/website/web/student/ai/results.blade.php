@extends('website.web.student.layouts.app')

@section('title', 'ئەنجامی ڕیزبەندی AI')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- کارتی سەرەکی -->
            <div class="card border-primary shadow-lg mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1"><i class="fas fa-robot me-2"></i>ڕیزبەندی AI بۆ باشترین بەشەکان</h4>
                            <p class="mb-0">تۆ: {{ $student->user->name }} | کۆد: {{ $student->user->code }}</p>
                        </div>
                        <form method="POST" action="{{ route('student.ai-ranking.retake') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fas fa-redo me-1"></i>دووبارەکردنەوە
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- ئامارەکان -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded bg-light">
                                <div class="fs-4 fw-bold text-primary">{{ $stats['total'] }}</div>
                                <small class="text-muted">ژمارەی بەشەکان</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded bg-light">
                                <div class="fs-4 fw-bold text-success">{{ $stats['average_score'] }}%</div>
                                <small class="text-muted">نمرەی گونجاویی</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded bg-light">
                                <div class="fs-4 fw-bold text-info">{{ $stats['top_category'] }}</div>
                                <small class="text-muted">باشترین کاتێگۆری</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded bg-light">
                                <div class="fs-4 fw-bold text-{{ $stats['match_level'] == 'زۆر بەرز' ? 'success' : ($stats['match_level'] == 'بەرز' ? 'info' : 'warning') }}">
                                    {{ $stats['match_level'] }}
                                </div>
                                <small class="text-muted">ئاستی گونجاویی</small>
                            </div>
                        </div>
                    </div>

                    <!-- چارتی کاتێگۆریەکان -->
                    <div class="card border-info mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>کاتێگۆریەکانی گونجاویی</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($stats['categories'] as $category => $score)
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="fs-5 fw-bold">{{ $score }}%</div>
                                        </div>
                                        <div>
                                            <div class="small text-muted">{{ $category }}</div>
                                            <div class="progress" style="height: 8px; width: 100px;">
                                                <div class="progress-bar bg-{{ $score >= 80 ? 'success' : ($score >= 60 ? 'info' : 'warning') }}" 
                                                     style="width: {{ $score }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- لیستی ڕیزبەندیەکان -->
                    <div class="card border-success">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>باشترین ٥٠ بەش بۆ تۆ</h5>
                            <span class="badge bg-light text-dark">١-٥٠</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="rankingTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">ڕیز</th>
                                            <th width="35%">بەش</th>
                                            <th width="15%">نمرەی گونجاویی</th>
                                            <th width="25%">هۆکار</th>
                                            <th width="10%">کردار</th>
                                            <th width="10%">وردەکاری</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rankings as $ranking)
                                        <tr class="{{ $ranking->score >= 80 ? 'table-success' : ($ranking->score >= 60 ? 'table-info' : '') }}">
                                            <td>
                                                <div class="rank-circle {{ $ranking->rank <= 10 ? 'rank-top' : ($ranking->rank <= 30 ? 'rank-medium' : 'rank-low') }}">
                                                    {{ $ranking->rank }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $ranking->department->name }}</div>
                                                <div class="small text-muted">
                                                    {{ $ranking->department->university->name ?? '' }} | 
                                                    {{ $ranking->department->college->name ?? '' }}
                                                </div>
                                                <div class="small">
                                                    <span class="badge bg-primary">{{ $ranking->department->province->name ?? '' }}</span>
                                                    <span class="badge bg-info">{{ $ranking->department->type }}</span>
                                                    @if($ranking->department->province_id == $student->province_id)
                                                    <span class="badge bg-success">ناو پارێزگا</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="score-display">
                                                    <div class="score-value">{{ number_format($ranking->score, 1) }}%</div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-{{ $ranking->score >= 80 ? 'success' : ($ranking->score >= 60 ? 'info' : 'warning') }}" 
                                                             style="width: {{ $ranking->score }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">{{ $ranking->reason }}</div>
                                                @php
                                                    $factors = json_decode($ranking->match_factors, true) ?? [];
                                                    if(isset($factors['mark_difference']) && $factors['mark_difference'] > 0) {
                                                        echo '<small class="text-success">+' . $factors['mark_difference'] . ' نمرەی زیادە</small><br>';
                                                    }
                                                    if(isset($factors['distance_km']) && $factors['distance_km'] < 100) {
                                                        echo '<small class="text-info">' . number_format($factors['distance_km'], 1) . 'km دوور</small>';
                                                    }
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                    $isSelected = $student->resultDeps()->where('department_id', $ranking->department_id)->exists();
                                                @endphp
                                                
                                                @if($isSelected)
                                                <button class="btn btn-success btn-sm w-100" disabled>
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                @else
                                                <button class="btn btn-primary btn-sm w-100 add-department-btn"
                                                        data-id="{{ $ranking->department_id }}"
                                                        data-rank="{{ $ranking->rank }}">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-sm w-100 view-details-btn"
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

                    <!-- دووبارەکردنەوە -->
                    <div class="card border-warning mt-4">
                        <div class="card-body text-center py-4">
                            <h5 class="text-warning mb-3">
                                <i class="fas fa-sync-alt me-2"></i>ئەگەر ویستت دووبارە ڕیزبەندیەکە بکەیتەوە:
                            </h5>
                            <form method="POST" action="{{ route('student.ai-ranking.retake') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-redo me-2"></i>دووبارەکردنەوەی پرسیارەکان
                                </button>
                            </form>
                            <a href="{{ route('student.departments.selection') }}" class="btn btn-secondary btn-lg ms-2">
                                <i class="fas fa-list me-2"></i>بینینی لیستی گشتی
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal بۆ وردەکاری -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- دینامیکی بار دەکرێت -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.rank-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    margin: 0 auto;
}

.rank-top {
    background: linear-gradient(135deg, #ffd700, #ff9800);
    box-shadow: 0 4px 10px rgba(255, 152, 0, 0.3);
}

.rank-medium {
    background: linear-gradient(135deg, #4CAF50, #2E7D32);
    box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3);
}

.rank-low {
    background: linear-gradient(135deg, #2196F3, #0D47A1);
    box-shadow: 0 4px 10px rgba(33, 150, 243, 0.3);
}

.score-display {
    min-width: 80px;
}

.score-value {
    font-weight: bold;
    margin-bottom: 5px;
}

.table-hover tbody tr:hover {
    transform: scale(1.01);
    transition: transform 0.2s;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // Animation بۆ ڕیزبەندیەکان
    function animateRankings() {
        $('#rankingTable tbody tr').each(function(index) {
            $(this).css('opacity', 0);
            $(this).css('transform', 'translateY(20px)');
            
            setTimeout(() => {
                $(this).animate({
                    opacity: 1,
                    transform: 'translateY(0)'
                }, 300);
            }, index * 100);
        });
    }
    
    // دەستپێکردنی Animation
    setTimeout(animateRankings, 500);
    
    // زیادکردنی بەش
    $(document).on('click', '.add-department-btn', function() {
        const btn = $(this);
        const departmentId = btn.data('id');
        const rank = btn.data('rank');
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '{{ route("student.departments.add") }}',
            method: 'POST',
            data: {
                _token: csrfToken,
                department_id: departmentId
            },
            success: function(response) {
                if (response.success) {
                    btn.removeClass('btn-primary').addClass('btn-success')
                        .html('<i class="fas fa-check"></i>')
                        .prop('disabled', true);
                    
                    // نوێکردنەوەی ژمارەی هەڵبژێردراوەکان
                    const currentCount = parseInt($('#selectedCount').text());
                    $('#selectedCount').text(currentCount + 1);
                    
                    // پەیامی سەرکەوتوو
                    Swal.fire({
                        icon: 'success',
                        title: 'سەرکەوتوو!',
                        text: 'بەشەکە بە سەرکەوتوویی زیاد کرا بە لیستی هەڵبژێردراوەکان.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    btn.prop('disabled', false).html('<i class="fas fa-plus"></i>');
                    Swal.fire({
                        icon: 'error',
                        title: 'هەڵە',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-plus"></i>');
                Swal.fire({
                    icon: 'error',
                    title: 'هەڵە',
                    text: xhr.responseJSON?.message || 'هەڵەیەک ڕوویدا'
                });
            }
        });
    });
    
    // بینینی وردەکاری
    $(document).on('click', '.view-details-btn', function() {
        const departmentId = $(this).data('id');
        
        // بارکردنی وردەکاری
        $.ajax({
            url: '/api/department/' + departmentId + '/details',
            method: 'GET',
            success: function(response) {
                $('#modalTitle').text(response.department.name);
                $('#modalBody').html(`
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table">
                                <tr><th>زانکۆ:</th><td>${response.department.university.name}</td></tr>
                                <tr><th>کۆلێژ:</th><td>${response.department.college.name}</td></tr>
                                <tr><th>پارێزگا:</th><td>${response.department.province.name}</td></tr>
                                <tr><th>نمرەی پێویست:</th>
                                    <td>
                                        <span class="badge bg-${response.department.province_id == {{ $student->province_id }} ? 'success' : 'warning'}">
                                            ${response.department.province_id == {{ $student->province_id }} ? response.department.local_score : response.department.external_score}
                                        </span>
                                        <small class="text-muted ms-2">${response.department.province_id == {{ $student->province_id }} ? '(ناو پارێزگا)' : '(دەرەوەی پارێزگا)'}</small>
                                    </td>
                                </tr>
                                <tr><th>جۆر:</th><td><span class="badge bg-info">${response.department.type}</span></td></tr>
                                <tr><th>سیستم:</th><td><span class="badge bg-primary">سیستمی ${response.department.system.name}</span></td></tr>
                                ${response.ranking ? `
                                <tr><th>نمرەی AI:</th><td>${response.ranking.score}%</td></tr>
                                <tr><th>هۆکار:</th><td>${response.ranking.reason}</td></tr>
                                ` : ''}
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-university fa-4x text-primary"></i>
                                </div>
                                <button class="btn btn-primary w-100 mb-2 add-from-modal" 
                                        data-id="${response.department.id}">
                                    <i class="fas fa-plus me-2"></i>زیادکردن
                                </button>
                            </div>
                        </div>
                    </div>
                `);
                
                $('#detailsModal').modal('show');
            }
        });
    });
    
    // زیادکردن لە Modal
    $(document).on('click', '.add-from-modal', function() {
        const departmentId = $(this).data('id');
        const btn = $(this);
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>چاوەڕوانی...');
        
        $.ajax({
            url: '{{ route("student.departments.add") }}',
            method: 'POST',
            data: {
                _token: csrfToken,
                department_id: departmentId
            },
            success: function(response) {
                if (response.success) {
                    btn.removeClass('btn-primary').addClass('btn-success')
                        .html('<i class="fas fa-check me-2"></i>زیاد کرا')
                        .prop('disabled', true);
                    
                    // نوێکردنەوەی لیست
                    $(`button[data-id="${departmentId}"]`).each(function() {
                        $(this).removeClass('btn-primary').addClass('btn-success')
                            .html('<i class="fas fa-check"></i>')
                            .prop('disabled', true);
                    });
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'سەرکەوتوو!',
                        text: 'بەشەکە زیاد کرا.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-plus me-2"></i>زیادکردن');
                Swal.fire({
                    icon: 'error',
                    title: 'هەڵە',
                    text: xhr.responseJSON?.message || 'هەڵەیەک ڕوویدا'
                });
            }
        });
    });
    
    // Sorting بەپێی ڕیز
    $('#rankingTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 25,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ku.json'
        },
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>'
    });
});
</script>
@endpush