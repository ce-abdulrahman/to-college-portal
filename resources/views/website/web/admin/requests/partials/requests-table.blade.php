{{-- resources/views/admin/requests/partials/requests-table.blade.php --}}
<table class="table table-hover table-striped" id="requestsTable">
    <thead class="table-light">
        <tr>
            <th width="5%">#</th>
            <th width="15%">داواکار</th>
            <th width="10%">جۆر</th>
            <th width="15%">کات</th>
            <th width="15%">تایبەتمەندییەکان</th>
            <th width="20%">هۆکار</th>
            <th width="10%">بار</th>
            <th width="10%">بەڕێوەبەر</th>
            <th width="10%">کردارەکان</th>
        </tr>
    </thead>
    <tbody>
        @forelse($requests as $request)
            <tr class="{{ $request->status == 'pending' ? 'table-warning' : '' }}">
                <td>{{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                <td>
                    <div class="fw-bold">{{ $request->user->name ?? 'N/A' }}</div>
                    <small class="text-muted">کۆد: {{ $request->user->code ?? 'N/A' }}</small>
                </td>
                <td>
                    @if ($request->user_type == 'student')
                        <span class="badge bg-primary">قوتابی</span>
                    @elseif($request->user_type == 'teacher')
                        <span class="badge bg-info">مامۆستا</span>
                    @elseif($request->user_type == 'center')
                        <span class="badge bg-purple" style="background-color: #6f42c1;">سەنتەر</span>
                    @else
                        <span class="badge bg-secondary">{{ $request->user_type }}</span>
                    @endif
                </td>
                <td>
                    <div>{{ $request->created_at->format('Y/m/d') }}</div>
                    <small class="text-muted">{{ $request->created_at->format('H:i') }}</small>
                </td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        @if ($request->request_all_departments)
                            <span class="badge bg-warning text-dark" title="Request All Departments">All Dep</span>
                        @endif
                        @if ($request->request_ai_rank)
                            <span class="badge bg-success" title="Request ڕیزبەندی کرد بە زیرەکی دەستکردing">AI</span>
                        @endif
                        @if ($request->request_gis)
                            <span class="badge bg-info" title="Request GIS Map">GIS</span>
                        @endif
                        @if ($request->request_queue_hand_department)
                            <span class="badge bg-primary" title="ڕیزبەندی بەشەکان">ڕیز</span>
                        @endif
                        @if ((int) $request->request_limit_teacher > 0)
                            <span class="badge bg-dark" title="Limit Teacher">T+{{ (int) $request->request_limit_teacher }}</span>
                        @endif
                        @if ((int) $request->request_limit_student > 0)
                            <span class="badge bg-secondary" title="Limit Student">S+{{ (int) $request->request_limit_student }}</span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="text-truncate" style="max-width: 200px;" title="{{ $request->reason }}">
                        {{ Str::limit($request->reason, 40) }}
                    </div>
                    @if ($request->admin_notes)
                        <small class="text-muted d-block">تێبینی: {{ Str::limit($request->admin_notes, 25) }}</small>
                    @endif
                </td>
                <td>
                    @if ($request->status == 'pending')
                        <span class="badge bg-warning">چاوەڕوان</span>
                    @elseif($request->status == 'approved')
                        <span class="badge bg-success">پەسەندکراو</span>
                    @else
                        <span class="badge bg-danger">ڕەتکراوە</span>
                    @endif
                </td>
                <td>
                    @if ($request->admin)
                        <div>{{ $request->admin->name }}</div>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-info" title="بینین">
                            <i class="fas fa-eye"></i>
                        </a>

                        @if ($request->status == 'pending')
                            <button class="btn btn-outline-danger delete-btn" data-id="{{ $request->id }}"
                                data-name="{{ $request->user->name ?? 'Request' }}" title="سڕینەوە">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">هیچ داواکاریەک نییە</h5>
                    <p class="text-muted">هێشتا هیچ داواکاریەک نەنێردراوە.</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($requests->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $requests->links() }}
    </div>
@endif
