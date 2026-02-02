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

    <!-- Filters Card -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">بەدواداچوون</label>
                    <input type="text" name="search" class="form-control" placeholder="ناوی قوتابی یان کۆد..." value="{{ request('search') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search me-2"></i>گەڕان
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
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
                            <td><strong>{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</strong></td>
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
                                    $rankingCount = \App\Models\AIRanking::where('student_id', $student->id)->count();
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
                                    <a href="{{ route('admin.ai.results.show', $student->id) }}" class="btn btn-outline-primary" title="تێپەڕین">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.ai.results.delete', $student->id) }}" style="display:inline;" onsubmit="return confirm('ئایا بڕوایی سڕینەوەی وەڵامەکان؟');">
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
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                قوتابی نیشتمان نییە
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($students->hasPages())
            <div class="card-footer bg-light">
                {{ $students->links() }}
            </div>
        @endif
    </div>

    <!-- Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-primary fw-bold">{{ $students->total() }}</h3>
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
</div>
@endsection
