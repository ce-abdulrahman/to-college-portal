@extends('website.web.admin.layouts.app')

@section('title', 'ئەنجامەکانی MBTI')

@section('content')
    <div class="container-fluid py-4">
        <!-- سەردێڕ -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">ئەنجامەکانی MBTI</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-chart-bar me-1"></i>
                        ئەنجامەکانی تاقیکردنەوەی MBTI
                    </h4>
                </div>
            </div>
        </div>

        <!-- ئامارەکان -->
        <div class="row mb-3">
            <div class="col-xl-3 col-md-6">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="text-muted">کۆی قوتابیەکان</h5>
                                <h3 class="mt-2">{{ $statistics['total_students'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="text-muted">تاقیکردنەوەیان کردووە</h5>
                                <h3 class="mt-2">{{ $statistics['tested_students'] }}</h3>
                                <p class="mb-0">
                                    <span
                                        class="text-success">{{ $statistics['total_students'] > 0 ? round(($statistics['tested_students'] / $statistics['total_students']) * 100, 1) : 0 }}%</span>
                                </p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="text-muted">تاقیکردنەوەیان نەکردووە</h5>
                                <h3 class="mt-2">{{ $statistics['untested_students'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="text-muted">جۆرە جیاوازەکان</h5>
                                <h3 class="mt-2">{{ $students->groupBy('mbti_type')->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-pie fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- لیستی قوتابیەکان -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- فیلتەر -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <form method="GET" action="{{ route('admin.mbti.results.filter') }}">
                                    <div class="input-group">
                                        <select name="type" class="form-select">
                                            <option value="all">هەموو جۆرەکان</option>
                                            @foreach (['ISTJ', 'ISFJ', 'INFJ', 'INTJ', 'ISTP', 'ISFP', 'INFP', 'INTP', 'ESTP', 'ESFP', 'ENFP', 'ENTP', 'ESTJ', 'ESFJ', 'ENFJ', 'ENTJ'] as $type)
                                                <option value="{{ $type }}"
                                                    {{ request('type') == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-filter me-1"></i>فیلتەر
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-8 text-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-primary" onclick="exportToExcel()">
                                        <i class="fas fa-file-excel me-1"></i>Export
                                    </button>
                                    <button type="button" class="btn btn-outline-success" onclick="window.print()">
                                        <i class="fas fa-print me-1"></i>چاپکردن
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>قوتابی</th>
                                        <th>کۆد</th>
                                        <th>جۆری MBTI</th>
                                        <th>ساڵ</th>
                                        <th>ژمارەی وەڵام</th>
                                        <th>کاتی تاقیکردنەوە</th>
                                        <th>کردارەکان</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <span class="avatar-title bg-primary rounded-circle">
                                                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $student->user->name }}</div>
                                                        @if ($student->province)
                                                            <small class="text-muted">{{ $student->province }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $student->user->code }}</td>
                                            <td>
                                                @if ($student->mbti_type)
                                                    <span class="badge bg-primary">{{ $student->mbti_type }}</span>
                                                @else
                                                    <span class="badge bg-secondary">نەکراوە</span>
                                                @endif
                                            </td>
                                            <td>{{ $student->year ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $student->mbtiAnswers->count() }}</span>
                                            </td>
                                            <td>
                                                @if ($student->mbtiAnswers->count() > 0)
                                                    <small>{{ $student->mbtiAnswers->first()->created_at->format('Y/m/d H:i') }}</small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.mbti.results.show', $student) }}"
                                                        class="btn btn-info" title="بینینی وردەکاری">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('admin.mbti.results.delete', $student) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            title="سڕینەوەی ئەنجام"
                                                            onclick="return confirm('دڵنیایت لە سڕینەوەی ئەنجامەکانی ئەم قوتابیە؟')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                                    <p>هیچ ئەنجامێک بوونی نییە</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function exportToExcel() {
            // کۆدی Export کردن بۆ Excel
            alert('کۆدی Export کردن لێرە دەنووسرێت...');
        }

        function printTable() {
            const printContent = document.querySelector('.table-responsive').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = `
        <html>
            <head>
                <title>ئەنجامەکانی MBTI</title>
                <style>
                    @media print {
                        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
                        th { background-color: #f8f9fa; font-weight: bold; }
                    }
                </style>
            </head>
            <body>
                <h2 style="text-align: center; margin-bottom: 20px;">ئەنجامەکانی تاقیکردنەوەی MBTI</h2>
                ${printContent}
            </body>
        </html>
    `;

            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
@endpush
