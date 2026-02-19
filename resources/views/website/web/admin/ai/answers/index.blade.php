@extends('website.web.admin.layouts.app')

@section('title', 'وەڵامەکانی قوتابیان - AI')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="page-title fw-bold">
                            <i class="fas fa-list me-2 text-primary"></i>
                            وەڵامەکانی سیستەمی AI
                        </h4>
                    </div>
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

        <!-- Statistics -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h3 class="text-primary fw-bold">{{ $students->count() }}</h3>
                        <p class="text-muted mb-0">کۆی قوتابیان</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h3 class="text-success fw-bold">
                            @php
                                $totalAnswers = $students->sum(fn($s) => $s->aiAnswers()->count());
                            @endphp
                            {{ $totalAnswers }}
                        </h3>
                        <p class="text-muted mb-0">کۆی وەڵامەکان</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="studentsTable" class="table table-hover table-centered w-100">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>ناوی قوتابی</th>
                                <th>کۆد</th>
                                <th>وەڵامەکان</th>
                                <th>ڕیزبەندی</th>
                                <th>تیشک دابنێ</th>
                                <th>کارەکان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $index => $student)
                                <tr>
                                    <td><strong>{{ $loop->iteration }}</strong></td>
                                    <td>
                                        <strong>{{ $student->user->name }}</strong>
                                    </td>
                                    <td>
                                        <code>{{ $student->user->code }}</code>
                                    </td>
                                    <td>
                                        @php
                                            $answerCount = $student->aiAnswers()->count();
                                        @endphp
                                        @if ($answerCount > 0)
                                            <span class="badge bg-success">{{ $answerCount }}</span>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $rankingCount = \App\Models\AIRanking::where(
                                                'student_id',
                                                $student->id,
                                            )->count();
                                        @endphp
                                        <span class="badge bg-info">{{ $rankingCount }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            {{ $student->aiAnswers()->latest()->first()?->created_at?->format('Y-m-d H:i') ?? 'نیشتمان' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.ai.results.show', $student->id) }}"
                                                class="btn btn-outline-primary" title="تێپەڕین">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('admin.ai.results.delete', $student->id) }}"
                                                style="display:inline;"
                                                onsubmit="return confirm('ئایا بڕوایی سڕینەوەی وەڵامەکان؟');">
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
            $('#studentsTable').DataTable({
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
                        targets: [0, 1, 2, 3, 4]
                    },
                    {
                        orderable: false,
                        targets: [6]
                    },
                    {
                        className: "text-center",
                        targets: [0, 2, 3, 4, 6]
                    }
                ]
            });
        });
    </script>
@endpush

