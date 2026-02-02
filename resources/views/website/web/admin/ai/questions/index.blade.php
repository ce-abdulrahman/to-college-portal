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

    <!-- Filters Card -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">کاتێگۆری</label>
                    <select name="category" class="form-select">
                        <option value="">هەموو کاتێگۆری</option>
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">بارودۆخ</label>
                    <select name="status" class="form-select">
                        <option value="">هەموو بارودۆخ</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>چالاک</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>لە چالاکی خوارە</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search me-2"></i>گەڕان
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Questions Table -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
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
                            <td><strong>{{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}</strong></td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 300px;" title="{{ $question->question_ku }}">
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
                                    <span class="badge bg-secondary"><i class="fas fa-times me-1"></i>لە چالاکی خوارە</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-dark">
                                    {{ $question->answers()->count() }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.ai.questions.edit', $question) }}" class="btn btn-outline-primary" title="دەستکاری">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.ai.questions.destroy', $question) }}" style="display:inline;" onsubmit="return confirm('ئایا بڕوایی؟');">
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
                                پرسیار نیشتمان نییە
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($questions->hasPages())
            <div class="card-footer bg-light">
                {{ $questions->links() }}
            </div>
        @endif
    </div>

    <!-- Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-primary fw-bold">{{ $questions->total() }}</h3>
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
</div>
@endsection
