@extends('website.web.admin.layouts.app')

@section('title', 'وەڵامەکانی ' . $student->user->name . ' - AI')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title fw-bold">
                        <i class="fas fa-user me-2 text-primary"></i>
                        وەڵامەکانی {{ $student->user->name }}
                    </h4>
                    <small class="text-muted">کۆد: {{ $student->user->code }}</small>
                </div>
                <a href="{{ route('admin.ai.results') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>گەڕانەوە
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

    <!-- Student Info Card -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted">ناوی قوتابی</h6>
                        <h5 class="fw-bold">{{ $student->user->name }}</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted">کۆد</h6>
                        <h5 class="fw-bold"><code>{{ $student->user->code }}</code></h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted">نمرە</h6>
                        <h5 class="fw-bold text-success">{{ $student->mark }}</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted">ڕیزبەندی</h6>
                        <h5 class="fw-bold text-info">
                            @php
                                $rankingCount = \App\Models\AIRanking::where('student_id', $student->id)->count();
                            @endphp
                            {{ $rankingCount }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Answers by Category -->
    @forelse ($answers as $category => $categoryAnswers)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-folder me-2"></i>
                    {{ $categories[$category] ?? $category }}
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>پرسیار</th>
                                <th>وەڵام</th>
                                <th>نمرە</th>
                                <th>تیشک</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categoryAnswers as $answer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <small class="text-truncate d-inline-block" style="max-width: 300px;" title="{{ $answer->question->question_ku }}">
                                            {{ $answer->question->question_ku }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $answer->answer }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $answer->score }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $answer->created_at->format('Y-m-d H:i') }}
                                        </small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="card shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                وەڵام نیشتمان نییە
            </div>
        </div>
    @endforelse

    <!-- Action Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title fw-bold mb-3">
                <i class="fas fa-cog me-2"></i>کارەکان
            </h5>
            <form method="POST" action="{{ route('admin.ai.results.delete', $student->id) }}" onsubmit="return confirm('ئایا بڕوایی سڕینەوەی هەموو وەڵامەکان؟');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>سڕینەوەی هەموو وەڵامەکان
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
