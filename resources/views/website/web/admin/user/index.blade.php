@extends('website.web.admin.layouts.app')

@section('page_name', 'users')
@section('view_name', 'index')

@section('content')
    {{-- Actions bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item active">بەکارهێنەران</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-users me-1"></i>
                    بەکارهێنەران
                </h4>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-user-plus me-1"></i> زیادکردنی بەکارهێنەری نوێ
        </a>
        <span class="chip"><i class="fa-solid fa-database"></i> کۆی گشتی: {{ $users->count() }}</span>
    </div>

    <div class="card glass fade-in">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">
                    <i class="fa-solid fa-users me-2"></i> لیستی بەکارهێنەران
                </h4>

                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
                        <input type="text" id="users-search" class="form-control form-control-sm" placeholder="گەڕان...">
                    </div>

                    <select id="users-role-filter" class="form-select form-select-sm" style="width: auto;">
                        <option value="">هەموو جۆرەکان</option>
                        <option value="admin">ئەدمین</option>
                        <option value="center">سەنتەر</option>
                        <option value="teacher">مامۆستا</option>
                        <option value="student">قوتابی</option>
                    </select>

                    <select id="users-status-filter" class="form-select form-select-sm" style="width: auto;">
                        <option value="">هەموو دۆخەکان</option>
                        <option value="1">چاڵاک</option>
                        <option value="0">ناچاڵاک</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table id="users-table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>ناو</th>
                            <th>کۆد</th>
                            <th>ژمارەی مۆبایل</th>
                            <th style="width: 100px">پیشە</th>
                            <th style="width: 100px">دۆخ</th>
                            <th style="width: 180px">کردار</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            @if (auth()->user()->id != $user->id)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 36px; height: 36px;">
                                                <i class="fa-solid fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->code }}</td>
                                    <td>{{ $user->phone ?? '—' }}</td>
                                    <td>
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-info">ئەدمین</span>
                                        @elseif ($user->role === 'center')
                                            <span class="badge bg-danger">سەنتەر</span>
                                        @elseif ($user->role === 'teacher')
                                            <span class="badge bg-warning text-dark">مامۆستا</span>
                                        @else
                                            <span class="badge bg-secondary">قوتابی</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچاڵاک</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">


                                            <div class="btn-group dropend">
                                                <button type="button" class="btn btn-secondary dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa-solid fa-ellipsis-vertical me-1"></i>
                                                </button>
                                                <ul class="dropdown-menu text-center">
                                                    <li>
                                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                               class="dropdown-item" title="دەستکاری">
                                                <i class="fa-solid fa-pen-to-square"></i> دەستکاری
                                            </a>
                                                    </li>
                                                    <li>
                                                        @if ($user->role === 'center')
                                                            <a class="dropdown-item" href="{{ route('admin.center.show', $user->id) }}">
                                                                <i class="fa-solid fa-eye me-1"></i> بینین
                                                            </a>
                                                        @elseif ($user->role === 'teacher')
                                                            <a class="dropdown-item" href="{{ route('admin.teacher.show', $user->id) }}">
                                                                <i class="fa-solid fa-eye me-1"></i> بینین
                                                            </a>
                                                        @elseif ($user->role === 'student')
                                                            <a class="dropdown-item" href="{{ route('admin.student.show', $user->id) }}">
                                                                <i class="fa-solid fa-eye me-1"></i> بینین
                                                            </a>
                                                        @else
                                                            <span class="dropdown-item text-muted">بینین نیە</span>
                                                        @endif
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('دڵنیایت دەتەوێت ئەم بەکارهێنەر بسڕیتەوە؟');">
                                                                <i class="fa-solid fa-trash me-1"></i> سڕینەوە
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
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
        const table = $('#users-table').DataTable({
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
            order: [[1, 'asc']],
            pageLength: 10,
            responsive: true,
            initComplete: function() {
                // Add search input handler
                $('#users-search').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // Add role filter handler
                $('#users-role-filter').on('change', function() {
                    const role = this.value;
                    if (role === '') {
                        table.columns(4).search('').draw();
                    } else {
                        table.columns(4).search('^' + role + '$', true, false).draw();
                    }
                });

                // Add status filter handler
                $('#users-status-filter').on('change', function() {
                    const status = this.value;
                    if (status === '') {
                        table.columns(5).search('').draw();
                    } else {
                        const statusText = status === '1' ? 'چاڵاک' : 'ناچاڵاک';
                        table.columns(5).search('^' + statusText + '$', true, false).draw();
                    }
                });
            }
        });
    });
</script>
@endpush
