{{-- resources/views/admin/requests/partials/requests-table.blade.php --}}
<table class="table table-hover table-striped" id="requestsTable">
    <thead class="table-light">
        <tr>
            <th width="5%">#</th>
            <th width="15%">قوتابی</th>
            <th width="15%">کات</th>
            <th width="10%">بەشەکان</th>
            <th width="25%">هۆکار</th>
            <th width="10%">بار</th>
            <th width="10%">بەڕێوەبەر</th>
            <th width="10%">کردارەکان</th>
        </tr>
    </thead>
    <tbody>
        @forelse($requests as $request)
        <tr class="{{ $request->status == 'pending' ? 'table-warning' : '' }}">
            <td>{{ $loop->iteration + (($requests->currentPage() - 1) * $requests->perPage()) }}</td>
            <td>
                <div class="fw-bold">{{ $request->student->user->name }}</div>
                <small class="text-muted">کۆد: {{ $request->student->user->code }}</small>
            </td>
            <td>
                <div>{{ $request->created_at->format('Y/m/d') }}</div>
                <small class="text-muted">{{ $request->created_at->format('H:i') }}</small>
            </td>
            <td>
                <span class="badge bg-secondary">{{ $request->current_max }} → {{ $request->requested_max }}</span>
            </td>
            <td>
                <div class="text-truncate" style="max-width: 250px;" title="{{ $request->reason }}">
                    {{ Str::limit($request->reason, 50) }}
                </div>
                @if($request->admin_notes)
                <small class="text-muted d-block">تێبینی: {{ Str::limit($request->admin_notes, 30) }}</small>
                @endif
            </td>
            <td>
                @if($request->status == 'pending')
                <span class="badge bg-warning">چاوەڕوان</span>
                @elseif($request->status == 'approved')
                <span class="badge bg-success">پەسەندکراو</span>
                @else
                <span class="badge bg-danger">ڕەتکراوە</span>
                @endif
            </td>
            <td>
                @if($request->admin)
                <div>{{ $request->admin->name }}</div>
                <small class="text-muted">
                    @if($request->approved_at)
                    {{ $request->approved_at->format('Y/m/d') }}
                    @endif
                </small>
                @else
                <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.requests.show', $request->id) }}" 
                       class="btn btn-info" title="بینین">
                        <i class="fas fa-eye"></i>
                    </a>
                    
                    @if($request->status == 'pending')
                    <button class="btn btn-success approve-btn" 
                            data-id="{{ $request->id }}"
                            title="پەسەندکردن">
                        <i class="fas fa-check"></i>
                    </button>
                    
                    <button class="btn btn-danger reject-btn" 
                            data-id="{{ $request->id }}"
                            title="ڕەتکردنەوە">
                        <i class="fas fa-times"></i>
                    </button>
                    
                    <button class="btn btn-outline-danger delete-btn" 
                            data-id="{{ $request->id }}"
                            data-name="{{ $request->student->user->name }}"
                            title="سڕینەوە">
                        <i class="fas fa-trash"></i>
                    </button>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">هیچ داواکاریەک نییە</h5>
                <p class="text-muted">هێشتا هیچ داواکاریەک نەنێردراوە.</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($requests->hasPages())
<div class="d-flex justify-content-center mt-3">
    {{ $requests->links() }}
</div>
@endif