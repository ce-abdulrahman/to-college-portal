
@extends('website.web.admin.layouts.app')

@section('title', 'تاقیکردنەوەی جۆری کەسی')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-primary shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">تاقیکردنەوەی جۆری کەسی (MBTI)</h4>
                            <p class="mb-0">خۆت ناسێنە: {{ $student->user->name }} | کۆد: {{ $student->user->code }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading">سەرنج بدە!</h5>
                                <p class="mb-0">ئەم تاقیکردنەوەیە 36 پرسیاری لەخۆ دەگرێت. بۆ هەر پرسیارێک نمرەی 1-10 دابنێ (١=زۆر ناڕەحەتە، ١٠=زۆر ڕەحەتە).</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('student.mbti.store') }}" id="mbtiForm">
                        @csrf
                        
                        @foreach($questions as $dimension => $dimensionQuestions)
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    @switch($dimension)
                                        @case('EI')
                                            <i class="fas fa-users me-2"></i>کۆمەڵایەتی (E) - تاکەکەسی (I)
                                            @break
                                        @case('SN')
                                            <i class="fas fa-eye me-2"></i>هەست (S) - ژیرێتی (N)
                                            @break
                                        @case('TF')
                                            <i class="fas fa-brain me-2"></i>بیرکردنەوە (T) - هەست (F)
                                            @break
                                        @case('JP')
                                            <i class="fas fa-gavel me-2"></i>ڕێکخستن (J) - چاودێری (P)
                                            @break
                                    @endswitch
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($dimensionQuestions as $question)
                                <div class="question-item mb-4 pb-3 border-bottom">
                                    <p class="question-text fw-bold mb-3">
                                        {{ $loop->iteration }}. {{ $question->question_ku }}
                                    </p>
                                    
                                    <div class="rating-scale">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-danger fw-bold">١ (ناڕەحەت)</small>
                                            
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                @for($i = 1; $i <= 10; $i++)
                                                <label class="btn btn-outline-primary rating-label">
                                                    <input type="radio" 
                                                           name="answers[{{ $question->id }}]" 
                                                           value="{{ $i }}" 
                                                           autocomplete="off">
                                                    {{ $i }}
                                                </label>
                                                @endfor
                                            </div>
                                            
                                            <small class="text-success fw-bold">١٠ (ڕەحەت)</small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5 py-3">
                                <i class="fas fa-check-circle me-2"></i>
                                تەواوکردنی تاقیکردنەوە و بینینی ئەنجام
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.rating-label {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    border-radius: 50% !important;
}

.rating-label.active {
    background-color: #198754 !important;
    color: white !important;
    border-color: #198754 !important;
}

.question-text {
    font-size: 1.1rem;
    color: #2c3e50;
    line-height: 1.6;
}

.card {
    border-radius: 15px;
    overflow: hidden;
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
document.addEventListener('DOMContentLoaded', function() {
    // چالاککردنی ڕەیتبۆکسەکان
    document.querySelectorAll('.rating-label').forEach(label => {
        label.addEventListener('click', function() {
            // سڕینەوەی چالاکی لە هەموو ڕەیتبۆکسەکانی ئەم پرسیارە
            const questionId = this.querySelector('input').name;
            const allInputs = document.querySelectorAll(`input[name="${questionId}"]`);
            allInputs.forEach(input => {
                input.parentElement.classList.remove('active');
            });
            
            // چالاککردنی ئەمە
            this.classList.add('active');
        });
    });
    
    // دڵنیاکردنەوە لە وەڵامدانی هەموو پرسیارەکان
    document.getElementById('mbtiForm').addEventListener('submit', function(e) {
        const totalQuestions = {{ count($questions->flatten()) }};
        const answered = document.querySelectorAll('input[type="radio"]:checked').length;
        
        if (answered !== totalQuestions) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'تکایە وەڵامی هەموو پرسیارەکان بدەوە!',
                text: 'تەنها ' + answered + ' لە ' + totalQuestions + ' پرسیارەکان وەڵامدراونەتەوە.',
                confirmButtonText: 'باشە',
                confirmButtonColor: '#198754'
            });
        }
    });
});

// Helper function بۆ ڕەنگی بەشەکان
function getDimensionColor(dimension) {
    const colors = {
        'EI': 'primary',
        'SN': 'success',
        'TF': 'warning',
        'JP': 'info'
    };
    return colors[dimension] || 'secondary';
}
</script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // چالاککردنی ڕەیتبۆکسەکان
            document.querySelectorAll('.rating-label').forEach(label => {
                label.addEventListener('click', function() {
                    // سڕینەوەی چالاکی لە هەموو ڕەیتبۆکسەکانی ئەم پرسیارە
                    const questionId = this.querySelector('input').name;
                    const allInputs = document.querySelectorAll(`input[name="${questionId}"]`);
                    allInputs.forEach(input => {
                        input.parentElement.classList.remove('active');
                    });

                    // چالاککردنی ئەمە
                    this.classList.add('active');
                });
            });

            // دڵنیاکردنەوە لە وەڵامدانی هەموو پرسیارەکان
            document.getElementById('mbtiForm').addEventListener('submit', function(e) {
                const totalQuestions = {{ count($questions->flatten()) }};
                const answered = document.querySelectorAll('input[type="radio"]:checked').length;

                if (answered !== totalQuestions) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'تکایە وەڵامی هەموو پرسیارەکان بدەوە!',
                        text: 'تەنها ' + answered + ' لە ' + totalQuestions +
                            ' پرسیارەکان وەڵامدراونەتەوە.',
                        confirmButtonText: 'باشە'
                    });
                }
            });
        });

        
    </script>

    <style>
        .rating-label {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
        }

        .rating-label.active {
            background-color: #0d6efd;
            color: white;
        }

        .question-text {
            font-size: 1.1rem;
            color: #333;
        }
    </style>
@endpush
