{{-- resources/views/student/departments/selection.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('title', 'هەڵبژاردنی بەشەکان')

@section('content')
<div class="container py-4">
    <!-- باڵای پەیج: زانیاری قوتابی و ئامارەکان -->
    <div class="card border-primary shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-3">
            {{-- لە بەشی card-header ی یەکەم --}}
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-1"><i class="fas fa-university me-2"></i>هەڵبژاردنی بەشەکان</h4>
        <p class="mb-0">قوتابی: {{ $student->user->name }} | کۆد: {{ $student->user->code }}</p>
    </div>
    <div class="text-end">
        @if($student->all_departments == 0)
<div class="card border-warning mt-4">
    <div class="card-body text-center py-3">
        <h5 class="text-warning mb-3">
            <i class="fas fa-info-circle me-2"></i>تێبینی گرنگ
        </h5>
        <p class="mb-3">ئێستا تۆ دەتوانی تەنها <strong>٢٠ بەش</strong> هەڵبژێریت.</p>
        <p class="mb-3">ئەگەر پێویستت بە هەڵبژاردنی بەشی زیاترە (ھەتا ٥٠ بەش)، دەتوانی داواکاری بکەیت بۆ بەڕێوەبەری سیستم.</p>
        <a href="{{ route('student.departments.request-more') }}" class="btn btn-warning">
            <i class="fas fa-paper-plane me-2"></i>داواکردنی مۆڵەتی بەشی زیاتر
        </a>
        <a href="{{ route('student.departments.request-history') }}" class="btn btn-info ms-2">
            <i class="fas fa-history me-2"></i>مێژووی داواکاریەکان
        </a>
    </div>
</div>
@endif
    </div>
</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded bg-light">
                        <div class="fs-4 fw-bold text-primary">{{ $student->mark }}</div>
                        <small class="text-muted">نمرەی کۆی قوتابی</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded bg-light">
                        <div class="fs-4 fw-bold text-success">{{ $student->type }}</div>
                        <small class="text-muted">لقی قوتابی</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded bg-light">
                        <div class="fs-4 fw-bold text-warning">{{ $maxSelections }}</div>
                        <small class="text-muted">سنووری هەڵبژاردن</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded bg-light">
                        <div class="fs-4 fw-bold {{ $student->all_departments ? 'text-danger' : 'text-info' }}">
                            {{ $student->all_departments ? '٥٠ بەش' : '٢٠ بەش' }}
                        </div>
                        <small class="text-muted">جۆری هەڵبژاردن</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بەشە هەڵبژێردراوەکان (ئێستا) -->
    <div class="card border-success shadow-sm mb-4" id="selectedDepartmentsCard">
        <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>بەشە هەڵبژێردراوەکان</h5>
            <span class="badge bg-light text-dark" id="selectedCount">{{ $currentCount }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                                <table class="table table-hover" id="selectedDepartmentsTable">

    <thead class="table-dark">
        <tr>
            <th>ناوی بەش</th>
            <th>نمرەی </th>
            <th>کردار</th>
        </tr>
    </thead>
    <tbody class="table-group-divider">
        @forelse($selectedDepartments as $item)
        <tr>
            <td> 
                <span class="badge bg-{{ $item->department->system->name === 1 ? 'primary' : ($item->department->system->name === 2 ? 'warning' : 'danger') }}">

                    {{ $item->department->system->name ?? '' }} 
                </span>
                
                / {{ $item->department->province->name ?? '' }} / {{ $item->department->university->name ?? '' }} / {{ $item->department->college->name ?? '' }} / {{ $item->department->name }}</td>
            <td>{{ $item->department->local_score }}</td>
            <td>
                                <button class="btn btn-sm btn-danger remove-btn" 
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->department->name_ku }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
        </tr>
        @empty
                        <tr id="noDepartmentsRow">
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-3"></i>
                                <p class="mb-0">هیچ بەشێک هەڵنەبژاردووە.</p>
                            </td>
                        </tr>
                        @endforelse
    </tbody>
</table>
                

            </div>
        </div>
    </div>

    <!-- هەموو بەشە گونجاوەکان -->
    <div class="card border-info shadow-sm">
        <div class="card-header bg-info text-white py-3">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>بەشە گونجاوەکان</h5>
        </div>
        <div class="card-body">
            <!-- فیلتەر و گەڕان -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="گەڕان بە ناوی بەش...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="sortSelect">
                        <option value="mark_desc">نمرە (بەرز بۆ نزم)</option>
                        <option value="mark_asc">نمرە (نزم بۆ بەرز)</option>
                        <option value="name">ناو (أ-ی)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showOnlyEligible">
                        <label class="form-check-label" for="showOnlyEligible">
                            تەنها نیشانی بەشە گونجاوەکان بدە
                        </label>
                    </div>
                </div>
            </div>

            <!-- لیستی بەشەکان -->
            <div class="row" id="departmentsContainer">
                {{-- لە بەشی نمایشی بەشەکان --}}
@foreach($availableDepartments as $department)

<table class="table table-hover table-striped">
    <thead class="table-dark">
        <tr>
            <th>ناوی بەش</th>
            <th>نمرەی </th>
            <th>کردار</th>
        </tr>
    </thead>
    <tbody class="table-group-divider">
        @foreach($availableDepartments as $department)
        <tr>
            <td> {{ $department->system->name ?? '' }} / {{ $department->province->name ?? '' }} / {{ $department->university->name ?? '' }} / {{ $department->college->name ?? '' }} / {{ $department->name }}</td>
            <td>{{ $department->local_score }}</td>
            <td>
                {{-- دوگمەی زیادکردن --}}
            @if(in_array($department->id, $selectedDepartments->pluck('department_id')->toArray()))
            <button class="btn btn-success btn-sm w-100" disabled>
                <i class="fas fa-check me-1"></i>هەڵبژێردراوە
            </button>
            @elseif($student->mark < $department->local_score)
            <button class="btn btn-secondary btn-sm w-100" disabled title="نمرەکەت پێویست نییە">
                <i class="fas fa-lock me-1"></i>نمرە کەمە
            </button>
            @elseif(!in_array($department->type, [$student->type, 'زانستی و وێژەیی']))
            <button class="btn btn-secondary btn-sm w-100" disabled title="تیپەکەت گونجاو نییە">
                <i class="fas fa-lock me-1"></i>تیپ گونجاو نییە
            </button>
            @elseif(!in_array($department->sex, [$student->gender, 'هەردووکیان']))
            <button class="btn btn-secondary btn-sm w-100" disabled title="جێندەرەکەت گونجاو نییە">
                <i class="fas fa-lock me-1"></i>جێندەر گونجاو نییە
            </button>
            @elseif($currentCount >= $maxSelections)
            <button class="btn btn-warning btn-sm w-100" disabled title="گەیشتیتە سنووری هەڵبژاردن">
                <i class="fas fa-ban me-1"></i>سنوور تێپەڕی
            </button>
            @else
            <button class="btn btn-primary btn-sm w-100 add-department-btn"
                    data-id="{{ $department->id }}"
                    data-name="{{ $department->name }}">
                <i class="fas fa-plus me-1"></i>زیادکردن
            </button>
            @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endforeach
            </div>

            <!-- Pagination -->
            @if($availableDepartments->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $availableDepartments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal بۆ پشتڕاستکردنەوەی سڕینەوە -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger me-2"></i>دڵنیای لە سڕینەوە؟</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>ئایا دڵنیای لە سڕینەوەی بەشی <span id="departmentNameToDelete" class="fw-bold"></span>؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">هەڵوەشاندنەوە</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">سڕینەوە</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.department-card {
    transition: transform 0.2s;
}
.department-card:hover {
    transform: translateY(-5px);
}
.card-footer .btn {
    transition: all 0.3s;
}
#selectedDepartmentsTable tbody tr {
    vertical-align: middle;
}
.badge {
    font-size: 0.85em;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let departmentToDelete = null;
    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    // فیلتەر و گەڕان
    $('#searchInput').on('keyup', function() {
        let searchText = $(this).val().toLowerCase();
        $('.department-card').each(function() {
            let name = $(this).data('name').toLowerCase();
            if (name.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Sort departments
    $('#sortSelect').change(function() {
        let container = $('#departmentsContainer');
        let items = container.children('.department-card').get();
        
        items.sort(function(a, b) {
            let aMark = $(a).data('mark');
            let bMark = $(b).data('mark');
            let aName = $(a).data('name');
            let bName = $(b).data('name');
            
            switch($(this).val()) {
                case 'mark_desc':
                    return bMark - aMark;
                case 'mark_asc':
                    return aMark - bMark;
                case 'name':
                    return aName.localeCompare(bName);
                default:
                    return 0;
            }
        }.bind(this));
        
        container.empty().append(items);
    });

    // Show only eligible departments
    $('#showOnlyEligible').change(function() {
        if ($(this).is(':checked')) {
            $('.department-card[data-eligible="0"]').hide();
        } else {
            $('.department-card').show();
        }
    });

    // زیادکردنی بەش
    $(document).on('click', '.add-department-btn', function() {
        let btn = $(this);
        let departmentId = btn.data('id');
        let departmentName = btn.data('name');
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>چاوەڕوانی...');
        
        $.ajax({
            url: '{{ route("student.departments.add") }}',
            method: 'POST',
            data: {
                _token: csrfToken,
                department_id: departmentId
            },
            success: function(response) {
                if (response.success) {
                    // نوێکردنەوەی لیست
                    updateSelectedDepartments(response.data);
                    
                    // گۆڕینی دوگمە
                    btn.removeClass('btn-primary').addClass('btn-success')
                        .html('<i class="fas fa-check me-1"></i>هەڵبژێردراوە')
                        .prop('disabled', true);
                    
                    // پەیامی سەرکەوتوو
                    showToast('سەرکەوتوو', response.message, 'success');
                    
                    // نوێکردنەوەی ژمارەکان
                    $('#selectedCount').text(parseInt($('#selectedCount').text()) + 1);
                    
                    // چەککردنەوەی دوگمەکان ئەگەر گەیشتە سنوور
                    if (response.data.remaining <= 0) {
                        $('.add-department-btn:not(:disabled)').prop('disabled', true)
                            .removeClass('btn-primary').addClass('btn-warning')
                            .html('<i class="fas fa-ban me-1"></i>سنوور تێپەڕی');
                    }
                } else {
                    showToast('هەڵە', response.message, 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-plus me-1"></i>زیادکردن');
                }
            },
            error: function(xhr) {
                let errorMsg = 'هەڵەیەک ڕوویدا';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showToast('هەڵە', errorMsg, 'error');
                btn.prop('disabled', false).html('<i class="fas fa-plus me-1"></i>زیادکردن');
            }
        });
    });

    // سڕینەوەی بەش
    $(document).on('click', '.remove-btn', function() {
        departmentToDelete = {
            id: $(this).data('id'),
            name: $(this).data('name')
        };
        $('#departmentNameToDelete').text(departmentToDelete.name);
        $('#confirmDeleteModal').modal('show');
    });

    $('#confirmDeleteBtn').click(function() {
        if (!departmentToDelete) return;
        
        $.ajax({
            url: '{{ url("student/departments/remove") }}/' + departmentToDelete.id,
            method: 'DELETE',
            data: {
                _token: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    // سڕینەوەی ڕیز
                    $('#row-' + departmentToDelete.id).remove();
                    
                    // نوێکردنەوەی لیستی بەشەکان
                    let card = $('.department-card').find(`.add-department-btn[data-id="${departmentToDelete.id}"]`).closest('.department-card');
                    let btn = card.find('.btn');
                    btn.removeClass('btn-success').addClass('btn-primary')
                        .html('<i class="fas fa-plus me-1"></i>زیادکردن')
                        .prop('disabled', false)
                        .removeClass('add-department-btn')
                        .addClass('add-department-btn');
                    
                    // پەیامی سەرکەوتوو
                    showToast('سەرکەوتوو', response.message, 'success');
                    
                    // نوێکردنەوەی ژمارەکان
                    let currentCount = parseInt($('#selectedCount').text());
                    $('#selectedCount').text(currentCount - 1);
                    
                    // چەککردنەوەی دوگمەکان ئەگەر لیست بەتاڵ بوو
                    if (currentCount - 1 === 0) {
                        $('#noDepartmentsRow').show();
                    }
                    
                    // چالاککردنەوەی هەموو دوگمەکان
                    if (response.data.remaining > 0) {
                        $('.add-department-btn').prop('disabled', false)
                            .removeClass('btn-warning').addClass('btn-primary')
                            .html('<i class="fas fa-plus me-1"></i>زیادکردن');
                    }
                }
                $('#confirmDeleteModal').modal('hide');
            },
            error: function(xhr) {
                let errorMsg = 'هەڵەیەک ڕوویدا';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showToast('هەڵە', errorMsg, 'error');
                $('#confirmDeleteModal').modal('hide');
            }
        });
    });

    // زیادکردنی ڕیزێکی نوێ بۆ لیستی هەڵبژێردراوەکان
    function updateSelectedDepartments(departmentData) {
        let tableBody = $('#selectedDepartmentsTable tbody');
        let noDepartmentsRow = $('#noDepartmentsRow');
        
        if (noDepartmentsRow.is(':visible')) {
            noDepartmentsRow.hide();
        }
        
        let newRow = `
            <tr id="row-${departmentData.id}">
                <td>${tableBody.children('tr').length + 1}</td>
                <td>${departmentData.department_name}</td>
                <td>
                    <span class="badge bg-success">
                        ${departmentData.local_mark}
                    </span>
                </td>
                <td>${departmentData.created_at}</td>
                <td>
                    <button class="btn btn-sm btn-danger remove-btn" 
                            data-id="${departmentData.id}"
                            data-name="${departmentData.department_name}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        
        tableBody.prepend(newRow);
        
        // نوێکردنەوەی ژمارەکان
        tableBody.children('tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    // Toast notification
    function showToast(title, message, type) {
        // ئەگەر Toast پێگەیەنت هەیە، بەکاری بێنە
        if (typeof Toast !== 'undefined') {
            Toast.fire({
                icon: type,
                title: title,
                text: message
            });
        } else {
            // پەیامی سادە
            alert(title + ': ' + message);
        }
    }
});
</script>
@endpush    