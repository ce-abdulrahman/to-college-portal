<div class="gis-popup">
    <h6 class="mb-2">{{ $department->name }}</h6>
    
    <table class="table table-sm mb-2">
        <tr>
            <td><small>زانکۆ:</small></td>
            <td><small>{{ $department->university->name ?? 'نەناسراو' }}</small></td>
        </tr>
        <tr>
            <td><small>نمرە:</small></td>
            <td>
                <small>
                    <span class="badge bg-{{ $student->mark >= $department->local_score ? 'success' : 'danger' }}">
                        {{ $department->local_score }}
                    </span>
                </small>
            </td>
        </tr>
        <tr>
            <td><small>سیستم:</small></td>
            <td>
                <small>
                    <span class="badge bg-{{ $department->system->name === 1 ? 'primary' : ($department->system->name === 2 ? 'warning' : 'danger') }}">
                        {{ $department->system->name ?? '' }}
                    </span>
                </small>
            </td>
        </tr>
    </table>
    
    <div class="text-center">
        @if($isSelected)
        <span class="badge bg-success w-100">
            <i class="fas fa-check me-1"></i>هەڵبژێردراوە
        </span>
        @elseif($student->mark >= $department->local_score)
        <button class="btn btn-sm btn-primary w-100 add-from-popup" 
                data-id="{{ $department->id }}"
                onclick="addDepartmentFromPopup({{ $department->id }})">
            <i class="fas fa-plus me-1"></i>زیادکردن
        </button>
        @else
        <span class="badge bg-danger w-100">
            <i class="fas fa-times me-1"></i>نمرە کەمە
        </span>
        @endif
    </div>
</div>

<style>
.gis-popup {
    min-width: 200px;
}
</style>