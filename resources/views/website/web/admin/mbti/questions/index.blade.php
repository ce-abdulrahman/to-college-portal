@extends('website.web.admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">پرسیارەکانی MBTI</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-chart-bar me-1"></i>پرسیارەکانی MBTI
                    </h4>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">پرسیارەکان</h5>
                            <div>
                                <a href="{{ route('admin.mbti.questions.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> پرسیاری نوێ
                                </a>
                            </div>
                        </div>

                        <table id="questionsTable" class="table table-hover table-centered w-100">
                            <thead>
                                <tr>
                                    <th width="50">ID</th>
                                    <th width="80">بەش</th>
                                    <th width="60">لا</th>
                                    <th>پرسیار</th>
                                    <th width="60">ڕیز</th>
                                    <th width="100">وەڵامەکان</th>
                                    <th width="120">کردارەکان</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questions as $question)
                                    <tr>
                                        <td>{{ $question->id }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $question->dimension == 'EI' ? 'primary' : ($question->dimension == 'SN' ? 'success' : ($question->dimension == 'TF' ? 'warning' : 'info')) }}">
                                                {{ $question->dimension }}
                                            </span>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $question->side }}</span></td>
                                        <td>{{ Str::limit($question->question_ku, 60) }}</td>
                                        <td>{{ $question->order }}</td>
                                        <td>
                                            <span
                                                class="badge bg-light text-dark">{{ $question->answers_count ?? $question->answers()->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.mbti.questions.show', $question) }}"
                                                    class="btn btn-outline-info" title="بینین">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.mbti.questions.edit', $question) }}"
                                                    class="btn btn-outline-warning" title="دەستکاری">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger delete-question"
                                                    data-id="{{ $question->id }}"
                                                    data-title="{{ Str::limit($question->question_ku, 30) }}"
                                                    title="سڕینەوە">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">هیچ پرسیارێک بوونی نییە</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">سڕینەوەی پرسیار</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>دڵنیایت لە سڕینەوەی پرسیار: <strong id="questionTitle"></strong>؟</p>
                    <p class="text-danger"><small>ئەم کردارە گەڕانەوەی نییە!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">پاشگەزبوونەوە</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">سڕینەوە</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <style>
        .dataTables_wrapper .dataTables_filter {
            text-align: left !important;
        }

        .dataTables_wrapper .dataTables_length {
            text-align: right !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Kurdish translation for DataTables
            const kurdishLanguage = {
                "processing": "چالاکیەکە لە جێبەجێکردن دایە...",
                "lengthMenu": "نیشاندانی _MENU_ تۆمار",
                "zeroRecords": "هیچ تۆمارێک نەدۆزرایەوە",
                "info": "نیشاندانی _START_ بۆ _END_ لە _TOTAL_ تۆمار",
                "infoEmpty": "نیشاندانی 0 بۆ 0 لە 0 تۆمار",
                "infoFiltered": "(پاڵاوتە بۆ _MAX_ کۆی تۆمار)",
                "search": "گەڕان:",
                "paginate": {
                    "first": "یەکەم",
                    "previous": "پێشوو",
                    "next": "داهاتوو",
                    "last": "کۆتایی"
                }
            };

            // Initialize DataTable
            const table = $('#questionsTable').DataTable({
                language: kurdishLanguage,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "هەموو"]
                ],
                order: [
                    [0, 'desc']
                ],
                responsive: true,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>><"row mt-2"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                columnDefs: [{
                        orderable: true,
                        targets: [0, 1, 2, 4]
                    },
                    {
                        orderable: false,
                        targets: [3, 5, 6]
                    },
                    {
                        className: "text-center",
                        targets: [0, 1, 2, 4, 5]
                    }
                ]
            });

            // Delete button handler
            $(document).on('click', '.delete-question', function() {
                const questionId = $(this).data('id');
                const questionTitle = $(this).data('title');
                const deleteUrl = "{{ route('admin.mbti.questions.destroy', ':id') }}".replace(':id',
                    questionId);

                // Set modal content
                $('#questionTitle').text(questionTitle);
                $('#deleteForm').attr('action', deleteUrl);

                // Show modal
                $('#deleteModal').modal('show');
            });

            // Reset form when modal is hidden
            $('#deleteModal').on('hidden.bs.modal', function() {
                $('#deleteForm').attr('action', '#');
                $('#questionTitle').text('');
            });

            // Auto-refresh DataTable every 30 seconds
            setInterval(function() {
                table.ajax.reload(null, false);
            }, 30000);
        });
    </script>
@endpush
