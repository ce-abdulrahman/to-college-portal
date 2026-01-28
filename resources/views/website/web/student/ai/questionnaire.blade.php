{{-- resources/views/student/ai/questionnaire.blade.php --}}
@extends('website.web.student.layouts.app')

@section('title', 'پرسیارەکانی AI بۆ ڕیزبەندی')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-primary shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-robot fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">پرسیارەکانی AI بۆ ڕیزبەندی بەشەکان</h4>
                            <p class="mb-0">تۆ: {{ $student->user->name }} | کۆد: {{ $student->user->code }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading">سەرنج بدە!</h5>
                                <p class="mb-0">ئەم پرسیارانە بۆ دیاریکردنی باشترین بەشەکان بۆ تۆ دروست کراون. وەڵامەکانت کاریگەری لەسەر ڕیزبەندیەکانی AI دەبێت.</p>
                                <p class="mb-0 mt-2"><strong>کات:</strong> نزیکەی ٥-١٠ خولەک</p>
                            </div>
                        </div>
                    </div>

                    <form id="aiQuestionnaireForm">
                        @csrf
                        
                        <!-- پرسیارەکانی جۆری کەسی -->
                        @if(isset($questions['personality']))
                        <div class="card mb-4 border-info">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user-tie me-2"></i>جۆری کەسی و تواناکان
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($questions['personality'] as $question)
                                <div class="question-item mb-4 pb-3 border-bottom">
                                    <p class="question-text fw-bold mb-3">
                                        {{ $loop->iteration }}. {{ $question->question_ku }}
                                    </p>
                                    
                                    <div class="options-container">
                                        @php
                                            $options = json_decode($question->options, true) ?? [
                                                ['text' => 'بەڵێ', 'score' => 100],
                                                ['text' => 'نەخێر', 'score' => 0],
                                                ['text' => 'نازانم', 'score' => 50]
                                            ];
                                        @endphp
                                        
                                        @foreach($options as $option)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   value="{{ $option['text'] }}"
                                                   id="q{{ $question->id }}_{{ $loop->index }}"
                                                   required>
                                            <label class="form-check-label" for="q{{ $question->id }}_{{ $loop->index }}">
                                                {{ $option['text'] }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- پرسیارەکانی حەز و ئارەزوو -->
                        @if(isset($questions['interest']))
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-heart me-2"></i>حەز و ئارەزووەکان
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($questions['interest'] as $question)
                                <div class="question-item mb-4 pb-3 border-bottom">
                                    <p class="question-text fw-bold mb-3">
                                        {{ $loop->iteration }}. {{ $question->question_ku }}
                                    </p>
                                    
                                    <div class="options-container">
                                        @php
                                            $options = json_decode($question->options, true) ?? [
                                                ['text' => 'زۆر حەزێکی پێیە', 'score' => 100],
                                                ['text' => 'حەزێکی پێیە', 'score' => 75],
                                                ['text' => 'مامناوەند', 'score' => 50],
                                                ['text' => 'کەم حەزێکی پێیە', 'score' => 25],
                                                ['text' => 'هیچ حەزێکی پێی نییە', 'score' => 0]
                                            ];
                                        @endphp
                                        
                                        <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                                            @foreach($options as $option)
                                            <label class="btn btn-outline-success m-1 interest-option">
                                                <input type="radio" 
                                                       name="answers[{{ $question->id }}]" 
                                                       value="{{ $option['text'] }}" 
                                                       autocomplete="off" required>
                                                {{ $option['text'] }}
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- پرسیارەکانی شوێن -->
                        @if(isset($questions['location']))
                        <div class="card mb-4 border-warning">
                            <div class="card-header bg-warning text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-map-marker-alt me-2"></i>شوێن و دابونەریت
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($questions['location'] as $question)
                                <div class="question-item mb-4 pb-3 border-bottom">
                                    <p class="question-text fw-bold mb-3">
                                        {{ $loop->iteration }}. {{ $question->question_ku }}
                                    </p>
                                    
                                    <div class="options-container">
                                        @php
                                            $options = json_decode($question->options, true) ?? [
                                                ['text' => 'بەڵێ، تەنها لە پارێزگای خۆم', 'score' => 100],
                                                ['text' => 'بەڵێ، بەڵام دەتوانم بچم بۆ پارێزگاکانی تر', 'score' => 75],
                                                ['text' => 'هیچ گرنگیەکم پێ نادەم', 'score' => 50],
                                                ['text' => 'پێم باشترە لە دەرەوەی پارێزگاکەم بخوێنم', 'score' => 25]
                                            ];
                                        @endphp
                                        
                                        @foreach($options as $option)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   value="{{ $option['text'] }}"
                                                   id="q{{ $question->id }}_{{ $loop->index }}"
                                                   required>
                                            <label class="form-check-label" for="q{{ $question->id }}_{{ $loop->index }}">
                                                {{ $option['text'] }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- پرسیارەکانی پێشەنگی -->
                        @if(isset($questions['priority']))
                        <div class="card mb-4 border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-flag me-2"></i>پێشەنگیەکان
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($questions['priority'] as $question)
                                <div class="question-item mb-4 pb-3 border-bottom">
                                    <p class="question-text fw-bold mb-3">
                                        {{ $loop->iteration }}. {{ $question->question_ku }}
                                    </p>
                                    
                                    @php
                                        $options = json_decode($question->options, true) ?? [
                                            ['text' => 'زۆر گرینگە', 'score' => 100],
                                            ['text' => 'گرینگە', 'score' : 75],
                                            ['text' => 'مامناوەند', 'score' : 50],
                                            ['text' => 'کەم گرینگە', 'score' : 25],
                                            ['text' : 'هیچ گرنگیەکم پێ نادەم', 'score' : 0]
                                        ];
                                    @endphp
                                    
                                    <div class="priority-slider mb-3">
                                        <input type="range" 
                                               class="form-range" 
                                               name="answers[{{ $question->id }}]"
                                               min="0" 
                                               max="{{ count($options) - 1 }}" 
                                               value="{{ floor(count($options) / 2) }}"
                                               id="slider{{ $question->id }}">
                                        <div class="d-flex justify-content-between">
                                            @foreach($options as $index => $option)
                                            <small class="text-muted">{{ $option['text'] }}</small>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" 
                                           name="answers[{{ $question->id }}]" 
                                           id="hidden{{ $question->id }}"
                                           value="{{ $options[floor(count($options) / 2)]['text'] }}">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3" id="submitBtn">
                                <i class="fas fa-brain me-2"></i>
                                تەواوکردنی پرسیارەکان و بینینی ڕیزبەندی
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                    <span class="visually-hidden">چاوەڕوانی...</span>
                </div>
                <h4 class="mt-4 text-primary">سیستەمی AI ڕیزبەندیەکان دادەنێت...</h4>
                <p class="text-muted mt-2">تکایە چاوەڕوانی بە. ئەمە نزیکەی ٣٠ چرکە دەخایەنێت.</p>
                <div class="progress mt-4">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         style="width: 0%" id="progressBar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.question-text {
    font-size: 1.1rem;
    color: #2c3e50;
    line-height: 1.6;
}

.interest-option {
    flex: 1;
    min-width: 180px;
    text-align: center;
    padding: 10px;
}

.interest-option.active {
    background-color: #198754 !important;
    color: white !important;
    border-color: #198754 !important;
}

.priority-slider input[type="range"] {
    height: 40px;
}

.btn-group-toggle .btn input[type="radio"] {
    position: absolute;
    clip: rect(0,0,0,0);
    pointer-events: none;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // چالاککردنی ڕەیتبۆکسەکان
    $('.interest-option').click(function() {
        const group = $(this).closest('.btn-group');
        group.find('.interest-option').removeClass('active');
        $(this).addClass('active');
    });
    
    // Slider بۆ پێشەنگیەکان
    $('input[type="range"]').on('input', function() {
        const questionId = this.id.replace('slider', '');
        const value = parseInt(this.value);
        const options = @json($questions['priority'] ?? []);
        
        if (options.length > 0) {
            const question = options.find(q => q.id == questionId);
            if (question && question.options) {
                const optionList = JSON.parse(question.options);
                if (optionList[value]) {
                    $(`#hidden${questionId}`).val(optionList[value].text);
                }
            }
        }
    });
    
    // پشکنینی فۆرم
    $('#aiQuestionnaireForm').submit(function(e) {
        e.preventDefault();
        
        // پشکنینی وەڵامی هەموو پرسیارەکان
        let allAnswered = true;
        const unanswered = [];
        
        $('input[type="radio"]:checked, input[type="hidden"]').each(function() {
            if (!$(this).val()) {
                allAnswered = false;
                const questionId = $(this).attr('name').replace('answers[', '').replace(']', '');
                unanswered.push(questionId);
            }
        });
        
        if (!allAnswered) {
            Swal.fire({
                icon: 'warning',
                title: 'تکایە وەڵامی هەموو پرسیارەکان بدەوە!',
                text: 'هێشتا ' + unanswered.length + ' پرسیار ماوە.',
                confirmButtonText: 'باشە',
                confirmButtonColor: '#198754'
            });
            return;
        }
        
        // نیشاندانی loading
        $('#loadingModal').modal('show');
        
        // Animationی progress bar
        let progress = 0;
        const progressBar = $('#progressBar');
        const progressInterval = setInterval(() => {
            progress += 5;
            progressBar.css('width', progress + '%');
            
            if (progress >= 100) {
                clearInterval(progressInterval);
            }
        }, 150);
        
        // ناردنی فۆرم
        $.ajax({
            url: '{{ route("student.ai-ranking.submit") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                clearInterval(progressInterval);
                
                if (response.success) {
                    // گواستنەوە بۆ ئەنجامەکان
                    setTimeout(() => {
                        $('#loadingModal').modal('hide');
                        window.location.href = response.redirect;
                    }, 1000);
                } else {
                    $('#loadingModal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'هەڵە',
                        text: response.message,
                        confirmButtonText: 'باشە'
                    });
                }
            },
            error: function(xhr) {
                clearInterval(progressInterval);
                $('#loadingModal').modal('hide');
                
                let errorMsg = 'هەڵەیەک ڕوویدا';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'هەڵە',
                    text: errorMsg,
                    confirmButtonText: 'باشە'
                });
            }
        });
    });
});
</script>
@endpush