@extends('website.web.admin.layouts.app')

@section('title', 'دروستکردنی پرسیاری نوێ - MBTI')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.mbti.questions.index') }}"> پرسیارەکانی
                                    MBTI</a></li>
                            <li class="breadcrumb-item active">دروستکردنی نوێ</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-plus-circle me-1"></i>
                        دروستکردنی پرسیاری نوێ
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-question-circle me-2"></i>
                            زانیاریەکانی پرسیار
                        </h5>
                    </div>

                    <form action="{{ route('admin.mbti.questions.store') }}" method="POST" id="createQuestionForm">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>هەڵە!</strong> تکایە هەڵەکان چارەسەر بکە:
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
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
                                        <option value="EI" {{ old('dimension') == 'EI' ? 'selected' : '' }}>
                                            Extraversion (E) - Introversion (I)
                                        </option>
                                        <option value="SN" {{ old('dimension') == 'SN' ? 'selected' : '' }}>
                                            Sensing (S) - Intuition (N)
                                        </option>
                                        <option value="TF" {{ old('dimension') == 'TF' ? 'selected' : '' }}>
                                            Thinking (T) - Feeling (F)
                                        </option>
                                        <option value="JP" {{ old('dimension') == 'JP' ? 'selected' : '' }}>
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
                                        placeholder="پرسیارەکە بنووسە بە کوردی..." required maxlength="500">{{ old('question_ku') }}</textarea>
                                    <div class="form-text text-end character-counter" id="counter_ku">0 / 500 پیت</div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="question_en" class="form-label">
                                        <i class="fas fa-language me-1"></i>پرسیار بە ئینگلیزی
                                    </label>
                                    <textarea name="question_en" id="question_en" class="form-control" rows="4"
                                        placeholder="پرسیارەکە بنووسە بە ئینگلیزی..." maxlength="500">{{ old('question_en') }}</textarea>
                                    <div class="form-text text-end character-counter" id="counter_en">0 / 500 پیت</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="order" class="form-label required">
                                        <i class="fas fa-sort-numeric-down me-1"></i>ڕیز
                                    </label>
                                    <input type="number" name="order" id="order" class="form-control"
                                        value="{{ old('order', 1) }}" min="1" max="100" required>
                                    <small class="form-text text-muted">ڕیزبەندی پرسیار لە بەشەکەیدا</small>
                                </div>

                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                                            value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">چالاکە</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.mbti.questions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-right me-1"></i>گەڕانەوە
                                </a>
                                <div class="btn-group">
                                    <button type="reset" class="btn btn-warning">
                                        <i class="fas fa-redo me-1"></i>پاککردنەوە
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>پاشکەوتکردن
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
                <div class="card border-info">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>یارمەتی
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-info"><i class="fas fa-lightbulb me-2"></i>ڕێنماییەکان:</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> پرسیارەکە بە
                                        شێوەیەکی ڕوون و ڕێک بنووسە</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> ڕیزبەندی
                                        پرسیارەکان بە وردی دیاری بکە</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> بەشی دروست
                                        هەڵبژێرە بۆ پرسیارەکە</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-info"><i class="fas fa-exclamation-triangle me-2"></i>تێبینیەکان:</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-circle text-warning me-2"
                                            style="font-size: 8px"></i> پرسیارەکان بە کوردی دەبێت بێت</li>
                                    <li class="mb-2"><i class="fas fa-circle text-warning me-2"
                                            style="font-size: 8px"></i> ڕیزبەندی پرسیار کاریگەری لەسەر تاقیکردنەوەکە هەیە
                                    </li>
                                </ul>
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
                populateSides(dimension, "{{ old('side') }}");
            });

            questionKu.on('input', function() {
                updateCharacterCounter($(this), counterKu);
            });

            questionEn.on('input', function() {
                updateCharacterCounter($(this), counterEn);
            });

            const oldDimension = "{{ old('dimension') }}";
            if (oldDimension) {
                dimensionSelect.val(oldDimension).trigger('change');
            }

            updateCharacterCounter(questionKu, counterKu);
            updateCharacterCounter(questionEn, counterEn);
        });
    </script>
@endpush
