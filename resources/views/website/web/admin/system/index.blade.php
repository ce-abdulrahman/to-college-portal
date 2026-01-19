@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item active">سیستەمەکانی خوێندن</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-bar me-1"></i>
                    سیستەمەکانی خوێندن
                </h4>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.systems.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> زیادکردنی
        </a>
        <span class="chip"><i class="fa-solid fa-database"></i> کۆی گشتی: {{ count($systems) }}</span>
    </div>

    <div class="table-wrap fade-in">
        <div class="table-responsive table-scroll-x">
            <table id="systems-table" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ناو</th>
                        <th>دۆخ</th>
                        <th>کردار</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($systems as $index => $system)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $system->name }}</td>
                            <td>
                                @if ($system->status)
                                    <span class="badge bg-success">چاڵاک</span>
                                @else
                                    <span class="badge bg-danger">ناچاڵاک</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.systems.show', $system->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.systems.edit', $system->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.systems.destroy', $system->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('ئیشکراوی ئەم سیستەمە دڵنیایت؟');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    // Initialize DataTable
    document.addEventListener('DOMContentLoaded', function() {
        // In systems index.blade.php DataTables initialization:
$('#systems-table').DataTable({
    language: {
        "sEmptyTable": "هیچ تۆمارێک نیە",
        "sInfo": "نیشاندانی _START_ بۆ _END_ لە _TOTAL_ تۆمار",
        "sInfoEmpty": "نیشاندانی 0 بۆ 0 لە 0 تۆمار",
        "sInfoFiltered": "(پاڵێوراوە لە _MAX_ کۆی تۆمار)",
        "sInfoPostFix": "",
        "sInfoThousands": ",",
        "sLengthMenu": "نیشاندانی _MENU_ تۆمار",
        "sLoadingRecords": "بارکردن...",
        "sProcessing": "پڕۆسەکردن...",
        "sSearch": "گەڕان:",
        "sZeroRecords": "هیچ تۆمارێکی هاوشێوە نەدۆزرایەوە",
        "oPaginate": {
            "sFirst": "یەکەم",
            "sLast": "کۆتا",
            "sNext": "داهاتوو",
            "sPrevious": "پێشوو"
        },
        "oAria": {
            "sSortAscending": ": چڕکردن بۆ ڕیزکردنی بەرزبوونەوە",
            "sSortDescending": ": چڕکردن بۆ ڕیزکردنی نزموونەوە"
        }
    },
    order: [],
    pageLength: 10,
    responsive: true
});
    });
</script>
@endpush