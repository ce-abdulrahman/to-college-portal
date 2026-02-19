@extends('website.web.admin.layouts.app')

@section('title', 'پرسیارەکانی AI')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="page-title fw-bold">
                            <i class="fas fa-brain me-2 text-primary"></i>
                            پرسیارەکانی سیستەمی AI
                        </h4>
                    </div>
                    <a href="{{ route('admin.ai.questions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>پرسیاری نوێ
                    </a>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h3 class="text-primary fw-bold">{{ $questions->count() }}</h3>
                        <p class="text-muted mb-0">کۆی پرسیارەکان</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h3 class="text-success fw-bold">{{ count($categories) }}</h3>
                        <p class="text-muted mb-0">کاتێگۆریەکان</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="questionsTable" class="table table-hover table-centered w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>پرسیار (کوردی)</th>
                                <th>کاتێگۆری</th>
                                <th>ڕێتینگ</th>
                                <th>بارودۆخ</th>
                                <th>وەڵامەکان</th>
                                <th>کارەکان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($questions as $index => $question)
                                <tr>
                                    <td><strong>{{ $loop->iteration }}</strong></td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 300px;"
                                            title="{{ $question->question_ku }}">
                                            {{ Str::limit($question->question_ku, 50) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $categories[$question->category] ?? $question->category }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $question->weight }}</span>
                                    </td>
                                    <td>
                                        @if ($question->status)
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>چالاک</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-times me-1"></i>لە چالاکی
                                                خوارە</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-dark">
                                            {{ $question->answers()->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.ai.questions.show', $question) }}"
                                                class="btn btn-outline-info" title="بینین">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.ai.questions.edit', $question) }}"
                                                class="btn btn-outline-primary" title="دەستکاری">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('admin.ai.questions.destroy', $question) }}"
                                                style="display:inline;" onsubmit="return confirm('ئایا بڕوایی؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="سڕینەوە">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
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
            $('#questionsTable').DataTable({
                language: kurdishLanguage,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "هەموو"]
                ],
                order: [
                    [0, 'asc']
                ],
                responsive: true,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>><"row mt-2"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                columnDefs: [{
                        orderable: true,
                        targets: [0, 1, 2, 3]
                    },
                    {
                        orderable: false,
                        targets: [5, 6]
                    },
                    {
                        className: "text-center",
                        targets: [0, 2, 3, 4, 5, 6]
                    }
                ]
            });
        });
    </script>
@endpush

