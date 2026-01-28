{{-- resources/views/student/departments/request-history.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('title', 'مێژووی داواکاریەکان')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-info shadow-lg">
                <div class="card-header bg-info text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1"><i class="fas fa-history me-2"></i>مێژووی داواکاریەکان</h4>
                            <p class="mb-0">قوتابی: {{ $student->user->name }} | کۆد: {{ $student->user->code }}</p>
                        </div>
                        <a href="{{ route('student.departments.selection') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>گەڕانەوە
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($requests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>کات</th>
                                    <th>جۆری داواکاری</th>
                                    <th>هۆکار</th>
                                    <th>بار</th>
                                    <th>کاتی وەڵام</th>
                                    <th>کردار</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $req)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $req->created_at->format('Y/m/d - H:i') }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $req->request_type }}</span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $req->reason }}">
                                            {{ Str::limit($req->reason, 50) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($req->status == 'pending')
                                        <span class="badge bg-warning">چاوەڕوانە</span>
                                        @elseif($req->status == 'approved')
                                        <span class="badge bg-success">پەسەندکراو</span>
                                        @else
                                        <span class="badge bg-danger">ڕەتکراوە</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($req->approved_at)
                                            {{ $req->approved_at->format('Y/m/d - H:i') }}
                                        @elseif($req->status == 'rejected')
                                            {{ $req->updated_at->format('Y/m/d - H:i') }}
                                        @else
                                            <span class="text-muted">هێشتا نەدراوە</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info view-details" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailsModal"
                                                data-reason="{{ $req->reason }}"
                                                data-notes="{{ $req->admin_notes ?? 'هیچ تێبینیەک نییە' }}"
                                                data-status="{{ $req->status }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        @if($req->status == 'pending')
                                        <form method="POST" action="{{ route('student.departments.cancel-request', $req->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('دڵنیای لە هەڵوەشاندنەوەی ئەم داواکاریە؟')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">هیچ داواکاریەک نییە</h5>
                        <p class="text-muted">هێشتا هیچ داواکاریەکت نەناردووە.</p>
                        <a href="{{ route('student.departments.request-more') }}" class="btn btn-warning mt-2">
                            <i class="fas fa-plus me-1"></i>داواکاری نوێ
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal بۆ بینینی وردەکاری -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i>وردەکاری داواکاری</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6><i class="fas fa-comment me-2"></i>هۆکار:</h6>
                    <p id="modalReason" class="p-3 bg-light rounded"></p>
                </div>
                <div class="mb-3">
                    <h6><i class="fas fa-sticky-note me-2"></i>تێبینی بەڕێوەبەر:</h6>
                    <p id="modalNotes" class="p-3 bg-light rounded"></p>
                </div>
                <div class="mb-3">
                    <h6><i class="fas fa-info-circle me-2"></i>بار:</h6>
                    <span id="modalStatus" class="badge"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">داخستن</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailsModal = document.getElementById('detailsModal');
    
    detailsModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        
        document.getElementById('modalReason').textContent = button.getAttribute('data-reason');
        document.getElementById('modalNotes').textContent = button.getAttribute('data-notes');
        
        const status = button.getAttribute('data-status');
        const statusBadge = document.getElementById('modalStatus');
        
        statusBadge.textContent = 
            status === 'pending' ? 'چاوەڕوانە' : 
            status === 'approved' ? 'پەسەندکراو' : 'ڕەتکراوە';
        
        statusBadge.className = 'badge ' + 
            (status === 'pending' ? 'bg-warning' : 
             status === 'approved' ? 'bg-success' : 'bg-danger');
    });
});
</script>
@endpush