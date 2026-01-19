@extends('website.web.admin.layouts.app')

@section('title', 'دەستکاری پرسیار - MBTI')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.mbti.questions.index') }}">پرسیارەکانی MBTI</a></li>
                        <li class="breadcrumb-item active">دەستکاری پرسیار #{{ $question->id }}</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-bar me-1"></i>
                    پرسیارەکانی MBTI
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header bg-warning text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-question-circle me-2"></i>دەستکاری پرسیار
                        </h5>
                        <span class="badge bg-light text-dark">ID: {{ $question->id }}</span>
                    </div>
                </div>

                <form action="{{ route('admin.mbti.questions.update', $question) }}" method="POST" id="editQuestionForm">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="alert alert-info d-flex align-items-center mb-4">
                            <i class="fas fa-chart-bar me-3 fa-2x"></i> 
                            <div>
                                <h6 class="mb-1">ئاماری ئەم پرسیارە :</h6>
                                <div class="d-flex flex-wrap gap-3 mt-2">
                                    <span class="badge bg-primary">
                                        <i class="fas fa-users me-1"></i>وەڵامەکان: {{ $question->answers()->count() }}
                                    </span>
                                    <span class="badge bg-success">
                                        <i class="fas fa-calendar me-1"></i>دروستکراوە: {{ $question->created_at->format('Y/m/d') }}
                                    </span>
                                    <span class="badge bg-info">
                                        <i class="fas fa-history me-1"></i>نوێکراوەتەوە: {{ $question->updated_at->format('Y/m/d') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>هەڵە!</strong> تکایە هەڵەکان چارەسەر بکە:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="dimension" class="form-label required">
                                    <i class="fas fa-layer-group me-1"></i>بەش
                                </label>
                                <select name="dimension" id="dimension" class="form-select" required>
                                    <option value="">بەشی هەڵبژێرە</option>
                                    <option value="EI" {{ $question->dimension == 'EI' ? 'selected' : '' }}>
                                        Extraversion (E) - Introversion (I)
                                    </option>
                                    <option value="SN" {{ $question->dimension == 'SN' ? 'selected' : '' }}>
                                        Sensing (S) - Intuition (N)
                                    </option>
                                    <option value="TF" {{ $question->dimension == 'TF' ? 'selected' : '' }}>
                                        Thinking (T) - Feeling (F)
                                    </option>
                                    <option value="JP" {{ $question->dimension == 'JP' ? 'selected' : '' }}>
                                        Judging (J) - Perceiving (P)
                                    </option>
                                </select>
                                <small class="form-text text-muted">بەشی پەیوەست بە پرسیارەکە</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="side" class="form-label required">
                                    <i class="fas fa-balance-scale me-1"></i>لا
                                </label>
                                <select name="side" id="side" class="form-select" required>
                                    <option value="">لای هەڵبژێرە</option>
                                </select>
                                <small class="form-text text-muted">لای پرسیار (E/I, S/N, T/F, J/P)</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12 mb-3">
                                <label for="question_ku" class="form-label required">
                                    <i class="fas fa-language me-1"></i>پرسیار بە کوردی
                                </label>
                                <textarea name="question_ku" id="question_ku" class="form-control" rows="4"
                                    placeholder="پرسیارەکە بنووسە بە کوردی..." required maxlength="500">{{ old('question_ku', $question->question_ku) }}</textarea>
                                <div class="form-text text-end character-counter" id="counter_ku">0 / 500 پیت</div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="question_en" class="form-label">
                                    <i class="fas fa-language me-1"></i>پرسیار بە ئینگلیزی
                                </label>
                                <textarea name="question_en" id="question_en" class="form-control" rows="4"
                                    placeholder="پرسیارەکە بنووسە بە ئینگلیزی..." maxlength="500">{{ old('question_en', $question->question_en) }}</textarea>
                                <div class="form-text text-end character-counter" id="counter_en">0 / 500 پیت</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="order" class="form-label required">
                                    <i class="fas fa-sort-numeric-down me-1"></i>ڕیز
                                </label>
                                <input type="number" name="order" id="order" class="form-control"
                                    value="{{ old('order', $question->order) }}" min="1" max="100" required>
                                <small class="form-text text-muted">ڕیزبەندی پرسیار لە بەشەکەیدا</small>
                            </div>

                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                                        value="1" {{ old('is_active', $question->is_active ?? true) ? 'checked' : '' }}>
                                    <label for="is_active" class="form-check-label">چالاکە</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('admin.mbti.questions.show', $question) }}" class="btn btn-info me-2">
                                    <i class="fas fa-eye me-1"></i>بینین
                                </a>
                                <a href="{{ route('admin.mbti.questions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>پاشگەزبوونەوە
                                </a>
                            </div>

                            <div class="btn-group">
                                <button type="reset" class="btn btn-warning">
                                    <i class="fas fa-redo me-1"></i>گەڕاندنەوە
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle me-1"></i>نوێکردنەوە
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-8 col-lg-10 mx-auto">
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-database me-2"></i>مێتا زانیاری
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">دروستکراوە لە:</label>
                                <p class="mb-0">{{ $question->created_at->format('Y/m/d - H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted">دروستکەر:</label>
                                <p class="mb-0">سیستەم</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">دوایین نوێکردنەوە:</label>
                                <p class="mb-0">{{ $question->updated_at->format('Y/m/d - H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted">دوایین نوێکەر:</label>
                                <p class="mb-0">سیستەم</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const dimensionSelect = $('#dimension');
    const sideSelect = $('#side');
    const questionKu = $('#question_ku');
    const questionEn = $('#question_en');
    const counterKu = $('#counter_ku');
    const counterEn = $('#counter_en');

    const sideOptions = {
        'EI': ['E', 'I'],
        'SN': ['S', 'N'],
        'TF': ['T', 'F'],
        'JP': ['J', 'P']
    };

    function populateSides(dimension, selectedSide = '') {
        sideSelect.html('<option value="">لای هەڵبژێرە</option>');
        
        if (dimension && sideOptions[dimension]) {
            sideOptions[dimension].forEach(side => {
                const option = $('<option>', {
                    value: side,
                    text: side,
                    selected: side === selectedSide
                });
                sideSelect.append(option);
            });
        }
    }

    function updateCharacterCounter(textarea, counter) {
        const count = textarea.val().length;
        counter.text(`${count} / 500 پیت`);
        counter.css('color', count > 500 ? '#dc3545' : '#6c757d');
    }

    dimensionSelect.on('change', function() {
        const dimension = $(this).val();
        populateSides(dimension);
    });

    questionKu.on('input', function() {
        updateCharacterCounter($(this), counterKu);
    });

    questionEn.on('input', function() {
        updateCharacterCounter($(this), counterEn);
    });

    const currentDimension = dimensionSelect.val();
    const currentSide = "{{ $question->side }}";
    
    if (currentDimension) {
        populateSides(currentDimension, currentSide);
    }

    updateCharacterCounter(questionKu, counterKu);
    updateCharacterCounter(questionEn, counterEn);
});
</script>
@endpush