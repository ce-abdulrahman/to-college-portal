@extends('website.web.admin.layouts.app')

@section('page_name', 'universities')
@section('view_name', 'index')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item active">زانکۆکان</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-building-columns me-1"></i>
                    زانکۆکان
                </h4>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.universities.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> زیادکردنی زانکۆی نوێ
        </a>
        <span class="chip"><i class="fa-solid fa-database"></i> کۆی گشتی: {{ count($universities) }}</span>
    </div>

    <div class="card glass fade-in">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">
                    <i class="fa-solid fa-building-columns me-2"></i> زانکۆکان
                </h4>
                
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
                        <input type="text" id="universities-search" class="form-control form-control-sm" placeholder="گەڕان...">
                    </div>
                    
                    <select id="universities-status-filter" class="form-select form-select-sm" style="width: auto;">
                        <option value="">هەموو دۆخەکان</option>
                        <option value="1">چاڵاک</option>
                        <option value="0">ناچاڵاک</option>
                    </select>
                    
                    <button id="universities-reset-filters" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="universities-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th style="width: 80px">وێنە</th>
                            <th>ناوی زانکۆ</th>
                            <th>ناوی ئینگلیزی</th>
                            <th>پارێزگا</th>
                            <th style="width: 100px">دۆخ</th>
                            <th style="width: 150px">کردار</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($universities as $index => $university)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($university->image)
                                        <img src="{{ $university->image }}" alt="{{ $university->name }}"
                                            class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded d-flex align-items-center justify-content-center" 
                                            style="width: 50px; height: 50px;">
                                            <i class="fa-solid fa-building-columns text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $university->name }}</td>
                                <td>{{ $university->name_en }}</td>
                                <td>{{ $university->province->name ?? '—' }}</td>
                                <td>
                                    @if ($university->status)
                                        <span class="badge bg-success">چاڵاک</span>
                                    @else
                                        <span class="badge bg-danger">ناچاڵاک</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.universities.show', $university->id) }}" 
                                           class="btn btn-outline-info" title="بینین">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.universities.edit', $university->id) }}" 
                                           class="btn btn-outline-primary" title="دەستکاری">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.universities.destroy', $university->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    onclick="return confirm('دڵنیایت دەتەوێت ئەم زانکۆ بسڕیتەوە؟');"
                                                    title="سڕینەوە">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kurdish language configuration for DataTables
        const kurdishLanguage = {
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
        };

        // Initialize DataTable
        const table = $('#universities-table').DataTable({
            language: kurdishLanguage,
            dom: '<"row"<"col-md-6"B><"col-md-6"f>>rt<"row"<"col-md-6"l><"col-md-6"p>>',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fa-solid fa-copy"></i> کۆپی',
                    className: 'btn btn-sm btn-outline-secondary'
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-solid fa-file-excel"></i> Excel',
                    className: 'btn btn-sm btn-outline-success'
                },
                {
                    extend: 'print',
                    text: '<i class="fa-solid fa-print"></i> چاپ',
                    className: 'btn btn-sm btn-outline-primary'
                }
            ],
            order: [[2, 'asc']],
            pageLength: 10,
            responsive: true,
            initComplete: function() {
                // Add search input handler
                $('#universities-search').on('keyup', function() {
                    table.search(this.value).draw();
                });
                
                // Add status filter handler
                $('#universities-status-filter').on('change', function() {
                    const status = this.value;
                    if (status === '') {
                        table.columns().search('').draw();
                    } else {
                        // Custom search for status column
                        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                            const rowStatus = $(table.row(dataIndex).node()).find('td:eq(5) .badge').text();
                            if (status === '1') {
                                return rowStatus.includes('چاڵاک');
                            } else if (status === '0') {
                                return rowStatus.includes('ناچاڵاک');
                            }
                            return true;
                        });
                        table.draw();
                        $.fn.dataTable.ext.search.pop(); // Remove the filter after drawing
                    }
                });
                
                // Add reset filters handler
                $('#universities-reset-filters').on('click', function() {
                    $('#universities-search').val('');
                    $('#universities-status-filter').val('');
                    table.search('').columns().search('').draw();
                });
            }
        });
    });
</script>
@endpush