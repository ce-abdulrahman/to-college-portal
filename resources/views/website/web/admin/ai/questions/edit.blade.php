@extends('website.web.admin.layouts.app')

@section('title', 'دەستکاری پرسیار - AI')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Page Header -->
            <div class="page-title-box mb-4">
                <h4 class="page-title fw-bold">
                    <i class="fas fa-edit me-2 text-primary"></i>
                    دەستکاری پرسیار
                </h4>
            </div>

            <!-- Form Card -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.ai.questions.update', $question) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Category -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">کاتێگۆری <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">هەڵبژێرە کاتێگۆری</option>
                                @foreach ($categories as $key => $label)
                                    <option value="{{ $key }}" {{ $question->category === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Question Kurdish -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">پرسیار (کوردی) <span class="text-danger">*</span></label>
                            <textarea name="question_ku" class="form-control @error('question_ku') is-invalid @enderror" rows="3" required placeholder="پرسیارەکە بە کوردی بنووسە">{{ $question->question_ku }}</textarea>
                            @error('question_ku')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Question English -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">پرسیار (ئینگلیزی)</label>
                            <textarea name="question_en" class="form-control @error('question_en') is-invalid @enderror" rows="3" placeholder="پرسیارەکە بە ئینگلیزی (بەتاختیکی)">{{ $question->question_en }}</textarea>
                            @error('question_en')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Weight -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">ڕێتینگ <span class="text-danger">*</span></label>
                            <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror"
                                   step="0.1" min="0" max="10" value="{{ $question->weight }}" required>
                            <small class="text-muted">ڕێتینگی گرنگی پرسیار (0-10)</small>
                            @error('weight')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Order -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">ڕێت <span class="text-danger">*</span></label>
                            <input type="number" name="order" class="form-control @error('order') is-invalid @enderror"
                                   min="1" value="{{ $question->order }}" required>
                            <small class="text-muted">ڕێتی نیشاندانی پرسیار لە صفحەکە</small>
                            @error('order')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="status" {{ $question->status ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="status">
                                    چالاک بکە
                                </label>
                            </div>
                        </div>

                        <!-- Info Alert -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>{{ $question->answers()->count() }} وەڵام</strong> بۆ ئەم پرسیارە تۆمار کراوە
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save me-2"></i>نوێکردنەوە
                            </button>
                            <a href="{{ route('admin.ai.questions.index') }}" class="btn btn-outline-secondary px-5">
                                <i class="fas fa-times me-2"></i>هەڵوەشاندن
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
