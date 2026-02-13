@extends('website.web.admin.layouts.app')

@section('title', 'بینینی پرسیار - AI')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title fw-bold">
                        <i class="fas fa-eye me-2 text-primary"></i>
                        بینینی پرسیار
                    </h4>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ai.questions.edit', $question) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>دەستکاری
                    </a>
                    <a href="{{ route('admin.ai.questions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>گەڕانەوە بۆ لیست
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    @php
                        $categories = [
                            'personality' => 'کەسایەتی',
                            'interest' => 'حەز و ئارەزوو',
                            'location' => 'شوێن',
                            'priority' => 'پێشەنگی',
                        ];
                        $options = $question->options ?? [];
                        if (is_string($options)) {
                            $options = json_decode($options, true) ?? [];
                        }
                    @endphp

                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <div class="text-muted small">ID</div>
                                    <div class="fw-bold fs-4">{{ $question->id }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <div class="text-muted small">کاتێگۆری</div>
                                    <div class="fw-bold">
                                        <span class="badge bg-info">
                                            {{ $categories[$question->category] ?? $question->category }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <div class="text-muted small">ڕێتینگ</div>
                                    <div class="fw-bold fs-5">{{ $question->weight }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <div class="text-muted small">ڕێت</div>
                                    <div class="fw-bold fs-5">{{ $question->order }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <div class="text-muted small">بارودۆخ</div>
                                    <div class="fw-bold">
                                        @if ($question->status)
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>چالاک</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-times me-1"></i>لە چالاکی خوارە</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <div class="text-muted small">کۆی وەڵامەکان</div>
                                    <div class="fw-bold fs-5">{{ $question->answers()->count() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <div class="text-muted small">دروستکراوە</div>
                                    <div class="fw-bold">{{ $question->created_at?->format('Y/m/d H:i') ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <div class="text-muted small">دوایین نوێکردنەوە</div>
                                    <div class="fw-bold">{{ $question->updated_at?->format('Y/m/d H:i') ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-4">
                        <label class="form-label fw-bold">پرسیار (کوردی)</label>
                        <div class="p-3 bg-light rounded border">
                            {{ $question->question_ku }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">پرسیار (ئینگلیزی)</label>
                        @if ($question->question_en)
                            <div class="p-3 bg-light rounded border">
                                {{ $question->question_en }}
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>پرسیاری ئینگلیزی بوونی نییە
                            </div>
                        @endif
                    </div>

                    @if (!empty($options))
                        <div>
                            <label class="form-label fw-bold">هەڵبژاردنەکان</label>
                            <ul class="list-group">
                                @foreach ($options as $option)
                                    <li class="list-group-item">
                                        {{ is_array($option) ? json_encode($option, JSON_UNESCAPED_UNICODE) : $option }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.ai.questions.edit', $question) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>دەستکاری
                </a>
                <form method="POST" action="{{ route('admin.ai.questions.destroy', $question) }}"
                    onsubmit="return confirm('ئایا بڕوایی؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i>سڕینەوە
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
